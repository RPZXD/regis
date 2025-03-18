<?php
class Uploads {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Method to handle document upload
    public function uploadDocument($citizenid, $files) {
        $uploadDir = '../uploads/' . $citizenid . '/' ;
        $uploadedFiles = [];

        foreach ($files as $key => $file) {
            $docName = isset($_POST[$key . '_name']) ? $_POST[$key . '_name'] : null;

            // Skip file if no document name is provided
            if ($docName === null || $file['error'] == UPLOAD_ERR_NO_FILE) {
                continue;
            }

            $newFileName = date('YmdHis') . '_' . bin2hex(random_bytes(3)) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $targetFile = $uploadDir . $newFileName;

            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                $uploadedFiles[$key] = $targetFile;

                // Check if the file with the same name already exists and has the status '✅ ตรวจสอบเรียบร้อยแล้ว'
                $query = "SELECT id FROM tbl_uploads WHERE citizenid = :citizenid AND name = :name AND status = '✅ ตรวจสอบเรียบร้อยแล้ว'";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':citizenid', $citizenid);
                $stmt->bindParam(':name', $docName);
                $stmt->execute();
                $existingFile = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existingFile) {
                    // Skip updating the file if it already exists with the status '✅ ตรวจสอบเรียบร้อยแล้ว'
                    continue;
                }

                // Check if the file with the same name already exists
                $query = "SELECT id FROM tbl_uploads WHERE citizenid = :citizenid AND name = :name";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':citizenid', $citizenid);
                $stmt->bindParam(':name', $docName);
                $stmt->execute();
                $existingFile = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existingFile) {
                    $query = "UPDATE tbl_uploads SET path = :path, create_at = NOW() WHERE id = :id";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':path', $newFileName);
                    $stmt->bindParam(':id', $existingFile['document_id']);
                } else {
                    $query = "INSERT INTO tbl_uploads (citizenid, name, path, create_at) VALUES (:citizenid, :name, :path, NOW())";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':citizenid', $citizenid);
                    $stmt->bindParam(':name', $docName);
                    $stmt->bindParam(':path', $newFileName);
                }
                $stmt->execute();
            } else {
                return ['success' => false, 'message' => 'Failed to upload ' . $file['name']];
            }
        }

        return ['success' => true, 'message' => 'Documents uploaded successfully', 'files' => $uploadedFiles];
    }

    // ...existing code...

    public function selectConfigUploadsByLevel($level) {
        $sql = "SELECT * FROM config_uploads WHERE level = :level";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':level', $level, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Function to generate file upload form
    public function generateFileUploadForm($documents) {
        foreach ($documents as $document) {
            echo '<div class="form-group mt-2">';
            echo '<label for="' . $document['document_id'] . '">' . $document['label'];
            if ($document['description']) {
                echo '<span class="text-danger">(' . $document['description'] . ')</span>';
            }
            echo '</label>';
            echo '<input type="file" accept="image/*" class="block w-full text-sm text-gray-500 file:py-2 file:px-4 file:border file:border-gray-300 file:rounded-lg file:bg-gray-50 hover:file:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 mt-2" id="' . $document['document_id'] . '" name="' . $document['document_id'] . '">';
            echo '</div>';
        }
    }


}
?>

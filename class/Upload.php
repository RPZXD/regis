<?php
class Uploads {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
        $this->createConfigUploadsTable();
    }

    // Method to create config_uploads table if it doesn't exist
    private function createConfigUploadsTable() {
        $query = "CREATE TABLE IF NOT EXISTS config_uploads (
            id INT AUTO_INCREMENT PRIMARY KEY,
            level INT NOT NULL,
            document_id VARCHAR(255) NOT NULL,
            label VARCHAR(255) NOT NULL,
            description TEXT
        )";
        $this->conn->exec($query);
    }

    // Method to handle document upload
    public function uploadDocument($citizenid, $level, $files) {
        $uploadDir = '../uploads/' . $citizenid . '/';
        $uploadedFiles = [];
    
        if (empty($files)) {
            return ['success' => false, 'message' => 'No files uploaded'];
        }
    
        foreach ($files as $key => $file) {
            $docName = isset($_POST[$key . '_name']) ? $_POST[$key . '_name'] : null;
    
            if ($docName === null || $file['error'] == UPLOAD_ERR_NO_FILE) {
                continue;
            }
    
            if ($file['error'] !== UPLOAD_ERR_OK) {
                return ['success' => false, 'message' => 'File upload error: ' . $file['error']];
            }
    
            $newFileName = date('YmdHis') . '_' . bin2hex(random_bytes(3)) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $targetFile = $uploadDir . $newFileName;
    
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
    
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                $uploadedFiles[$key] = $targetFile;
    
                // ตรวจสอบไฟล์ในฐานข้อมูล
                $query = "SELECT id, status FROM tbl_uploads WHERE citizenid = :citizenid AND name = :name";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':citizenid', $citizenid);
                $stmt->bindParam(':name', $docName);
                $stmt->execute();
                $existingFile = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if ($existingFile) {
                    if ($existingFile['status'] == 1) {
                        // ข้ามถ้า status = 1
                        continue;
                    } else {
                        // ถ้า status ≠ 1 ทำการอัพเดท path
                        $query = "UPDATE tbl_uploads SET path = :path, create_at = NOW() WHERE id = :id";
                        $stmt = $this->conn->prepare($query);
                        $stmt->bindParam(':path', $newFileName);
                        $stmt->bindParam(':id', $existingFile['id']);
                        $stmt->execute();
                    }
                } else {
                    // ไม่พบไฟล์ -> INSERT ใหม่
                    $query = "INSERT INTO tbl_uploads (citizenid, name, path, level, create_at) VALUES (:citizenid, :name, :path, :level, NOW())";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':citizenid', $citizenid);
                    $stmt->bindParam(':name', $docName);
                    $stmt->bindParam(':path', $newFileName);
                    $stmt->bindParam(':level', $level);
                    $stmt->execute();
                }
            } else {
                return ['success' => false, 'message' => 'Failed to upload ' . $file['name']];
            }
        }
    
        return ['success' => true, 'message' => 'Documents uploaded successfully', 'files' => $uploadedFiles];
    }
    
    // Method to fetch the status of an uploaded image
    public function getStatusImg($citizenid, $upload_name) {
        $query = "SELECT status FROM tbl_uploads WHERE citizenid = :citizenid AND name = :upload_name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':citizenid', $citizenid);
        $stmt->bindParam(':upload_name', $upload_name);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
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

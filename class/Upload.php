<?php
class Uploads {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Method to handle document upload
    public function uploadDocument($citizenid, $files) {
        $uploadDir = '../uploads/' . $citizenid . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadedFiles = [];
        foreach ($files as $key => $file) {
            $newFileName = date('YmdHis') . '_' . bin2hex(random_bytes(3)) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $targetFile = $uploadDir . $newFileName;
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                $uploadedFiles[$key] = $targetFile;

                // Check if the file with the same name already exists for the given citizenid
                $query = "SELECT id FROM tbl_uploads WHERE citizenid = :citizenid AND name = :name";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':citizenid', $citizenid);
                $stmt->bindParam(':name', $_POST[$key . '_name']);
                $stmt->execute();
                $existingFile = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existingFile) {
                    // Update the path if the file already exists
                    $query = "UPDATE tbl_uploads SET path = :path, create_at = NOW() WHERE id = :id";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':path', $newFileName);
                    $stmt->bindParam(':id', $existingFile['id']);
                } else {
                    // Insert new file information into the database
                    $query = "INSERT INTO tbl_uploads (citizenid, name, path, create_at) VALUES (:citizenid, :name, :path, NOW())";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':citizenid', $citizenid);
                    $stmt->bindParam(':name', $_POST[$key . '_name']);
                    $stmt->bindParam(':path', $newFileName);
                }
                $stmt->execute();
            } else {
                return ['success' => false, 'message' => 'Failed to upload ' . $file['name']];
            }
        }

        return ['success' => true, 'files' => $uploadedFiles];
    }

    // ...existing code...

}
?>

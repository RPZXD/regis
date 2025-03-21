<?php
// Require necessary files
require_once '../../config/Database.php';
require_once '../../class/Upload.php';

// Initialize database connection
$connectDB = new Database_Regis();
$db = $connectDB->getConnection();

// Get POST data and sanitize inputs
$citizenid = filter_var($_POST['citizenid'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$uploadName = filter_var($_POST['upload_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$status = filter_var($_POST['status'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$errorDetail = isset($_POST['error_detail']) ? htmlspecialchars($_POST['error_detail'], ENT_QUOTES, 'UTF-8') : null;

// Update upload status
$query = "UPDATE tbl_uploads SET status = :status, error_detail = :error_detail WHERE citizenid = :citizenid AND name = :name";
$stmt = $db->prepare($query);

// Bind parameters
$stmt->bindParam(':status', $status);
$stmt->bindParam(':error_detail', $errorDetail);
$stmt->bindParam(':citizenid', $citizenid);
$stmt->bindParam(':name', $uploadName);

// Execute query and check if successful
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    $errorInfo = $stmt->errorInfo(); // Get detailed error info
    echo json_encode(['success' => false, 'message' => 'Failed to update status', 'error' => $errorInfo]);
}
?>

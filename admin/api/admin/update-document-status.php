<?php
header('Content-Type: application/json');
require_once('../../../config/Database.php');

session_start();
if (!isset($_SESSION['Admin_login'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$id = intval($_POST['id'] ?? 0);
$status = $_POST['status'] ?? '';
$rejectReason = $_POST['reject_reason'] ?? '';

if (!$id || !in_array($status, ['approved', 'rejected', 'pending'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
    exit;
}

try {
    $connectDB = new Database_Regis();
    $db = $connectDB->getConnection();
    
    $adminId = $_SESSION['Admin_login'];
    
    $sql = "UPDATE student_documents SET 
            status = ?,
            reviewed_by = ?,
            reviewed_at = NOW(),
            reject_reason = ?
            WHERE id = ?";
    
    $stmt = $db->prepare($sql);
    $result = $stmt->execute([$status, $adminId, $rejectReason, $id]);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Status updated']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

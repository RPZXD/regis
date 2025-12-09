<?php
header('Content-Type: application/json');
require_once('../../../config/Database.php');

session_start();
if (!isset($_SESSION['Admin_login'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$studentId = isset($_POST['student_id']) ? intval($_POST['student_id']) : 0;

if (!$studentId) {
    echo json_encode(['success' => false, 'error' => 'Missing student ID']);
    exit;
}

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();

try {
    $stmt = $db->prepare("UPDATE users SET status = 1, update_at = NOW() WHERE id = ?");
    $result = $stmt->execute([$studentId]);
    
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>

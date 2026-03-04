<?php
header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../../../config/Database.php';

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

    $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';

    if (!$userId) {
        echo json_encode(['success' => false, 'error' => 'Missing student ID']);
        exit;
    }

    $connectDB = new Database_Regis();
    $db = $connectDB->getConnection();

    if (!$db) {
        throw new Exception("Failed to obtain database connection.");
    }

    // Set status to 3 (ไม่ผ่านคุณสมบัติ) and save reason
    $stmt = $db->prepare("UPDATE users SET status = 3, reject_reason = ?, update_at = NOW() WHERE id = ?");
    $result = $stmt->execute([$reason, $userId]);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update']);
    }
} catch (Throwable $t) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Internal Server Error',
        'message' => $t->getMessage()
    ]);
}
?>
<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../../config/Database.php';

try {
    session_start();
    if (!isset($_SESSION['Admin_login'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    $connectDB = new Database_Regis();
    $db = $connectDB->getConnection();

    if (!$db) {
        throw new Exception("Failed to obtain database connection.");
    }

    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'Room ID is required']);
        exit;
    }

    $id = $data['id'];
    
    // Delete the room
    $sql = "DELETE FROM exam_rooms WHERE id = ?";
    $stmt = $db->prepare($sql);
    $result = $stmt->execute([$id]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Room deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete room']);
    }
} catch (Throwable $t) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal Server Error',
        'message' => $t->getMessage()
    ]);
    exit;
}
?>

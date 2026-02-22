<?php
header('Content-Type: application/json');

require_once '../config/Database.php';

$id = $_POST['id'] ?? '';

if (empty($id)) {
    echo json_encode(['success' => false, 'error' => 'ไม่พบข้อมูล ID']);
    exit;
}

try {
    $db = (new Database_Regis())->getConnection();

    // Update student status to 2 (ยืนยันสิทธิ์)
    $stmt = $db->prepare("UPDATE users SET status = 2 WHERE id = ?");
    $result = $stmt->execute([$id]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'ยืนยันการรายงานตัวสำเร็จ']);
    } else {
        echo json_encode(['success' => false, 'error' => 'ไม่สามารถปรับปรุงข้อมูลได้']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

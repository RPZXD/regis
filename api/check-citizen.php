<?php
header('Content-Type: application/json');

require_once '../config/Database.php';

$citizenid = $_POST['citizenid'] ?? '';
$type_id = $_POST['type_id'] ?? '';

if (empty($citizenid)) {
    echo json_encode(['valid' => false, 'error' => 'กรุณากรอกเลขบัตรประชาชน']);
    exit;
}

$cleanId = str_replace('-', '', $citizenid);

$db = (new Database_Regis())->getConnection();

// Check if already registered
$stmt = $db->prepare("SELECT id, typeregis, level FROM users WHERE citizenid = ?");
$stmt->execute([$cleanId]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing) {
    echo json_encode([
        'valid' => false,
        'error' => "เลขบัตรประชาชนนี้เคยสมัครแล้ว ในประเภท {$existing['typeregis']} (ม.{$existing['level']})"
    ]);
} else {
    echo json_encode(['valid' => true]);
}

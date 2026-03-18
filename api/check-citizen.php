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

// Check if already registered for THIS type
$stmt = $db->prepare("SELECT id, typeregis, level FROM users WHERE citizenid = ?");
$stmt->execute([$cleanId]);
$existingRegistrations = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($existingRegistrations) {
    // Check if the requested type matches any existing registration
    $isAlreadyAppliedForThisType = false;
    $appliedTypes = [];

    // Get registration type details for the requested type_id
    $typeStmt = $db->prepare("SELECT name FROM registration_types WHERE id = ?");
    $typeStmt->execute([$type_id]);
    $requestedTypeName = $typeStmt->fetchColumn();

    $zoneTypes = ['ในเขต', 'นอกเขต', 'รอบทั่วไป'];
    $isRequestedGeneral = ($requestedTypeName === 'รอบทั่วไป' || in_array($requestedTypeName, $zoneTypes));

    foreach ($existingRegistrations as $reg) {
        $appliedTypes[] = "{$reg['typeregis']} (ม.{$reg['level']})";
        
        $isExistingGeneral = in_array($reg['typeregis'], $zoneTypes);
        
        if ($reg['typeregis'] === $requestedTypeName || ($isRequestedGeneral && $isExistingGeneral)) {
            $isAlreadyAppliedForThisType = true;
        }
    }

    if ($isAlreadyAppliedForThisType) {
        echo json_encode([
            'valid' => false,
            'error' => "เลขบัตรประชาชนนี้เคยสมัครในประเภท รอบทั่วไป/ในเขต/นอกเขต แล้ว"
        ]);
        exit;
    }

    // If different type, allow but maybe inform? (The user wants to allow it without restriction)
    echo json_encode([
        'valid' => true,
        'info' => "พบข้อมูลเดิม: " . implode(', ', $appliedTypes)
    ]);
} else {
    echo json_encode(['valid' => true]);
}

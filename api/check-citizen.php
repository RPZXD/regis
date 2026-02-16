<?php
/**
 * Check Citizen ID API
 * - Validates format (13 digits)
 * - Validates checksum
 * - Checks if already registered
 */
header('Content-Type: application/json');
require_once '../config/Database.php';

$citizenId = isset($_POST['citizenid']) ? preg_replace('/[^0-9]/', '', $_POST['citizenid']) : '';
$typeId = isset($_POST['type_id']) ? intval($_POST['type_id']) : 0;

// Validate format
if (strlen($citizenId) !== 13) {
    echo json_encode([
        'valid' => false,
        'error' => 'เลขบัตรประชาชนต้องมี 13 หลัก'
    ]);
    exit;
}

// Validate checksum (Thai national ID algorithm)
function validateThaiCitizenId($id)
{
    if (strlen($id) !== 13)
        return false;

    $sum = 0;
    for ($i = 0; $i < 12; $i++) {
        $sum += intval($id[$i]) * (13 - $i);
    }
    $checkDigit = (11 - ($sum % 11)) % 10;

    return intval($id[12]) === $checkDigit;
}

if (!validateThaiCitizenId($citizenId)) {
    echo json_encode([
        'valid' => false,
        'error' => 'รูปแบบเลขบัตรประชาชนไม่ถูกต้อง'
    ]);
    exit;
}

// Check if already registered in this year for the same type
try {
    $db = (new Database_Regis())->getConnection();
    $db->exec("SET NAMES utf8");

    // Get current academic year
    $stmt = $db->prepare("SELECT value FROM settings WHERE key_name = 'academic_year'");
    $stmt->execute();
    $yearSetting = $stmt->fetch(PDO::FETCH_ASSOC);
    $currentYear = $yearSetting['value'] ?? (date('Y') + 543);

    // Get registration type name from type_id
    $typeName = '';
    if ($typeId) {
        $typeStmt = $db->prepare("SELECT name FROM registration_types WHERE id = ?");
        $typeStmt->execute([$typeId]);
        $typeResult = $typeStmt->fetch(PDO::FETCH_ASSOC);
        $typeName = $typeResult['name'] ?? '';
    }

    // Check in users table — only block if same type
    if ($typeName) {
        $stmt = $db->prepare("SELECT id, stu_name, stu_lastname, level, typeregis FROM users WHERE citizenid = ? AND reg_pee = ? AND typeregis = ?");
        $stmt->execute([$citizenId, $currentYear, $typeName]);
    } else {
        // Fallback: if no type_id provided, check all registrations
        $stmt = $db->prepare("SELECT id, stu_name, stu_lastname, level, typeregis FROM users WHERE citizenid = ? AND reg_pee = ?");
        $stmt->execute([$citizenId, $currentYear]);
    }
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        $level = strtolower($existing['level']);
        $levelName = (in_array($level, ['1', 'm1'])) ? 'ม.1' : 'ม.4';
        $typeregis = $existing['typeregis'] ?? '';
        echo json_encode([
            'valid' => false,
            'registered' => true,
            'error' => "เลขบัตรประชาชนนี้ได้สมัครประเภท \"{$typeregis}\" แล้วในปีการศึกษานี้"
        ]);
        exit;
    }

    echo json_encode([
        'valid' => true,
        'message' => 'สามารถใช้เลขบัตรประชาชนนี้ได้'
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'valid' => false,
        'error' => 'เกิดข้อผิดพลาดในการตรวจสอบ: ' . $e->getMessage()
    ]);
}
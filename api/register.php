<?php
/**
 * api/register.php - Handle registration submission
 */
header('Content-Type: application/json');

require_once '../config/Database.php';
require_once '../class/AdminConfig.php';
require_once '../class/NotificationHelper.php';

// Helper to get location name from code
function getLocationName($db, $table, $code)
{
    if (empty($code))
        return '';
    try {
        $stmt = $db->prepare("SELECT name_th FROM {$table} WHERE code = ?");
        $stmt->execute([$code]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['name_th'] : $code;
    } catch (Exception $e) {
        return $code;
    }
}

try {
    $db = (new Database_Regis())->getConnection();
    $adminConfig = new AdminConfig($db);

    // Get input data
    $citizenid = str_replace('-', '', $_POST['citizenid'] ?? '');
    $typeId = intval($_POST['registration_type_id'] ?? 0);
    $level = $_POST['grade_level'] ?? '';

    if (empty($citizenid) || empty($typeId)) {
        throw new Exception('ข้อมูลไม่ครบถ้วน');
    }

    // Check for duplicate citizen ID
    $checkStmt = $db->prepare("SELECT id FROM users WHERE citizenid = ?");
    $checkStmt->execute([$citizenid]);
    if ($checkStmt->fetch()) {
        throw new Exception('เลขบัตรประชาชนนี้เคยสมัครแล้ว');
    }

    // Get Academic Year
    $academicYear = $adminConfig->getSetting('academic_year') ?? (date('Y') + 543);
    $yearShort = substr($academicYear, -2);

    // Get Type Name
    $typeStmt = $db->prepare("SELECT name FROM registration_types WHERE id = ?");
    $typeStmt->execute([$typeId]);
    $typeRow = $typeStmt->fetch(PDO::FETCH_ASSOC);
    $typeName = $typeRow ? $typeRow['name'] : 'ไม่ระบุ';

    // Generate numreg if empty
    $numreg = $_POST['numreg'] ?? '';
    if (empty($numreg)) {
        $countStmt = $db->prepare("SELECT COUNT(*) FROM users WHERE reg_pee = ? AND level = ?");
        $countStmt->execute([$academicYear, $level]);
        $count = $countStmt->fetchColumn();
        $seq = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        $numreg = $yearShort . $level . $seq;
    }

    // Map input fields to database columns
    $data = [
        'citizenid' => $citizenid,
        'stu_prefix' => $_POST['stu_prefix'] ?? '',
        'stu_name' => $_POST['stu_name'] ?? '',
        'stu_lastname' => $_POST['stu_lastname'] ?? '',
        'stu_sex' => $_POST['stu_sex'] ?? '',
        'stu_blood_group' => $_POST['stu_blood_group'] ?? '',
        'stu_religion' => $_POST['stu_religion'] ?? '',
        'stu_ethnicity' => $_POST['stu_ethnicity'] ?? '',
        'stu_nationality' => $_POST['stu_nationality'] ?? '',
        'date_birth' => $_POST['date_birth'] ?? '',
        'month_birth' => $_POST['month_birth'] ?? '',
        'year_birth' => $_POST['year_birth'] ?? '',
        'now_tel' => $_POST['now_tel'] ?? '',
        'old_school' => $_POST['old_school_name'] ?? '',
        'old_school_province' => getLocationName($db, 'province', $_POST['old_school_province'] ?? ''),
        'old_school_district' => getLocationName($db, 'district', $_POST['old_school_district'] ?? ''),
        'now_hno' => $_POST['now_hno'] ?? '',
        'now_moo' => $_POST['now_moo'] ?? '',
        'now_soy' => $_POST['now_soi'] ?? '',
        'now_street' => $_POST['now_road'] ?? '',
        'now_subdistrict' => getLocationName($db, 'subdistrict', $_POST['now_subdistrict'] ?? ''),
        'now_district' => getLocationName($db, 'district', $_POST['now_district'] ?? ''),
        'now_province' => getLocationName($db, 'province', $_POST['now_province'] ?? ''),
        'now_post' => $_POST['now_postcode'] ?? '',
        'dad_prefix' => $_POST['dad_prefix'] ?? '',
        'dad_name' => $_POST['dad_name'] ?? '',
        'dad_lastname' => $_POST['dad_lastname'] ?? '',
        'dad_job' => $_POST['dad_job'] ?? '',
        'dad_tel' => $_POST['dad_tel'] ?? '',
        'mom_prefix' => $_POST['mom_prefix'] ?? '',
        'mom_name' => $_POST['mom_name'] ?? '',
        'mom_lastname' => $_POST['mom_lastname'] ?? '',
        'mom_job' => $_POST['mom_job'] ?? '',
        'mom_tel' => $_POST['mom_tel'] ?? '',
        'parent_prefix' => $_POST['parent_prefix'] ?? '',
        'parent_name' => $_POST['parent_name'] ?? '',
        'parent_lastname' => $_POST['parent_lastname'] ?? '',
        'parent_tel' => $_POST['parent_tel'] ?? '',
        'parent_relation' => $_POST['parent_relation'] ?? '',
        'parent_job' => $_POST['parent_occupation'] ?? '',
        'gpa_total' => !empty($_POST['gpa_total']) ? $_POST['gpa_total'] : null,
        'grade_math' => !empty($_POST['grade_math']) ? $_POST['grade_math'] : null,
        'grade_science' => !empty($_POST['grade_science']) ? $_POST['grade_science'] : null,
        'grade_english' => !empty($_POST['grade_english']) ? $_POST['grade_english'] : null,
        'typeregis' => $typeName,
        'level' => $level,
        'reg_pee' => $academicYear,
        'numreg' => $numreg,
        'status' => 0
    ];

    // Add legacy number1-10 fields for backward compatibility with StudentRegis.php and other parts of the system
    for ($i = 1; $i <= 10; $i++) {
        $data['number' . $i] = $_POST['study_plan_' . $i] ?? '';
    }

    // Build query
    $columns = array_keys($data);
    $placeholders = array_map(function ($c) {
        return ':' . $c;
    }, $columns);
    $sql = "INSERT INTO users (" . implode(',', $columns) . ") VALUES (" . implode(',', $placeholders) . ")";

    $stmt = $db->prepare($sql);
    $stmt->execute($data);
    $newId = $db->lastInsertId();

    // Save plans to student_study_plans (Relational storage for modern queries)
    for ($i = 1; $i <= 10; $i++) {
        $planId = $_POST['study_plan_' . $i] ?? '';
        if ($planId) {
            $planStmt = $db->prepare("INSERT INTO student_study_plans (user_id, citizenid, plan_id, priority) VALUES (?, ?, ?, ?)");
            $planStmt->execute([$newId, $citizenid, $planId, $i]);
        }
    }

    // Notification
    try {
        $notifier = new NotificationHelper($db);
        $fullname = ($data['stu_prefix'] ?? '') . ($data['stu_name'] ?? '') . ' ' . ($data['stu_lastname'] ?? '');
        $notifier->notifyNewRegistration($fullname, "ม." . $level, $typeName, $citizenid);
    } catch (Exception $e) {
        // Notification error shouldn't stop the registration process
    }

    echo json_encode([
        'success' => true,
        'message' => 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว',
        'citizen_id' => $citizenid,
        'reg_number' => $numreg
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'ผิดพลาด: ' . $e->getMessage()
    ]);
}

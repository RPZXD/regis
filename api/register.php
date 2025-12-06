<?php
/**
 * Main Registration Submission API
 * Handles data from dynamic-form.php
 */
header('Content-Type: application/json');
require_once '../config/Database.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $dbRegis = new Database_Regis();
    $db = $dbRegis->getConnection();
    $db->exec("set names utf8");

    // Get current academic year
    $settings_stmt = $db->prepare("SELECT value FROM setting WHERE config_name = 'year'");
    $settings_stmt->execute();
    $yearSetting = $settings_stmt->fetch(PDO::FETCH_ASSOC);
    $currentYear = $yearSetting['value'] ?? (date('Y') + 543);

    // Basic Data Cleaning
    $citizenId = isset($_POST['citizenid']) ? preg_replace('/[^0-9]/', '', $_POST['citizenid']) : '';
    
    // Validate Citizen ID again (server-side safety)
    if (strlen($citizenId) !== 13) {
        throw new Exception('เลขบัตรประชาชนไม่ถูกต้อง');
    }

    // Check Duplicate
    $check_stmt = $db->prepare("SELECT citizenid FROM users WHERE citizenid = :citizenid AND reg_pee = :year");
    $check_stmt->execute([':citizenid' => $citizenId, ':year' => $currentYear]);
    if ($check_stmt->fetch()) {
        throw new Exception('เลขบัตรประชาชนนี้ได้สมัครในปีการศึกษานี้แล้ว');
    }

    // Helper to get name by code
    function getNameByCode($db, $table, $code) {
        if (empty($code)) return '';
        $stmt = $db->prepare("SELECT name_th FROM $table WHERE code = :code");
        $stmt->execute([':code' => $code]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['name_th'] : '';
    }

    // Study plans are now handled via student_study_plans table (see below)


    // Prepare Data for Insertion
    $data = [
        // Personal
        'citizenid' => $citizenId,
        'date_birth' => $_POST['date_birth'] ?? '',
        'month_birth' => $_POST['month_birth'] ?? '',
        'year_birth' => $_POST['year_birth'] ?? '',
        'stu_prefix' => $_POST['stu_prefix'] ?? '',
        'stu_name' => $_POST['stu_name'] ?? '',
        'stu_lastname' => $_POST['stu_lastname'] ?? '',
        'stu_sex' => $_POST['stu_sex'] ?? '',
        'stu_blood_group' => $_POST['stu_blood_group'] ?? '',
        'stu_religion' => $_POST['stu_religion'] ?? '',
        'stu_ethnicity' => $_POST['stu_ethnicity'] ?? '',
        'stu_nationality' => $_POST['stu_nationality'] ?? '',
        
        // Registration Meta
        'typeregis' => $_POST['type_id'] ?? '', // Registration Type ID
        'zone_type' => $_POST['zone_type'] ?? '', // In-Area / Out-Area choice
        'level' => $_POST['grade_level'] ?? '1', // Grade Level (1 or 4)
        'reg_pee' => $currentYear,
        'create_at' => date('Y-m-d H:i:s'),
        'roles' => 0,

        // Previous School
        'old_school' => $_POST['old_school'] ?? '',
        'old_school_province' => getNameByCode($db, 'province', $_POST['oldSchoolProvince'] ?? ''),
        'old_school_district' => getNameByCode($db, 'district', $_POST['oldSchoolDistrict'] ?? ''),
        
        // Current Address
        'now_addr' => $_POST['now_addr'] ?? '',
        'now_moo' => $_POST['now_moo'] ?? '',
        'now_soy' => $_POST['now_soy'] ?? '',
        'now_street' => $_POST['now_street'] ?? '',
        'now_province' => getNameByCode($db, 'province', $_POST['nowProvince'] ?? ''),
        'now_district' => getNameByCode($db, 'district', $_POST['nowDistrict'] ?? ''),
        'now_subdistrict' => getNameByCode($db, 'subdistrict', $_POST['nowSubdistrict'] ?? ''),
        'now_post' => $_POST['now_postcode'] ?? '',
        'now_tel' => $_POST['now_tel'] ?? '',
        
        // Registered Address
        'old_addr' => $_POST['reg_hno'] ?? '',
        'old_moo' => $_POST['reg_moo'] ?? '',
        'old_soy' => $_POST['reg_soi'] ?? '',
        'old_street' => $_POST['reg_road'] ?? '',
        'old_province' => getNameByCode($db, 'province', $_POST['regProvince'] ?? ''),
        'old_district' => getNameByCode($db, 'district', $_POST['regDistrict'] ?? ''),
        'old_subdistrict' => getNameByCode($db, 'subdistrict', $_POST['regSubdistrict'] ?? ''),
        'old_post' => $_POST['reg_postcode'] ?? '',
        'old_tel' => $_POST['now_tel'] ?? '', // Default to mobile

        // Parents info (Dad/Mom fields are in DB but form primarily collects Guardian now)
        // If Dad/Mom not provided, we leave empty or could map Guardian if selected as Dad/Mom?
        // Form allows setting Guardian Relation.
        'dad_prefix' => $_POST['dad_prefix'] ?? '',
        'dad_name' => $_POST['dad_name'] ?? '',
        'dad_lastname' => $_POST['dad_surname'] ?? '',
        'dad_job' => $_POST['dad_occupation'] ?? '',
        'dad_tel' => $_POST['dad_tel'] ?? '',
        
        'mom_prefix' => $_POST['mom_prefix'] ?? '',
        'mom_name' => $_POST['mom_name'] ?? '',
        'mom_lastname' => $_POST['mom_surname'] ?? '',
        'mom_job' => $_POST['mom_occupation'] ?? '',
        'mom_tel' => $_POST['mom_tel'] ?? '',
        
        // Guardian Info
        'parent_prefix' => $_POST['parent_prefix'] ?? '',
        'parent_name' => $_POST['parent_name'] ?? '',
        'parent_lastname' => $_POST['parent_lastname'] ?? '',
        'parent_tel' => $_POST['parent_tel'] ?? '',
        'parent_relation' => $_POST['parent_relation'] ?? '',
        'parent_job' => $_POST['parent_occupation'] ?? '', // New column
        
        // Academic / Talent
        'gpa_total' => $_POST['gpa_total'] ?? '0.00',
        'talent_skill' => $_POST['talent_skill'] ?? '', // New column
        
        // Study Plans
        // Study Plans - Moved to student_study_plans table

    ];

    // Construct SQL
    $fields = array_keys($data);
    $placeholders = array_map(function($f) { return ":$f"; }, $fields);
    
    $sql = "INSERT INTO users (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
    
    $stmt = $db->prepare($sql);
    foreach ($data as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    
    if ($stmt->execute()) {
        // Insert Study Plans into new table
        $planStmt = $db->prepare("INSERT INTO student_study_plans (citizenid, plan_id, priority) VALUES (:citizenid, :plan_id, :priority)");
        
        // Loop through all potential study plan inputs (allowing more than 10)
        // Adjust the loop limit if more plans are expected in the future, e.g. 20
        for ($i = 1; $i <= 20; $i++) {
            $key = "study_plan_$i";
            if (!empty($_POST[$key])) {
                $planStmt->execute([
                    ':citizenid' => $citizenId,
                    ':plan_id' => $_POST[$key],
                    ':priority' => $i
                ]);
            } elseif ($i === 1 && !empty($_POST['study_plan_id'])) {
                // Legacy fallback for single plan input
                $planStmt->execute([
                    ':citizenid' => $citizenId,
                    ':plan_id' => $_POST['study_plan_id'],
                    ':priority' => 1
                ]);
            }
        }

        echo json_encode(['success' => true, 'message' => 'สมัครเรียนสำเร็จ', 'citizen_id' => $citizenId]);
    } else {
        throw new Exception('บันทึกข้อมูลไม่สำเร็จ');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
}

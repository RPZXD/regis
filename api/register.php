<?php
/**
 * Registration API Endpoint
 * Handles student registration form submission
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../class/AdminConfig.php';
require_once __DIR__ . '/../class/NotificationHelper.php';

// Error handling
set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

try {
    $db = (new Database_Regis())->getConnection();
    if (!$db) {
        throw new Exception("เชื่อมต่อฐานข้อมูลล้มเหลว");
    }
    $db->exec("SET NAMES utf8");

    $adminConfig = new AdminConfig($db);

    // Helper function to get province name from code
    if (!function_exists('getProvinceName')) {
        function getProvinceName($db, $code)
        {
            if (empty($code))
                return '';
            $stmt = $db->prepare("SELECT name_th FROM province WHERE code = ? LIMIT 1");
            $stmt->execute([$code]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['name_th'] ?? $code;
        }
    }

    // Helper function to get district name from code
    if (!function_exists('getDistrictName')) {
        function getDistrictName($db, $code)
        {
            if (empty($code))
                return '';
            $stmt = $db->prepare("SELECT name_th FROM district WHERE code = ? LIMIT 1");
            $stmt->execute([$code]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['name_th'] ?? $code;
        }
    }

    // Helper function to get subdistrict name from code
    if (!function_exists('getSubdistrictName')) {
        function getSubdistrictName($db, $code)
        {
            if (empty($code))
                return '';
            $stmt = $db->prepare("SELECT name_th FROM subdistrict WHERE code = ? LIMIT 1");
            $stmt->execute([$code]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['name_th'] ?? $code;
        }
    }

    // Get POST data
    $citizenId = preg_replace('/[^0-9]/', '', $_POST['citizenid'] ?? '');
    $registrationTypeId = intval($_POST['registration_type_id'] ?? 0);
    $gradeLevel = intval($_POST['grade_level'] ?? 0);
    $typeCode = $_POST['type_code'] ?? '';

    // Validate required fields
    if (strlen($citizenId) !== 13) {
        throw new Exception('เลขบัตรประชาชนไม่ถูกต้อง');
    }

    if (!$registrationTypeId) {
        throw new Exception('ไม่พบประเภทการสมัคร');
    }

    // Get registration type info
    $typeSql = "SELECT rt.*, gl.name as grade_name, gl.code as grade_code 
                FROM registration_types rt 
                JOIN grade_levels gl ON rt.grade_level_id = gl.id
                WHERE rt.id = ?";
    $typeStmt = $db->prepare($typeSql);
    $typeStmt->execute([$registrationTypeId]);
    $regType = $typeStmt->fetch(PDO::FETCH_ASSOC);

    if (!$regType) {
        throw new Exception('ประเภทการสมัครไม่ถูกต้อง');
    }

    // Get current academic year
    $academicYear = $adminConfig->getSetting('academic_year') ?? (date('Y') + 543);

    // Check for duplicate registration
    $checkSql = "SELECT id FROM users WHERE citizenid = ? AND reg_pee = ? AND level = ? AND typeregis = ?";
    $checkStmt = $db->prepare($checkSql);
    $checkStmt->execute([$citizenId, $academicYear, $regType['grade_code'], $regType['name']]);

    if ($checkStmt->fetch()) {
        throw new Exception('เลขบัตรประชาชนนี้ได้สมัครประเภท "' . $regType['name'] . '" แล้วในปีการศึกษานี้');
    }

    $level = $regType['grade_code']; // m1 or m4
    $typeRegis = $regType['name'];

    // Use manual numreg if provided, otherwise generate
    $regNumber = !empty($_POST['numreg']) ? trim($_POST['numreg']) : generateRegNumber($db, $level, $academicYear, $regType['id']);

    $sql = "INSERT INTO users (
        citizenid, reg_pee, level, typeregis, numreg,
        stu_prefix, stu_name, stu_lastname,
        date_birth, month_birth, year_birth,
        stu_sex, stu_blood_group, stu_religion, stu_ethnicity, stu_nationality,
        now_tel, gpa_total, grade_math, grade_science, grade_english, zone_type, talent_skill, talent_awards, old_student_id,
        old_school, old_school_province, old_school_district,
        now_addr, now_moo, now_soy, now_street, now_province, now_district, now_subdistrict, now_post,
        old_addr, old_moo, old_soy, old_street, old_province, old_district, old_subdistrict, old_post, old_tel,
        dad_prefix, dad_name, dad_lastname, dad_job, dad_tel,
        mom_prefix, mom_name, mom_lastname, mom_job, mom_tel,
        parent_prefix, parent_name, parent_lastname, parent_relation, parent_tel, parent_job,
        status, create_at
    ) VALUES (
        :citizenid, :reg_pee, :level, :typeregis, :numreg,
        :stu_prefix, :stu_name, :stu_lastname,
        :date_birth, :month_birth, :year_birth,
        :stu_sex, :stu_blood_group, :stu_religion, :stu_ethnicity, :stu_nationality,
        :now_tel, :gpa_total, :grade_math, :grade_science, :grade_english, :zone_type, :talent_skill, :talent_awards, :old_student_id,
        :old_school, :old_school_province, :old_school_district,
        :now_addr, :now_moo, :now_soy, :now_street, :now_province, :now_district, :now_subdistrict, :now_post,
        :old_addr, :old_moo, :old_soy, :old_street, :old_province, :old_district, :old_subdistrict, :old_post, :old_tel,
        :dad_prefix, :dad_name, :dad_lastname, :dad_job, :dad_tel,
        :mom_prefix, :mom_name, :mom_lastname, :mom_job, :mom_tel,
        :parent_prefix, :parent_name, :parent_lastname, :parent_relation, :parent_tel, :parent_job,
        0, NOW()
    )";

    $stmt = $db->prepare($sql);

    $params = [
        ':citizenid' => $citizenId,
        ':reg_pee' => $academicYear,
        ':level' => $level,
        ':typeregis' => $typeRegis,
        ':numreg' => $regNumber,
        ':stu_prefix' => $_POST['stu_prefix'] ?? '',
        ':stu_name' => $_POST['stu_name'] ?? '',
        ':stu_lastname' => $_POST['stu_lastname'] ?? '',
        ':date_birth' => $_POST['date_birth'] ?? '',
        ':month_birth' => $_POST['month_birth'] ?? '',
        ':year_birth' => $_POST['year_birth'] ?? '',
        ':stu_sex' => $_POST['stu_sex'] ?? '',
        ':stu_blood_group' => $_POST['stu_blood_group'] ?? '',
        ':stu_religion' => $_POST['stu_religion'] ?? '',
        ':stu_ethnicity' => $_POST['stu_ethnicity'] ?? '',
        ':stu_nationality' => $_POST['stu_nationality'] ?? '',
        ':now_tel' => $_POST['now_tel'] ?? '',
        ':gpa_total' => !empty($_POST['gpa_total']) ? $_POST['gpa_total'] : 0,
        ':grade_math' => $_POST['grade_math'] ?? '',
        ':grade_science' => $_POST['grade_science'] ?? '',
        ':grade_english' => $_POST['grade_english'] ?? '',
        ':zone_type' => $_POST['zone_type'] ?? '',
        ':talent_skill' => $_POST['talent_skill'] ?? '',
        ':talent_awards' => $_POST['talent_awards'] ?? '',
        ':old_student_id' => $_POST['old_student_id'] ?? '',
        ':old_school' => $_POST['old_school_name'] ?? '',
        ':old_school_province' => getProvinceName($db, $_POST['old_school_province'] ?? ''),
        ':old_school_district' => getDistrictName($db, $_POST['old_school_district'] ?? ''),
        ':now_addr' => $_POST['now_hno'] ?? '',
        ':now_moo' => $_POST['now_moo'] ?? '',
        ':now_soy' => $_POST['now_soi'] ?? '',
        ':now_street' => $_POST['now_road'] ?? '',
        ':now_province' => getProvinceName($db, $_POST['now_province'] ?? ''),
        ':now_district' => getDistrictName($db, $_POST['now_district'] ?? ''),
        ':now_subdistrict' => getSubdistrictName($db, $_POST['now_subdistrict'] ?? ''),
        ':now_post' => $_POST['now_postcode'] ?? '',
        ':old_addr' => $_POST['reg_hno'] ?? '',
        ':old_moo' => $_POST['reg_moo'] ?? '',
        ':old_soy' => $_POST['reg_soi'] ?? '',
        ':old_street' => $_POST['reg_road'] ?? '',
        ':old_province' => getProvinceName($db, $_POST['reg_province'] ?? ''),
        ':old_district' => getDistrictName($db, $_POST['reg_district'] ?? ''),
        ':old_subdistrict' => getSubdistrictName($db, $_POST['reg_subdistrict'] ?? ''),
        ':old_post' => $_POST['reg_postcode'] ?? '',
        ':old_tel' => $_POST['old_tel'] ?? '',
        ':dad_prefix' => $_POST['dad_prefix'] ?? '',
        ':dad_name' => $_POST['dad_name'] ?? '',
        ':dad_lastname' => $_POST['dad_lastname'] ?? '',
        ':dad_job' => $_POST['dad_job'] ?? '',
        ':dad_tel' => $_POST['dad_tel'] ?? '',
        ':mom_prefix' => $_POST['mom_prefix'] ?? '',
        ':mom_name' => $_POST['mom_name'] ?? '',
        ':mom_lastname' => $_POST['mom_lastname'] ?? '',
        ':mom_job' => $_POST['mom_job'] ?? '',
        ':mom_tel' => $_POST['mom_tel'] ?? '',
        ':parent_prefix' => $_POST['parent_prefix'] ?? '',
        ':parent_name' => $_POST['parent_name'] ?? '',
        ':parent_lastname' => $_POST['parent_lastname'] ?? '',
        ':parent_relation' => $_POST['parent_relation'] ?? '',
        ':parent_tel' => $_POST['parent_tel'] ?? '',
        ':parent_job' => $_POST['parent_occupation'] ?? '',
    ];

    $stmt->execute($params);
    $insertId = $db->lastInsertId();

    saveStudyPlans($db, $insertId, $citizenId, $_POST);

    try {
        $notifier = new NotificationHelper($db);
        $studentName = ($_POST['stu_prefix'] ?? '') . ($_POST['stu_name'] ?? '') . ' ' . ($_POST['stu_lastname'] ?? '');
        $levelText = ($level == 'm1' || $level == '1') ? 'ม.1' : 'ม.4';
        $notifier->notifyNewRegistration($studentName, $levelText, $typeRegis, $citizenId);
    } catch (Throwable $e) {
        // Silently fail
    }

    echo json_encode([
        'success' => true,
        'message' => 'ลงทะเบียนสำเร็จ',
        'reg_number' => $regNumber,
        'citizen_id' => $citizenId,
        'id' => $insertId
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(200);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

if (!function_exists('generateRegNumber')) {
    function generateRegNumber($db, $level, $year, $typeId)
    {
        $yearShort = substr($year, -2);
        $levelNum = str_replace('m', '', $level);
        $typeNum = str_pad($typeId, 2, '0', STR_PAD_LEFT);
        $prefix = $yearShort . $levelNum . $typeNum;

        $sql = "SELECT MAX(CAST(SUBSTRING_INDEX(numreg, '-', -1) AS UNSIGNED)) as max_seq 
                FROM users 
                WHERE numreg LIKE ? AND reg_pee = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$prefix . '-%', $year]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $nextSeq = ($result['max_seq'] ?? 0) + 1;

        return $prefix . '-' . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('saveStudyPlans')) {
    function saveStudyPlans($db, $userId, $citizenId, $postData)
    {
        try {
            $checkTable = $db->query("SHOW TABLES LIKE 'student_study_plans'");
            if ($checkTable->rowCount() == 0) {
                return;
            }

            $clearSql = "DELETE FROM student_study_plans WHERE user_id = ?";
            $clearStmt = $db->prepare($clearSql);
            $clearStmt->execute([$userId]);

            $insertSql = "INSERT INTO student_study_plans (user_id, citizenid, plan_id, priority) VALUES (?, ?, ?, ?)";
            $insertStmt = $db->prepare($insertSql);

            $choiceIndex = 1;
            while (isset($postData["study_plan_{$choiceIndex}"])) {
                $planId = $postData["study_plan_{$choiceIndex}"];
                if (!empty($planId)) {
                    $insertStmt->execute([$userId, $citizenId, intval($planId), $choiceIndex]);
                }
                $choiceIndex++;
                if ($choiceIndex > 20)
                    break;
            }
        } catch (Throwable $e) {
            // Silently fail
        }
    }
}
?>
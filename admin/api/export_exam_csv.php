<?php
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename=ExamRoomData_' . date('Ymd_His') . '.csv');

// Add BOM for UTF-8 Excel support
echo "\xEF\xBB\xBF";

try {
    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../class/StudentRegis.php';
    require_once __DIR__ . '/../../class/AdminConfig.php';

    session_start();
    if (!isset($_SESSION['Admin_login'])) {
        http_response_code(401);
        echo "Unauthorized";
        exit;
    }

    $connectDB = new Database_Regis();
    $db = $connectDB->getConnection();

    if (!$db) {
        throw new Exception("Failed to obtain database connection.");
    }

    $adminConfig = new AdminConfig($db);
    $studentRegis = new StudentRegis($db);

    $typeId = isset($_GET['type_id']) ? intval($_GET['type_id']) : 0;

    if (!$typeId) {
        die("Missing registration type ID.");
    }

    $type = $adminConfig->getRegistrationTypeById($typeId);
    if (!$type) {
        die("Invalid registration type ID.");
    }

    $level = ($type['grade_code'] == 'm1') ? '1' : '4';
    $students = $studentRegis->getStudentsByCriteria($level, $type['name']);

    $output = fopen("php://output", "w");

    // All study plans mapping
    $allPlans = $adminConfig->getStudyPlans();
    $plansMap = [];
    foreach ($allPlans as $plan) {
        $plansMap[$plan['id']] = $plan['name'];
    }

    // CSV Header matching requested columns
    fputcsv($output, [
        'เลขประจำตัวผู้สมัคร',
        'เลขบัตรประชาชน',
        'ชื่อนามสกุล',
        'แผนการเรียน',
        'เลขที่นั่งสอบ',
        'ห้องสอบ',
        'วันสอบ',
        'สถานะ (0=รอตรวจสอบ, 1=ยืนยันแล้ว, 2=สละสิทธิ์)'
    ]);

    foreach ($students as $student) {
        // Format plans
        $plansStr = '-';
        if (!empty($student['plan_string'])) {
            $planPairs = explode(',', $student['plan_string']);
            $formattedPlans = [];
            foreach ($planPairs as $pair) {
                list($priority, $planId) = explode(':', $pair);
                $planName = isset($plansMap[$planId]) ? $plansMap[$planId] : $planId;
                $formattedPlans[] = "{$priority}. {$planName}";
            }
            $plansStr = implode(', ', $formattedPlans);
        }

        fputcsv($output, [
            $student['numreg'] ?? '',
            '="' . $student['citizenid'] . '"', // Prevent scientific notation in Excel
            $student['fullname'],
            $plansStr,
            $student['seat_number'] ?? '',
            $student['exam_room'] ?? '',
            $student['exam_date'] ?? '',
            $student['status']
        ]);
    }

    fclose($output);
} catch (Throwable $t) {
    echo "Error generating CSV: " . $t->getMessage();
}
?>
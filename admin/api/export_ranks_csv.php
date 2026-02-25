<?php
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="export_ranks_' . date('Ymd_His') . '.csv"');

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../class/StudentRegis.php';
require_once __DIR__ . '/../../class/AdminConfig.php';

session_start();
if (!isset($_SESSION['Admin_login'])) {
    http_response_code(401);
    die('Unauthorized');
}

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();

if (!$db) {
    die("Failed to obtain database connection.");
}

$adminConfig = new AdminConfig($db);
$studentRegis = new StudentRegis($db);

$typeId = isset($_GET['type_id']) ? intval($_GET['type_id']) : 0;
$date = isset($_GET['date']) ? $_GET['date'] : null;

if (!$typeId) {
    die("type_id is required.");
}

$type = $adminConfig->getRegistrationTypeById($typeId);
if (!$type) {
    die("Invalid type_id.");
}

// Ensure UTF-8 BOM so Excel opens it correctly with Thai characters
echo "\xEF\xBB\xBF";

$output = fopen('php://output', 'w');

// Write the header
fputcsv($output, ['citizenid', 'final_plan_id', 'pass_rank', 'is_called', 'fullname', 'gpa_total']);

$level = ($type['grade_code'] == 'm1') ? '1' : '4';
$students = $studentRegis->getStudentsPassed($level, $type['name'], $date);

foreach ($students as $student) {
    // Use tab to force Excel to treat citizenid as text
    fputcsv($output, [
        "\t" . $student['citizenid'],
        $student['final_plan_id'],
        $student['pass_rank'],
        $student['is_called'],
        $student['fullname'],
        $student['gpa_total']
    ]);
}

fclose($output);
?>
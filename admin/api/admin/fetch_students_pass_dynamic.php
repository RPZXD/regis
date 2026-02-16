<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../../config/Database.php';
require_once __DIR__ . '/../../../class/StudentRegis.php';
require_once __DIR__ . '/../../../class/AdminConfig.php';

session_start();
if (!isset($_SESSION['Admin_login'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();

try {
    $adminConfig = new AdminConfig($db);
    $studentRegis = new StudentRegis($db);

    $typeId = isset($_GET['type_id']) ? intval($_GET['type_id']) : 0;
    $date = isset($_GET['date']) ? $_GET['date'] : null;

    if ($typeId) {
        $type = $adminConfig->getRegistrationTypeById($typeId);
        if ($type) {
            $level = ($type['grade_code'] == 'm1') ? '1' : '4';
            $students = $studentRegis->getStudentsPassed($level, $type['name'], $date);
            echo json_encode($students);
            exit;
        }
    }

    echo json_encode([]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal Server Error', 'message' => $e->getMessage()]);
    exit;
}
?>
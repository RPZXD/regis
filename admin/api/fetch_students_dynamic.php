<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../../config/Database.php';
require_once __DIR__ . '/../../../class/StudentRegis.php';

try {
    session_start();
    if (!isset($_SESSION['Admin_login'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    $connectDB = new Database_Regis();
    $db = $connectDB->getConnection();

    if (!$db) {
        throw new Exception("Failed to obtain database connection.");
    }

    $typeId = isset($_GET['type_id']) ? intval($_GET['type_id']) : 0;
    
    if ($typeId) {
        require_once __DIR__ . '/../../../class/AdminConfig.php';
        $adminConfig = new AdminConfig($db);
        $type = $adminConfig->getRegistrationTypeById($typeId);
        
        if ($type) {
            $level = ($type['grade_code'] == 'm1') ? '1' : '4';
            $students = $studentRegis->getStudentsByCriteria($level, $type['name']);
            echo json_encode($students);
            exit;
        }
    }

    echo json_encode([]);
} catch (Throwable $t) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal Server Error',
        'message' => $t->getMessage()
    ]);
    exit;
}
?>

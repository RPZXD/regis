<?php
header('Content-Type: application/json');
require_once('../../../config/Database.php');
require_once('../../../class/AdminConfig.php');

session_start();
if (!isset($_SESSION['Admin_login'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();
$config = new AdminConfig($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $typeId = $_GET['type_id'] ?? null;
        echo json_encode($config->getStudyPlans($typeId));
        break;
    
    case 'POST':
        $data = [
            'registration_type_id' => $_POST['registration_type_id'],
            'code' => $_POST['code'],
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? '',
            'quota' => $_POST['quota'] ?? 0,
            'is_active' => $_POST['is_active'] ?? 1,
            'sort_order' => $_POST['sort_order'] ?? 0
        ];
        if ($config->addStudyPlan($data)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to add plan']);
        }
        break;
    
    case 'PUT':
        parse_str(file_get_contents('php://input'), $data);
        $id = $data['id'] ?? 0;
        if ($id && $config->updateStudyPlan($id, $data)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to update plan']);
        }
        break;
    
    case 'DELETE':
        $id = $_GET['id'] ?? 0;
        if ($id && $config->deleteStudyPlan($id)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to delete plan']);
        }
        break;
    
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}

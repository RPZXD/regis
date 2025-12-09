<?php
header('Content-Type: application/json');
require_once('../../config/Database.php');
require_once('../../class/AdminConfig.php');

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
        $planId = $_GET['plan_id'] ?? null;
        if ($planId) {
            echo json_encode($config->getPlanFees($planId));
        } else {
            echo json_encode([]);
        }
        break;
    
    case 'POST':
        $data = [
            'plan_id' => $_POST['plan_id'],
            'category' => $_POST['category'],
            'item_name' => $_POST['item_name'],
            'term1_amount' => $_POST['term1_amount'] ?? 0,
            'term2_amount' => $_POST['term2_amount'] ?? 0,
            'sort_order' => $_POST['sort_order'] ?? 0
        ];
        if ($config->addPlanFee($data)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to add fee']);
        }
        break;
    
    case 'PUT':
        parse_str(file_get_contents('php://input'), $data);
        $id = $data['id'] ?? 0;
        if ($id && $config->updatePlanFee($id, $data)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to update fee']);
        }
        break;
    
    case 'DELETE':
        $id = $_GET['id'] ?? 0;
        if ($id && $config->deletePlanFee($id)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to delete fee']);
        }
        break;
    
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
?>

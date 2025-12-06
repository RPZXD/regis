<?php
header('Content-Type: application/json');
require_once('../../../config/Database.php');
require_once('../../../class/AdminConfig.php');

session_start();
if (!isset($_SESSION['Teacher_login'])) {
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
        echo json_encode($config->getMenus());
        break;
    
    case 'POST':
        $id = $_POST['id'] ?? 0;
        $data = [
            'is_enabled' => $_POST['is_enabled'] ?? 1,
            'use_schedule' => $_POST['use_schedule'] ?? 0,
            'start_datetime' => $_POST['start_datetime'] ?: null,
            'end_datetime' => $_POST['end_datetime'] ?: null
        ];
        if ($id && $config->updateMenu($id, $data)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to update menu']);
        }
        break;
    
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}

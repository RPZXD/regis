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
        echo json_encode($config->getAllSettings());
        break;
    
    case 'POST':
        $key = $_POST['key'] ?? '';
        $value = $_POST['value'] ?? '';
        if ($key && $config->setSetting($key, $value)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to update setting']);
        }
        break;
    
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}

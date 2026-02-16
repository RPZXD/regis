<?php
header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../../../config/Database.php';
    require_once __DIR__ . '/../../../class/AdminConfig.php';

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
} catch (Throwable $t) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal Server Error',
        'message' => $t->getMessage()
    ]);
}
?>
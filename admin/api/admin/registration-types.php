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
        $gradeId = $_GET['grade_id'] ?? null;
        echo json_encode($config->getRegistrationTypes($gradeId));
        break;
    
    case 'POST':
        $action = $_POST['action'] ?? 'add';
        
        if ($action === 'update' && isset($_POST['id'])) {
            // Quick update (url, is_active)
            $id = $_POST['id'];
            $sql = "UPDATE registration_types SET url = ?, is_active = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([
                $_POST['url'] ?? '',
                $_POST['is_active'] ?? 1,
                $id
            ]);
            echo json_encode(['success' => $result]);
        } elseif ($action === 'update_schedules' && isset($_POST['id'])) {
            // Update schedule periods
            $id = $_POST['id'];
            $sql = "UPDATE registration_types SET 
                    register_start = ?, register_end = ?,
                    print_form_start = ?, print_form_end = ?,
                    upload_start = ?, upload_end = ?,
                    exam_card_start = ?, exam_card_end = ?,
                    report_start = ?, report_end = ?,
                    announce_start = ?, announce_end = ?
                    WHERE id = ?";
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([
                $_POST['register_start'] ?: null, $_POST['register_end'] ?: null,
                $_POST['print_form_start'] ?: null, $_POST['print_form_end'] ?: null,
                $_POST['upload_start'] ?: null, $_POST['upload_end'] ?: null,
                $_POST['exam_card_start'] ?: null, $_POST['exam_card_end'] ?: null,
                $_POST['report_start'] ?: null, $_POST['report_end'] ?: null,
                $_POST['announce_start'] ?: null, $_POST['announce_end'] ?: null,
                $id
            ]);
            echo json_encode(['success' => $result]);
        } else {
            // Add new type
            $data = [
                'grade_level_id' => $_POST['grade_level_id'],
                'code' => $_POST['code'],
                'name' => $_POST['name'],
                'description' => $_POST['description'] ?? '',
                'url' => $_POST['url'] ?? '',
                'is_active' => $_POST['is_active'] ?? 1,
                'use_schedule' => $_POST['use_schedule'] ?? 0,
                'start_datetime' => $_POST['start_datetime'] ?: null,
                'end_datetime' => $_POST['end_datetime'] ?: null,
                'sort_order' => $_POST['sort_order'] ?? 0
            ];
            if ($config->addRegistrationType($data)) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Failed to add type']);
            }
        }
        break;
    
    case 'PUT':
        parse_str(file_get_contents('php://input'), $data);
        $id = $data['id'] ?? 0;
        if ($id && $config->updateRegistrationType($id, $data)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to update type']);
        }
        break;
    
    case 'DELETE':
        $id = $_GET['id'] ?? 0;
        if ($id && $config->deleteRegistrationType($id)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to delete type']);
        }
        break;
    
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}

<?php
header('Content-Type: application/json');
require_once('../../../config/Database.php');

session_start();
if (!isset($_SESSION['Admin_login'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $typeId = $_GET['type_id'] ?? null;
        
        $sql = "SELECT dr.*, rt.name as type_name, gl.name as grade_name
                FROM document_requirements dr
                JOIN registration_types rt ON dr.registration_type_id = rt.id
                JOIN grade_levels gl ON rt.grade_level_id = gl.id";
        
        if ($typeId) {
            $sql .= " WHERE dr.registration_type_id = ?";
            $stmt = $db->prepare($sql . " ORDER BY dr.sort_order");
            $stmt->execute([$typeId]);
        } else {
            $stmt = $db->prepare($sql . " ORDER BY rt.id, dr.sort_order");
            $stmt->execute();
        }
        
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;
    
    case 'POST':
        $action = $_POST['action'] ?? 'add';
        
        if ($action === 'add') {
            $sql = "INSERT INTO document_requirements 
                    (registration_type_id, name, description, is_required, file_types, max_size_mb, sort_order)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([
                $_POST['registration_type_id'],
                $_POST['name'],
                $_POST['description'] ?? '',
                $_POST['is_required'] ?? 1,
                $_POST['file_types'] ?? 'jpg,jpeg,png,pdf',
                $_POST['max_size_mb'] ?? 5,
                $_POST['sort_order'] ?? 0
            ]);
            echo json_encode(['success' => $result, 'id' => $db->lastInsertId()]);
        } elseif ($action === 'update' && isset($_POST['id'])) {
            $sql = "UPDATE document_requirements SET 
                    name = ?, description = ?, is_required = ?, 
                    file_types = ?, max_size_mb = ?, is_active = ?
                    WHERE id = ?";
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([
                $_POST['name'],
                $_POST['description'] ?? '',
                $_POST['is_required'] ?? 1,
                $_POST['file_types'] ?? 'jpg,jpeg,png,pdf',
                $_POST['max_size_mb'] ?? 5,
                $_POST['is_active'] ?? 1,
                $_POST['id']
            ]);
            echo json_encode(['success' => $result]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
        }
        break;
    
    case 'DELETE':
        $id = $_GET['id'] ?? 0;
        if ($id) {
            $stmt = $db->prepare("DELETE FROM document_requirements WHERE id = ?");
            $result = $stmt->execute([$id]);
            echo json_encode(['success' => $result]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Missing ID']);
        }
        break;
    
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}

<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/Database.php';

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

    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
        exit;
    }

    $id = $data['id'] ?? null;
    $name = trim($data['name'] ?? '');
    $building = trim($data['building'] ?? '');
    $seats = $data['seats'] ?? null;
    $capacity = $data['capacity'] ?? null;
    $details = trim($data['details'] ?? '');
    $isActive = $data['is_active'] ?? true;

    if (empty($name) || empty($building)) {
        echo json_encode(['success' => false, 'message' => 'Room name and building are required']);
        exit;
    }

    if ($id) {
        // Update existing room
        $sql = "UPDATE exam_rooms SET name = ?, building = ?, seats = ?, capacity = ?, details = ?, is_active = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$name, $building, $seats, $capacity, $details, $isActive, $id]);
    } else {
        // Create new room
        $sql = "INSERT INTO exam_rooms (name, building, seats, capacity, details, is_active) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$name, $building, $seats, $capacity, $details, $isActive]);
    }

    if ($result) {
        echo json_encode(['success' => true, 'message' => $id ? 'Room updated successfully' : 'Room created successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save room']);
    }
} catch (Throwable $t) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal Server Error',
        'message' => $t->getMessage()
    ]);
    exit;
}
?>

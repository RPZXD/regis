<?php
header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../../../config/Database.php';

    session_start();
    if (!isset($_SESSION['Admin_login'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    $citizenid = $_GET['citizenid'] ?? '';
    $typeId = isset($_GET['type_id']) ? intval($_GET['type_id']) : 0;

    if (!$citizenid || !$typeId) {
        echo json_encode([]);
        exit;
    }

    $connectDB = new Database_Regis();
    $db = $connectDB->getConnection();

    if (!$db) {
        throw new Exception("Failed to obtain database connection.");
    }

    $sql = "SELECT sd.*, dr.name as doc_name, dr.is_required 
            FROM student_documents sd 
            JOIN document_requirements dr ON sd.requirement_id = dr.id 
            WHERE sd.citizenid = ? AND dr.registration_type_id = ?
            ORDER BY dr.sort_order";

    $stmt = $db->prepare($sql);
    $stmt->execute([$citizenid, $typeId]);
    $docs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($docs);
} catch (Throwable $t) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal Server Error',
        'message' => $t->getMessage()
    ]);
}
?>
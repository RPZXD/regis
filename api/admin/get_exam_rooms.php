<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../../config/Database.php';
require_once __DIR__ . '/../../../class/AdminConfig.php';

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

    // Create exam_rooms table if it doesn't exist
    $createTableSQL = "CREATE TABLE IF NOT EXISTS exam_rooms (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        building VARCHAR(100) NOT NULL,
        seats INT DEFAULT NULL,
        capacity INT DEFAULT NULL,
        details TEXT DEFAULT NULL,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $db->exec($createTableSQL);

    // Fetch all rooms
    $stmt = $db->prepare("SELECT * FROM exam_rooms ORDER BY building, name");
    $stmt->execute();
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($rooms);
} catch (Throwable $t) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal Server Error',
        'message' => $t->getMessage()
    ]);
    exit;
}
?>

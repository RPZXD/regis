<?php
header("Content-Type: application/json; charset=UTF-8");
include_once("../../config/Database.php");

session_start();
if (!isset($_SESSION['Admin_login'])) {
    http_response_code(401);
    echo json_encode(array("success" => false, "message" => "Unauthorized"));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(array("success" => false, "message" => "Only POST method is allowed."));
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['id'])) {
    http_response_code(400);
    echo json_encode(array("success" => false, "message" => "Invalid JSON or missing ID."));
    exit();
}

$database = new Database_Regis();
$db = $database->getConnection();

try {
    $isCalled = isset($data['is_called']) ? intval($data['is_called']) : 0;

    $sql = "UPDATE users SET is_called = :is_called WHERE id = :id";
    $stmt = $db->prepare($sql);

    $params = [
        ':is_called' => $isCalled,
        ':id' => $data['id']
    ];

    if ($stmt->execute($params)) {
        echo json_encode(array("success" => true, "message" => "Updated successfully."));
    } else {
        echo json_encode(array("success" => false, "message" => "Database update failed."));
    }

} catch (PDOException $e) {
    echo json_encode(array("success" => false, "message" => "Error: " . $e->getMessage()));
}
?>
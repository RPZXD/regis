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
    $planId = !empty($data['final_plan_id']) ? $data['final_plan_id'] : null;

    $sql = "UPDATE users SET final_plan_id = :final_plan_id WHERE id = :id";
    $stmt = $db->prepare($sql);
    
    $params = [
        ':final_plan_id' => $planId,
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

<?php

header("Content-Type: application/json; charset=UTF-8");
include_once("../../config/Database.php");
include_once("../../class/StudentRegis.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(array("success" => false, "message" => "Only POST method is allowed."));
    exit();
}

// Get input JSON data
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['id'])) {
    http_response_code(400);
    echo json_encode(array("success" => false, "message" => "Invalid JSON or missing ID."));
    exit();
}

$database = new Database_Regis();
$db = $database->getConnection();
$student = new StudentRegis($db);

// Call the deleteStudent method with the ID from the request
$result = $student->deleteStudent($data['id']);

// Return response based on the result
if ($result) {
    echo json_encode(array("success" => true, "message" => "Student deleted successfully."));
} else {
    echo json_encode(array("success" => false, "message" => "Failed to delete student."));
}
?>

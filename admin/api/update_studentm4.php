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

if (!$data) {
    http_response_code(400);
    echo json_encode(array("success" => false, "message" => "Invalid JSON or empty request body."));
    exit();
}

// Check if all required fields are present
if (isset($data['id'], $data['citizenid'], $data['stu_prefix'], $data['stu_name'], 
          $data['stu_lastname'], $data['date_birth'], $data['month_birth'], 
          $data['year_birth'], $data['now_tel'], $data['parent_tel'], $data['gpa_total'])) {

    $database = new Database_Regis();
    $db = $database->getConnection();
    $student = new StudentRegis($db);

    // Call the updateStudent method with the data from the request
    $result = $student->updateStudentM4(
        $data['id'],
        $data['citizenid'],
        $data['typeregis'],
        $data['stu_prefix'],
        $data['stu_name'],
        $data['stu_lastname'],
        $data['date_birth'],
        $data['month_birth'],
        $data['year_birth'],
        $data['now_tel'],
        $data['parent_tel'],
        $data['gpa_total'],
        $data['number1'],
        $data['number2'],
        $data['number3'],
        $data['number4'],
        $data['number5'],
        $data['number6']
    );

    // Return response based on the result
    if ($result) {
        echo json_encode(array("success" => true, "message" => "Student updated successfully."));
    } else {
        echo json_encode(array("success" => false, "message" => "Failed to update student."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("success" => false, "message" => "Incomplete data."));
}

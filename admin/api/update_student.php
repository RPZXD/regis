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

    // Collect plans
    $plans = [];
    for ($i = 1; $i <= 10; $i++) {
        if (isset($data['number' . $i]) && !empty($data['number' . $i])) {
            $plans[$i] = $data['number' . $i];
        }
    }

    // Call the updateStudent method
    // Pass the entire $data array to the method
    $result = $student->updateStudent(
        $data['id'],
        $data,
        $plans
    );

    // Return response based on the result
    if ($result === true) {
        echo json_encode(array("success" => true, "message" => "Student updated successfully."));
    } else {
        // Result contains the error message
        echo json_encode(array("success" => false, "message" => "Failed to update student: " . $result));
    }
} else {
    http_response_code(400);
    echo json_encode(array("success" => false, "message" => "Incomplete data."));
}

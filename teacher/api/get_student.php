<?php
include_once("../../config/Database.php");
include_once("../../class/StudentRegis.php");

$database = new Database_Regis();
$db = $database->getConnection();

$student = new StudentRegis($db);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $studentData = $student->getStudentById($id);
    echo json_encode($studentData);
} else {
    echo json_encode(array("message" => "Student ID not provided"));
}
?>

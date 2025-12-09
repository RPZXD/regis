<?php
include_once("../../config/Database.php");
include_once("../../class/StudentRegis.php");

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();

$studentRegis = new StudentRegis($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numreg = $_POST['numreg'];
    $status = $_POST['status'];

    if ($studentRegis->updateConfirmStatus($numreg, $status)) {
        echo json_encode(["message" => "Status updated successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to update status"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed"]);
}
?>

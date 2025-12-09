<?php

include_once("../../config/Database.php");
include_once("../../class/StudentRegis.php");

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();

$studentRegis = new StudentRegis($db);

$citizenid = $_POST['citizenid'];
$status = $_POST['status'];

$response = $studentRegis->updateAllUploadStatuses($citizenid, $status);

header('Content-Type: application/json');
echo json_encode(['success' => $response]);
?>

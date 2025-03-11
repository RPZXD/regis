<?php

include_once("../../config/Database.php");
include_once("../../class/StudentRegis.php");

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();

$studentRegis = new StudentRegis($db);
$students = $studentRegis->getM4QuotaStudentsConfirm();

header('Content-Type: application/json');
echo json_encode($students);
?>

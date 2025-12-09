<?php

include_once("../../config/Database.php");
include_once("../../class/StudentRegis.php");

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();

$studentRegis = new StudentRegis($db);

$date = isset($_GET['date']) ? $_GET['date'] : null;
$students = $studentRegis->getM4NomalStudents_Pass($date);

header('Content-Type: application/json');
echo json_encode($students);
?>

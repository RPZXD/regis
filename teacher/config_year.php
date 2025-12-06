<?php 
session_start();
require_once("../config/Database.php");
require_once("../config/Setting.php");
require_once("../class/UserLogin.php");
require_once("../class/Utils.php");

$connectDB = new Database_User();
$db = $connectDB->getConnection();
$user = new UserLogin($db);
$setting = new Setting();

if (isset($_SESSION['Teacher_login'])) {
    $userid = $_SESSION['Teacher_login'];
    $userData = $user->userData($userid);
} else {
    $sw2 = new SweetAlert2('คุณยังไม่ได้เข้าสู่ระบบ', 'error', '../login.php');
    $sw2->renderAlert();
    exit;
}

$term = $user->getTerm();
$pee = $user->getPee();

$pageTitle = 'ตั้งค่าปีการศึกษา';
ob_start();
require '../views/teacher/config-year.php';
$content = ob_get_clean();
require '../views/teacher/layouts/app.php';

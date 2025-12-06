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

$pageTitle = 'รายงานตัว ม.4 (โควต้า ม.3 เดิม)';
ob_start();
require '../views/teacher/con-m4-quota.php';
$content = ob_get_clean();
require '../views/teacher/layouts/app.php';

<?php 
/**
 * M1 Normal Student List
 * Uses the new MVC layout with modern UI
 */
session_start();

require_once("../config/Database.php");
require_once("../config/Setting.php");
require_once("../class/UserLogin.php");
require_once("../class/Utils.php");

// Initialize database connection
$connectDB = new Database_User();
$db = $connectDB->getConnection();

// Initialize classes
$user = new UserLogin($db);
$setting = new Setting();

// Fetch terms and pee
$term = $user->getTerm();
$pee = $user->getPee();

// Check login
if (isset($_SESSION['Teacher_login'])) {
    $userid = $_SESSION['Teacher_login'];
    $userData = $user->userData($userid);
} else {
    $sw2 = new SweetAlert2('คุณยังไม่ได้เข้าสู่ระบบ', 'error', '../login.php');
    $sw2->renderAlert();
    exit;
}
$teacher_id = $userData['Teacher_id'];

$pageTitle = 'ข้อมูล ม.1 (ทั่วไป)';

ob_start();
require '../views/teacher/m1-nomal.php';
$content = ob_get_clean();
require '../views/teacher/layouts/app.php';

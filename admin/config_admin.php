<?php 
session_start();
require_once("../config/Database.php");
require_once("../config/Setting.php");
require_once("../class/UserLogin.php");
require_once("../class/Utils.php");
require_once("../class/AdminConfig.php");

// Initialize database connection
$connectDB = new Database_User();
$db = $connectDB->getConnection();
$connectDBregis = new Database_Regis();
$dbRegis = $connectDBregis->getConnection();

// Initialize classes
require_once("../class/UserAdminLogin.php");
$user = new UserLogin($db); // Keep for utility if needed?
$userAdmin = new UserAdminLogin($dbRegis);
$adminConfig = new AdminConfig($dbRegis);

$setting = new Setting();

// Fetch terms and pee
$term = $user->getTerm();
$pee = $user->getPee();

// Check login
if (isset($_SESSION['Admin_login'])) {
    $userid = $_SESSION['Admin_login'];
    $userData = $userAdmin->userData($userid); 
} else {
    $sw2 = new SweetAlert2(
        'คุณยังไม่ได้เข้าสู่ระบบ (Admin)',
        'error',
        '../login.php'
    );
    $sw2->renderAlert();
    exit;
}



$pageTitle = 'ตั้งค่าระบบ';
ob_start();
require '../views/admin/config-admin.php';
$content = ob_get_clean();
require '../views/admin/layouts/app.php';

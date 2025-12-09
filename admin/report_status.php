<?php 
/**
 * Report Status Controller - Admin view for confirmed/cancelled students
 */
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
$user = new UserLogin($db);
$userAdmin = new UserAdminLogin($dbRegis);
$adminConfig = new AdminConfig($dbRegis);
$setting = new Setting();

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

$pageTitle = 'สถานะการรายงานตัว';

ob_start();
require '../views/admin/report-status.php';
$content = ob_get_clean();
require '../views/admin/layouts/app.php';

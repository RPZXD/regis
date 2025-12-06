<?php 
/**
 * Teacher Dashboard
 * Uses the new MVC layout with modern UI
 */
session_start();

require_once("../config/Database.php");
require_once("../config/Setting.php");
require_once("../class/UserLogin.php");
require_once("../class/Utils.php");
require_once("../class/StudentRegis.php");

// Initialize database connection
$connectDB = new Database_User();
$db = $connectDB->getConnection();
$connectDBregis = new Database_Regis();
$dbRegis = $connectDBregis->getConnection();

// Initialize classes
$user = new UserLogin($db);
$studentRegis = new StudentRegis($dbRegis);
$setting = new Setting();

// Fetch terms and pee
$term = $user->getTerm();
$pee = $user->getPee();

// Check login
if (isset($_SESSION['Teacher_login'])) {
    $userid = $_SESSION['Teacher_login'];
    $userData = $user->userData($userid);
} else {
    $sw2 = new SweetAlert2(
        'คุณยังไม่ได้เข้าสู่ระบบ',
        'error',
        '../login.php'
    );
    $sw2->renderAlert();
    exit;
}

// Fetch counts
$Count_Confirm_Confirmed = $studentRegis->countConfirm(1, 4, 'โควต้า', 2568);
$Count_Confirm_Declined = $studentRegis->countConfirm(9, 4, 'โควต้า', 2568);
$Count_Confirm_Pending = $studentRegis->countConfirm(0, 4, 'โควต้า', 2568);
$Count_m1_in = $studentRegis->countRegis(1, 'ในเขต', 2568);
$Count_m1_out = $studentRegis->countRegis(1, 'นอกเขต', 2568);
$Count_m4 = $studentRegis->countRegis(4, 'รอบทั่วไป', 2568);

$pageTitle = 'หน้าหลัก';

ob_start();
require '../views/teacher/dashboard.php';
$content = ob_get_clean();
require '../views/teacher/layouts/app.php';

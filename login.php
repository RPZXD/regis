<?php 
/**
 * Login Page
 * Uses the new MVC layout with modern UI
 */
session_start();
require_once 'config/Setting.php';
require_once 'config/Database.php';
require_once 'class/UserLogin.php';
require_once 'class/Utils.php';

$setting = new Setting();

// Check if already logged in - redirect to appropriate dashboard
if (isset($_SESSION['Admin_login'])) {
    header("location: admin/index.php");
    exit;
}
/*
} else if (isset($_SESSION['Director_login'])) {
    header("location: director/index.php");
    exit;
} else if (isset($_SESSION['Officer_login'])) {
    header("location: Officer/index.php");
    exit;
} else if (isset($_SESSION['Admin_login'])) {
    // Already handled above or merged
    header("location: admin/index.php");
    exit;
} else if (isset($_SESSION['Student_login'])) {
    header("location: student/index.php");
    exit;
}
*/

// Handle login form submission
if (isset($_POST['signin'])) {
    $username = filter_input(INPUT_POST, 'txt_username_email', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'txt_password', FILTER_SANITIZE_STRING);

    // Initialize Database
    $connectDBRegis = new Database_Regis();
    $dbRegis = $connectDBRegis->getConnection();
    
    // Admin Login Logic
    require_once 'class/UserAdminLogin.php';
    $userAdmin = new UserAdminLogin($dbRegis);
    $userAdmin->setUsername($username);
    $userAdmin->setPassword($password);

    if ($userAdmin->userNotExists()) {
        $sw2 = new SweetAlert2('ไม่มีชื่อผู้ใช้นี้', 'error', 'login.php');
        $sw2->renderAlert();
    } else {
        if ($userAdmin->verifyPassword()) {
            // Session 'Admin_login' is set in verifyPassword
            $sw2 = new SweetAlert2('ลงชื่อเข้าสู่ระบบเรียบร้อย', 'success', 'admin/index.php');
            $sw2->renderAlert();
        } else {
            $sw2 = new SweetAlert2('พาสเวิร์ดไม่ถูกต้อง', 'error', 'login.php');
            $sw2->renderAlert();
        }
    }
    
    /* 
    // Legacy Logic (Commented out as per request "other roles not used")
    $connectDB = new Database_User();
    $db = $connectDB->getConnection();
    ...
    */
}

$pageTitle = 'เข้าสู่ระบบ';

ob_start();
require 'views/auth/login.php';
$content = ob_get_clean();
require 'views/layouts/app.php';

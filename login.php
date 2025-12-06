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
if (isset($_SESSION['Teacher_login'])) {
    header("location: teacher/index.php");
    exit;
} else if (isset($_SESSION['Director_login'])) {
    header("location: director/index.php");
    exit;
} else if (isset($_SESSION['Officer_login'])) {
    header("location: Officer/index.php");
    exit;
} else if (isset($_SESSION['Admin_login'])) {
    header("location: admin/index.php");
    exit;
} else if (isset($_SESSION['Student_login'])) {
    header("location: student/index.php");
    exit;
}

// Handle login form submission
if (isset($_POST['signin'])) {
    $connectDB = new Database_User();
    $db = $connectDB->getConnection();
    
    $user = new UserLogin($db);
    $bs = new Bootstrap();

    $username = filter_input(INPUT_POST, 'txt_username_email', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'txt_password', FILTER_SANITIZE_STRING);

    $allowed_roles = ['Admin', 'Teacher', 'Officer'];
    $role = filter_input(INPUT_POST, 'txt_role', FILTER_SANITIZE_STRING);
    
    if (!in_array($role, $allowed_roles)) {
        $role = 'Teacher';
    }

    $user->setUsername($username);
    $user->setPassword($password);

    if ($user->userNotExists()) {
        $sw2 = new SweetAlert2('ไม่มีชื่อผู้ใช้นี้', 'error', 'login.php');
        $sw2->renderAlert();
    } else {
        if ($user->verifyPassword()) {
            $userRole = $user->getUserRole();
            $allowedUserRolesForTeacher = ['T', 'ADM', 'VP', 'OF', 'DIR'];
            $allowedUserRolesForOfficer = ['ADM', 'OF'];
            $allowedUserRolesForAdmin = ['ADM'];
            
            if (in_array($userRole, $allowedUserRolesForTeacher) && $role === 'Teacher') {
                $_SESSION['Teacher_login'] = $_SESSION['user'];
                $sw2 = new SweetAlert2('ลงชื่อเข้าสู่ระบบเรียบร้อย', 'success', 'teacher/index.php');
                $sw2->renderAlert();
            } 
            else if (in_array($userRole, $allowedUserRolesForOfficer) && $role === 'Officer') {
                $_SESSION['Officer_login'] = $_SESSION['user'];
                $sw2 = new SweetAlert2('ลงชื่อเข้าสู่ระบบเรียบร้อย', 'success', 'Officer/index.php');
                $sw2->renderAlert();
            } 
            else if (in_array($userRole, $allowedUserRolesForAdmin) && $role === 'Admin') {
                $_SESSION['Admin_login'] = $_SESSION['user'];
                $sw2 = new SweetAlert2('ลงชื่อเข้าสู่ระบบเรียบร้อย', 'success', 'Admin/index.php');
                $sw2->renderAlert();
            } 
            else {
                $sw2 = new SweetAlert2('บทบาทผู้ใช้ไม่ถูกต้อง', 'error', 'login.php');
                $sw2->renderAlert();
            } 
        } else {
            $sw2 = new SweetAlert2('พาสเวิร์ดไม่ถูกต้อง', 'error', 'login.php');
            $sw2->renderAlert();
        }
    }
}

$pageTitle = 'เข้าสู่ระบบ';

ob_start();
require 'views/auth/login.php';
$content = ob_get_clean();
require 'views/layouts/app.php';

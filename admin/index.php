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
require_once("../class/UserAdminLogin.php");
$user = new UserLogin($db); // Keep for utility if needed?
$userAdmin = new UserAdminLogin($dbRegis);
require_once("../class/AdminConfig.php");
$adminConfig = new AdminConfig($dbRegis);
$studentRegis = new StudentRegis($dbRegis);

$setting = new Setting();

// Fetch terms and pee
$term = $user->getTerm();
$pee = $user->getPee();

// Check login
if (isset($_SESSION['Admin_login'])) {
    $userid = $_SESSION['Admin_login'];
    $userData = $userAdmin->userData($userid); 
    
    // Fetch Dynamic Stats
    $regisTypes = $adminConfig->getRegistrationTypes();
    $dashboardStats = [];
    
    foreach ($regisTypes as $type) {
        $gradeCode = $type['grade_code']; // 'm1' or 'm4'
        $stats = $studentRegis->countStudentsByCriteria($gradeCode == 'm1' ? '1' : '4', $type['name']);
        
        $dashboardStats[$gradeCode][] = [
            'id' => $type['id'],
            'name' => $type['name'],
            'total' => $stats['total'] ?? 0,
            'confirmed' => $stats['confirmed'] ?? 0,
            'pending' => $stats['pending'] ?? 0
        ];
    }
} else {
    $sw2 = new SweetAlert2('คุณยังไม่ได้เข้าสู่ระบบ (Admin)', 'error', '../login.php');
    $sw2->renderAlert();
    exit;
}


$pageTitle = 'หน้าหลัก';

ob_start();
require '../views/admin/dashboard.php';
$content = ob_get_clean();
require '../views/admin/layouts/app.php';

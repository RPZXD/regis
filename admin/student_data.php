<?php 
/**
 * Dynamic Student Data Controller
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

// Get Type ID
$typeId = isset($_GET['type_id']) ? intval($_GET['type_id']) : 0;
$regisType = $adminConfig->getRegistrationTypeById($typeId);

if (!$regisType) {
    // Redirect or show error if type not found
    header("Location: index.php");
    exit;
}

$pageTitle = $regisType['grade_name'] . ' (' . $regisType['name'] . ')';

// Fetch study plans for mapping
$studyPlans = $adminConfig->getStudyPlans($typeId);
$plansMap = [];
foreach ($studyPlans as $plan) {
    $plansMap[$plan['id']] = $plan['name'];
}

ob_start();
require '../views/admin/student-data.php';
$content = ob_get_clean();
require '../views/admin/layouts/app.php';

<?php 
/**
 * Notification Settings Controller
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

$pageTitle = 'ตั้งค่าการแจ้งเตือน';

// Get current settings
$notificationSettings = [];
try {
    $stmt = $dbRegis->query("SELECT setting_key, setting_value FROM notification_settings");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $notificationSettings[$row['setting_key']] = $row['setting_value'];
    }
} catch (PDOException $e) {
    // Table might not exist, will be created on first save
}

ob_start();
require '../views/admin/notification-settings.php';
$content = ob_get_clean();
require '../views/admin/layouts/app.php';

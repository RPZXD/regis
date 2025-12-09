<?php 
/**
 * Admin - Document Review Page
 * For reviewing and approving/rejecting uploaded documents
 */
session_start();

require_once("../config/Database.php");
require_once("../config/Setting.php");
require_once("../class/AdminConfig.php");

// Initialize database connection
$connectDBregis = new Database_Regis();
$dbRegis = $connectDBregis->getConnection();

// Initialize classes
require_once("../class/UserAdminLogin.php");
$userAdmin = new UserAdminLogin($dbRegis);
$adminConfig = new AdminConfig($dbRegis);
$setting = new Setting();

// Check login
if (isset($_SESSION['Admin_login'])) {
    $userid = $_SESSION['Admin_login'];
    $userData = $userAdmin->userData($userid); 
} else {
    header("Location: login.php");
    exit;
}

$pageTitle = 'ตรวจสอบเอกสาร';

ob_start();
require '../views/admin/check-documents.php';
$content = ob_get_clean();
require '../views/admin/layouts/app.php';

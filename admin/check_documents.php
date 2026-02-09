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

// Get Type ID from URL (like student_data.php)
$typeId = isset($_GET['type_id']) ? intval($_GET['type_id']) : 0;
$regisType = null;

if ($typeId) {
    $regisType = $adminConfig->getRegistrationTypeById($typeId);
}

$pageTitle = 'ตรวจสอบเอกสาร';
if ($regisType) {
    $pageTitle .= ' - ' . $regisType['grade_name'] . ' (' . $regisType['name'] . ')';
}

ob_start();
require '../views/admin/check-documents.php';
$content = ob_get_clean();
require '../views/admin/layouts/app.php';


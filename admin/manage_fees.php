<?php
session_start();
require_once("../config/Database.php");
require_once("../class/AdminConfig.php");

if (!isset($_SESSION['Admin_login'])) {
    header("Location: ../login.php");
    exit;
}

$userData = $_SESSION['Admin_login'];
$pageTitle = 'จัดการค่าใช้จ่าย';

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();
$adminConfig = new AdminConfig($db);

// Fetch ALL plans (regardless of registration schedule)
$allPlans = $adminConfig->getStudyPlans();
foreach ($allPlans as &$plan) {
    $plan['grade_code'] = ($plan['grade_code'] == 'm1') ? 'ม.1' : 'ม.4';
}
unset($plan);

ob_start();
include '../views/admin/manage-fees.php';
$content = ob_get_clean();

include '../views/admin/layouts/app.php';
?>
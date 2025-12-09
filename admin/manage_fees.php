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

// Fetch all plans
// We need to iterate through all active types to get plans, or if getStudyPlans(null) works.
// Let's manually aggregate to be safe and get Type Names.
$allPlans = [];

$m1Types = $adminConfig->getActiveRegistrationTypes('m1');
$m4Types = $adminConfig->getActiveRegistrationTypes('m4');
$allTypes = array_merge($m1Types, $m4Types);



foreach ($allTypes as $type) {
    $plans = $adminConfig->getStudyPlans($type['id']);
    foreach ($plans as $plan) {
        $plan['type_name'] = $type['name'];
        $plan['grade_code'] = ($type['grade_code'] == 'm1') ? 'ม.1' : 'ม.4';
        $allPlans[] = $plan;
    }
}

ob_start();
include '../views/admin/manage-fees.php';
$content = ob_get_clean();

include '../views/admin/layouts/app.php';
?>

<?php
/**
 * Cancel Report Controller
 */
session_start();
require_once 'config/Database.php';
require_once 'class/StudentRegis.php';
require_once 'config/Setting.php';

$setting = new Setting();
$pageTitle = 'สละสิทธิ์การรายงานตัว';

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();

$studentRegis = new StudentRegis($db);

$studentData = null;
$error = null;
$success = false;

// Handle Search
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $citizenid = trim($_POST['citizenid']);
    
    if (empty($citizenid)) {
        $error = "กรุณากรอกเลขประจำตัวประชาชน";
    } else {
        $student = $studentRegis->getStudentByCitizenId($citizenid);
        
        if ($student) {
            $studentData = $student;
        } else {
            $error = "ไม่พบข้อมูลผู้สมัคร";
        }
    }
}

ob_start();
require 'views/confirm/cancel-report.php';
$content = ob_get_clean();

require 'views/layouts/app.php';
?>

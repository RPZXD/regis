<?php
/**
 * Index Page - Dashboard
 * Uses the new MVC layout with modern UI
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'config/Setting.php';
require_once 'config/Database.php';
require_once 'class/StudentRegis.php';

$setting = new Setting();

// Get database connection
$databaseRegis = new Database_Regis();
$db = $databaseRegis->getConnection();

// Get statistics
$studentRegis = new StudentRegis($db);
$year = date('Y') + 543; // Buddhist year

$stats = [
    'm1_in' => $studentRegis->countRegis(1, 'ในเขต', $year),
    'm1_out' => $studentRegis->countRegis(1, 'นอกเขต', $year),
    'm4_quota' => $studentRegis->countRegis(4, 'โควต้า', $year),
    'm4_normal' => $studentRegis->countRegis(4, 'รอบทั่วไป', $year),
];

$pageTitle = 'รับสมัครนักเรียน 2568';

// Render view with layout
ob_start();
require 'views/home/index.php';
$content = ob_get_clean();
require 'views/layouts/app.php';

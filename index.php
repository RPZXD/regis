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

require_once 'class/AdminConfig.php';

// Initialize classes
$adminConfig = new AdminConfig($db);

// Fetch Dynamic Stats
$regisTypes = $adminConfig->getRegistrationTypes(); // Get all types
$dashboardStats = [];

foreach ($regisTypes as $type) {
    if (!$type['is_active']) continue; // Show only active types

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

// Fetch Daily Stats (Last 7 Days)
$dailyStats = $studentRegis->getDailyRegistrations(7);

// Get academic year from settings
$academicYear = $adminConfig->getSetting('academic_year') ?? (date('Y') + 543);

$pageTitle = 'รับสมัครนักเรียน ' . $academicYear;

// Render view with layout
ob_start();
require 'views/home/index.php';
$content = ob_get_clean();
require 'views/layouts/app.php';

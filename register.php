<?php
/**
 * Unified Dynamic Registration Form
 * Single entry point for all registration types
 * Usage: register.php?type={type_id}
 */
session_start();
require_once 'config/Setting.php';
require_once 'config/Database.php';
require_once 'class/AdminConfig.php';

$setting = new Setting();
$connectDB = new Database_Regis();
$db = $connectDB->getConnection();
$adminConfig = new AdminConfig($db);

// Get type ID from URL
$typeId = intval($_GET['type'] ?? 0);

if (!$typeId) {
    header('Location: regis.php');
    exit;
}

// Get registration type details
$sql = "SELECT rt.*, gl.name as grade_name, gl.code as grade_code 
        FROM registration_types rt 
        JOIN grade_levels gl ON rt.grade_level_id = gl.id
        WHERE rt.id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$typeId]);
$registrationType = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$registrationType) {
    header('Location: regis.php');
    exit;
}

// Check if type is active
if (!$registrationType['is_active']) {
    header('Location: regis.php?error=inactive');
    exit;
}

// Check registration schedule (using register_start and register_end)
$now = new DateTime();
$registerStart = !empty($registrationType['register_start']) ? new DateTime($registrationType['register_start']) : null;
$registerEnd = !empty($registrationType['register_end']) ? new DateTime($registrationType['register_end']) : null;

// If schedule is not set, block access (must configure schedule first)
if (!$registerStart || !$registerEnd) {
    header('Location: regis.php?error=no_schedule');
    exit;
}

// Check if within registration period
if ($now < $registerStart) {
    // Registration hasn't started yet
    header('Location: regis.php?error=not_started');
    exit;
}
if ($now > $registerEnd) {
    // Registration has ended
    header('Location: regis.php?error=ended');
    exit;
}

// Get study plans for this type
$studyPlans = $adminConfig->getStudyPlans($typeId);

// Get academic year
$academicYear = $adminConfig->getSetting('academic_year') ?? (date('Y') + 543);

// Set page title
$gradeNumber = substr($registrationType['grade_code'], 1); // m1 -> 1, m4 -> 4
$pageTitle = "สมัครเรียน ม.{$gradeNumber} - {$registrationType['name']}";

// Pass data to view
$formData = [
    'type' => $registrationType,
    'plans' => $studyPlans,
    'grade' => $gradeNumber,
    'academicYear' => $academicYear
];

// Render view with layout
ob_start();
require 'views/registration/dynamic-form.php';
$content = ob_get_clean();
require 'views/layouts/app.php';

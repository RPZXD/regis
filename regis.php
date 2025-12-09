<?php
/**
 * Registration Selection Page
 * Uses the new MVC layout with modern UI and dynamic config
 */
session_start();
require_once 'config/Setting.php';
require_once 'config/Database.php';
require_once 'class/AdminConfig.php';

$setting = new Setting();
$connectDB = new Database_Regis();
$db = $connectDB->getConnection();
$adminConfig = new AdminConfig($db);

// Get settings
$academicYear = $adminConfig->getSetting('academic_year') ?? (date('Y') + 543);
$registrationOpen = $adminConfig->getSetting('registration_open') ?? 1;

// Get grade levels and their registration types
$gradeLevels = $adminConfig->getGradeLevels();
$registrationTypes = $adminConfig->getRegistrationTypes();

// Get menu config for calendar display
$menuConfig = $adminConfig->getMenus();

$pageTitle = 'เลือกประเภทการสมัคร';

// Render view with layout
ob_start();
require 'views/registration/select.php';
$content = ob_get_clean();
require 'views/layouts/app.php';

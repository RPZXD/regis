<?php
/**
 * Document Upload Page
 * Allows applicants to upload required documents
 */
session_start();
require_once 'config/Setting.php';
require_once 'config/Database.php';
require_once 'class/AdminConfig.php';

$setting = new Setting();
$connectDB = new Database_Regis();
$db = $connectDB->getConnection();
$adminConfig = new AdminConfig($db);

// Get academic year for display
$academicYear = $adminConfig->getSetting('academic_year') ?? (date('Y') + 543);

$pageTitle = 'อัพโหลดหลักฐานการสมัคร';

// Render view with layout
ob_start();
require 'views/upload/index.php';
$content = ob_get_clean();
require 'views/layouts/app.php';

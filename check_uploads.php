<?php
/**
 * Check Uploads Status Page
 * Uses the new MVC layout with modern UI
 */
session_start();
require_once 'config/Setting.php';
$setting = new Setting();
$pageTitle = 'ตรวจสอบสถานะอัพโหลด';

ob_start();
require 'views/check/uploads.php';
$content = ob_get_clean();
require 'views/layouts/app.php';

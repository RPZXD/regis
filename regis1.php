<?php
/**
 * M.1 Registration Form Page
 * Uses the new MVC layout with modern UI
 */
session_start();
require_once 'config/Setting.php';

$setting = new Setting();
$pageTitle = 'สมัครเรียน ม.1';

// Render view with layout
ob_start();
require 'views/registration/form-m1.php';
$content = ob_get_clean();
require 'views/layouts/app.php';

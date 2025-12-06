<?php
/**
 * Contact Page
 * Uses the new MVC layout with modern UI
 */
session_start();
require_once 'config/Setting.php';
$setting = new Setting();
$pageTitle = 'ติดต่อ-สอบถาม';

ob_start();
require 'views/contact/index.php';
$content = ob_get_clean();
require 'views/layouts/app.php';

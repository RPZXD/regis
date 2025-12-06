<?php
/**
 * Print Registration Page
 * Uses the new MVC layout with modern UI
 */
session_start();
require_once 'config/Setting.php';
$setting = new Setting();
$pageTitle = 'พิมพ์ใบสมัคร';

ob_start();
require 'views/print/registration.php';
$content = ob_get_clean();
require 'views/layouts/app.php';

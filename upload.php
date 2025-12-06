<?php
/**
 * Upload Selection Page
 * Uses the new MVC layout with modern UI
 */
session_start();
require_once 'config/Setting.php';
$setting = new Setting();
$pageTitle = 'อัพโหลดหลักฐาน';

ob_start();
require 'views/upload/select.php';
$content = ob_get_clean();
require 'views/layouts/app.php';

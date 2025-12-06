<?php
/**
 * Announcement Page
 * Uses the new MVC layout with modern UI
 */
session_start();
require_once 'config/Setting.php';
require_once 'config/Database.php';

$setting = new Setting();
$pageTitle = 'ประกาศรับสมัคร';

// Get media settings if needed
$databaseRegis = new Database_Regis();
$db = $databaseRegis->getConnection();
$media_sets = [];
try {
    $stmt = $db->query("SELECT * FROM setting WHERE config_name LIKE 'file_%'");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $media_sets[$row['config_name']] = $row['value'];
    }
} catch (Exception $e) {
    // Handle error silently
}

ob_start();
require 'views/announce/index.php';
$content = ob_get_clean();
require 'views/layouts/app.php';

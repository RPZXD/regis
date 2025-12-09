<?php
/**
 * Results Announcement Page
 * Displays exam results and allows students to search their results
 */
session_start();
require_once 'config/Setting.php';
require_once 'config/Database.php';
require_once 'class/AdminConfig.php';

$setting = new Setting();
$connectDB = new Database_Regis();
$db = $connectDB->getConnection();
$adminConfig = new AdminConfig($db);

// Get academic year
$academicYear = $adminConfig->getSetting('academic_year') ?? (date('Y') + 543);

// Get media sets (for PDF files)
$media_sets = [];
try {
    $stmt = $db->query("SELECT * FROM setting_media");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $media_sets[$row['key_name']] = $row['value'];
    }
} catch (Exception $e) {
    // Table might not exist
}

// Get result announcements
$m1Results = [];
$m4Results = [];

try {
    // Check if result_announcements table exists
    $tableCheck = $db->query("SHOW TABLES LIKE 'result_announcements'");
    if ($tableCheck->rowCount() > 0) {
        $stmt = $db->prepare("SELECT * FROM result_announcements WHERE is_active = 1 ORDER BY created_at DESC");
        $stmt->execute();
        $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($announcements as $ann) {
            if ($ann['grade_level'] == 'm1' || $ann['grade_level'] == '1') {
                $m1Results[] = $ann;
            } else {
                $m4Results[] = $ann;
            }
        }
    }
} catch (Exception $e) {
    // Table might not exist
}

$pageTitle = "ประกาศผลคัดเลือก";

// Render view with layout
ob_start();
require 'views/results/index.php';
$content = ob_get_clean();
require 'views/layouts/app.php';

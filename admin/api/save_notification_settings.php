<?php
header('Content-Type: application/json');
require_once('../../config/Database.php');

session_start();
if (!isset($_SESSION['Admin_login'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();

try {
    // Create table if not exists
    $db->exec("CREATE TABLE IF NOT EXISTS notification_settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) UNIQUE NOT NULL,
        setting_value TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    
    // Define settings to save
    $settings = [
        'discord_enabled' => isset($_POST['discord_enabled']) ? '1' : '0',
        'discord_webhook' => $_POST['discord_webhook'] ?? '',
        'discord_channel_name' => $_POST['discord_channel_name'] ?? '',
        'telegram_enabled' => isset($_POST['telegram_enabled']) ? '1' : '0',
        'telegram_bot_token' => $_POST['telegram_bot_token'] ?? '',
        'telegram_chat_id' => $_POST['telegram_chat_id'] ?? '',
        'notify_new_registration' => isset($_POST['notify_new_registration']) ? '1' : '0',
        'notify_document_upload' => isset($_POST['notify_document_upload']) ? '1' : '0',
        'notify_report_confirm' => isset($_POST['notify_report_confirm']) ? '1' : '0',
        'notify_report_cancel' => isset($_POST['notify_report_cancel']) ? '1' : '0',
    ];
    
    // Insert or update each setting
    $stmt = $db->prepare("INSERT INTO notification_settings (setting_key, setting_value) VALUES (?, ?) 
                          ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
    
    foreach ($settings as $key => $value) {
        $stmt->execute([$key, $value]);
    }
    
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>

<?php
header('Content-Type: application/json');

session_start();
if (!isset($_SESSION['Admin_login'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$type = $_POST['type'] ?? '';

if ($type === 'discord') {
    $webhook = $_POST['webhook'] ?? '';
    if (empty($webhook)) {
        echo json_encode(['success' => false, 'error' => 'Webhook URL is required']);
        exit;
    }
    
    $message = [
        'content' => '🔔 **ทดสอบการแจ้งเตือน**',
        'embeds' => [[
            'title' => '✅ การเชื่อมต่อสำเร็จ!',
            'description' => 'ระบบรับสมัครนักเรียนสามารถส่งการแจ้งเตือนมายัง Discord ได้แล้ว',
            'color' => 5814783, // Blue color
            'footer' => ['text' => 'ระบบรับสมัครนักเรียน'],
            'timestamp' => date('c')
        ]]
    ];
    
    $ch = curl_init($webhook);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode >= 200 && $httpCode < 300) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => "Discord API returned: $httpCode"]);
    }
    
} elseif ($type === 'telegram') {
    $token = $_POST['token'] ?? '';
    $chatId = $_POST['chat_id'] ?? '';
    
    if (empty($token) || empty($chatId)) {
        echo json_encode(['success' => false, 'error' => 'Bot Token and Chat ID are required']);
        exit;
    }
    
    $message = "🔔 *ทดสอบการแจ้งเตือน*\n\n✅ การเชื่อมต่อสำเร็จ!\nระบบรับสมัครนักเรียนสามารถส่งการแจ้งเตือนมายัง Telegram ได้แล้ว";
    
    $url = "https://api.telegram.org/bot{$token}/sendMessage";
    $data = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'Markdown'
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $result = json_decode($response, true);
    curl_close($ch);
    
    if (isset($result['ok']) && $result['ok']) {
        echo json_encode(['success' => true]);
    } else {
        $error = $result['description'] ?? 'Unknown error';
        echo json_encode(['success' => false, 'error' => $error]);
    }
    
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid notification type']);
}
?>

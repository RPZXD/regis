<?php
/**
 * Notification Helper Class
 * Sends notifications to Discord and Telegram based on settings
 */
class NotificationHelper {
    private $db;
    private $settings = [];
    
    public function __construct($db) {
        $this->db = $db;
        $this->loadSettings();
    }
    
    private function loadSettings() {
        try {
            $stmt = $this->db->query("SELECT setting_key, setting_value FROM notification_settings");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->settings[$row['setting_key']] = $row['setting_value'];
            }
        } catch (PDOException $e) {
            // Table might not exist
        }
    }
    
    /**
     * Send notification for new registration
     */
    public function notifyNewRegistration($studentName, $level, $typeregis, $citizenid) {
        if (($this->settings['notify_new_registration'] ?? '0') !== '1') return;
        
        $message = "🎓 **มีการสมัครใหม่!**\n\n";
        $message .= "👤 ชื่อ: {$studentName}\n";
        $message .= "📚 ระดับ: {$level}\n";
        $message .= "📋 ประเภท: {$typeregis}\n";
        $message .= "🆔 เลขบัตร: {$citizenid}";
        
        $this->send($message, 'สมัครใหม่', 0x22c55e); // Green
    }
    
    /**
     * Send notification for document upload
     */
    public function notifyDocumentUpload($studentName, $docName, $citizenid) {
        if (($this->settings['notify_document_upload'] ?? '0') !== '1') return;
        
        $message = "📄 **อัปโหลดเอกสารใหม่**\n\n";
        $message .= "👤 ชื่อ: {$studentName}\n";
        $message .= "📎 เอกสาร: {$docName}\n";
        $message .= "🆔 เลขบัตร: {$citizenid}";
        
        $this->send($message, 'อัปโหลดเอกสาร', 0x3b82f6); // Blue
    }
    
    /**
     * Send notification for report confirmation
     */
    public function notifyReportConfirm($studentName, $citizenid) {
        if (($this->settings['notify_report_confirm'] ?? '0') !== '1') return;
        
        $message = "✅ **ยืนยันรายงานตัว**\n\n";
        $message .= "👤 ชื่อ: {$studentName}\n";
        $message .= "🆔 เลขบัตร: {$citizenid}\n";
        $message .= "📍 สถานะ: ยืนยันสิทธิ์เรียบร้อย";
        
        $this->send($message, 'ยืนยันรายงานตัว', 0x10b981); // Emerald
    }
    
    /**
     * Send notification for report cancellation
     */
    public function notifyReportCancel($studentName, $citizenid) {
        if (($this->settings['notify_report_cancel'] ?? '0') !== '1') return;
        
        $message = "❌ **สละสิทธิ์**\n\n";
        $message .= "👤 ชื่อ: {$studentName}\n";
        $message .= "🆔 เลขบัตร: {$citizenid}\n";
        $message .= "📍 สถานะ: สละสิทธิ์การเข้าศึกษา";
        
        $this->send($message, 'สละสิทธิ์', 0xef4444); // Red
    }
    
    /**
     * Send to all enabled channels
     */
    private function send($message, $title, $color) {
        // Send to Discord
        if (($this->settings['discord_enabled'] ?? '0') === '1' && !empty($this->settings['discord_webhook'])) {
            $this->sendDiscord($message, $title, $color);
        }
        
        // Send to Telegram
        if (($this->settings['telegram_enabled'] ?? '0') === '1' && 
            !empty($this->settings['telegram_bot_token']) && 
            !empty($this->settings['telegram_chat_id'])) {
            $this->sendTelegram($message);
        }
    }
    
    /**
     * Send Discord webhook
     */
    private function sendDiscord($message, $title, $color) {
        $webhook = $this->settings['discord_webhook'];
        
        $payload = [
            'embeds' => [[
                'title' => $title,
                'description' => str_replace(['**', '*'], ['', ''], $message),
                'color' => $color,
                'footer' => ['text' => 'ระบบรับสมัครนักเรียน'],
                'timestamp' => date('c')
            ]]
        ];
        
        $ch = curl_init($webhook);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_exec($ch);
        curl_close($ch);
    }
    
    /**
     * Send Telegram message
     */
    private function sendTelegram($message) {
        $token = $this->settings['telegram_bot_token'];
        $chatId = $this->settings['telegram_chat_id'];
        
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
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_exec($ch);
        curl_close($ch);
    }
}
?>

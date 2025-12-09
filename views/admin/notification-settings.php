
<!-- Notification Settings View - Premium UI -->
<div class="space-y-8 animate-fade-in">
    <!-- Page Header with Gradient -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-violet-600 via-purple-600 to-indigo-600 p-8 shadow-2xl shadow-purple-500/25">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4xIj48cGF0aCBkPSJNMzYgMzRjMC0yIDItNCAyLTZzLTItNC0yLTYgMi00IDItNi0yLTQtMi02bTAgMGMwLTItMi00LTItNnMgMi00IDItNi0yLTQtMi02IDItNCAyLTYiLz48L2c+PC9nPjwvc3ZnPg==')] opacity-30"></div>
        <div class="relative flex items-center gap-6">
            <div class="flex-shrink-0">
                <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center ring-4 ring-white/30">
                    <i class="fas fa-bell text-4xl text-white animate-pulse"></i>
                </div>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">ตั้งค่าการแจ้งเตือน</h1>
                <p class="text-purple-100 text-lg">เชื่อมต่อ Discord และ Telegram เพื่อรับการแจ้งเตือนแบบเรียลไทม์</p>
            </div>
        </div>
    </div>

    <form id="notificationForm" class="space-y-8">
        <!-- Platform Cards -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            
            <!-- Discord Card -->
            <div class="group relative">
                <div class="absolute -inset-1 bg-gradient-to-r from-[#5865F2] to-[#7289DA] rounded-3xl blur-lg opacity-25 group-hover:opacity-40 transition duration-500"></div>
                <div class="relative glass rounded-3xl overflow-hidden">
                    <!-- Discord Header -->
                    <div class="bg-gradient-to-r from-[#5865F2] to-[#7289DA] p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                    <i class="fab fa-discord text-3xl text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">Discord</h3>
                                    <p class="text-white/70 text-sm">Webhook Integration</p>
                                </div>
                            </div>
                            <!-- Toggle Switch -->
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="discord_enabled" value="1" class="sr-only peer" 
                                    <?php echo ($notificationSettings['discord_enabled'] ?? '') == '1' ? 'checked' : ''; ?>>
                                <div class="w-14 h-7 bg-white/30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-white/50 after:shadow-lg"></div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Discord Body -->
                    <div class="p-6 space-y-5">
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <span class="w-8 h-8 rounded-lg bg-[#5865F2]/10 flex items-center justify-center">
                                    <i class="fas fa-link text-[#5865F2]"></i>
                                </span>
                                Webhook URL
                            </label>
                            <input type="url" name="discord_webhook" 
                                value="<?php echo htmlspecialchars($notificationSettings['discord_webhook'] ?? ''); ?>"
                                class="w-full px-5 py-4 rounded-2xl border-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-800 focus:border-[#5865F2] focus:ring-4 focus:ring-[#5865F2]/20 outline-none transition-all text-sm font-mono"
                                placeholder="https://discord.com/api/webhooks/...">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <span class="w-8 h-8 rounded-lg bg-[#5865F2]/10 flex items-center justify-center">
                                    <i class="fas fa-hashtag text-[#5865F2]"></i>
                                </span>
                                ชื่อช่อง (สำหรับอ้างอิง)
                            </label>
                            <input type="text" name="discord_channel_name" 
                                value="<?php echo htmlspecialchars($notificationSettings['discord_channel_name'] ?? ''); ?>"
                                class="w-full px-5 py-4 rounded-2xl border-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-800 focus:border-[#5865F2] focus:ring-4 focus:ring-[#5865F2]/20 outline-none transition-all text-sm"
                                placeholder="#การแจ้งเตือน">
                        </div>

                        <!-- Info Box -->
                        <div class="flex items-start gap-3 p-4 rounded-2xl bg-gradient-to-r from-[#5865F2]/5 to-[#7289DA]/5 border border-[#5865F2]/20">
                            <div class="w-10 h-10 rounded-xl bg-[#5865F2]/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-lightbulb text-[#5865F2]"></i>
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                <p class="font-medium text-gray-800 dark:text-gray-200 mb-1">วิธีสร้าง Webhook</p>
                                <p>ไปที่ Server Settings → Integrations → Webhooks → New Webhook</p>
                            </div>
                        </div>
                        
                        <button type="button" onclick="testDiscord()" 
                            class="w-full py-4 bg-gradient-to-r from-[#5865F2] to-[#7289DA] hover:from-[#4752C4] hover:to-[#5a73c4] text-white rounded-2xl font-bold shadow-lg shadow-[#5865F2]/30 hover:shadow-xl hover:shadow-[#5865F2]/40 transition-all duration-300 hover:-translate-y-0.5">
                            <i class="fas fa-paper-plane mr-2"></i>ทดสอบส่งข้อความ
                        </button>
                    </div>
                </div>
            </div>

            <!-- Telegram Card -->
            <div class="group relative">
                <div class="absolute -inset-1 bg-gradient-to-r from-[#0088cc] to-[#00b4d8] rounded-3xl blur-lg opacity-25 group-hover:opacity-40 transition duration-500"></div>
                <div class="relative glass rounded-3xl overflow-hidden">
                    <!-- Telegram Header -->
                    <div class="bg-gradient-to-r from-[#0088cc] to-[#00b4d8] p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                    <i class="fab fa-telegram text-3xl text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">Telegram</h3>
                                    <p class="text-white/70 text-sm">Bot API Integration</p>
                                </div>
                            </div>
                            <!-- Toggle Switch -->
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="telegram_enabled" value="1" class="sr-only peer"
                                    <?php echo ($notificationSettings['telegram_enabled'] ?? '') == '1' ? 'checked' : ''; ?>>
                                <div class="w-14 h-7 bg-white/30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-white/50 after:shadow-lg"></div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Telegram Body -->
                    <div class="p-6 space-y-5">
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <span class="w-8 h-8 rounded-lg bg-[#0088cc]/10 flex items-center justify-center">
                                    <i class="fas fa-robot text-[#0088cc]"></i>
                                </span>
                                Bot Token
                            </label>
                            <input type="text" name="telegram_bot_token" 
                                value="<?php echo htmlspecialchars($notificationSettings['telegram_bot_token'] ?? ''); ?>"
                                class="w-full px-5 py-4 rounded-2xl border-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-800 focus:border-[#0088cc] focus:ring-4 focus:ring-[#0088cc]/20 outline-none transition-all text-sm font-mono"
                                placeholder="1234567890:ABCdefGHIjklMNOpqrsTUVwxyz">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <span class="w-8 h-8 rounded-lg bg-[#0088cc]/10 flex items-center justify-center">
                                    <i class="fas fa-users text-[#0088cc]"></i>
                                </span>
                                Chat ID / Group ID
                            </label>
                            <input type="text" name="telegram_chat_id" 
                                value="<?php echo htmlspecialchars($notificationSettings['telegram_chat_id'] ?? ''); ?>"
                                class="w-full px-5 py-4 rounded-2xl border-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-800 focus:border-[#0088cc] focus:ring-4 focus:ring-[#0088cc]/20 outline-none transition-all text-sm font-mono"
                                placeholder="-1001234567890">
                        </div>

                        <!-- Info Box -->
                        <div class="flex items-start gap-3 p-4 rounded-2xl bg-gradient-to-r from-[#0088cc]/5 to-[#00b4d8]/5 border border-[#0088cc]/20">
                            <div class="w-10 h-10 rounded-xl bg-[#0088cc]/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-lightbulb text-[#0088cc]"></i>
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                <p class="font-medium text-gray-800 dark:text-gray-200 mb-1">วิธีสร้าง Bot</p>
                                <p>พิมพ์หา @BotFather บน Telegram แล้วใช้คำสั่ง /newbot</p>
                            </div>
                        </div>
                        
                        <button type="button" onclick="testTelegram()" 
                            class="w-full py-4 bg-gradient-to-r from-[#0088cc] to-[#00b4d8] hover:from-[#0077b5] hover:to-[#00a1c4] text-white rounded-2xl font-bold shadow-lg shadow-[#0088cc]/30 hover:shadow-xl hover:shadow-[#0088cc]/40 transition-all duration-300 hover:-translate-y-0.5">
                            <i class="fas fa-paper-plane mr-2"></i>ทดสอบส่งข้อความ
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Events Section -->
        <div class="glass rounded-3xl p-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-orange-500/30">
                    <i class="fas fa-list-check text-2xl text-white"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">เหตุการณ์ที่ต้องการแจ้งเตือน</h3>
                    <p class="text-gray-500">เลือกประเภทการแจ้งเตือนที่ต้องการรับ</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- New Registration -->
                <label class="group cursor-pointer">
                    <input type="checkbox" name="notify_new_registration" value="1" class="hidden peer"
                        <?php echo ($notificationSettings['notify_new_registration'] ?? '') == '1' ? 'checked' : ''; ?>>
                    <div class="p-5 rounded-2xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 transition-all duration-300 hover:border-emerald-300">
                        <div class="flex flex-col items-center text-center gap-3">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-user-plus text-2xl text-emerald-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 dark:text-gray-200">สมัครใหม่</p>
                                <p class="text-xs text-gray-500 mt-1">มีผู้สมัครใหม่เข้ามา</p>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Document Upload -->
                <label class="group cursor-pointer">
                    <input type="checkbox" name="notify_document_upload" value="1" class="hidden peer"
                        <?php echo ($notificationSettings['notify_document_upload'] ?? '') == '1' ? 'checked' : ''; ?>>
                    <div class="p-5 rounded-2xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 transition-all duration-300 hover:border-blue-300">
                        <div class="flex flex-col items-center text-center gap-3">
                            <div class="w-14 h-14 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-file-upload text-2xl text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 dark:text-gray-200">อัปโหลดเอกสาร</p>
                                <p class="text-xs text-gray-500 mt-1">มีการส่งเอกสารใหม่</p>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Report Confirm -->
                <label class="group cursor-pointer">
                    <input type="checkbox" name="notify_report_confirm" value="1" class="hidden peer"
                        <?php echo ($notificationSettings['notify_report_confirm'] ?? '') == '1' ? 'checked' : ''; ?>>
                    <div class="p-5 rounded-2xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 transition-all duration-300 hover:border-green-300">
                        <div class="flex flex-col items-center text-center gap-3">
                            <div class="w-14 h-14 rounded-2xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-check-circle text-2xl text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 dark:text-gray-200">ยืนยันรายงานตัว</p>
                                <p class="text-xs text-gray-500 mt-1">มีการยืนยันสิทธิ์</p>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Report Cancel -->
                <label class="group cursor-pointer">
                    <input type="checkbox" name="notify_report_cancel" value="1" class="hidden peer"
                        <?php echo ($notificationSettings['notify_report_cancel'] ?? '') == '1' ? 'checked' : ''; ?>>
                    <div class="p-5 rounded-2xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 transition-all duration-300 hover:border-red-300">
                        <div class="flex flex-col items-center text-center gap-3">
                            <div class="w-14 h-14 rounded-2xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-user-times text-2xl text-red-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 dark:text-gray-200">สละสิทธิ์</p>
                                <p class="text-xs text-gray-500 mt-1">มีการสละสิทธิ์</p>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex justify-center">
            <button type="submit" class="group relative px-12 py-4 bg-gradient-to-r from-violet-600 to-indigo-600 text-white rounded-2xl font-bold text-lg shadow-2xl shadow-violet-500/30 hover:shadow-violet-500/50 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <span class="relative z-10 flex items-center gap-3">
                    <i class="fas fa-save text-xl"></i>
                    บันทึกการตั้งค่า
                </span>
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </button>
        </div>
    </form>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}
</style>

<script>
$('#notificationForm').on('submit', function(e) {
    e.preventDefault();
    
    const btn = $(this).find('button[type="submit"]');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>กำลังบันทึก...');
    
    $.ajax({
        url: 'api/save_notification_settings.php',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            btn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i>บันทึกการตั้งค่า');
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกสำเร็จ!',
                    text: 'การตั้งค่าถูกบันทึกเรียบร้อยแล้ว',
                    timer: 2000,
                    showConfirmButton: false,
                    background: '#1e293b',
                    color: '#fff'
                });
            } else {
                Swal.fire('เกิดข้อผิดพลาด', response.error, 'error');
            }
        },
        error: function() {
            btn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i>บันทึกการตั้งค่า');
            Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถบันทึกการตั้งค่าได้', 'error');
        }
    });
});

function testDiscord() {
    const webhook = $('input[name="discord_webhook"]').val();
    if (!webhook) {
        Swal.fire({ icon: 'warning', title: 'กรุณากรอก Webhook URL', background: '#1e293b', color: '#fff' });
        return;
    }
    
    Swal.fire({
        title: '<i class="fab fa-discord text-[#5865F2]"></i> ทดสอบ Discord',
        html: 'กำลังส่งข้อความทดสอบ...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
        background: '#1e293b',
        color: '#fff'
    });
    
    $.post('api/test_notification.php', { type: 'discord', webhook: webhook }, function(res) {
        if (res.success) {
            Swal.fire({ icon: 'success', title: 'สำเร็จ!', text: 'ส่งข้อความไปยัง Discord แล้ว', background: '#1e293b', color: '#fff' });
        } else {
            Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: res.error, background: '#1e293b', color: '#fff' });
        }
    }, 'json');
}

function testTelegram() {
    const token = $('input[name="telegram_bot_token"]').val();
    const chatId = $('input[name="telegram_chat_id"]').val();
    if (!token || !chatId) {
        Swal.fire({ icon: 'warning', title: 'กรุณากรอกข้อมูลให้ครบ', text: 'ต้องการ Bot Token และ Chat ID', background: '#1e293b', color: '#fff' });
        return;
    }
    
    Swal.fire({
        title: '<i class="fab fa-telegram text-[#0088cc]"></i> ทดสอบ Telegram',
        html: 'กำลังส่งข้อความทดสอบ...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
        background: '#1e293b',
        color: '#fff'
    });
    
    $.post('api/test_notification.php', { type: 'telegram', token: token, chat_id: chatId }, function(res) {
        if (res.success) {
            Swal.fire({ icon: 'success', title: 'สำเร็จ!', text: 'ส่งข้อความไปยัง Telegram แล้ว', background: '#1e293b', color: '#fff' });
        } else {
            Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: res.error, background: '#1e293b', color: '#fff' });
        }
    }, 'json');
}
</script>

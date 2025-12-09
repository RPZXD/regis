<?php 
require_once __DIR__ . '/../../config/Setting.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../class/AdminConfig.php';

$setting = new Setting();

// Get dynamic menus from database
$connectDB = new Database_Regis();
$db = $connectDB->getConnection();
$adminConfig = new AdminConfig($db);
$activeMenus = $adminConfig->getActiveMenus();

// Menu styling configuration
$menuStyles = [
    'register' => ['from' => 'emerald-500', 'to' => 'emerald-600', 'icon' => 'fa-user-plus'],
    'check_reg' => ['from' => 'amber-500', 'to' => 'orange-500', 'icon' => 'fa-search'],
    'print_form' => ['from' => 'purple-500', 'to' => 'purple-600', 'icon' => 'fa-print'],
    'upload' => ['from' => 'cyan-500', 'to' => 'cyan-600', 'icon' => 'fa-upload'],
    'check_status' => ['from' => 'teal-500', 'to' => 'teal-600', 'icon' => 'fa-check-circle'],
    'exam_card' => ['from' => 'rose-500', 'to' => 'pink-600', 'icon' => 'fa-id-card'],
    'confirm' => ['from' => 'indigo-500', 'to' => 'indigo-600', 'icon' => 'fa-file-signature'],
    'announce' => ['from' => 'red-500', 'to' => 'red-600', 'icon' => 'fa-bullhorn'],
    'results' => ['from' => 'green-500', 'to' => 'teal-600', 'icon' => 'fa-trophy'],
];

// Badge numbers for menus
$badges = [
    'register' => '1',
    'print_form' => '2',
    'upload' => '3',
    'check_status' => '4',
    'exam_card' => '5',
    'confirm' => '6'
];
?>
<!-- Sidebar Overlay (Mobile) -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full lg:translate-x-0">
    <!-- Sidebar Background -->
    <div class="h-full overflow-y-auto bg-gradient-to-b from-slate-800 via-slate-900 to-slate-950 dark:from-slate-900 dark:via-slate-950 dark:to-black">
        
        <!-- Logo Section -->
        <div class="px-6 py-5 border-b border-white/10">
            <a href="index.php" class="flex items-center space-x-3 group">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full blur-lg opacity-50 group-hover:opacity-75 transition-opacity"></div>
                    <img src="dist/img/logo-phicha.png" class="relative w-12 h-12 rounded-full ring-2 ring-white/20 group-hover:ring-primary-400 transition-all" alt="Logo">
                </div>
                <div>
                    <span class="text-lg font-bold text-white"><?php echo $setting->getPageTitleShort(); ?></span>
                    <p class="text-xs text-gray-400">ระบบรับสมัครนักเรียน</p>
                </div>
            </a>
        </div>
        
        <!-- Navigation -->
        <nav class="mt-6 px-3">
            <ul class="space-y-1">
                <!-- หน้าหลัก (Always visible) -->
                <li>
                    <a href="index.php" class="sidebar-item flex items-center px-4 py-3 text-gray-300 rounded-xl hover:bg-white/10 hover:text-white group">
                        <span class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg shadow-blue-500/30 group-hover:shadow-blue-500/50 transition-shadow">
                            <i class="fas fa-home text-white"></i>
                        </span>
                        <span class="ml-3 font-medium">หน้าหลัก</span>
                    </a>
                </li>
                
                <!-- Dynamic Menus from Database -->
                <?php foreach ($activeMenus as $menu): 
                    $style = $menuStyles[$menu['menu_key']] ?? ['from' => 'gray-500', 'to' => 'gray-600', 'icon' => 'fa-circle'];
                    $badge = $badges[$menu['menu_key']] ?? null;
                ?>
                <li>
                    <a href="<?php echo htmlspecialchars($menu['menu_url']); ?>" class="sidebar-item flex items-center px-4 py-3 text-gray-300 rounded-xl hover:bg-white/10 hover:text-white group">
                        <span class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-<?php echo $style['from']; ?> to-<?php echo $style['to']; ?> rounded-lg shadow-lg shadow-<?php echo explode('-', $style['from'])[0]; ?>-500/30 group-hover:shadow-<?php echo explode('-', $style['from'])[0]; ?>-500/50 transition-shadow">
                            <i class="fas <?php echo $style['icon']; ?> text-white"></i>
                        </span>
                        <span class="ml-3 font-medium"><?php echo htmlspecialchars($menu['menu_name']); ?></span>
                        <?php if ($badge): ?>
                        <span class="ml-auto px-2 py-0.5 text-xs font-bold bg-red-500 text-white rounded-full"><?php echo $badge; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php endforeach; ?>
                
                <!-- ติดต่อ-สอบถาม (Always visible) -->
                <li>
                    <a href="contact.php" class="sidebar-item flex items-center px-4 py-3 text-gray-300 rounded-xl hover:bg-white/10 hover:text-white group">
                        <span class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-slate-500 to-slate-600 rounded-lg shadow-lg shadow-slate-500/30 group-hover:shadow-slate-500/50 transition-shadow">
                            <i class="fas fa-phone text-white"></i>
                        </span>
                        <span class="ml-3 font-medium">ติดต่อ-สอบถาม</span>
                    </a>
                </li>
                
                <!-- Divider -->
                <li class="my-4 border-t border-white/10"></li>
                
                <!-- ผู้ดูแลระบบ (Always visible) -->
                <li>
                    <a href="login.php" class="sidebar-item flex items-center px-4 py-3 text-gray-300 rounded-xl hover:bg-white/10 hover:text-white group">
                        <span class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-gray-600 to-gray-700 rounded-lg shadow-lg shadow-gray-500/30 group-hover:shadow-gray-500/50 transition-shadow">
                            <i class="fas fa-sign-in-alt text-white"></i>
                        </span>
                        <span class="ml-3 font-medium">ผู้ดูแลระบบ</span>
                    </a>
                </li>
            </ul>
        </nav>
        
       
    </div>
</aside>

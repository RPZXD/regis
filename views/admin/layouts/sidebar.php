<?php 
require_once __DIR__ . '/../../../config/Setting.php';
$setting = new Setting();
?>
<!-- Sidebar Overlay (Mobile) -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full lg:translate-x-0">
    <div class="h-full overflow-y-auto bg-gradient-to-b from-slate-800 via-slate-900 to-slate-950">
        
        <!-- Logo & User Section -->
        <div class="px-6 py-5 border-b border-white/10">
            <div class="flex flex-col items-center">
                <!-- User Avatar -->
                
                <h3 class="text-white font-bold text-center"><?php echo $userData['Teach_name'] ?? 'ผู้ดูแลระบบ'; ?></h3>
                <span class="px-3 py-1 mt-2 text-xs font-medium bg-primary-500/20 text-primary-300 rounded-full">ผู้ดูแลระบบ</span>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="mt-4 px-3">
            <ul class="space-y-1">
                <?php
                // Fetch dynamic types
                $m1Types = [];
                $m4Types = [];

                // Ensure AdminConfig is available
                if (!isset($adminConfig)) {
                    // Try to initialize it if we have potential DB connections or can create them
                    // We need Database_Regis
                    if (!class_exists('Database_Regis')) {
                        // Assuming path relative to views/admin/layouts/
                        $dbPath = __DIR__ . '/../../../config/Database.php';
                        if (file_exists($dbPath)) require_once $dbPath;
                    }
                    if (!class_exists('AdminConfig')) {
                         $configPath = __DIR__ . '/../../../class/AdminConfig.php';
                         if (file_exists($configPath)) require_once $configPath;
                    }

                    if (class_exists('Database_Regis') && class_exists('AdminConfig')) {
                         $dbRegisConn = null;
                         if (isset($dbRegis)) {
                             $dbRegisConn = $dbRegis;
                         } else {
                             $connectRegis = new Database_Regis();
                             $dbRegisConn = $connectRegis->getConnection();
                         }
                         $adminConfig = new AdminConfig($dbRegisConn);
                    }
                }

                if (isset($adminConfig)) {
                    $m1Types = $adminConfig->getActiveRegistrationTypes('m1');
                    $m4Types = $adminConfig->getActiveRegistrationTypes('m4');
                }

                $studentMenu = [];
                foreach($m1Types as $type) {
                    $studentMenu[] = ['label' => "ม.1 ({$type['name']})", 'href' => "student_data.php?type_id={$type['id']}"];
                }
                foreach($m4Types as $type) {
                    $studentMenu[] = ['label' => "ม.4 ({$type['name']})", 'href' => "student_data.php?type_id={$type['id']}"];
                }

                $checkMenu = [];
                foreach($m1Types as $type) {
                    $checkMenu[] = ['label' => "ม.1 ({$type['name']})", 'href' => "check_documents.php?type_id={$type['id']}"];
                }
                foreach($m4Types as $type) {
                    $checkMenu[] = ['label' => "ม.4 ({$type['name']})", 'href' => "check_documents.php?type_id={$type['id']}"];
                }

                // Define Sidebar Menu Structure
                $menuItems = [
                    [
                        'type' => 'link',
                        'label' => 'หน้าหลัก',
                        'href' => 'index.php',
                        'icon' => 'fas fa-home',
                        'color' => 'from-blue-500 to-blue-600',
                        'shadow' => 'shadow-blue-500/30'
                    ],
                    [
                        'type' => 'dropdown',
                        'id' => 'dataMenu',
                        'label' => 'ข้อมูลสมัคร',
                        'icon' => 'fas fa-user-graduate',
                        'color' => 'from-emerald-500 to-green-600',
                        'shadow' => 'shadow-emerald-500/30',
                        'items' => $studentMenu
                    ],
                    [
                        'type' => 'dropdown',
                        'id' => 'checkMenu',
                        'label' => 'ตรวจหลักฐาน',
                        'icon' => 'fas fa-check-circle',
                        'color' => 'from-amber-500 to-orange-600',
                        'shadow' => 'shadow-amber-500/30',
                        'items' => $checkMenu
                    ],
                ];  
                $passMenu = [];
                foreach($m1Types as $type) {
                    $passMenu[] = ['label' => "ม.1 ({$type['name']})", 'href' => "pass_students.php?type_id={$type['id']}"];
                }
                foreach($m4Types as $type) {
                    $passMenu[] = ['label' => "ม.4 ({$type['name']})", 'href' => "pass_students.php?type_id={$type['id']}"];
                }

                // Define Sidebar Menu Structure
                $menuItems = [
                    [
                        'type' => 'link',
                        'label' => 'หน้าหลัก',
                        'href' => 'index.php',
                        'icon' => 'fas fa-home',
                        'color' => 'from-blue-500 to-blue-600',
                        'shadow' => 'shadow-blue-500/30'
                    ],
                    [
                        'type' => 'dropdown',
                        'id' => 'dataMenu',
                        'label' => 'ข้อมูลสมัคร',
                        'icon' => 'fas fa-user-graduate',
                        'color' => 'from-emerald-500 to-green-600',
                        'shadow' => 'shadow-emerald-500/30',
                        'items' => $studentMenu
                    ],
                    [
                        'type' => 'dropdown',
                        'id' => 'checkMenu',
                        'label' => 'ตรวจหลักฐาน',
                        'icon' => 'fas fa-check-circle',
                        'color' => 'from-amber-500 to-orange-600',
                        'shadow' => 'shadow-amber-500/30',
                        'items' => $checkMenu
                    ],
                    [
                        'type' => 'dropdown',
                        'id' => 'examMenu',
                        'label' => 'จัดห้องสอบ/ที่นั่ง',
                        'icon' => 'fas fa-chair',
                        'color' => 'from-pink-500 to-rose-600',
                        'shadow' => 'shadow-pink-500/30',
                        'items' => [
                            ['label' => 'จัดการข้อมูลสอบ', 'href' => 'exam_management.php']
                        ]
                    ],
                    [
                        'type' => 'dropdown',
                        'id' => 'passMenu',
                        'label' => 'ผ่านการตรวจแล้ว',
                        'icon' => 'fas fa-clipboard-list',
                        'color' => 'from-purple-500 to-indigo-600',
                        'shadow' => 'shadow-purple-500/30',
                        'items' => $passMenu
                    ],
                    [
                        'type' => 'link',
                        'label' => 'สถานะรายงานตัว',
                        'href' => 'report_status.php',
                        'icon' => 'fas fa-clipboard-check',
                        'color' => 'from-cyan-500 to-teal-600',
                        'shadow' => 'shadow-cyan-500/30'
                    ],
                    ['type' => 'divider'],
                    [
                        'type' => 'dropdown',
                        'id' => 'configMenu',
                        'label' => 'ตั้งค่า',
                        'icon' => 'fas fa-cog',
                        'color' => 'from-gray-500 to-gray-600',
                        'shadow' => 'shadow-gray-500/30',
                        'items' => [
                            ['label' => 'ตั้งค่าระบบ', 'href' => 'config_admin.php', 'icon' => 'fas fa-cogs'],
                            ['label' => 'จัดการค่าใช้จ่าย', 'href' => 'manage_fees.php', 'icon' => 'fas fa-coins'],
                            ['label' => 'ตั้งค่าแจ้งเตือน', 'href' => 'notification_settings.php', 'icon' => 'fas fa-bell'],
                        ]
                    ],
                    [
                        'type' => 'link',
                        'label' => 'ออกจากระบบ',
                        'href' => '../logout.php',
                        'icon' => 'fas fa-sign-out-alt',
                        'color' => 'from-red-500 to-red-600',
                        'shadow' => 'shadow-red-500/30',
                        'text_color' => 'text-red-400',
                        'hover_bg' => 'hover:bg-red-500/10',
                        'hover_text' => 'hover:text-red-300'
                    ]
                ];

                foreach ($menuItems as $item) {
                    if (isset($item['type']) && $item['type'] === 'divider') {
                        echo '<li class="my-4 border-t border-white/10"></li>';
                        continue;
                    }

                    if (isset($item['type']) && $item['type'] === 'dropdown') {
                        echo '<li>
                            <button onclick="toggleMenu(\'' . $item['id'] . '\')" class="sidebar-item w-full flex items-center justify-between px-4 py-3 text-gray-300 rounded-xl hover:bg-white/10 hover:text-white group">
                                <div class="flex items-center">
                                    <span class="w-10 h-10 flex items-center justify-center bg-gradient-to-br ' . $item['color'] . ' rounded-lg shadow-lg ' . $item['shadow'] . '">
                                        <i class="' . $item['icon'] . ' text-white"></i>
                                    </span>
                                    <span class="ml-3 font-medium">' . $item['label'] . '</span>
                                </div>
                                <i class="fas fa-chevron-down text-sm transition-transform" id="' . $item['id'] . 'Icon"></i>
                            </button>
                            <ul id="' . $item['id'] . '" class="hidden mt-1 ml-4 space-y-1">';
                        foreach ($item['items'] as $subItem) {
                            $subIcon = isset($subItem['icon']) ? $subItem['icon'] : 'fas fa-angle-right';
                            echo '<li><a href="' . $subItem['href'] . '" class="block px-4 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5"><i class="' . $subIcon . ' mr-2"></i>' . $subItem['label'] . '</a></li>';
                        }
                        echo '</ul></li>';
                    } else {
                        $textColor = isset($item['text_color']) ? $item['text_color'] : 'text-gray-300';
                        $hoverBg = isset($item['hover_bg']) ? $item['hover_bg'] : 'hover:bg-white/10';
                        $hoverText = isset($item['hover_text']) ? $item['hover_text'] : 'hover:text-white';
                        
                        echo '<li>
                            <a href="' . $item['href'] . '" class="sidebar-item flex items-center px-4 py-3 ' . $textColor . ' rounded-xl ' . $hoverBg . ' ' . $hoverText . ' group">
                                <span class="w-10 h-10 flex items-center justify-center bg-gradient-to-br ' . $item['color'] . ' rounded-lg shadow-lg ' . $item['shadow'] . '">
                                    <i class="' . $item['icon'] . ' text-white"></i>
                                </span>
                                <span class="ml-3 font-medium">' . $item['label'] . '</span>
                            </a>
                        </li>';
                    }
                }
                ?>
            </ul>
        </nav>
    </div>
</aside>

<script>
function toggleMenu(menuId) {
    const menu = document.getElementById(menuId);
    const icon = document.getElementById(menuId + 'Icon');
    menu.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}
</script>

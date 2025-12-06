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
                <div class="relative mb-3">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary-500 to-purple-500 rounded-full blur-lg opacity-50"></div>
                    <img src="<?php echo $setting->getImgProfile() . ($userData['Teach_photo'] ?? 'default.jpg'); ?>" 
                         class="relative w-20 h-20 rounded-full ring-4 ring-white/20 object-cover" alt="Profile">
                </div>
                <h3 class="text-white font-bold text-center"><?php echo $userData['Teach_name'] ?? 'ครู'; ?></h3>
                <span class="px-3 py-1 mt-2 text-xs font-medium bg-primary-500/20 text-primary-300 rounded-full">ครู</span>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="mt-4 px-3">
            <ul class="space-y-1">
                <!-- หน้าหลัก -->
                <li>
                    <a href="index.php" class="sidebar-item flex items-center px-4 py-3 text-gray-300 rounded-xl hover:bg-white/10 hover:text-white group">
                        <span class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg shadow-blue-500/30">
                            <i class="fas fa-home text-white"></i>
                        </span>
                        <span class="ml-3 font-medium">หน้าหลัก</span>
                    </a>
                </li>
                
                <!-- ข้อมูลสมัคร -->
                <li>
                    <button onclick="toggleMenu('dataMenu')" class="sidebar-item w-full flex items-center justify-between px-4 py-3 text-gray-300 rounded-xl hover:bg-white/10 hover:text-white group">
                        <div class="flex items-center">
                            <span class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg shadow-lg shadow-emerald-500/30">
                                <i class="fas fa-user text-white"></i>
                            </span>
                            <span class="ml-3 font-medium">ข้อมูลสมัคร</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm transition-transform" id="dataMenuIcon"></i>
                    </button>
                    <ul id="dataMenu" class="hidden mt-1 ml-4 space-y-1">
                        <li><a href="m1_nomal.php" class="block px-4 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5"><i class="fas fa-angle-right mr-2"></i>ม.1 (ทั่วไป)</a></li>
                        <li><a href="m4_nomal.php" class="block px-4 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5"><i class="fas fa-angle-right mr-2"></i>ม.4 (ทั่วไป)</a></li>
                        <li><a href="m4_quota.php" class="block px-4 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5"><i class="fas fa-angle-right mr-2"></i>ม.4 (โควต้า ม.3 เดิม)</a></li>
                    </ul>
                </li>
                
                <!-- ตรวจหลักฐาน -->
                <li>
                    <button onclick="toggleMenu('checkMenu')" class="sidebar-item w-full flex items-center justify-between px-4 py-3 text-gray-300 rounded-xl hover:bg-white/10 hover:text-white group">
                        <div class="flex items-center">
                            <span class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg shadow-lg shadow-amber-500/30">
                                <i class="fas fa-check text-white"></i>
                            </span>
                            <span class="ml-3 font-medium">ตรวจหลักฐาน</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm transition-transform" id="checkMenuIcon"></i>
                    </button>
                    <ul id="checkMenu" class="hidden mt-1 ml-4 space-y-1">
                        <li><a href="check_m1_nomal.php" class="block px-4 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5"><i class="fas fa-angle-right mr-2"></i>ม.1 (ทั่วไป)</a></li>
                        <li><a href="check_m4_nomal.php" class="block px-4 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5"><i class="fas fa-angle-right mr-2"></i>ม.4 (ทั่วไป)</a></li>
                    </ul>
                </li>
                
                <!-- ผ่านการตรวจ -->
                <li>
                    <button onclick="toggleMenu('passMenu')" class="sidebar-item w-full flex items-center justify-between px-4 py-3 text-gray-300 rounded-xl hover:bg-white/10 hover:text-white group">
                        <div class="flex items-center">
                            <span class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg shadow-lg shadow-purple-500/30">
                                <i class="fas fa-clipboard-list text-white"></i>
                            </span>
                            <span class="ml-3 font-medium">ผ่านการตรวจแล้ว</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm transition-transform" id="passMenuIcon"></i>
                    </button>
                    <ul id="passMenu" class="hidden mt-1 ml-4 space-y-1">
                        <li><a href="pass_m1_nomal.php" class="block px-4 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5"><i class="fas fa-angle-right mr-2"></i>ม.1 (ทั่วไป)</a></li>
                        <li><a href="pass_m4_nomal.php" class="block px-4 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5"><i class="fas fa-angle-right mr-2"></i>ม.4 (ทั่วไป)</a></li>
                    </ul>
                </li>
                
                <!-- รายงานตัว -->
                <li>
                    <button onclick="toggleMenu('reportMenu')" class="sidebar-item w-full flex items-center justify-between px-4 py-3 text-gray-300 rounded-xl hover:bg-white/10 hover:text-white group">
                        <div class="flex items-center">
                            <span class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-cyan-500 to-teal-600 rounded-lg shadow-lg shadow-cyan-500/30">
                                <i class="fas fa-tasks text-white"></i>
                            </span>
                            <span class="ml-3 font-medium">รายงานตัว</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm transition-transform" id="reportMenuIcon"></i>
                    </button>
                    <ul id="reportMenu" class="hidden mt-1 ml-4 space-y-1">
                        <li><a href="con_m1_nomal.php" class="block px-4 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5"><i class="fas fa-angle-right mr-2"></i>ม.1 (ทั่วไป)</a></li>
                        <li><a href="con_m4_nomal.php" class="block px-4 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5"><i class="fas fa-angle-right mr-2"></i>ม.4 (ทั่วไป)</a></li>
                        <li><a href="con_m4_quota.php" class="block px-4 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5"><i class="fas fa-angle-right mr-2"></i>ม.4 (โควต้า ม.3 เดิม)</a></li>
                    </ul>
                </li>
                
                <li class="my-4 border-t border-white/10"></li>
                
                <!-- ตั้งค่า -->
                <li>
                    <button onclick="toggleMenu('configMenu')" class="sidebar-item w-full flex items-center justify-between px-4 py-3 text-gray-300 rounded-xl hover:bg-white/10 hover:text-white group">
                        <div class="flex items-center">
                            <span class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-gray-500 to-gray-600 rounded-lg shadow-lg shadow-gray-500/30">
                                <i class="fas fa-cog text-white"></i>
                            </span>
                            <span class="ml-3 font-medium">ตั้งค่า</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm transition-transform" id="configMenuIcon"></i>
                    </button>
                    <ul id="configMenu" class="hidden mt-1 ml-4 space-y-1">
                        <li><a href="config_admin.php" class="block px-4 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5"><i class="fas fa-cogs mr-2"></i>ตั้งค่าระบบ</a></li>
                        <li><a href="config_year.php" class="block px-4 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5"><i class="fas fa-calendar mr-2"></i>ปีการศึกษา</a></li>
                        <li><a href="config_check.php" class="block px-4 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5"><i class="fas fa-tasks mr-2"></i>หลักฐาน</a></li>
                        <li><a href="config_plan.php" class="block px-4 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5"><i class="fas fa-clipboard mr-2"></i>แผนการเรียน</a></li>
                    </ul>
                </li>
                
                <!-- ออกจากระบบ -->
                <li>
                    <a href="../logout.php" class="sidebar-item flex items-center px-4 py-3 text-red-400 rounded-xl hover:bg-red-500/10 hover:text-red-300 group">
                        <span class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg shadow-red-500/30">
                            <i class="fas fa-sign-out-alt text-white"></i>
                        </span>
                        <span class="ml-3 font-medium">ออกจากระบบ</span>
                    </a>
                </li>
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

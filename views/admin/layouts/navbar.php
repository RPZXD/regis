<!-- Top Navbar -->
<header class="sticky top-0 z-30 glass border-b border-gray-200/50 dark:border-gray-700/50">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Left Section -->
            <div class="flex items-center">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-colors">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="ml-4 lg:ml-0">
                    <h1 class="text-lg font-semibold text-gray-800 dark:text-white">
                        <i class="fas fa-user-shield text-primary-500 mr-2"></i>
                        <?php echo $pageTitle ?? 'ระบบผู้ดูแล'; ?>
                    </h1>
                </div>
            </div>
            
            <!-- Right Section -->
            <div class="flex items-center space-x-4">
                <!-- Current Time -->
                <div class="hidden md:flex items-center space-x-2 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 rounded-lg">
                    <i class="fas fa-clock text-primary-500"></i>
                    <span id="currentTime" class="text-sm font-medium text-gray-600 dark:text-gray-300"></span>
                </div>
                
                <!-- Dark Mode Toggle -->
                <button onclick="toggleDarkMode()" class="relative w-14 h-7 bg-gray-200 dark:bg-gray-700 rounded-full transition-colors focus:outline-none">
                    <div class="absolute inset-1 flex items-center justify-between px-1">
                        <i class="fas fa-sun text-xs text-amber-500 opacity-100 dark:opacity-50"></i>
                        <i class="fas fa-moon text-xs text-blue-300 opacity-50 dark:opacity-100"></i>
                    </div>
                    <div class="absolute top-0.5 left-0.5 w-6 h-6 bg-white dark:bg-gray-800 rounded-full shadow-md transform dark:translate-x-7 transition-transform flex items-center justify-center">
                        <i class="fas fa-sun text-xs text-amber-500 dark:hidden"></i>
                        <i class="fas fa-moon text-xs text-blue-300 hidden dark:block"></i>
                    </div>
                </button>
                
                <!-- User Info -->
                <div class="hidden sm:flex items-center space-x-2 px-3 py-1.5 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-lg">
                    <i class="fas fa-user-circle"></i>
                    <span class="text-sm font-medium"><?php echo $userData['Teach_name'] ?? 'ผู้ดูแลระบบ'; ?></span>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
function updateTime() {
    const now = new Date();
    const options = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
    const timeString = now.toLocaleTimeString('th-TH', options);
    const dateElement = document.getElementById('currentTime');
    if (dateElement) dateElement.textContent = timeString;
}
updateTime();
setInterval(updateTime, 1000);
</script>

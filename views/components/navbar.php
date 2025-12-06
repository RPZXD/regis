<!-- Top Navbar -->
<header class="sticky top-0 z-30 glass border-b border-gray-200/50 dark:border-gray-700/50">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Left Section -->
            <div class="flex items-center">
                <!-- Mobile Menu Button -->
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                <!-- Breadcrumb or Title -->
                <div class="ml-4 lg:ml-0">
                    <h1 class="text-lg font-semibold text-gray-800 dark:text-white hidden sm:block">
                        <?php echo $pageTitle ?? 'ระบบรับสมัครนักเรียน'; ?>
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
                <button onclick="toggleDarkMode()" class="relative w-14 h-7 bg-gray-200 dark:bg-gray-700 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 group">
                    <div class="absolute inset-1 flex items-center justify-between px-1">
                        <i class="fas fa-sun text-xs text-amber-500 opacity-100 dark:opacity-50 transition-opacity"></i>
                        <i class="fas fa-moon text-xs text-blue-300 opacity-50 dark:opacity-100 transition-opacity"></i>
                    </div>
                    <div class="absolute top-0.5 left-0.5 w-6 h-6 bg-white dark:bg-gray-800 rounded-full shadow-md transform transition-transform dark:translate-x-7 flex items-center justify-center">
                        <i class="fas fa-sun text-xs text-amber-500 dark:hidden"></i>
                        <i class="fas fa-moon text-xs text-blue-300 hidden dark:block"></i>
                    </div>
                </button>
                
                <!-- Notification Bell -->
                <button class="relative p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full animate-ping"></span>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                
                <!-- User Avatar (if logged in) -->
                <?php if (isset($_SESSION['user_id'])): ?>
                <div class="relative">
                    <button class="flex items-center space-x-2 p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-bold text-sm">
                            <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?>
                        </div>
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<script>
    // Update current time
    function updateTime() {
        const now = new Date();
        const options = { 
            hour: '2-digit', 
            minute: '2-digit',
            second: '2-digit',
            hour12: false 
        };
        const timeString = now.toLocaleTimeString('th-TH', options);
        const dateElement = document.getElementById('currentTime');
        if (dateElement) {
            dateElement.textContent = timeString;
        }
    }
    
    updateTime();
    setInterval(updateTime, 1000);
</script>

<!-- Dashboard Content -->
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold gradient-text">รับสมัครนักเรียน 2568</h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">ยินดีต้อนรับสู่ระบบรับสมัครนักเรียนโรงเรียนพิชัย</p>
        </div>
        <div class="mt-4 md:mt-0">
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                ระบบพร้อมใช้งาน
            </span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- M.1 ในเขต -->
        <div class="card-hover glass rounded-2xl p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/20 to-blue-600/20 rounded-full -mr-16 -mt-16"></div>
            <div class="relative">
                <div class="flex items-center justify-between">
                    <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg shadow-blue-500/30">
                        <i class="fas fa-user-graduate text-2xl text-white"></i>
                    </div>
                    <span class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 rounded-full">ม.1</span>
                </div>
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">ยอดสมัคร ในเขต</h3>
                    <p id="stat-m1-in" class="text-3xl font-bold text-gray-900 dark:text-white mt-1"><?php echo $stats['m1_in'] ?? 0; ?></p>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                    <span class="text-green-500 font-medium">+12%</span>
                    <span class="text-gray-400 ml-2">จากเมื่อวาน</span>
                </div>
            </div>
        </div>

        <!-- M.1 นอกเขต -->
        <div class="card-hover glass rounded-2xl p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/20 to-purple-600/20 rounded-full -mr-16 -mt-16"></div>
            <div class="relative">
                <div class="flex items-center justify-between">
                    <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg shadow-purple-500/30">
                        <i class="fas fa-user-graduate text-2xl text-white"></i>
                    </div>
                    <span class="px-3 py-1 text-xs font-medium bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400 rounded-full">ม.1</span>
                </div>
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">ยอดสมัคร นอกเขต</h3>
                    <p id="stat-m1-out" class="text-3xl font-bold text-gray-900 dark:text-white mt-1"><?php echo $stats['m1_out'] ?? 0; ?></p>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                    <span class="text-green-500 font-medium">+8%</span>
                    <span class="text-gray-400 ml-2">จากเมื่อวาน</span>
                </div>
            </div>
        </div>

        <!-- M.4 โควต้า -->
        <div class="card-hover glass rounded-2xl p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-500/20 to-emerald-600/20 rounded-full -mr-16 -mt-16"></div>
            <div class="relative">
                <div class="flex items-center justify-between">
                    <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg shadow-emerald-500/30">
                        <i class="fas fa-user-tie text-2xl text-white"></i>
                    </div>
                    <span class="px-3 py-1 text-xs font-medium bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-full">ม.4</span>
                </div>
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">ยอดสมัคร โควต้า</h3>
                    <p id="stat-m4-quota" class="text-3xl font-bold text-gray-900 dark:text-white mt-1"><?php echo $stats['m4_quota'] ?? 0; ?></p>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                    <span class="text-green-500 font-medium">+5%</span>
                    <span class="text-gray-400 ml-2">จากเมื่อวาน</span>
                </div>
            </div>
        </div>

        <!-- M.4 รอบทั่วไป -->
        <div class="card-hover glass rounded-2xl p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-amber-500/20 to-orange-500/20 rounded-full -mr-16 -mt-16"></div>
            <div class="relative">
                <div class="flex items-center justify-between">
                    <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl shadow-lg shadow-amber-500/30">
                        <i class="fas fa-user-tie text-2xl text-white"></i>
                    </div>
                    <span class="px-3 py-1 text-xs font-medium bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 rounded-full">ม.4</span>
                </div>
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">ยอดสมัคร ทั่วไป</h3>
                    <p id="stat-m4-normal" class="text-3xl font-bold text-gray-900 dark:text-white mt-1"><?php echo $stats['m4_normal'] ?? 0; ?></p>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                    <span class="text-green-500 font-medium">+15%</span>
                    <span class="text-gray-400 ml-2">จากเมื่อวาน</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Donut Chart M.1 -->
        <div class="glass rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <i class="fas fa-chart-pie text-blue-500 mr-2"></i>
                    ยอดผู้สมัคร ม.1
                </h3>
                <span class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 rounded-full">
                    ปีการศึกษา 2568
                </span>
            </div>
            <div class="relative h-[300px]">
                <canvas id="donutChart1"></canvas>
            </div>
        </div>

        <!-- Donut Chart M.4 -->
        <div class="glass rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <i class="fas fa-chart-pie text-emerald-500 mr-2"></i>
                    ยอดผู้สมัคร ม.4
                </h3>
                <span class="px-3 py-1 text-xs font-medium bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-full">
                    ปีการศึกษา 2568
                </span>
            </div>
            <div class="relative h-[300px]">
                <canvas id="donutChart4"></canvas>
            </div>
        </div>
    </div>

    <!-- Bar Chart -->
    <div class="glass rounded-2xl p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                <i class="fas fa-chart-bar text-amber-500 mr-2"></i>
                ยอดสมัครในแต่ละวัน
            </h3>
            <div class="mt-4 sm:mt-0 flex items-center space-x-2">
                <button class="px-3 py-1.5 text-sm font-medium text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">7 วัน</button>
                <button class="px-3 py-1.5 text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 rounded-lg transition-colors">30 วัน</button>
            </div>
        </div>
        <div class="relative h-[300px]">
            <canvas id="barChart"></canvas>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="glass rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            <i class="fas fa-bolt text-yellow-500 mr-2"></i>
            เริ่มต้นใช้งาน
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="regis.php" class="group p-4 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 hover:border-primary-500 dark:hover:border-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 flex items-center justify-center bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-plus text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">สมัครเรียน</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">เริ่มกรอกใบสมัคร</p>
                    </div>
                </div>
            </a>
            
            <a href="checkreg.php" class="group p-4 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 hover:border-amber-500 dark:hover:border-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-all">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 flex items-center justify-center bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-search text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">เช็คการสมัคร</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">ตรวจสอบสถานะ</p>
                    </div>
                </div>
            </a>
            
            <a href="upload.php" class="group p-4 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 hover:border-cyan-500 dark:hover:border-cyan-400 hover:bg-cyan-50 dark:hover:bg-cyan-900/20 transition-all">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 flex items-center justify-center bg-cyan-100 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 rounded-xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-upload text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">อัพโหลดเอกสาร</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">ส่งหลักฐาน</p>
                    </div>
                </div>
            </a>
            
            <a href="print_reg.php" class="group p-4 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 hover:border-purple-500 dark:hover:border-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 flex items-center justify-center bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-print text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">พิมพ์ใบสมัคร</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">ดาวน์โหลด PDF</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get stats from PHP
    const m1In = <?php echo $stats['m1_in'] ?? 0; ?>;
    const m1Out = <?php echo $stats['m1_out'] ?? 0; ?>;
    const m4Quota = <?php echo $stats['m4_quota'] ?? 0; ?>;
    const m4Normal = <?php echo $stats['m4_normal'] ?? 0; ?>;
    
    // Chart colors
    const chartColors = {
        blue: 'rgba(59, 130, 246, 0.8)',
        purple: 'rgba(139, 92, 246, 0.8)',
        emerald: 'rgba(16, 185, 129, 0.8)',
        amber: 'rgba(245, 158, 11, 0.8)',
        red: 'rgba(239, 68, 68, 0.8)',
        cyan: 'rgba(6, 182, 212, 0.8)'
    };
    
    // Donut Chart M.1
    new Chart(document.getElementById('donutChart1'), {
        type: 'doughnut',
        data: {
            labels: ['ในเขต', 'นอกเขต'],
            datasets: [{
                data: [m1In, m1Out],
                backgroundColor: [chartColors.blue, chartColors.purple],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: { family: 'Mali', size: 14 }
                    }
                }
            }
        }
    });
    
    // Donut Chart M.4
    new Chart(document.getElementById('donutChart4'), {
        type: 'doughnut',
        data: {
            labels: ['โควต้า', 'รอบทั่วไป'],
            datasets: [{
                data: [m4Quota, m4Normal],
                backgroundColor: [chartColors.emerald, chartColors.amber],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: { family: 'Mali', size: 14 }
                    }
                }
            }
        }
    });
    
    // Bar Chart - Daily registrations
    const labels = [];
    const data = [];
    for (let i = 6; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        labels.push(date.toLocaleDateString('th-TH', { weekday: 'short', day: 'numeric' }));
        data.push(Math.floor(Math.random() * 50) + 10); // Sample data
    }
    
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'จำนวนผู้สมัคร',
                data: data,
                backgroundColor: 'rgba(59, 130, 246, 0.6)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 8,
                hoverBackgroundColor: 'rgba(59, 130, 246, 0.8)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: { family: 'Mali' }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { family: 'Mali' }
                    }
                }
            }
        }
    });
});
</script>

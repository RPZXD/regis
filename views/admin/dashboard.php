<!-- Teacher Dashboard Content -->
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="glass rounded-2xl p-6 border-l-4 border-green-500">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg shadow-green-500/30">
                <i class="fas fa-hand-wave text-2xl text-white"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">ยินดีต้อนรับคุณครู <?php echo $userData['Teach_name']; ?></h2>
                <p class="text-gray-600 dark:text-gray-400">เข้าสู่ระบบรับสมัครนักเรียน โรงเรียนพิชัย</p>
            </div>
        </div>
    </div>

    <!-- Dynamic M.1 Section -->
    <?php if (!empty($dashboardStats['m1'])): ?>
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
            <span class="w-8 h-8 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg mr-2">
                <i class="fas fa-user-graduate text-sm"></i>
            </span>
            ยอดสมัครระดับชั้นมัธยมศึกษาปีที่ 1
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($dashboardStats['m1'] as $index => $stat): 
                $gradients = [
                    'from-blue-500 to-cyan-400', 
                    'from-indigo-500 to-purple-500', 
                    'from-emerald-500 to-teal-400'
                ];
                $grad = $gradients[$index % count($gradients)];
            ?>
            <a href="student_data.php?type_id=<?php echo $stat['id']; ?>" class="group card-hover glass rounded-2xl p-6 relative overflow-hidden transition-all hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br <?php echo $grad; ?> opacity-10 rounded-full -mr-16 -mt-16 group-hover:opacity-20 transition-opacity"></div>
                
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo $stat['name']; ?></p>
                        <h4 class="text-4xl font-extrabold text-gray-800 dark:text-white mt-2"><?php echo number_format($stat['total']); ?></h4>
                        <span class="text-xs text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-2 py-1 rounded-md mt-2 inline-block">คน</span>
                    </div>
                    <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-br <?php echo $grad; ?> shadow-lg text-white">
                        <i class="fas fa-users text-lg"></i>
                    </div>
                </div>

                <div class="mt-6 flex space-x-4 text-sm">
                    <div class="flex items-center text-green-600 dark:text-green-400">
                        <i class="fas fa-check-circle mr-1.5"></i>
                        <span class="font-bold"><?php echo $stat['confirmed']; ?></span>
                    </div>
                    <div class="flex items-center text-yellow-600 dark:text-yellow-400">
                        <i class="fas fa-clock mr-1.5"></i>
                        <span class="font-bold"><?php echo $stat['pending']; ?></span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Dynamic M.4 Section -->
    <?php if (!empty($dashboardStats['m4'])): ?>
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
            <span class="w-8 h-8 flex items-center justify-center bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-lg mr-2">
                <i class="fas fa-user-tie text-sm"></i>
            </span>
            ยอดสมัครระดับชั้นมัธยมศึกษาปีที่ 4
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($dashboardStats['m4'] as $index => $stat): 
                $gradients = [
                    'from-purple-500 to-pink-500', 
                    'from-rose-500 to-red-400', 
                    'from-amber-500 to-orange-400'
                ];
                $grad = $gradients[$index % count($gradients)];
            ?>
            <a href="student_data.php?type_id=<?php echo $stat['id']; ?>" class="group card-hover glass rounded-2xl p-6 relative overflow-hidden transition-all hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br <?php echo $grad; ?> opacity-10 rounded-full -mr-16 -mt-16 group-hover:opacity-20 transition-opacity"></div>
                
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo $stat['name']; ?></p>
                        <h4 class="text-4xl font-extrabold text-gray-800 dark:text-white mt-2"><?php echo number_format($stat['total']); ?></h4>
                        <span class="text-xs text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20 px-2 py-1 rounded-md mt-2 inline-block">คน</span>
                    </div>
                    <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-br <?php echo $grad; ?> shadow-lg text-white">
                        <i class="fas fa-users text-lg"></i>
                    </div>
                </div>

                <div class="mt-6 flex space-x-4 text-sm">
                    <div class="flex items-center text-green-600 dark:text-green-400">
                        <i class="fas fa-check-circle mr-1.5"></i>
                        <span class="font-bold"><?php echo $stat['confirmed']; ?></span>
                    </div>
                    <div class="flex items-center text-yellow-600 dark:text-yellow-400">
                        <i class="fas fa-clock mr-1.5"></i>
                        <span class="font-bold"><?php echo $stat['pending']; ?></span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Statistics Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Chart: Applicant Distribution (M.1) -->
        <div class="glass rounded-2xl p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-chart-pie text-blue-500 mr-2"></i>
                สัดส่วนผู้สมัคร ม.1
            </h3>
            <canvas id="chartM1"></canvas>
        </div>

        <!-- Chart: Applicant Distribution (M.4) -->
        <div class="glass rounded-2xl p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-chart-pie text-purple-500 mr-2"></i>
                สัดส่วนผู้สมัคร ม.4
            </h3>
            <canvas id="chartM4"></canvas>
        </div>
        
        <!-- Chart: Status Comparison -->
        <div class="glass rounded-2xl p-6 lg:col-span-2">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-chart-bar text-green-500 mr-2"></i>
                เปรียบเทียบสถานะการสมัคร (ทั้งหมด)
            </h3>
            <canvas id="chartCompare" height="100"></canvas>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data Preparation
    const statsM1 = <?php echo json_encode($dashboardStats['m1'] ?? []); ?>;
    const statsM4 = <?php echo json_encode($dashboardStats['m4'] ?? []); ?>;

    // Helper: Extract Labels and Data
    const getLabels = (stats) => stats.map(s => s.name);
    const getData = (stats, key) => stats.map(s => s[key]);

    // Chart M.1
    if (statsM1.length > 0) {
        new Chart(document.getElementById('chartM1'), {
            type: 'doughnut',
            data: {
                labels: getLabels(statsM1),
                datasets: [{
                    data: getData(statsM1, 'total'),
                    backgroundColor: ['#3b82f6', '#8b5cf6', '#10b981', '#f59e0b', '#ef4444'],
                }]
            }
        });
    }

    // Chart M.4
    if (statsM4.length > 0) {
        new Chart(document.getElementById('chartM4'), {
            type: 'doughnut',
            data: {
                labels: getLabels(statsM4),
                datasets: [{
                    data: getData(statsM4, 'total'),
                    backgroundColor: ['#d946ef', '#ec4899', '#f43f5e', '#8b5cf6', '#6366f1'],
                }]
            }
        });
    }

    // Chart Comparison (Combined)
    const allStats = [...statsM1, ...statsM4];
    if (allStats.length > 0) {
        new Chart(document.getElementById('chartCompare'), {
            type: 'bar',
            data: {
                labels: getLabels(allStats),
                datasets: [
                    {
                        label: 'ยืนยันแล้ว',
                        data: getData(allStats, 'confirmed'),
                        backgroundColor: '#10b981'
                    },
                    {
                        label: 'รอตรวจสอบ',
                        data: getData(allStats, 'pending'),
                        backgroundColor: '#f59e0b'
                    }
                ]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
});
</script>

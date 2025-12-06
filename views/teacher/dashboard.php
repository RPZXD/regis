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

    <!-- M.1 Stats Section -->
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
            <span class="w-8 h-8 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg mr-2">
                <i class="fas fa-user-graduate text-sm"></i>
            </span>
            ยอดสมัครระดับชั้นมัธยมศึกษาปีที่ 1
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- ในเขต -->
            <div class="card-hover glass rounded-2xl p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-blue-500/20 to-blue-600/20 rounded-full -mr-12 -mt-12"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">ในเขตพื้นที่บริการ</p>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mt-2"><?php echo $Count_m1_in; ?></p>
                    </div>
                    <div class="w-16 h-16 flex items-center justify-center bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg shadow-blue-500/30">
                        <i class="fas fa-map-marker-alt text-2xl text-white"></i>
                    </div>
                </div>
            </div>
            
            <!-- นอกเขต -->
            <div class="card-hover glass rounded-2xl p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-pink-500/20 to-rose-600/20 rounded-full -mr-12 -mt-12"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">นอกเขตพื้นที่บริการ</p>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mt-2"><?php echo $Count_m1_out; ?></p>
                    </div>
                    <div class="w-16 h-16 flex items-center justify-center bg-gradient-to-br from-pink-500 to-rose-600 rounded-2xl shadow-lg shadow-pink-500/30">
                        <i class="fas fa-map-marker-alt text-2xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- M.4 Stats Section -->
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
            <span class="w-8 h-8 flex items-center justify-center bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-lg mr-2">
                <i class="fas fa-user-tie text-sm"></i>
            </span>
            ยอดสมัครระดับชั้นมัธยมศึกษาปีที่ 4
        </h3>
        <div class="glass rounded-2xl p-6 card-hover relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-purple-500/20 to-indigo-600/20 rounded-full -mr-12 -mt-12"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">รอบทั่วไป</p>
                    <p class="text-4xl font-bold text-gray-900 dark:text-white mt-2"><?php echo $Count_m4; ?></p>
                </div>
                <div class="w-16 h-16 flex items-center justify-center bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl shadow-lg shadow-purple-500/30">
                    <i class="fas fa-users text-2xl text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- M.4 Quota Confirmation Stats -->
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
            <span class="w-8 h-8 flex items-center justify-center bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-lg mr-2">
                <i class="fas fa-clipboard-check text-sm"></i>
            </span>
            ยอดรายงานตัว ม.4 (โควต้า ม.3 เดิม)
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- ยืนยันแล้ว -->
            <div class="card-hover glass rounded-2xl p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-green-500/20 to-emerald-600/20 rounded-full -mr-10 -mt-10"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-3 py-1 text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full">ยืนยันแล้ว</span>
                        <div class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl">
                            <i class="fas fa-check text-white"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white"><?php echo $Count_Confirm_Confirmed; ?></p>
                </div>
            </div>
            
            <!-- สละสิทธิ์ -->
            <div class="card-hover glass rounded-2xl p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-red-500/20 to-rose-600/20 rounded-full -mr-10 -mt-10"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-3 py-1 text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full">สละสิทธิ์</span>
                        <div class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-red-500 to-rose-600 rounded-xl">
                            <i class="fas fa-times text-white"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white"><?php echo $Count_Confirm_Declined; ?></p>
                </div>
            </div>
            
            <!-- รอการยืนยัน -->
            <div class="card-hover glass rounded-2xl p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-amber-500/20 to-orange-600/20 rounded-full -mr-10 -mt-10"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-3 py-1 text-xs font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-full">รอการยืนยัน</span>
                        <div class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white"><?php echo $Count_Confirm_Pending; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="glass rounded-2xl p-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
            <i class="fas fa-bolt text-amber-500 mr-2"></i>
            เข้าถึงด่วน
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="m1_nomal.php" class="group p-4 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all text-center">
                <div class="w-12 h-12 mx-auto flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-list text-xl"></i>
                </div>
                <p class="mt-2 text-sm font-medium text-gray-700 dark:text-gray-300">ข้อมูล ม.1</p>
            </a>
            <a href="m4_nomal.php" class="group p-4 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 hover:border-purple-500 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all text-center">
                <div class="w-12 h-12 mx-auto flex items-center justify-center bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-list text-xl"></i>
                </div>
                <p class="mt-2 text-sm font-medium text-gray-700 dark:text-gray-300">ข้อมูล ม.4</p>
            </a>
            <a href="check_m1_nomal.php" class="group p-4 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 hover:border-amber-500 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-all text-center">
                <div class="w-12 h-12 mx-auto flex items-center justify-center bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-check-double text-xl"></i>
                </div>
                <p class="mt-2 text-sm font-medium text-gray-700 dark:text-gray-300">ตรวจหลักฐาน</p>
            </a>
            <a href="../logout.php" class="group p-4 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 hover:border-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all text-center">
                <div class="w-12 h-12 mx-auto flex items-center justify-center bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-sign-out-alt text-xl"></i>
                </div>
                <p class="mt-2 text-sm font-medium text-gray-700 dark:text-gray-300">ออกจากระบบ</p>
            </a>
        </div>
    </div>
</div>

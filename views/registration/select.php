<!-- Registration Selection Page - Dynamic Config -->
<div class="space-y-8">
    <!-- Page Header -->
    <div class="text-center max-w-3xl mx-auto">
        <h1 class="text-4xl font-bold gradient-text">สมัครเรียนโรงเรียนพิชัย</h1>
        <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">ปีการศึกษา <?php echo $academicYear; ?></p>
    </div>

    <?php if (!$registrationOpen): ?>
    <!-- System Closed Notice -->
    <div class="glass rounded-2xl p-6 text-center border-2 border-red-500">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-red-500 to-pink-600 rounded-full shadow-lg mb-4">
            <i class="fas fa-times text-3xl text-white"></i>
        </div>
        <h3 class="text-2xl font-bold text-red-600 dark:text-red-400">ระบบปิดรับสมัครชั่วคราว</h3>
        <p class="text-gray-600 dark:text-gray-400 mt-2">กรุณาติดต่อเจ้าหน้าที่หากมีข้อสงสัย</p>
    </div>
    <?php else: ?>
    
    <!-- Important Notice -->
    <div class="glass rounded-2xl p-6 border-l-4 border-red-500 animate-slide-up">
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 flex items-center justify-center bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-xl">
                    <i class="fas fa-bullhorn text-2xl"></i>
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">ข้อตกลงก่อนการกรอกข้อมูลใบสมัคร</h3>
                <div class="mt-4 space-y-3 text-gray-600 dark:text-gray-300 text-sm">
                    <p class="flex items-start"><span class="flex-shrink-0 w-6 h-6 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-sm font-bold mr-3">1</span>ผู้สมัครควรใช้เครื่องคอมพิวเตอร์ที่มีระบบโปรแกรมเบราว์เซอร์ Google Chrome, Microsoft Edge หรือ Firefox</p>
                    <p class="flex items-start"><span class="flex-shrink-0 w-6 h-6 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-sm font-bold mr-3">2</span>หากข้อมูลไม่ตรงกับความเป็นจริงหรือเป็นเท็จ ทางโรงเรียนขอสงวนสิทธิ์ในการไม่พิจารณา</p>
                    <p class="flex items-start"><span class="flex-shrink-0 w-6 h-6 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-sm font-bold mr-3">3</span>ข้อมูลจะยังไม่บันทึกจนกว่าจะจบขั้นตอนการกรอกข้อมูล</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Selection - Dynamic from Config -->
    <div class="glass rounded-2xl p-6">
        <div class="flex items-center space-x-3 mb-6">
            <div class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg">
                <i class="fas fa-graduation-cap text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">กรุณาเลือกระดับชั้นที่ต้องการสมัคร</h3>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <?php 
            $colors = [
                'm1' => ['from' => 'blue-500', 'to' => 'indigo-600', 'shadow' => 'blue'],
                'm4' => ['from' => 'emerald-500', 'to' => 'teal-600', 'shadow' => 'emerald']
            ];
            
            foreach ($gradeLevels as $grade): 
                $color = $colors[$grade['code']] ?? ['from' => 'gray-500', 'to' => 'gray-600', 'shadow' => 'gray'];
                $gradeTypes = array_filter($registrationTypes, fn($t) => $t['grade_level_id'] == $grade['id']);
            ?>
            <!-- <?php echo $grade['name']; ?> Card -->
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-<?php echo $color['from']; ?> to-<?php echo $color['to']; ?> p-1">
                <div class="relative bg-white dark:bg-slate-800 rounded-xl p-6">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-16 h-16 flex items-center justify-center bg-gradient-to-br from-<?php echo $color['from']; ?> to-<?php echo $color['to']; ?> rounded-2xl shadow-lg shadow-<?php echo $color['shadow']; ?>-500/30">
                            <i class="fas fa-user-plus text-3xl text-white"></i>
                        </div>
                        <div>
                            <h4 class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo $grade['name']; ?></h4>
                            <p class="text-gray-500 dark:text-gray-400"><?php echo count($gradeTypes); ?> ประเภทการสมัคร</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <?php foreach ($gradeTypes as $type): 
                            $isActive = $type['is_active'];
                            $typeUrl = $type['url'] ?? '';
                            $startDate = $type['start_datetime'] ? new DateTime($type['start_datetime']) : null;
                            $endDate = $type['end_datetime'] ? new DateTime($type['end_datetime']) : null;
                            $now = new DateTime();
                            
                            // Check schedule if enabled
                            $withinSchedule = true;
                            if ($type['use_schedule'] && $startDate && $endDate) {
                                $withinSchedule = ($now >= $startDate && $now <= $endDate);
                            }
                            $canRegister = $isActive && $withinSchedule && !empty($typeUrl);
                            
                            $btnClass = $canRegister 
                                ? 'bg-gradient-to-r from-'.$color['from'].' to-'.$color['to'].' hover:shadow-lg cursor-pointer' 
                                : 'bg-gray-300 dark:bg-gray-600 cursor-not-allowed';
                        ?>
                        <button 
                            onclick="<?php echo $canRegister ? "location.href='{$typeUrl}'" : ''; ?>"
                            <?php echo !$canRegister ? 'disabled' : ''; ?>
                            class="w-full py-3 px-6 <?php echo $btnClass; ?> text-white font-bold rounded-xl transition-all"
                            data-type-id="<?php echo $type['id']; ?>">
                            <i class="fas fa-<?php echo $canRegister ? 'arrow-right' : 'lock'; ?> mr-2"></i>
                            สมัคร <?php echo $type['name']; ?>
                            <?php if ($type['use_schedule'] && $startDate): ?>
                            <span class="block text-xs font-normal mt-1 opacity-75">
                                <?php echo $startDate->format('d/m') . ' - ' . $endDate->format('d/m/Y'); ?>
                            </span>
                            <?php endif; ?>
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php endif; ?>

    <!-- Calendar Image -->
    <div class="glass rounded-2xl p-6 overflow-hidden">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
            <i class="fas fa-calendar-alt text-primary-500 mr-2"></i>
            ปฏิทินการรับสมัคร
        </h3>
        <div class="rounded-xl overflow-hidden">
            <img src="dist/img/calendarregis.jpg" alt="Calendar" class="w-full h-auto">
        </div>
    </div>
</div>

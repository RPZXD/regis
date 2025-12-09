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
                            $typeUrl = 'register.php?type=' . $type['id'];
                            $registerStart = !empty($type['register_start']) ? new DateTime($type['register_start']) : null;
                            $registerEnd = !empty($type['register_end']) ? new DateTime($type['register_end']) : null;
                            $now = new DateTime();
                            
                            // Check if within registration schedule
                            $withinSchedule = true;
                            if ($registerStart && $registerEnd) {
                                $withinSchedule = ($now >= $registerStart && $now <= $registerEnd);
                            }
                            $canRegister = $isActive && $withinSchedule;
                            
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
                            <?php if ($registerStart && $registerEnd): ?>
                            <span class="block text-xs font-normal mt-1 opacity-75">
                                <?php echo $registerStart->format('d/m') . ' - ' . $registerEnd->format('d/m/Y'); ?>
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

    <!-- Calendar / Schedule Section -->
    <div class="glass rounded-2xl p-6 overflow-hidden">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">
            <i class="fas fa-calendar-alt text-primary-500 mr-2"></i>
            ปฏิทินการรับสมัคร ปีการศึกษา <?php echo $academicYear; ?>
        </h3>
        
        <?php if (!empty($registrationTypes)): ?>
        <div class="space-y-6">
            <?php 
            // Define schedule types with their properties
            $scheduleTypes = [
                ['key' => 'register', 'name' => 'สมัครเรียน', 'icon' => 'fa-user-plus', 'color' => 'blue'],
                ['key' => 'print_form', 'name' => 'พิมพ์ใบสมัคร', 'icon' => 'fa-print', 'color' => 'purple'],
                ['key' => 'upload', 'name' => 'อัพโหลดหลักฐาน', 'icon' => 'fa-upload', 'color' => 'amber'],
                ['key' => 'exam_card', 'name' => 'พิมพ์บัตรสอบ', 'icon' => 'fa-id-card', 'color' => 'cyan'],
                ['key' => 'report', 'name' => 'รายงานตัว', 'icon' => 'fa-check-circle', 'color' => 'green'],
                ['key' => 'announce', 'name' => 'ประกาศผล', 'icon' => 'fa-bullhorn', 'color' => 'rose'],
            ];
            
            foreach ($registrationTypes as $type): 
                if (!$type['is_active']) continue;
            ?>
            <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                <!-- Type Header -->
                <div class="bg-gradient-to-r from-gray-100 to-gray-50 dark:from-slate-700 dark:to-slate-800 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-1 text-xs font-bold rounded bg-<?php echo $type['grade_code'] == 'm1' ? 'blue' : 'purple'; ?>-100 text-<?php echo $type['grade_code'] == 'm1' ? 'blue' : 'purple'; ?>-600 dark:bg-<?php echo $type['grade_code'] == 'm1' ? 'blue' : 'purple'; ?>-900/30 dark:text-<?php echo $type['grade_code'] == 'm1' ? 'blue' : 'purple'; ?>-400">
                            <?php echo $type['grade_name']; ?>
                        </span>
                        <h4 class="font-bold text-gray-900 dark:text-white"><?php echo htmlspecialchars($type['name']); ?></h4>
                    </div>
                </div>
                
                <!-- Schedule Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 divide-x divide-y md:divide-y-0 divide-gray-200 dark:divide-gray-700">
                    <?php foreach ($scheduleTypes as $schedule): 
                        $startField = $schedule['key'] . '_start';
                        $endField = $schedule['key'] . '_end';
                        $startDate = !empty($type[$startField]) ? new DateTime($type[$startField]) : null;
                        $endDate = !empty($type[$endField]) ? new DateTime($type[$endField]) : null;
                        $hasSchedule = $startDate && $endDate;
                        $color = $schedule['color'];
                    ?>
                    <div class="p-3 text-center <?php echo $hasSchedule ? 'bg-'.$color.'-50 dark:bg-'.$color.'-900/10' : 'bg-gray-50 dark:bg-slate-800'; ?>">
                        <div class="flex items-center justify-center gap-1 mb-2">
                            <i class="fas <?php echo $schedule['icon']; ?> text-<?php echo $hasSchedule ? $color.'-500' : 'gray-400'; ?> text-sm"></i>
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-400"><?php echo $schedule['name']; ?></span>
                        </div>
                        <?php if ($hasSchedule): ?>
                        <div class="text-xs text-<?php echo $color; ?>-600 dark:text-<?php echo $color; ?>-400 font-medium">
                            <?php echo $startDate->format('d/m'); ?> - <?php echo $endDate->format('d/m'); ?>
                        </div>
                        <?php else: ?>
                        <div class="text-xs text-gray-400">-</div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <i class="fas fa-calendar-times text-4xl mb-3 opacity-50"></i>
            <p>ยังไม่มีข้อมูลปฏิทิน</p>
        </div>
        <?php endif; ?>
    </div>
</div>


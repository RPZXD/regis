<!-- Dynamic Registration Form - Works with any registration type -->
<?php
$type = $formData['type'];
$plans = $formData['plans'];
$grade = $formData['grade'];
$academicYear = $formData['academicYear'];

// Determine which fields to show based on type
$typeCode = $type['code'];
$showGPA = in_array($typeCode, ['special', 'general', 'quota']);
$showTalent = ($typeCode === 'talent');
$showQuotaConfirm = ($typeCode === 'quota');
$showZoneSelect = ($grade == '1' && $typeCode === 'general'); // ม.1 ทั่วไป มีในเขต/นอกเขต

// Prefix options based on grade
$prefixOptions = ($grade == '1') 
    ? ['เด็กชาย', 'เด็กหญิง'] 
    : ['นาย', 'นางสาว'];
?>

<div class="space-y-6">
    <!-- Page Header -->
    <div class="text-center">
        <h1 class="text-3xl font-bold gradient-text">สมัครเรียนมัธยมศึกษาปีที่ <?php echo $grade; ?></h1>
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-400"><?php echo $type['name']; ?> - ปีการศึกษา <?php echo $academicYear; ?></p>
        <span class="inline-block mt-2 px-4 py-1 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-full text-sm font-medium">
            <i class="fas fa-tag mr-1"></i><?php echo $type['grade_name']; ?> / <?php echo $type['name']; ?>
        </span>
    </div>

    <!-- Progress Steps -->
    <div class="glass rounded-2xl p-4">
        <div class="flex items-center justify-between max-w-3xl mx-auto">
            <?php 
            $steps = ['ข้อมูลส่วนตัว', 'โรงเรียนเดิม', 'ที่อยู่ปัจจุบัน', 'ทะเบียนบ้าน', 'ผู้ปกครอง', 'ยืนยัน'];
            foreach ($steps as $i => $step): 
            ?>
            <div class="step-indicator flex flex-col items-center <?php echo $i > 0 ? 'flex-1' : ''; ?>">
                <div class="step-circle w-10 h-10 flex items-center justify-center rounded-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-800 text-gray-500 dark:text-gray-400 font-bold text-sm transition-all" data-step="<?php echo $i; ?>">
                    <?php echo $i + 1; ?>
                </div>
                <span class="hidden md:block text-xs mt-2 text-gray-500 dark:text-gray-400 text-center"><?php echo $step; ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Form Container -->
    <div class="glass rounded-2xl p-6 md:p-8">
        <form id="regForm" method="POST" enctype="multipart/form-data">
            <!-- Hidden fields for type info -->
            <input type="hidden" name="registration_type_id" value="<?php echo $type['id']; ?>">
            <input type="hidden" name="grade_level" value="<?php echo $grade; ?>">
            <input type="hidden" name="type_code" value="<?php echo $typeCode; ?>">
            
            <!-- Step 1: Personal Info -->
            <div class="tab animate-fade-in" data-step="0">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span class="w-10 h-10 flex items-center justify-center bg-primary-500 text-white rounded-xl mr-3">1</span>
                    ข้อมูลส่วนตัว
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Citizen ID -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-id-card mr-2 text-primary-500"></i>เลขประจำตัวประชาชน *
                        </label>
                        <input type="text" id="citizenid" name="citizenid" maxlength="17" 
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                               placeholder="x-xxxx-xxxxx-xx-x" required>
                    </div>
                    
                    <!-- Study Plan Priority Selection (if multiple plans available) -->
                    <?php if (count($plans) > 0): 
                        $maxChoices = min(count($plans), 5); // Max 5 choices or total plans
                    ?>
                    <div class="lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            <i class="fas fa-list-ol mr-2 text-primary-500"></i>เลือกแผนการเรียนตามลำดับความต้องการ *
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">เลือกแผนการเรียนอย่างน้อย 1 อันดับ และไม่สามารถเลือกซ้ำได้</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="studyPlanSelects">
                            <?php for ($choice = 1; $choice <= $maxChoices; $choice++): ?>
                            <div class="plan-choice-wrapper">
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                    <?php 
                                    $badges = ['1' => 'อันดับ 1 🥇', '2' => 'อันดับ 2 🥈', '3' => 'อันดับ 3 🥉'];
                                    echo $badges[$choice] ?? "อันดับ {$choice}"; 
                                    ?>
                                </label>
                                <select name="study_plan_<?php echo $choice; ?>" 
                                        class="study-plan-select w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                        data-choice="<?php echo $choice; ?>"
                                        <?php echo $choice === 1 ? 'required' : ''; ?>>
                                    <option value=""><?php echo $choice === 1 ? 'เลือกแผน (จำเป็น)' : 'ว่าง (ไม่บังคับ)'; ?></option>
                                    <?php foreach ($plans as $plan): ?>
                                    <option value="<?php echo $plan['id']; ?>" data-quota="<?php echo $plan['quota']; ?>">
                                        <?php echo htmlspecialchars($plan['name']); ?> (รับ <?php echo $plan['quota']; ?> คน)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Zone Selection for M.1 General (ในเขต/นอกเขต) -->
                    <?php if ($showZoneSelect): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-primary-500"></i>ประเภทพื้นที่ *
                        </label>
                        <select name="zone_type" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                            <option value="">เลือก</option>
                            <option value="ในเขต">ในเขต</option>
                            <option value="นอกเขต">นอกเขต</option>
                        </select>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Prefix -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-user mr-2 text-primary-500"></i>คำนำหน้า *
                        </label>
                        <select name="stu_prefix" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                            <option value="">เลือก</option>
                            <?php foreach ($prefixOptions as $prefix): ?>
                            <option value="<?php echo $prefix; ?>"><?php echo $prefix; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- First Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ชื่อ *</label>
                        <input type="text" name="stu_name" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" placeholder="ชื่อ" required>
                    </div>
                    
                    <!-- Last Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">นามสกุล *</label>
                        <input type="text" name="stu_lastname" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" placeholder="นามสกุล" required>
                    </div>
                    
                    <!-- Birth Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-calendar mr-2 text-primary-500"></i>วันเกิด *
                        </label>
                        <select name="date_birth" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                            <option value="">วัน</option>
                            <?php for($i=1; $i<=31; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <!-- Birth Month -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">เดือนเกิด *</label>
                        <select name="month_birth" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                            <option value="">เดือน</option>
                            <?php 
                            $months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
                            foreach($months as $index => $month): 
                                $monthValue = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                            ?>
                            <option value="<?php echo $monthValue; ?>"><?php echo $month; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Birth Year -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ปีเกิด (พ.ศ.) *</label>
                        <select name="year_birth" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                            <option value="">ปี</option>
                            <?php 
                            $currentYear = date('Y') + 543;
                            $startAge = ($grade == '1') ? 12 : 15;
                            for($i = $currentYear - $startAge; $i >= $currentYear - ($startAge + 8); $i--): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <!-- Sex -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">เพศ *</label>
                        <select name="stu_sex" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                            <option value="">เลือก</option>
                            <option value="ชาย">ชาย</option>
                            <option value="หญิง">หญิง</option>
                        </select>
                    </div>

                    <!-- Blood Group -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">กรุ๊ปเลือด *</label>
                        <select name="stu_blood_group" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                            <option value="">เลือก</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="O">O</option>
                            <option value="AB">AB</option>
                            <option value="-">-</option>
                        </select>
                    </div>
                    
                    <!-- Religion -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ศาสนา *</label>
                        <select name="stu_religion" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                            <option value="">เลือก</option>
                            <option value="พุทธ">พุทธ</option>
                            <option value="คริสต์">คริสต์</option>
                            <option value="อิสลาม">อิสลาม</option>
                            <option value="อื่นๆ">อื่นๆ</option>
                        </select>
                    </div>
                    
                    <!-- Ethnicity & Nationality -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">เชื้อชาติ *</label>
                        <input type="text" name="stu_ethnicity" value="ไทย" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">สัญชาติ *</label>
                        <input type="text" name="stu_nationality" value="ไทย" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                    </div>
                    
                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-phone mr-2 text-primary-500"></i>เบอร์โทรศัพท์ *
                        </label>
                        <input type="text" name="now_tel" maxlength="10" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" placeholder="0xxxxxxxxx" required>
                    </div>
                    
                    <!-- GPA (for special, general, quota types) -->
                    <?php if ($showGPA): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-star mr-2 text-amber-500"></i>เกรดเฉลี่ยสะสม *
                        </label>
                        <input type="text" name="gpa_total" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" placeholder="0.00" required>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Talent fields -->
                    <?php if ($showTalent): ?>
                    <div class="lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-trophy mr-2 text-amber-500"></i>ความสามารถพิเศษ *
                        </label>
                        <textarea name="talent_skill" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" placeholder="ระบุความสามารถพิเศษ เช่น กีฬา ดนตรี ศิลปะ" required></textarea>
                    </div>
                    <div class="lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-medal mr-2 text-amber-500"></i>ผลงาน/รางวัล
                        </label>
                        <textarea name="talent_awards" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" placeholder="ระบุผลงานหรือรางวัลที่เคยได้รับ"></textarea>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Quota confirmation -->
                    <?php if ($showQuotaConfirm): ?>
                    <div class="lg:col-span-3">
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/30 rounded-xl border border-blue-200 dark:border-blue-800">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="quota_confirm" value="1" required class="w-5 h-5 rounded text-primary-500 focus:ring-primary-500 mr-3">
                                <span class="text-gray-700 dark:text-gray-300">ข้าพเจ้ายืนยันว่าเป็นนักเรียนที่กำลังศึกษาอยู่ชั้น ม.3 โรงเรียนพิชัย</span>
                            </label>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Step 2: Previous School -->
            <div class="tab animate-fade-in hidden" data-step="1">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span class="w-10 h-10 flex items-center justify-center bg-primary-500 text-white rounded-xl mr-3">2</span>
                    ข้อมูลโรงเรียนเดิม
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-school mr-2 text-primary-500"></i>ชื่อโรงเรียนเดิม *
                        </label>
                        <input type="text" name="old_school_name" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" placeholder="ชื่อโรงเรียน" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">จังหวัด *</label>
                        <select name="old_school_province" id="oldSchoolProvince" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                            <option value="">เลือกจังหวัด</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">อำเภอ *</label>
                        <select name="old_school_district" id="oldSchoolDistrict" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                            <option value="">เลือกอำเภอ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ตำบล *</label>
                        <select name="old_school_subdistrict" id="oldSchoolSubdistrict" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                            <option value="">เลือกตำบล</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Step 3: Current Address -->
            <div class="tab animate-fade-in hidden" data-step="2">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span class="w-10 h-10 flex items-center justify-center bg-primary-500 text-white rounded-xl mr-3">3</span>
                    ที่อยู่ปัจจุบัน
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">บ้านเลขที่ *</label>
                        <input type="text" name="now_hno" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">หมู่ที่</label>
                        <input type="text" name="now_moo" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ซอย</label>
                        <input type="text" name="now_soi" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ถนน</label>
                        <input type="text" name="now_road" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">จังหวัด *</label>
                        <select name="now_province" id="nowProvince" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                            <option value="">เลือกจังหวัด</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">อำเภอ *</label>
                        <select name="now_district" id="nowDistrict" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                            <option value="">เลือกอำเภอ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ตำบล *</label>
                        <select name="now_subdistrict" id="nowSubdistrict" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                            <option value="">เลือกตำบล</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">รหัสไปรษณีย์ *</label>
                        <input type="text" name="now_postcode" id="nowPostcode" maxlength="5" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                    </div>
                </div>
            </div>
            
            <!-- Step 4: Registered Address -->
            <div class="tab animate-fade-in hidden" data-step="3">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span class="w-10 h-10 flex items-center justify-center bg-primary-500 text-white rounded-xl mr-3">4</span>
                    ที่อยู่ตามทะเบียนบ้าน
                </h3>
                
                <div class="mb-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" id="sameAddress" class="w-5 h-5 rounded text-primary-500 focus:ring-primary-500 mr-3">
                        <span class="text-gray-700 dark:text-gray-300">ที่อยู่เดียวกับที่อยู่ปัจจุบัน</span>
                    </label>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="regAddressFields">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">บ้านเลขที่ *</label>
                        <input type="text" name="reg_hno" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">หมู่ที่</label>
                        <input type="text" name="reg_moo" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ซอย</label>
                        <input type="text" name="reg_soi" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ถนน</label>
                        <input type="text" name="reg_road" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">จังหวัด *</label>
                        <select name="reg_province" id="regProvince" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                            <option value="">เลือกจังหวัด</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">อำเภอ *</label>
                        <select name="reg_district" id="regDistrict" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                            <option value="">เลือกอำเภอ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ตำบล *</label>
                        <select name="reg_subdistrict" id="regSubdistrict" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                            <option value="">เลือกตำบล</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">รหัสไปรษณีย์ *</label>
                        <input type="text" name="reg_postcode" id="regPostcode" maxlength="5" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                    </div>
                </div>
            </div>
            
            <!-- Step 5: Guardian Info -->
            <div class="tab animate-fade-in hidden" data-step="4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span class="w-10 h-10 flex items-center justify-center bg-primary-500 text-white rounded-xl mr-3">5</span>
                    ข้อมูลผู้ปกครอง
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-user-tie mr-2 text-primary-500"></i>คำนำหน้า *
                        </label>
                        <select name="parent_prefix" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                            <option value="">เลือก</option>
                            <option value="นาย">นาย</option>
                            <option value="นาง">นาง</option>
                            <option value="นางสาว">นางสาว</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ชื่อผู้ปกครอง *</label>
                        <input type="text" name="parent_name" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">นามสกุลผู้ปกครอง *</label>
                        <input type="text" name="parent_lastname" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ความสัมพันธ์ *</label>
                        <select name="parent_relation" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" required>
                            <option value="">เลือก</option>
                            <option value="บิดา">บิดา</option>
                            <option value="มารดา">มารดา</option>
                            <option value="ปู่">ปู่</option>
                            <option value="ย่า">ย่า</option>
                            <option value="ตา">ตา</option>
                            <option value="ยาย">ยาย</option>
                            <option value="ลุง">ลุง</option>
                            <option value="ป้า">ป้า</option>
                            <option value="อื่นๆ">อื่นๆ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-phone mr-2 text-primary-500"></i>เบอร์โทรศัพท์ *
                        </label>
                        <input type="text" name="parent_tel" maxlength="10" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" placeholder="0xxxxxxxxx" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">อาชีพ</label>
                        <input type="text" name="parent_occupation" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>
            </div>
            
            <!-- Step 6: Confirmation -->
            <div class="tab animate-fade-in hidden" data-step="5">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span class="w-10 h-10 flex items-center justify-center bg-green-500 text-white rounded-xl mr-3">
                        <i class="fas fa-check"></i>
                    </span>
                    ยืนยันการสมัคร
                </h3>
                
                <div class="p-6 bg-green-50 dark:bg-green-900/30 rounded-xl border border-green-200 dark:border-green-800 mb-6">
                    <h4 class="text-lg font-bold text-green-800 dark:text-green-300 mb-4">กรุณาตรวจสอบข้อมูลก่อนยืนยัน</h4>
                    <ul class="space-y-2 text-green-700 dark:text-green-400">
                        <li><i class="fas fa-check-circle mr-2"></i>ตรวจสอบข้อมูลส่วนตัวให้ถูกต้อง</li>
                        <li><i class="fas fa-check-circle mr-2"></i>ตรวจสอบที่อยู่และข้อมูลติดต่อ</li>
                        <li><i class="fas fa-check-circle mr-2"></i>ตรวจสอบข้อมูลผู้ปกครอง</li>
                    </ul>
                </div>
                
                <div class="p-4 bg-amber-50 dark:bg-amber-900/30 rounded-xl border border-amber-200 dark:border-amber-800">
                    <label class="flex items-start cursor-pointer">
                        <input type="checkbox" name="confirm_agreement" value="1" required class="w-5 h-5 rounded text-primary-500 focus:ring-primary-500 mt-1 mr-3">
                        <span class="text-gray-700 dark:text-gray-300">
                            ข้าพเจ้ายืนยันว่าข้อมูลที่กรอกทั้งหมดเป็นความจริงทุกประการ และยอมรับเงื่อนไขการสมัครของโรงเรียน
                        </span>
                    </label>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="button" id="prevBtn" onclick="nextPrev(-1)" class="hidden px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>ย้อนกลับ
                </button>
                <button type="button" id="nextBtn" onclick="nextPrev(1)" class="ml-auto px-8 py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 transition-all transform hover:-translate-y-1">
                    ถัดไป<i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const typeId = <?php echo $type['id']; ?>;
const gradeLevel = <?php echo $grade; ?>;
var currentTab = 0;
showTab(currentTab);

function showTab(n) {
    var tabs = document.getElementsByClassName("tab");
    var stepCircles = document.querySelectorAll('.step-circle');
    
    for (var i = 0; i < tabs.length; i++) {
        tabs[i].classList.add('hidden');
        tabs[i].classList.remove('animate-fade-in');
    }
    
    tabs[n].classList.remove('hidden');
    tabs[n].classList.add('animate-fade-in');
    
    stepCircles.forEach((circle, index) => {
        if (index < n) {
            circle.classList.remove('border-gray-300', 'dark:border-gray-600', 'text-gray-500');
            circle.classList.add('border-green-500', 'bg-green-500', 'text-white');
            circle.innerHTML = '<i class="fas fa-check"></i>';
        } else if (index === n) {
            circle.classList.remove('border-gray-300', 'text-gray-500', 'border-green-500', 'bg-green-500');
            circle.classList.add('border-primary-500', 'bg-primary-500', 'text-white');
            circle.innerHTML = index + 1;
        } else {
            circle.classList.remove('border-primary-500', 'bg-primary-500', 'bg-green-500', 'text-white');
            circle.classList.add('border-gray-300', 'text-gray-500');
            circle.innerHTML = index + 1;
        }
    });
    
    document.getElementById("prevBtn").classList.toggle('hidden', n === 0);
    document.getElementById("nextBtn").innerHTML = n === (tabs.length - 1) 
        ? '<i class="fas fa-check mr-2"></i>ยืนยันการสมัคร' 
        : 'ถัดไป<i class="fas fa-arrow-right ml-2"></i>';
}

function nextPrev(n) {
    var tabs = document.getElementsByClassName("tab");
    if (n === 1 && !validateForm()) return false;
    currentTab += n;
    if (currentTab >= tabs.length) {
        submitForm();
        return false;
    }
    showTab(currentTab);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function validateForm() {
    var valid = true;
    var inputs = document.getElementsByClassName("tab")[currentTab].querySelectorAll("input[required], select[required], textarea[required]");
    
    inputs.forEach(function(input) {
        if (input.type === 'checkbox' && !input.checked) {
            input.closest('label').classList.add('text-red-500');
            valid = false;
        } else if (input.value === "") {
            input.classList.add('border-red-500', 'ring-2', 'ring-red-500');
            valid = false;
        } else {
            input.classList.remove('border-red-500', 'ring-2', 'ring-red-500');
        }
    });
    
    if (!valid) {
        Swal.fire({
            icon: 'warning',
            title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
            text: 'โปรดตรวจสอบข้อมูลที่จำเป็นต้องกรอก',
            confirmButtonColor: '#3b82f6'
        });
    }
    return valid;
}

function submitForm() {
    Swal.fire({
        title: 'กำลังบันทึกข้อมูล...',
        html: 'กรุณารอสักครู่',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    var form = document.getElementById("regForm");
    var formData = new FormData(form);
    
    fetch('api/register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                html: `${data.message}<br><strong>เลขที่สมัคร: ${data.reg_number}</strong>`,
                confirmButtonColor: '#10b981'
            }).then(() => window.location.href = 'regis.php');
        } else {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: data.message,
                confirmButtonColor: '#ef4444'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: error.message,
            confirmButtonColor: '#ef4444'
        });
    });
}

// Citizen ID formatting
// Citizen ID formatting and Validation
const citizenInput = document.getElementById('citizenid');
let isCitizenIdValid = false;

citizenInput.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 13) value = value.substr(0, 13);
    
    let formatted = '';
    if (value.length > 0) formatted += value.substr(0, 1);
    if (value.length > 1) formatted += '-' + value.substr(1, 4);
    if (value.length > 5) formatted += '-' + value.substr(5, 5);
    if (value.length > 10) formatted += '-' + value.substr(10, 2);
    if (value.length > 12) formatted += '-' + value.substr(12, 1);
    
    e.target.value = formatted;
    
    // Reset validity on input
    if (value.length === 13) {
        checkCitizenId(value);
    } else {
        isCitizenIdValid = false;
        citizenInput.classList.remove('border-green-500', 'focus:ring-green-500');
        citizenInput.classList.remove('border-red-500', 'focus:ring-red-500');
    }
});

function checkCitizenId(id) {
    const formData = new FormData();
    formData.append('citizenid', id);
    formData.append('type_id', '<?php echo $type['id']; ?>');
    
    fetch('api/check-citizen.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.valid) {
            isCitizenIdValid = true;
            citizenInput.classList.remove('border-red-500', 'focus:ring-red-500');
            citizenInput.classList.add('border-green-500', 'focus:ring-green-500');
        } else {
            isCitizenIdValid = false;
            citizenInput.classList.remove('border-green-500', 'focus:ring-green-500');
            citizenInput.classList.add('border-red-500', 'focus:ring-red-500');
            
            Swal.fire({
                icon: 'error',
                title: 'ไม่สามารถใช้เลขบัตรนี้ได้',
                text: data.error,
                confirmButtonText: 'ตกลง',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg'
                }
            });
        }
    })
    .catch(error => console.error('Error:', error));
}

function submitForm() {
    if (!isCitizenIdValid) {
        Swal.fire({
            icon: 'warning',
            title: 'กรุณาตรวจสอบข้อมูล',
            text: 'เลขบัตรประจำตัวประชาชนไม่ถูกต้อง หรือมีการสมัครแล้ว',
            confirmButtonText: 'ตกลง',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg'
            }
        });
        showTab(0); // Go back to first tab
        return;
    }
    
    Swal.fire({
        title: 'กำลังบันทึกข้อมูล...',
        text: 'กรุณารอสักครู่',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        },
        customClass: {
            popup: 'rounded-2xl'
        }
    });
    
    var form = document.getElementById("regForm");
    var formData = new FormData(form);
    
    fetch('api/register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'สมัครเรียนสำเร็จ!',
                text: 'ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว',
                confirmButtonText: 'พิมพ์ใบสมัคร',
                allowOutsideClick: false,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-xl text-lg shadow-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to print page
                    window.location.href = 'print_card.php?citizenid=' + data.citizen_id; 
                }
            });
        } else {
            throw new Error(data.message || 'Unknown error occurred');
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'บันทึกข้อมูลไม่สำเร็จ',
            text: error.message,
            confirmButtonText: 'ลองใหม่อีกครั้ง',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg'
            }
        });
    });
}


// Same address checkbox
document.getElementById('sameAddress')?.addEventListener('change', function() {
    if (this.checked) {
        document.querySelector('[name="reg_hno"]').value = document.querySelector('[name="now_hno"]').value;
        document.querySelector('[name="reg_moo"]').value = document.querySelector('[name="now_moo"]').value;
        document.querySelector('[name="reg_soi"]').value = document.querySelector('[name="now_soi"]').value;
        document.querySelector('[name="reg_road"]').value = document.querySelector('[name="now_road"]').value;
        document.querySelector('[name="reg_postcode"]').value = document.querySelector('[name="now_postcode"]').value;
        // Province/District/Subdistrict would need AJAX handling
    }
});

// Load provinces on page load (would need AJAX endpoint)
document.addEventListener('DOMContentLoaded', function() {
    // Load provinces here if needed
    
    // Study Plan Priority Selection - Prevent Duplicates
    const planSelects = document.querySelectorAll('.study-plan-select');
    
    if (planSelects.length > 0) {
        planSelects.forEach(select => {
            select.addEventListener('change', updatePlanOptions);
        });
    }
    
    function updatePlanOptions() {
        const planSelects = document.querySelectorAll('.study-plan-select');
        
        // 1. First pass: Clear any duplicates immediately (Higher priority wins)
        const outputValues = new Set();
        planSelects.forEach(select => {
            if (select.value) {
                if (outputValues.has(select.value)) {
                    select.value = ''; // Duplicate found, clear it
                } else {
                    outputValues.add(select.value);
                }
            }
        });

        // 2. Second pass: Update UI (Disable options)
        const selectedValues = [];
        planSelects.forEach(select => {
            if (select.value) selectedValues.push(select.value);
        });
        
        planSelects.forEach(select => {
            const currentValue = select.value;
            
            Array.from(select.options).forEach(option => {
                if (option.value === '') return;
                
                // Disable if selected elsewhere (and not by self)
                if (selectedValues.includes(option.value) && option.value !== currentValue) {
                    option.disabled = true;
                    option.style.color = '#ccc'; // Visual feedback
                } else {
                    option.disabled = false;
                    option.style.color = '';
                }
            });
        });
        
        // Clear lower priority selections if higher one is empty
        let emptyFound = false;
        planSelects.forEach(select => {
            if (emptyFound) {
                select.value = '';
            }
            if (select.value === '') {
                emptyFound = true;
            }
        });
    }
    
    // ============ Cascading Address Dropdowns ============
    
    // Load provinces and initialize all address dropdowns
    loadProvinces();
    
    function loadProvinces() {
        fetch('services/ajax.province.php')
            .then(res => res.json())
            .then(data => {
                const provinceSelects = document.querySelectorAll('[id$="Province"]');
                provinceSelects.forEach(select => {
                    let options = '<option value="">เลือกจังหวัด</option>';
                    data.forEach(p => {
                        options += `<option value="${p.code}">${p.name}</option>`;
                    });
                    select.innerHTML = options;
                });
            });
    }
    
    function loadDistricts(provinceCode, districtSelectId) {
        const districtSelect = document.getElementById(districtSelectId);
        const subdistrictSelectId = districtSelectId.replace('District', 'Subdistrict');
        const subdistrictSelect = document.getElementById(subdistrictSelectId);
        
        // Reset dropdowns
        districtSelect.innerHTML = '<option value="">กำลังโหลด...</option>';
        if (subdistrictSelect) {
            subdistrictSelect.innerHTML = '<option value="">เลือกตำบล</option>';
        }
        
        if (!provinceCode) {
            districtSelect.innerHTML = '<option value="">เลือกอำเภอ</option>';
            return;
        }
        
        const formData = new FormData();
        formData.append('id', provinceCode);
        
        fetch('services/ajax.district.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            let options = '<option value="">เลือกอำเภอ</option>';
            data.forEach(d => {
                options += `<option value="${d.code}">${d.name}</option>`;
            });
            districtSelect.innerHTML = options;
        });
    }
    
    function loadSubdistricts(districtCode, subdistrictSelectId) {
        const subdistrictSelect = document.getElementById(subdistrictSelectId);
        
        subdistrictSelect.innerHTML = '<option value="">กำลังโหลด...</option>';
        
        if (!districtCode) {
            subdistrictSelect.innerHTML = '<option value="">เลือกตำบล</option>';
            return;
        }
        
        const formData = new FormData();
        formData.append('id', districtCode);
        
        fetch('services/ajax.subdistrict.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            let options = '<option value="">เลือกตำบล</option>';
            data.forEach(s => {
                options += `<option value="${s.code}">${s.name}</option>`;
            });
            subdistrictSelect.innerHTML = options;
        });
    }
    
    // Old School Address
    document.getElementById('oldSchoolProvince')?.addEventListener('change', function() {
        loadDistricts(this.value, 'oldSchoolDistrict');
    });
    document.getElementById('oldSchoolDistrict')?.addEventListener('change', function() {
        loadSubdistricts(this.value, 'oldSchoolSubdistrict');
    });
    
    // Current Address
    document.getElementById('nowProvince')?.addEventListener('change', function() {
        loadDistricts(this.value, 'nowDistrict');
    });
    document.getElementById('nowDistrict')?.addEventListener('change', function() {
        loadSubdistricts(this.value, 'nowSubdistrict');
    });
    
    // Registered Address
    document.getElementById('regProvince')?.addEventListener('change', function() {
        loadDistricts(this.value, 'regDistrict');
    });
    document.getElementById('regDistrict')?.addEventListener('change', function() {
        loadSubdistricts(this.value, 'regSubdistrict');
    });
});
</script>

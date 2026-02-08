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
$isSpecialType = ($typeCode === 'special'); // ห้องเรียนพิเศษ - ข้ามขั้นตอน 3-5

// Prefix options based on grade
$prefixOptions = ($grade == '1')
    ? ['เด็กชาย', 'เด็กหญิง']
    : ['นาย', 'นางสาว'];
?>

<div class="space-y-6">
    <!-- Page Header -->
    <div class="text-center">
        <h1 class="text-3xl font-bold gradient-text">สมัครเรียนมัธยมศึกษาปีที่ <?php echo $grade; ?></h1>
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-400"><?php echo $type['name']; ?> - ปีการศึกษา
            <?php echo $academicYear; ?>
        </p>
        <span
            class="inline-block mt-2 px-4 py-1 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-full text-sm font-medium">
            <i class="fas fa-tag mr-1"></i><?php echo $type['grade_name']; ?> / <?php echo $type['name']; ?>
        </span>
    </div>

    <!-- Progress Steps -->
    <div class="glass rounded-2xl p-4">
        <div class="flex items-center justify-between max-w-4xl mx-auto">
            <?php
            // ห้องเรียนพิเศษ ใช้ 4 steps แทน 7 steps
            if ($isSpecialType) {
                $steps = ['ข้อมูลส่วนตัว', 'โรงเรียนเดิม', 'ผู้ปกครอง', 'ยืนยัน'];
            } else {
                $steps = ['ข้อมูลส่วนตัว', 'โรงเรียนเดิม', 'ที่อยู่ปัจจุบัน', 'ทะเบียนบ้าน', 'บิดา-มารดา', 'ผู้ปกครอง', 'ยืนยัน'];
            }
            foreach ($steps as $i => $step):
                ?>
                <div class="step-indicator flex flex-col items-center <?php echo $i > 0 ? 'flex-1' : ''; ?>">
                    <div class="step-circle w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-800 text-gray-500 dark:text-gray-400 font-bold text-xs md:text-sm transition-all"
                        data-step="<?php echo $i; ?>">
                        <?php echo $i + 1; ?>
                    </div>
                    <span
                        class="hidden lg:block text-xs mt-2 text-gray-500 dark:text-gray-400 text-center whitespace-nowrap"><?php echo $step; ?></span>
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
                    <span
                        class="w-10 h-10 flex items-center justify-center bg-primary-500 text-white rounded-xl mr-3">1</span>
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

                    <!-- Registration Number (numreg) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-hashtag mr-2 text-primary-500"></i>เลขที่ผู้สมัคร *
                        </label>
                        <input type="text" id="numreg" name="numreg" maxlength="20"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                            placeholder="เลขที่ผู้สมัคร" required>
                    </div>

                    <!-- Study Plan Priority Selection (if multiple plans available) -->
                    <?php if (count($plans) > 0):
                        $maxChoices = count($plans); // Show all available plans as choices
                        ?>
                        <div class="lg:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                <i class="fas fa-list-ol mr-2 text-primary-500"></i>เลือกแผนการเรียนตามลำดับความต้องการ *
                            </label>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">เลือกแผนการเรียนอย่างน้อย 1 อันดับ
                                และไม่สามารถเลือกซ้ำได้</p>

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
                                            data-choice="<?php echo $choice; ?>" <?php echo $choice === 1 ? 'required' : ''; ?>>
                                            <option value="">
                                                <?php echo $choice === 1 ? 'เลือกแผน (จำเป็น)' : 'ว่าง (ไม่บังคับ)'; ?>
                                            </option>
                                            <?php foreach ($plans as $plan): ?>
                                                <option value="<?php echo $plan['id']; ?>"
                                                    data-quota="<?php echo $plan['quota']; ?>">
                                                    <?php echo htmlspecialchars($plan['name']); ?> (รับ
                                                    <?php echo $plan['quota']; ?> คน)
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
                            <select name="zone_type"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                required>
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
                        <select name="stu_prefix" id="stuPrefix"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            required onchange="autoSetGender(this.value)">
                            <option value="">เลือก</option>
                            <?php foreach ($prefixOptions as $prefix): ?>
                                <option value="<?php echo $prefix; ?>"><?php echo $prefix; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- First Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ชื่อ *</label>
                        <input type="text" name="stu_name"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            placeholder="ชื่อ" required>
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">นามสกุล *</label>
                        <input type="text" name="stu_lastname"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            placeholder="นามสกุล" required>
                    </div>

                    <!-- Birth Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-calendar mr-2 text-primary-500"></i>วันเกิด *
                        </label>
                        <select name="date_birth"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            required>
                            <option value="">วัน</option>
                            <?php for ($i = 1; $i <= 31; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Birth Month -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">เดือนเกิด
                            *</label>
                        <select name="month_birth"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            required>
                            <option value="">เดือน</option>
                            <?php
                            $months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
                            foreach ($months as $index => $month):
                                $monthValue = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                                ?>
                                <option value="<?php echo $monthValue; ?>"><?php echo $month; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Birth Year -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ปีเกิด (พ.ศ.)
                            *</label>
                        <select name="year_birth"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            required>
                            <option value="">ปี</option>
                            <?php
                            $currentYear = date('Y') + 543;
                            $startAge = ($grade == '1') ? 12 : 15;
                            // Expanded range: ±5 years from expected age
                            for ($i = $currentYear - ($startAge - 3); $i >= $currentYear - ($startAge + 7); $i--): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Sex -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">เพศ *</label>
                        <select name="stu_sex" id="stuSex"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            required>
                            <option value="">เลือก</option>
                            <option value="ชาย">ชาย</option>
                            <option value="หญิง">หญิง</option>
                        </select>
                    </div>

                    <!-- Blood Group -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">กรุ๊ปเลือด
                            *</label>
                        <select name="stu_blood_group"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            required>
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
                        <select name="stu_religion"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            required>
                            <option value="">เลือก</option>
                            <option value="พุทธ" selected>พุทธ</option>
                            <option value="คริสต์">คริสต์</option>
                            <option value="อิสลาม">อิสลาม</option>
                            <option value="อื่นๆ">อื่นๆ</option>
                        </select>
                    </div>

                    <!-- Ethnicity & Nationality -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">เชื้อชาติ
                            *</label>
                        <input type="text" name="stu_ethnicity" value="ไทย"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">สัญชาติ *</label>
                        <input type="text" name="stu_nationality" value="ไทย"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            required>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-phone mr-2 text-primary-500"></i>เบอร์โทรศัพท์ *
                        </label>
                        <input type="text" name="now_tel" maxlength="10" value="0"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            placeholder="0xxxxxxxxx" required>
                    </div>

                    <!-- GPA (for special, general, quota types) -->
                    <?php if ($showGPA): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-star mr-2 text-amber-500"></i>เกรดเฉลี่ยสะสม *
                            </label>
                            <input type="text" name="gpa_total"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                placeholder="0.00" required>
                        </div>
                    <?php endif; ?>

                    <!-- Subject Grades (for special classroom only) -->
                    <?php if ($typeCode === 'special'): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-calculator mr-2 text-blue-500"></i>เกรดคณิตศาสตร์ *
                            </label>
                            <input type="text" name="grade_math"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                placeholder="0.00" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-flask mr-2 text-green-500"></i>เกรดวิทยาศาสตร์/เทคโนโลยี *
                            </label>
                            <input type="text" name="grade_science"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                placeholder="0.00" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-globe mr-2 text-purple-500"></i>เกรดภาษาอังกฤษ *
                            </label>
                            <input type="text" name="grade_english"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                placeholder="0.00" required>
                        </div>
                    <?php endif; ?>

                    <!-- Talent fields -->
                    <?php if ($showTalent): ?>
                        <div class="lg:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-trophy mr-2 text-amber-500"></i>ความสามารถพิเศษ *
                            </label>
                            <textarea name="talent_skill" rows="3"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                placeholder="ระบุความสามารถพิเศษ เช่น กีฬา ดนตรี ศิลปะ" required></textarea>
                        </div>
                        <div class="lg:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-medal mr-2 text-amber-500"></i>ผลงาน/รางวัล
                            </label>
                            <textarea name="talent_awards" rows="3"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                placeholder="ระบุผลงานหรือรางวัลที่เคยได้รับ"></textarea>
                        </div>
                    <?php endif; ?>

                    <!-- Quota: Old Student ID -->
                    <?php if ($showQuotaConfirm): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-id-badge mr-2 text-blue-500"></i>เลขประจำตัวเดิม *
                            </label>
                            <input type="text" name="old_student_id" maxlength="10"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                                placeholder="เลขประจำตัวนักเรียน ม.3" required>
                        </div>

                        <!-- Quota confirmation -->
                        <div class="lg:col-span-3">
                            <div
                                class="p-4 bg-blue-50 dark:bg-blue-900/30 rounded-xl border border-blue-200 dark:border-blue-800">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="quota_confirm" value="1" required
                                        class="w-5 h-5 rounded text-primary-500 focus:ring-primary-500 mr-3">
                                    <span
                                        class="text-gray-700 dark:text-gray-300">ข้าพเจ้ายืนยันว่าเป็นนักเรียนที่กำลังศึกษาอยู่ชั้น
                                        ม.3 โรงเรียนพิชัย</span>
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Step 2: Previous School -->
            <div class="tab animate-fade-in hidden" data-step="1">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span
                        class="w-10 h-10 flex items-center justify-center bg-primary-500 text-white rounded-xl mr-3">2</span>
                    ข้อมูลโรงเรียนเดิม
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-school mr-2 text-primary-500"></i>ชื่อโรงเรียนเดิม *
                        </label>
                        <input type="text" name="old_school_name"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            placeholder="ชื่อโรงเรียน"
                            value="<?php echo $typeCode === 'quota' ? 'โรงเรียนพิชัย' : ''; ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">จังหวัด *</label>
                        <select name="old_school_province" id="oldSchoolProvince"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            required>
                            <option value="">เลือกจังหวัด</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">อำเภอ *</label>
                        <select name="old_school_district" id="oldSchoolDistrict"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            required>
                            <option value="">เลือกอำเภอ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ตำบล *</label>
                        <select name="old_school_subdistrict" id="oldSchoolSubdistrict"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            required>
                            <option value="">เลือกตำบล</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Step 3: Current Address - Hidden for Special Classroom -->
            <?php if (!$isSpecialType): ?>
                <div class="tab animate-fade-in hidden" data-step="2">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <span
                            class="w-10 h-10 flex items-center justify-center bg-primary-500 text-white rounded-xl mr-3">3</span>
                        ที่อยู่ปัจจุบัน
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">บ้านเลขที่
                                *</label>
                            <input type="text" name="now_hno"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">หมู่ที่</label>
                            <input type="text" name="now_moo"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ซอย</label>
                            <input type="text" name="now_soi"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ถนน</label>
                            <input type="text" name="now_road"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">จังหวัด *</label>
                            <select name="now_province" id="nowProvince"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                required>
                                <option value="">เลือกจังหวัด</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">อำเภอ *</label>
                            <select name="now_district" id="nowDistrict"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                required>
                                <option value="">เลือกอำเภอ</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ตำบล *</label>
                            <select name="now_subdistrict" id="nowSubdistrict"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                required>
                                <option value="">เลือกตำบล</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">รหัสไปรษณีย์
                                *</label>
                            <input type="text" name="now_postcode" id="nowPostcode" maxlength="5"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                required>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Step 4: Registered Address - Hidden for Special Classroom -->
            <?php if (!$isSpecialType): ?>
            <div class="tab animate-fade-in hidden" data-step="3">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span
                        class="w-10 h-10 flex items-center justify-center bg-primary-500 text-white rounded-xl mr-3">4</span>
                    ที่อยู่ตามทะเบียนบ้าน
                </h3>

                <div class="mb-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" id="sameAddress"
                            class="w-5 h-5 rounded text-primary-500 focus:ring-primary-500 mr-3">
                        <span class="text-gray-700 dark:text-gray-300">ที่อยู่เดียวกับที่อยู่ปัจจุบัน</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="regAddressFields">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">บ้านเลขที่
                            *</label>
                        <input type="text" name="reg_hno"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">หมู่ที่</label>
                        <input type="text" name="reg_moo"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ซอย</label>
                        <input type="text" name="reg_soi"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ถนน</label>
                        <input type="text" name="reg_road"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">จังหวัด *</label>
                        <select name="reg_province" id="regProvince"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            required>
                            <option value="">เลือกจังหวัด</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">อำเภอ *</label>
                        <select name="reg_district" id="regDistrict"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            required>
                            <option value="">เลือกอำเภอ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ตำบล *</label>
                        <select name="reg_subdistrict" id="regSubdistrict"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            required>
                            <option value="">เลือกตำบล</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">รหัสไปรษณีย์
                            *</label>
                        <input type="text" name="reg_postcode" id="regPostcode" maxlength="5"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-phone mr-2 text-primary-500"></i>เบอร์โทรศัพท์บ้าน
                        </label>
                        <input type="text" name="old_tel" maxlength="10"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            placeholder="0xxxxxxxxx">
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Step 5: Father & Mother Info - Hidden for Special Classroom -->
            <?php if (!$isSpecialType): ?>
            <div class="tab animate-fade-in hidden" data-step="4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span
                        class="w-10 h-10 flex items-center justify-center bg-primary-500 text-white rounded-xl mr-3">5</span>
                    ข้อมูลบิดา-มารดา
                </h3>

                <!-- Father Info Section -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-blue-600 dark:text-blue-400 mb-4 flex items-center">
                        <i class="fas fa-male mr-2"></i>ข้อมูลบิดา
                    </h4>
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">คำนำหน้า</label>
                            <div class="relative">
                                <select name="dad_prefix" id="dadPrefixSelect" onchange="toggleCustomPrefix('dad')"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                                    <option value="">เลือกคำนำหน้า</option>
                                    <option value="นาย">นาย</option>
                                    <option value="ดร.">ดร.</option>
                                    <option value="ศ.">ศ.</option>
                                    <option value="รศ.">รศ.</option>
                                    <option value="ผศ.">ผศ.</option>
                                    <option value="นพ.">นพ.</option>
                                    <option value="ทพ.">ทพ.</option>
                                    <option value="ว่าที่ร้อยตรี">ว่าที่ร้อยตรี</option>
                                    <option value="ร.ต.">ร.ต.</option>
                                    <option value="ร.ท.">ร.ท.</option>
                                    <option value="ร.อ.">ร.อ.</option>
                                    <option value="พ.ต.">พ.ต.</option>
                                    <option value="พ.ท.">พ.ท.</option>
                                    <option value="พ.อ.">พ.อ.</option>
                                    <option value="ร.ต.ต.">ร.ต.ต.</option>
                                    <option value="ร.ต.ท.">ร.ต.ท.</option>
                                    <option value="ร.ต.อ.">ร.ต.อ.</option>
                                    <option value="พ.ต.ต.">พ.ต.ต.</option>
                                    <option value="พ.ต.ท.">พ.ต.ท.</option>
                                    <option value="พ.ต.อ.">พ.ต.อ.</option>
                                    <option value="other">ระบุเอง</option>
                                </select>
                                <input type="text" id="dadPrefixCustom"
                                    class="hidden mt-2 w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                    placeholder="ระบุคำนำหน้า" onchange="updateCustomPrefix('dad')">
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ชื่อบิดา</label>
                            <input type="text" name="dad_name"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                placeholder="ชื่อ">
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">นามสกุลบิดา</label>
                            <input type="text" name="dad_lastname"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                placeholder="นามสกุล">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">อาชีพ</label>
                            <input type="text" name="dad_job"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                placeholder="อาชีพ">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-phone mr-2 text-blue-500"></i>เบอร์โทรศัพท์
                            </label>
                            <input type="text" name="dad_tel" maxlength="10" value="0"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                placeholder="0xxxxxxxxx">
                        </div>
                    </div>
                </div>

                <!-- Mother Info Section -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-pink-600 dark:text-pink-400 mb-4 flex items-center">
                        <i class="fas fa-female mr-2"></i>ข้อมูลมารดา
                    </h4>
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4 bg-pink-50 dark:bg-pink-900/20 rounded-xl">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">คำนำหน้า</label>
                            <div class="relative">
                                <select name="mom_prefix" id="momPrefixSelect" onchange="toggleCustomPrefix('mom')"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                                    <option value="">เลือกคำนำหน้า</option>
                                    <option value="นาง">นาง</option>
                                    <option value="นางสาว">นางสาว</option>
                                    <option value="ดร.">ดร.</option>
                                    <option value="ศ.">ศ.</option>
                                    <option value="รศ.">รศ.</option>
                                    <option value="ผศ.">ผศ.</option>
                                    <option value="พญ.">พญ.</option>
                                    <option value="ทพญ.">ทพญ.</option>
                                    <option value="ว่าที่ร้อยตรี">ว่าที่ร้อยตรี</option>
                                    <option value="ร.ต.หญิง">ร.ต.หญิง</option>
                                    <option value="ร.ท.หญิง">ร.ท.หญิง</option>
                                    <option value="ร.อ.หญิง">ร.อ.หญิง</option>
                                    <option value="พ.ต.หญิง">พ.ต.หญิง</option>
                                    <option value="พ.ท.หญิง">พ.ท.หญิง</option>
                                    <option value="พ.อ.หญิง">พ.อ.หญิง</option>
                                    <option value="ร.ต.ต.หญิง">ร.ต.ต.หญิง</option>
                                    <option value="ร.ต.ท.หญิง">ร.ต.ท.หญิง</option>
                                    <option value="ร.ต.อ.หญิง">ร.ต.อ.หญิง</option>
                                    <option value="other">ระบุเอง</option>
                                </select>
                                <input type="text" id="momPrefixCustom"
                                    class="hidden mt-2 w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                    placeholder="ระบุคำนำหน้า" onchange="updateCustomPrefix('mom')">
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ชื่อมารดา</label>
                            <input type="text" name="mom_name"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                placeholder="ชื่อ">
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">นามสกุลมารดา</label>
                            <input type="text" name="mom_lastname"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                placeholder="นามสกุล">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">อาชีพ</label>
                            <input type="text" name="mom_job"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                placeholder="อาชีพ">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-phone mr-2 text-pink-500"></i>เบอร์โทรศัพท์
                            </label>
                            <input type="text" name="mom_tel" maxlength="10"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                placeholder="0xxxxxxxxx">
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Step 6: Guardian Info (Step 3 for Special) -->
            <div class="tab animate-fade-in hidden" data-step="<?php echo $isSpecialType ? '2' : '5'; ?>">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span
                        class="w-10 h-10 flex items-center justify-center bg-primary-500 text-white rounded-xl mr-3"><?php echo $isSpecialType ? '3' : '6'; ?></span>
                    ข้อมูลผู้ปกครอง
                </h3>

                <div
                    class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800 mb-6">
                    <p class="text-amber-700 dark:text-amber-400 text-sm">
                        <i class="fas fa-info-circle mr-2"></i>ผู้ปกครองคือผู้ที่มีอำนาจปกครองตามกฎหมาย
                        หรือผู้ที่ได้รับมอบอำนาจให้ดูแลนักเรียน
                    </p>
                </div>

                <!-- Quick Fill from Father/Mother (hide for special type) -->
                <?php if (!$isSpecialType): ?>
                <div class="mb-6 flex flex-wrap gap-3">
                    <button type="button" id="fillFromDad"
                        class="px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                        <i class="fas fa-male mr-2"></i>ใช้ข้อมูลบิดา
                    </button>
                    <button type="button" id="fillFromMom"
                        class="px-4 py-2 bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-300 rounded-lg hover:bg-pink-200 dark:hover:bg-pink-900/50 transition-colors">
                        <i class="fas fa-female mr-2"></i>ใช้ข้อมูลมารดา
                    </button>
                </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-user-tie mr-2 text-primary-500"></i>คำนำหน้า *
                        </label>
                        <div class="relative">
                            <select name="parent_prefix" id="parentPrefixSelect" onchange="toggleCustomPrefix('parent')"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                required>
                                <option value="">เลือกคำนำหน้า</option>
                                <option value="นาย">นาย</option>
                                <option value="นาง">นาง</option>
                                <option value="นางสาว">นางสาว</option>
                                <option value="ดร.">ดร.</option>
                                <option value="ศ.">ศ.</option>
                                <option value="รศ.">รศ.</option>
                                <option value="ผศ.">ผศ.</option>
                                <option value="นพ.">นพ.</option>
                                <option value="พญ.">พญ.</option>
                                <option value="ทพ.">ทพ.</option>
                                <option value="ทพญ.">ทพญ.</option>
                                <option value="ว่าที่ร้อยตรี">ว่าที่ร้อยตรี</option>
                                <option value="ร.ต.">ร.ต.</option>
                                <option value="ร.ท.">ร.ท.</option>
                                <option value="ร.อ.">ร.อ.</option>
                                <option value="พ.ต.">พ.ต.</option>
                                <option value="พ.ท.">พ.ท.</option>
                                <option value="พ.อ.">พ.อ.</option>
                                <option value="ร.ต.ต.">ร.ต.ต.</option>
                                <option value="ร.ต.ท.">ร.ต.ท.</option>
                                <option value="ร.ต.อ.">ร.ต.อ.</option>
                                <option value="พ.ต.ต.">พ.ต.ต.</option>
                                <option value="พ.ต.ท.">พ.ต.ท.</option>
                                <option value="พ.ต.อ.">พ.ต.อ.</option>
                                <option value="other">ระบุเอง</option>
                            </select>
                            <input type="text" id="parentPrefixCustom"
                                class="hidden mt-2 w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                                placeholder="ระบุคำนำหน้า" onchange="updateCustomPrefix('parent')">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ชื่อผู้ปกครอง
                            *</label>
                        <input type="text" name="parent_name"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">นามสกุลผู้ปกครอง
                            *</label>
                        <input type="text" name="parent_lastname"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ความสัมพันธ์
                            *</label>
                        <select name="parent_relation"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            required>
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
                        <input type="text" name="parent_tel" maxlength="10" value="0"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                            placeholder="0xxxxxxxxx" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">อาชีพ</label>
                        <input type="text" name="parent_occupation"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>
            </div>

            <!-- Step 7: Confirmation (Step 4 for Special) -->
            <div class="tab animate-fade-in hidden" data-step="<?php echo $isSpecialType ? '3' : '6'; ?>">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span class="w-10 h-10 flex items-center justify-center bg-green-500 text-white rounded-xl mr-3">
                        <i class="fas fa-check"></i>
                    </span>
                    ยืนยันการสมัคร
                </h3>

                <div
                    class="p-6 bg-green-50 dark:bg-green-900/30 rounded-xl border border-green-200 dark:border-green-800 mb-6">
                    <h4 class="text-lg font-bold text-green-800 dark:text-green-300 mb-4">กรุณาตรวจสอบข้อมูลก่อนยืนยัน
                    </h4>
                    <ul class="space-y-2 text-green-700 dark:text-green-400">
                        <li><i class="fas fa-check-circle mr-2"></i>ตรวจสอบข้อมูลส่วนตัวให้ถูกต้อง</li>
                        <li><i class="fas fa-check-circle mr-2"></i>ตรวจสอบข้อมูลโรงเรียนเดิม</li>
                        <?php if (!$isSpecialType): ?>
                        <li><i class="fas fa-check-circle mr-2"></i>ตรวจสอบที่อยู่และข้อมูลติดต่อ</li>
                        <li><i class="fas fa-check-circle mr-2"></i>ตรวจสอบข้อมูลบิดา-มารดา</li>
                        <?php endif; ?>
                        <li><i class="fas fa-check-circle mr-2"></i>ตรวจสอบข้อมูลผู้ปกครอง</li>
                    </ul>
                </div>

                <div
                    class="p-4 bg-amber-50 dark:bg-amber-900/30 rounded-xl border border-amber-200 dark:border-amber-800">
                    <label class="flex items-start cursor-pointer">
                        <input type="checkbox" name="confirm_agreement" value="1" required
                            class="w-5 h-5 rounded text-primary-500 focus:ring-primary-500 mt-1 mr-3">
                        <span class="text-gray-700 dark:text-gray-300">
                            ข้าพเจ้ายืนยันว่าข้อมูลที่กรอกทั้งหมดเป็นความจริงทุกประการ
                            และยอมรับเงื่อนไขการสมัครของโรงเรียน
                        </span>
                    </label>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="button" id="prevBtn" onclick="nextPrev(-1)"
                    class="hidden px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>ย้อนกลับ
                </button>
                <button type="button" id="nextBtn" onclick="nextPrev(1)"
                    class="ml-auto px-8 py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 transition-all transform hover:-translate-y-1">
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
        var tabs = document.getElementsByClassName("tab");
        var currentTabEl = tabs[currentTab];
        var inputs = currentTabEl.querySelectorAll("input[required], select[required], textarea[required]");
        var errorMessages = [];

        // Remove previous error styling
        currentTabEl.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500', 'ring-2', 'ring-red-500');
        });

        inputs.forEach(function (input) {
            if (input.type === 'checkbox' && !input.checked) {
                input.closest('label').classList.add('text-red-500');
                valid = false;
                errorMessages.push('กรุณายอมรับเงื่อนไข');
            } else if (input.value === "" || input.value === null) {
                input.classList.add('border-red-500', 'ring-2', 'ring-red-500');
                valid = false;
                // Get label text
                var label = input.closest('div')?.querySelector('label');
                var fieldName = label ? label.textContent.replace('*', '').trim() : 'ข้อมูล';
                errorMessages.push('กรุณากรอก ' + fieldName);
            }
        });

        // Additional Validation for specific fields
        // Phone number validation (10 digits starting with 0)
        var phoneInputs = currentTabEl.querySelectorAll('input[name*="_tel"], input[name*="phone"]');
        phoneInputs.forEach(function (input) {
            if (input.value) {
                var phoneValue = input.value.replace(/\D/g, '');
                if (phoneValue.length > 0 && (phoneValue.length !== 10 || !phoneValue.startsWith('0'))) {
                    input.classList.add('border-red-500', 'ring-2', 'ring-red-500');
                    valid = false;
                    errorMessages.push('เบอร์โทรศัพท์ต้องมี 10 หลัก และขึ้นต้นด้วย 0');
                }
            }
        });

        // GPA validation (0.00 - 4.00)
        var gpaInput = currentTabEl.querySelector('input[name="gpa_total"]');
        if (gpaInput && gpaInput.value) {
            var gpa = parseFloat(gpaInput.value);
            if (isNaN(gpa) || gpa < 0 || gpa > 4) {
                gpaInput.classList.add('border-red-500', 'ring-2', 'ring-red-500');
                valid = false;
                errorMessages.push('GPA ต้องอยู่ระหว่าง 0.00 - 4.00');
            }
        }

        // Postcode validation (5 digits)
        var postcodeInputs = currentTabEl.querySelectorAll('input[name*="postcode"], input[name*="_post"]');
        postcodeInputs.forEach(function (input) {
            if (input.value && input.hasAttribute('required')) {
                var postcode = input.value.replace(/\D/g, '');
                if (postcode.length !== 5) {
                    input.classList.add('border-red-500', 'ring-2', 'ring-red-500');
                    valid = false;
                    errorMessages.push('รหัสไปรษณีย์ต้องมี 5 หลัก');
                }
            }
        });

        if (!valid) {
            // Remove duplicate messages
            var uniqueMessages = [...new Set(errorMessages)];
            Swal.fire({
                icon: 'warning',
                title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                html: '<ul class="text-left text-sm mt-2">' +
                    uniqueMessages.slice(0, 5).map(m => '<li class="mb-1">• ' + m + '</li>').join('') +
                    (uniqueMessages.length > 5 ? '<li class="text-gray-500">และอื่นๆ อีก ' + (uniqueMessages.length - 5) + ' รายการ</li>' : '') +
                    '</ul>',
                confirmButtonColor: '#3b82f6',
                customClass: {
                    popup: 'rounded-2xl'
                }
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

    citizenInput.addEventListener('input', function (e) {
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

    // Toggle custom prefix input
    function toggleCustomPrefix(type) {
        const select = document.getElementById(type + 'PrefixSelect');
        const customInput = document.getElementById(type + 'PrefixCustom');

        if (select.value === 'other') {
            customInput.classList.remove('hidden');
            customInput.focus();
            select.name = ''; // Disable select name so custom input is used
            customInput.name = type === 'parent' ? 'parent_prefix' : (type + '_prefix');
            customInput.required = type === 'parent';
        } else {
            customInput.classList.add('hidden');
            customInput.value = '';
            customInput.name = '';
            select.name = type === 'parent' ? 'parent_prefix' : (type + '_prefix');
        }
    }

    // Update custom prefix value
    function updateCustomPrefix(type) {
        // Custom input value is already set, nothing additional needed
    }
    function autoSetGender(prefix) {
        const sexSelect = document.getElementById('stuSex');
        if (!sexSelect) return;

        const malePrefix = ['นาย', 'เด็กชาย', 'ด.ช.'];
        const femalePrefix = ['นางสาว', 'นาง', 'เด็กหญิง', 'ด.ญ.'];

        if (malePrefix.includes(prefix)) {
            sexSelect.value = 'ชาย';
        } else if (femalePrefix.includes(prefix)) {
            sexSelect.value = 'หญิง';
        }
    }

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
                        confirmButtonText: 'ตรวจวสอบการสมัครและพิมพ์บัตร',
                        allowOutsideClick: false,
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-xl text-lg shadow-lg'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to print page
                            window.location.href = 'print.php?citizenid=' + data.citizen_id;
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


    // Same address checkbox - Copy all address fields including province/district/subdistrict
    document.getElementById('sameAddress')?.addEventListener('change', function () {
        if (this.checked) {
            // Copy text fields
            document.querySelector('[name="reg_hno"]').value = document.querySelector('[name="now_hno"]').value;
            document.querySelector('[name="reg_moo"]').value = document.querySelector('[name="now_moo"]').value;
            document.querySelector('[name="reg_soi"]').value = document.querySelector('[name="now_soi"]').value;
            document.querySelector('[name="reg_road"]').value = document.querySelector('[name="now_road"]').value;
            document.querySelector('[name="reg_postcode"]').value = document.querySelector('[name="now_postcode"]').value;

            // Copy province/district/subdistrict selections
            const nowProvince = document.getElementById('nowProvince');
            const nowDistrict = document.getElementById('nowDistrict');
            const nowSubdistrict = document.getElementById('nowSubdistrict');
            const regProvince = document.getElementById('regProvince');
            const regDistrict = document.getElementById('regDistrict');
            const regSubdistrict = document.getElementById('regSubdistrict');

            if (nowProvince && regProvince) {
                // Copy province
                regProvince.value = nowProvince.value;

                // Load districts for reg address, then copy district value
                if (nowProvince.value) {
                    const formData = new FormData();
                    formData.append('id', nowProvince.value);

                    fetch('services/ajax.district.php', { method: 'POST', body: formData })
                        .then(res => res.json())
                        .then(data => {
                            let options = '<option value="">เลือกอำเภอ</option>';
                            data.forEach(d => {
                                options += `<option value="${d.code}">${d.name}</option>`;
                            });
                            regDistrict.innerHTML = options;
                            regDistrict.value = nowDistrict.value;

                            // Then load subdistricts
                            if (nowDistrict.value) {
                                const formData2 = new FormData();
                                formData2.append('id', nowDistrict.value);

                                fetch('services/ajax.subdistrict.php', { method: 'POST', body: formData2 })
                                    .then(res => res.json())
                                    .then(data => {
                                        let options = '<option value="">เลือกตำบล</option>';
                                        data.forEach(s => {
                                            options += `<option value="${s.code}">${s.name}</option>`;
                                        });
                                        regSubdistrict.innerHTML = options;
                                        regSubdistrict.value = nowSubdistrict.value;
                                    });
                            }
                        });
                }
            }
        }
    });

    // Fill guardian info from father
    document.getElementById('fillFromDad')?.addEventListener('click', function () {
        const dadPrefix = document.querySelector('[name="dad_prefix"]')?.value || '';
        const dadName = document.querySelector('[name="dad_name"]')?.value || '';
        const dadLastname = document.querySelector('[name="dad_lastname"]')?.value || '';
        const dadTel = document.querySelector('[name="dad_tel"]')?.value || '';
        const dadJob = document.querySelector('[name="dad_job"]')?.value || '';

        document.querySelector('[name="parent_prefix"]').value = dadPrefix;
        document.querySelector('[name="parent_name"]').value = dadName;
        document.querySelector('[name="parent_lastname"]').value = dadLastname;
        document.querySelector('[name="parent_tel"]').value = dadTel;
        document.querySelector('[name="parent_occupation"]').value = dadJob;
        document.querySelector('[name="parent_relation"]').value = 'บิดา';

        // Show success feedback
        this.classList.add('ring-2', 'ring-green-500');
        setTimeout(() => this.classList.remove('ring-2', 'ring-green-500'), 1000);
    });

    // Fill guardian info from mother
    document.getElementById('fillFromMom')?.addEventListener('click', function () {
        const momPrefix = document.querySelector('[name="mom_prefix"]')?.value || '';
        const momName = document.querySelector('[name="mom_name"]')?.value || '';
        const momLastname = document.querySelector('[name="mom_lastname"]')?.value || '';
        const momTel = document.querySelector('[name="mom_tel"]')?.value || '';
        const momJob = document.querySelector('[name="mom_job"]')?.value || '';

        document.querySelector('[name="parent_prefix"]').value = momPrefix;
        document.querySelector('[name="parent_name"]').value = momName;
        document.querySelector('[name="parent_lastname"]').value = momLastname;
        document.querySelector('[name="parent_tel"]').value = momTel;
        document.querySelector('[name="parent_occupation"]').value = momJob;
        document.querySelector('[name="parent_relation"]').value = 'มารดา';

        // Show success feedback
        this.classList.add('ring-2', 'ring-green-500');
        setTimeout(() => this.classList.remove('ring-2', 'ring-green-500'), 1000);
    });

    // Phone number input - only allow digits
    document.querySelectorAll('input[name*="_tel"], input[name*="phone"]').forEach(function (input) {
        input.addEventListener('input', function (e) {
            // Remove non-digits
            let value = e.target.value.replace(/\D/g, '');
            // Limit to 10 digits
            if (value.length > 10) value = value.substr(0, 10);
            e.target.value = value;

            // Visual feedback for valid phone
            if (value.length === 10 && value.startsWith('0')) {
                e.target.classList.remove('border-red-500', 'ring-red-500');
                e.target.classList.add('border-green-500');
            } else if (value.length > 0) {
                e.target.classList.remove('border-green-500');
            }
        });
    });

    // Postcode input - only allow 5 digits
    document.querySelectorAll('input[name*="postcode"]').forEach(function (input) {
        input.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) value = value.substr(0, 5);
            e.target.value = value;
        });
    });

    // GPA input - format as decimal
    document.querySelectorAll('input[name="gpa_total"]').forEach(function (input) {
        input.addEventListener('input', function (e) {
            let value = e.target.value.replace(/[^0-9.]/g, '');
            // Allow only one decimal point
            const parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }
            // Limit to 4.00
            if (parseFloat(value) > 4) value = '4.00';
            e.target.value = value;
        });
    });

    // Load provinces on page load (would need AJAX endpoint)
    document.addEventListener('DOMContentLoaded', function () {
        // Re-attach phone validation after DOM is ready
        document.querySelectorAll('input[name*="_tel"]').forEach(function (input) {
            input.setAttribute('inputmode', 'numeric');
            input.setAttribute('pattern', '[0-9]*');
        });

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

                    // Auto-fill for quota
                    if ('<?php echo $typeCode; ?>' === 'quota') {
                        const oldSchoolProv = document.getElementById('oldSchoolProvince');
                        if (oldSchoolProv) {
                            const pTarget = data.find(p => p.name.includes('อุตรดิตถ์'));
                            if (pTarget) {
                                oldSchoolProv.value = pTarget.code;
                                loadDistricts(pTarget.code, 'oldSchoolDistrict', 'พิชัย');
                            }
                        }
                    }
                });
        }

        function loadDistricts(provinceCode, districtSelectId, defaultKey = null) {
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

                    if (defaultKey) {
                        const dTarget = data.find(d => d.name.includes(defaultKey) || d.code === defaultKey);
                        if (dTarget) {
                            districtSelect.value = dTarget.code;
                            if ('<?php echo $typeCode; ?>' === 'quota' && districtSelectId === 'oldSchoolDistrict') {
                                loadSubdistricts(dTarget.code, subdistrictSelectId, 'ในเมือง');
                            }
                        }
                    }
                });
        }

        function loadSubdistricts(districtCode, subdistrictSelectId, defaultKey = null) {
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

                    if (defaultKey) {
                        const sTarget = data.find(s => s.name.includes(defaultKey) || s.code === defaultKey);
                        if (sTarget) {
                            subdistrictSelect.value = sTarget.code;
                        }
                    }
                });
        }

        // Old School Address
        document.getElementById('oldSchoolProvince')?.addEventListener('change', function () {
            loadDistricts(this.value, 'oldSchoolDistrict');
        });
        document.getElementById('oldSchoolDistrict')?.addEventListener('change', function () {
            loadSubdistricts(this.value, 'oldSchoolSubdistrict');
        });

        // Current Address
        document.getElementById('nowProvince')?.addEventListener('change', function () {
            loadDistricts(this.value, 'nowDistrict');
        });
        document.getElementById('nowDistrict')?.addEventListener('change', function () {
            loadSubdistricts(this.value, 'nowSubdistrict');
        });

        // Registered Address
        document.getElementById('regProvince')?.addEventListener('change', function () {
            loadDistricts(this.value, 'regDistrict');
        });
        document.getElementById('regDistrict')?.addEventListener('change', function () {
            loadSubdistricts(this.value, 'regSubdistrict');
        });
    });
</script>
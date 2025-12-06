<!-- M.1 Registration Form -->
<div class="space-y-6">
    <!-- Page Header -->
    <div class="text-center">
        <h1 class="text-3xl font-bold gradient-text">สมัครเรียนมัธยมศึกษาปีที่ 1</h1>
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">(ในเขต / นอกเขต)</p>
    </div>

    <!-- Progress Steps -->
    <div class="glass rounded-2xl p-4">
        <div class="flex items-center justify-between max-w-3xl mx-auto">
            <?php 
            $steps = ['ข้อมูลส่วนตัว', 'ที่อยู่โรงเรียนเดิม', 'ที่อยู่ปัจจุบัน', 'ที่อยู่ตามทะเบียนบ้าน', 'ข้อมูลบิดามารดา', 'ข้อมูลผู้ปกครอง', 'เลือกวิชา'];
            foreach ($steps as $i => $step): 
            ?>
            <div class="step-indicator flex flex-col items-center <?php echo $i > 0 ? 'flex-1' : ''; ?>">
                <?php if ($i > 0): ?>
                <div class="step-line h-1 w-full bg-gray-200 dark:bg-gray-700 absolute top-5 -z-10"></div>
                <?php endif; ?>
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
                        <span class="error text-sm mt-1 block"></span>
                    </div>
                    
                    <!-- Registration Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-primary-500"></i>ประเภทการสมัคร *
                        </label>
                        <select name="typeregis" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                            <option value="">เลือก</option>
                            <option value="ในเขต">ในเขต</option>
                            <option value="นอกเขต">นอกเขต</option>
                        </select>
                    </div>
                    
                    <!-- Prefix -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-user mr-2 text-primary-500"></i>คำนำหน้า *
                        </label>
                        <select name="stu_prefix" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                            <option value="">เลือก</option>
                            <option value="เด็กชาย">เด็กชาย</option>
                            <option value="เด็กหญิง">เด็กหญิง</option>
                        </select>
                    </div>
                    
                    <!-- First Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            ชื่อ *
                        </label>
                        <input type="text" name="stu_name" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="ชื่อ" required>
                    </div>
                    
                    <!-- Last Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            นามสกุล *
                        </label>
                        <input type="text" name="stu_lastname" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="นามสกุล" required>
                    </div>
                    
                    <!-- Birth Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-calendar mr-2 text-primary-500"></i>วันเกิด *
                        </label>
                        <select name="date_birth" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                            <option value="">วัน</option>
                            <?php for($i=1; $i<=31; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <!-- Birth Month -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            เดือนเกิด *
                        </label>
                        <select name="month_birth" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                            <option value="">เดือน</option>
                            <?php 
                            $months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
                            foreach($months as $i => $month): 
                            ?>
                            <option value="<?php echo $month; ?>"><?php echo $month; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Birth Year -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            ปีเกิด (พ.ศ.) *
                        </label>
                        <select name="year_birth" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                            <option value="">ปี</option>
                            <?php 
                            $currentYear = date('Y') + 543;
                            for($i = $currentYear - 12; $i >= $currentYear - 20; $i--): 
                            ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <!-- Sex -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">เพศ *</label>
                        <select name="stu_sex" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                            <option value="">เลือก</option>
                            <option value="ชาย">ชาย</option>
                            <option value="หญิง">หญิง</option>
                        </select>
                    </div>
                    
                    <!-- Blood Group -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">กรุ๊ปเลือด</label>
                        <select name="stu_blood_group" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">เลือก</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O">O</option>
                        </select>
                    </div>
                    
                    <!-- Religion -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ศาสนา *</label>
                        <select name="stu_religion" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                            <option value="">เลือก</option>
                            <option value="พุทธ">พุทธ</option>
                            <option value="คริสต์">คริสต์</option>
                            <option value="อิสลาม">อิสลาม</option>
                            <option value="อื่นๆ">อื่นๆ</option>
                        </select>
                    </div>
                    
                    <!-- Ethnicity -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">เชื้อชาติ *</label>
                        <input type="text" name="stu_ethnicity" value="ไทย" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                    </div>
                    
                    <!-- Nationality -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">สัญชาติ *</label>
                        <input type="text" name="stu_nationality" value="ไทย" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                    </div>
                    
                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-phone mr-2 text-primary-500"></i>เบอร์โทรศัพท์ *
                        </label>
                        <input type="text" name="now_tel" maxlength="10" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="0xxxxxxxxx" required>
                    </div>
                    
                    <!-- GPA -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-star mr-2 text-primary-500"></i>เกรดเฉลี่ยสะสม (5 เทอม) *
                        </label>
                        <input type="text" name="gpa_total" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="0.00" required>
                    </div>
                </div>
            </div>

            <!-- Step 2-7: Include from stepregm1 files dynamically -->
            <div class="tab animate-fade-in hidden" data-step="1">
                <?php include 'stepregm1/step2.php'; ?>
            </div>
            <div class="tab animate-fade-in hidden" data-step="2">
                <?php include 'stepregm1/step3.php'; ?>
            </div>
            <div class="tab animate-fade-in hidden" data-step="3">
                <?php include 'stepregm1/step4.php'; ?>
            </div>
            <div class="tab animate-fade-in hidden" data-step="4">
                <?php include 'stepregm1/step5.php'; ?>
            </div>
            <div class="tab animate-fade-in hidden" data-step="5">
                <?php include 'stepregm1/step6.php'; ?>
            </div>
            <div class="tab animate-fade-in hidden" data-step="6">
                <?php include 'stepregm1/step7.php'; ?>
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
var currentTab = 0;
showTab(currentTab);

function showTab(n) {
    var tabs = document.getElementsByClassName("tab");
    var stepCircles = document.querySelectorAll('.step-circle');
    
    // Hide all tabs
    for (var i = 0; i < tabs.length; i++) {
        tabs[i].classList.add('hidden');
        tabs[i].classList.remove('animate-fade-in');
    }
    
    // Show current tab
    tabs[n].classList.remove('hidden');
    tabs[n].classList.add('animate-fade-in');
    
    // Update step indicators
    stepCircles.forEach((circle, index) => {
        if (index < n) {
            // Completed steps
            circle.classList.remove('border-gray-300', 'dark:border-gray-600', 'text-gray-500', 'dark:text-gray-400');
            circle.classList.add('border-green-500', 'bg-green-500', 'text-white');
            circle.innerHTML = '<i class="fas fa-check"></i>';
        } else if (index === n) {
            // Current step
            circle.classList.remove('border-gray-300', 'dark:border-gray-600', 'text-gray-500', 'dark:text-gray-400', 'border-green-500', 'bg-green-500');
            circle.classList.add('border-primary-500', 'bg-primary-500', 'text-white');
            circle.innerHTML = index + 1;
        } else {
            // Future steps
            circle.classList.remove('border-primary-500', 'bg-primary-500', 'border-green-500', 'bg-green-500', 'text-white');
            circle.classList.add('border-gray-300', 'dark:border-gray-600', 'text-gray-500', 'dark:text-gray-400');
            circle.innerHTML = index + 1;
        }
    });
    
    // Show/hide buttons
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
    var inputs = document.getElementsByClassName("tab")[currentTab].querySelectorAll("input[required], select[required]");
    
    inputs.forEach(function(input) {
        if (input.value === "") {
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
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    var form = document.getElementById("regForm");
    var formData = new FormData(form);
    
    fetch('insert_reg_m1.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: data.message,
                confirmButtonColor: '#10b981'
            }).then(() => {
                window.location.href = 'regis.php';
            });
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
document.getElementById('citizenid').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 13) value = value.substr(0, 13);
    
    let formatted = '';
    if (value.length > 0) formatted += value.substr(0, 1);
    if (value.length > 1) formatted += '-' + value.substr(1, 4);
    if (value.length > 5) formatted += '-' + value.substr(5, 5);
    if (value.length > 10) formatted += '-' + value.substr(10, 2);
    if (value.length > 12) formatted += '-' + value.substr(12, 1);
    
    e.target.value = formatted;
});
</script>

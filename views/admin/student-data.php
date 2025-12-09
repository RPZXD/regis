
<!-- Dynamic Student Data View -->
<!-- Dynamic Student Data View -->
<div class="space-y-6 animate-fade-in-up">
    <!-- Page Header & Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Title Card -->
        <div class="lg:col-span-2 glass rounded-2xl p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fas fa-users text-8xl text-indigo-600"></i>
            </div>
            <div class="relative z-10 flex flex-col h-full justify-between">
                <div>
                    <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400">
                        <?php echo $regisType['grade_name']; ?>
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1 text-lg"><?php echo $regisType['name']; ?></p>
                </div>
                <div class="mt-4 flex gap-3">
                    <button onclick="exportCSV()" class="flex items-center gap-2 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl shadow-lg shadow-green-500/30 transition-all hover:-translate-y-1 text-sm font-medium">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </button>
                    <button onclick="exportExcel()" class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-lg shadow-emerald-500/30 transition-all hover:-translate-y-1 text-sm font-medium">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="glass rounded-2xl p-6 flex flex-col justify-between border-l-4 border-blue-500">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">นักเรียนทั้งหมด</p>
                <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1" id="stat-total">-</h3>
            </div>
            <div class="mt-4 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                <div class="bg-blue-500 h-1.5 rounded-full" style="width: 100%"></div>
            </div>
        </div>

        <div class="glass rounded-2xl p-6 flex flex-col justify-between border-l-4 border-green-500">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">ยืนยันแล้ว</p>
                <h3 class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1" id="stat-passed">-</h3>
            </div>
            <div class="flex items-center gap-2 mt-2">
                <span class="text-xs text-gray-500" id="stat-pending-label">รอตรวจสอบ: -</span>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="glass rounded-2xl p-6 shadow-xl shadow-gray-200/50 dark:shadow-none">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="record_table">
                <thead>
                    <tr class="bg-gray-50 dark:bg-slate-700/50 text-gray-600 dark:text-gray-300">
                        <th class="px-4 py-4 text-center rounded-l-xl">#</th>
                        <th class="px-4 py-4 text-center">รหัสประจำตัว</th>
                        <th class="px-4 py-4 text-left">ชื่อ - นามสกุล</th>
                        <th class="px-4 py-4 text-center">เบอร์โทร</th>
                        <th class="px-4 py-4 text-center">GPA</th>
                        <th class="px-4 py-4 text-left">แผนการเรียน</th>
                        <th class="px-4 py-4 text-center">สถานะ</th>
                        <th class="px-4 py-4 text-center rounded-r-xl">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-300 divide-y divide-gray-100 dark:divide-gray-800"></tbody>
            </table>
        </div>
    </div>
</div>

<style>
/* Custom Scrollbar for Table */
.overflow-x-auto::-webkit-scrollbar { height: 8px; }
.overflow-x-auto::-webkit-scrollbar-track { background: transparent; }
.overflow-x-auto::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
.overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

<!-- Edit Student Modal -->
<div id="editStudentModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('editStudentModal')"></div>
        <div class="relative glass rounded-2xl w-full max-w-5xl max-h-[90vh] flex flex-col shadow-2xl">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 text-blue-600 rounded-xl">
                        <i class="fas fa-user-edit text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">แก้ไขข้อมูลนักเรียน</h3>
                        <p class="text-sm text-gray-500 text-id-display">รหัส: -</p>
                    </div>
                </div>
                <button onclick="closeModal('editStudentModal')" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Tabs -->
            <div class="flex border-b border-gray-200 dark:border-gray-700 px-6 space-x-6 overflow-x-auto">
                <button onclick="switchTab('tab-general')" class="tab-btn active py-4 text-sm font-medium border-b-2 border-primary-500 text-primary-600">ข้อมูลทั่วไป</button>
                <button onclick="switchTab('tab-address')" class="tab-btn py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700">ที่อยู่</button>
                <button onclick="switchTab('tab-education')" class="tab-btn py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700">การศึกษาเดิม</button>
                <button onclick="switchTab('tab-family')" class="tab-btn py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700">ครอบครัว</button>
                <button onclick="switchTab('tab-plans')" class="tab-btn py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700">แผนการเรียน</button>
            </div>

            <form id="editStudentForm" class="flex-1 overflow-hidden flex flex-col">
                <input type="hidden" id="editStudentId">
                <input type="hidden" id="editCitizenIdHidden">

                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    
                    <!-- Tab: General -->
                    <div id="tab-general" class="tab-content space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="label-text">เลขบัตรประชาชน</label>
                                <input type="text" id="editCitizenId" maxlength="13" class="input-field" required>
                            </div>
                            <div>
                                <label class="label-text">ประเภทการสมัคร</label>
                                <select id="editTyperegis" class="input-field">
                                    <option value="ในเขต">ในเขตพื้นที่บริการ</option>
                                    <option value="นอกเขต">นอกเขตพื้นที่บริการ</option>
                                    <option value="พิเศษ">พิเศษ</option>
                                    <option value="โควต้า">โควต้า</option>
                                </select>
                            </div>
                            <div>
                                <label class="label-text">คำนำหน้า</label>
                                <select id="editPrefix" class="input-field">
                                    <option value="เด็กชาย">เด็กชาย</option>
                                    <option value="เด็กหญิง">เด็กหญิง</option>
                                    <option value="นาย">นาย</option>
                                    <option value="นางสาว">นางสาว</option>
                                </select>
                            </div>
                            <div>
                                <label class="label-text">ชื่อ</label>
                                <input type="text" id="editFirstName" class="input-field" required>
                            </div>
                            <div>
                                <label class="label-text">นามสกุล</label>
                                <input type="text" id="editLastName" class="input-field" required>
                            </div>
                            <div>
                                <label class="label-text">เพศ</label>
                                <select id="editSex" class="input-field">
                                    <option value="ชาย">ชาย</option>
                                    <option value="หญิง">หญิง</option>
                                </select>
                            </div>
                            <div>
                                <label class="label-text">วันเกิด (วว-ดด-ปปปป)</label>
                                <input type="text" id="editBirthday" placeholder="DD-MM-YYYY" class="input-field">
                            </div>
                            <div>
                                <label class="label-text">กรุ๊ปเลือด</label>
                                <input type="text" id="editBloodGroup" class="input-field">
                            </div>
                            <div>
                                <label class="label-text">ศาสนา</label>
                                <input type="text" id="editReligion" class="input-field">
                            </div>
                            <div>
                                <label class="label-text">เชื้อชาติ</label>
                                <input type="text" id="editEthnicity" class="input-field">
                            </div>
                            <div>
                                <label class="label-text">สัญชาติ</label>
                                <input type="text" id="editNationality" class="input-field">
                            </div>
                            <div>
                                <label class="label-text">เบอร์โทรศัพท์นักเรียน</label>
                                <input type="tel" id="editNowTel" class="input-field">
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Address -->
                    <div id="tab-address" class="tab-content hidden space-y-4">
                        <h4 class="font-bold text-gray-700 dark:text-gray-300 border-b pb-2">ที่อยู่ปัจจุบัน</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="col-span-1 md:col-span-3">
                                <label class="label-text">บ้านเลขที่ / หมู่บ้าน</label>
                                <input type="text" id="editNowAddr" class="input-field">
                            </div>
                            <div>
                                <label class="label-text">หมู่ที่</label>
                                <input type="text" id="editNowMoo" class="input-field">
                            </div>
                            <div>
                                <label class="label-text">ซอย</label>
                                <input type="text" id="editNowSoy" class="input-field">
                            </div>
                            <div>
                                <label class="label-text">ถนน</label>
                                <input type="text" id="editNowStreet" class="input-field">
                            </div>
                            <div>
                                <label class="label-text">ตำบล/แขวง</label>
                                <input type="text" id="editNowSubdistrict" class="input-field">
                            </div>
                            <div>
                                <label class="label-text">อำเภอ/เขต</label>
                                <input type="text" id="editNowDistrict" class="input-field">
                            </div>
                            <div>
                                <label class="label-text">จังหวัด</label>
                                <input type="text" id="editNowProvince" class="input-field">
                            </div>
                            <div>
                                <label class="label-text">รหัสไปรษณีย์</label>
                                <input type="text" id="editNowPost" class="input-field">
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Education -->
                    <div id="tab-education" class="tab-content hidden space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="label-text">โรงเรียนเดิม</label>
                                <input type="text" id="editOldSchool" class="input-field">
                            </div>
                            <div>
                                <label class="label-text">จังหวัดโรงเรียนเดิม</label>
                                <input type="text" id="editOldSchoolProvince" class="input-field">
                            </div>
                            <div>
                                <label class="label-text">อำเภอโรงเรียนเดิม</label>
                                <input type="text" id="editOldSchoolDistrict" class="input-field">
                            </div>
                            <div>
                                <label class="label-text">รหัสประจำตัวนักเรียนเดิม (ปพ.)</label>
                                <input type="text" id="editOldSchoolStuid" class="input-field">
                            </div>
                            <div>
                                <label class="label-text">เกรดเฉลี่ยสะสม (GPA)</label>
                                <input type="text" id="editGpaTotal" class="input-field bg-yellow-50 dark:bg-yellow-900/10 border-yellow-200">
                            </div>
                            <div>
                                <label class="label-text">GPA วิทย์</label>
                                <input type="text" id="editGpaSci" class="input-field">
                            </div>
                            <div>
                                <label class="label-text">GPA คณิต</label>
                                <input type="text" id="editGpaMath" class="input-field">
                            </div>
                            <div>
                                <label class="label-text">GPA อังกฤษ</label>
                                <input type="text" id="editGpaEng" class="input-field">
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Family -->
                    <div id="tab-family" class="tab-content hidden space-y-6">
                        <!-- Dad -->
                        <div class="space-y-3">
                            <h4 class="font-bold text-gray-700 dark:text-gray-300 border-b pb-1">ข้อมูลบิดา</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <div><label class="label-text">คำนำหน้า</label><input type="text" id="editDadPrefix" class="input-field"></div>
                                <div><label class="label-text">ชื่อ</label><input type="text" id="editDadName" class="input-field"></div>
                                <div><label class="label-text">นามสกุล</label><input type="text" id="editDadLastname" class="input-field"></div>
                                <div><label class="label-text">อาชีพ</label><input type="text" id="editDadJob" class="input-field"></div>
                                <div><label class="label-text">เบอร์โทร</label><input type="text" id="editDadTel" class="input-field"></div>
                            </div>
                        </div>
                        <!-- Mom -->
                        <div class="space-y-3">
                            <h4 class="font-bold text-gray-700 dark:text-gray-300 border-b pb-1">ข้อมูลมารดา</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <div><label class="label-text">คำนำหน้า</label><input type="text" id="editMomPrefix" class="input-field"></div>
                                <div><label class="label-text">ชื่อ</label><input type="text" id="editMomName" class="input-field"></div>
                                <div><label class="label-text">นามสกุล</label><input type="text" id="editMomLastname" class="input-field"></div>
                                <div><label class="label-text">อาชีพ</label><input type="text" id="editMomJob" class="input-field"></div>
                                <div><label class="label-text">เบอร์โทร</label><input type="text" id="editMomTel" class="input-field"></div>
                            </div>
                        </div>
                        <!-- Parent -->
                        <div class="space-y-3">
                            <h4 class="font-bold text-gray-700 dark:text-gray-300 border-b pb-1">ผู้ปกครอง</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <div><label class="label-text">ความสัมพันธ์</label><input type="text" id="editParentRelation" class="input-field"></div>
                                <div><label class="label-text">คำนำหน้า</label><input type="text" id="editParentPrefix" class="input-field"></div>
                                <div><label class="label-text">ชื่อ</label><input type="text" id="editParentName" class="input-field"></div>
                                <div><label class="label-text">นามสกุล</label><input type="text" id="editParentLastname" class="input-field"></div>
                                <div><label class="label-text">อาชีพ</label><input type="text" id="editParentJob" class="input-field"></div>
                                <div><label class="label-text">เบอร์โทร</label><input type="text" id="editParentTel" class="input-field"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Plans -->
                    <div id="tab-plans" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php for ($i = 1; $i <= 10; $i++): ?>
                            <div>
                                <label class="label-text">อันดับที่ <?php echo $i; ?></label>
                                <select id="editNumber<?php echo $i; ?>" class="input-field">
                                    <option value="">-- ไม่เลือก --</option>
                                    <?php foreach ($plansMap as $id => $name): ?>
                                    <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                </div>
                
                <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-800 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('editStudentModal')" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">ยกเลิก</button>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-lg hover:shadow-lg transition-all transform hover:-translate-y-0.5">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.label-text { @apply block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1; }
.input-field { @apply w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 outline-none transition-all; }
.tab-btn.active { @apply border-primary-500 text-primary-600 dark:text-primary-400; }
</style>

<script>
const typeId = <?php echo $typeId; ?>;
const plansMap = <?php echo json_encode($plansMap); ?>;

function getPlanName(id) {
    return plansMap[id] || '-';
}

function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

// Global Data for Export
let allStudentData = [];

function loadTable() {
    $.ajax({
        url: 'api/admin/fetch_students_dynamic.php?type_id=' + typeId,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            allStudentData = response; // Store for export
            
            // Calculate Stats
            const total = response.length;
            const passed = response.filter(r => r.status == 1).length;
            const pending = total - passed;

            // Animate Numbers
            animateValue("stat-total", 0, total, 1000);
            animateValue("stat-passed", 0, passed, 1000);
            $('#stat-pending-label').text('รอตรวจสอบ: ' + pending);

            $('#record_table').DataTable().clear().destroy();
            $('#record_table tbody').empty();

            if (response.length === 0) {
                $('#record_table tbody').append('<tr><td colspan="8" class="text-center py-8 text-gray-400">ไม่พบข้อมูลในระบบ</td></tr>');
            } else {
                $.each(response, function(index, record) {
                    // Parse Plan String
                    var plansHtml = '<div class="flex flex-col space-y-1">';
                    if (record.plan_string) {
                        var planPairs = record.plan_string.split(',');
                        planPairs.forEach(function(pair) {
                            var parts = pair.split(':');
                            if (parts.length === 2) {
                                var priority = parts[0];
                                var planId = parts[1];
                                var color = priority == 1 ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400';
                                plansHtml += '<span class="text-xs '+color+' px-2 py-0.5 rounded-md w-fit font-medium">' + priority + '. ' + getPlanName(planId) + '</span>';
                            }
                        });
                    } else {
                        plansHtml += '-';
                    }
                    plansHtml += '</div>';

                    // Determine Status Badge
                    var statusBadge = '<span class="px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-400 rounded-full">รอตรวจสอบ</span>';
                    if (record.status == 1) {
                         statusBadge = '<span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 dark:bg-green-900/30 dark:text-green-400 rounded-full">ยืนยันแล้ว</span>';
                    }

                    var row = '<tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors border-b border-gray-100 dark:border-gray-800">' +
                    '<td class="px-4 py-3 text-center text-sm text-gray-500">' + (index + 1) + '</td>' +
                    '<td class="px-4 py-3 text-center font-mono text-sm text-indigo-600 dark:text-indigo-400 font-medium">' + (record.citizenid || '-') + '</td>' +
                    '<td class="px-4 py-3 text-gray-800 dark:text-gray-200 font-medium">' + (record.fullname || '-') + '</td>' +
                    '<td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400 font-mono text-sm">' + (record.now_tel || '-') + '</td>' +
                    '<td class="px-4 py-3 text-center"><span class="px-2 py-1 bg-gray-100 dark:bg-slate-700 rounded text-gray-700 dark:text-gray-300 font-bold text-xs">' + (record.gpa_total || '-') + '</span></td>' +
                    '<td class="px-4 py-3">' + plansHtml + '</td>' +
                    '<td class="px-4 py-3 text-center">' + statusBadge + '</td>' + 
                    '<td class="px-4 py-3 text-center">' + 
                        '<div class="flex items-center justify-center space-x-2">' +
                        '<button class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors edit-btn" data-id="' + record.id + '" title="แก้ไข"><i class="fas fa-edit"></i></button>' +
                        '<button class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors delete-btn" data-id="' + record.id + '" title="ลบ"><i class="fas fa-trash"></i></button>' +
                        '</div>' +
                    '</td></tr>';
                    $('#record_table tbody').append(row);
                });
            }

            $('#record_table').DataTable({
                "pageLength": 10,
                "responsive": true,
                "dom": 'Bfrtip',
                "buttons": [], // Custom buttons used instead
                "language": {
                    "search": "ค้นหา:",
                    "lengthMenu": "แสดง _MENU_ รายการ",
                    "info": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                    "paginate": { "first": "หน้าแรก", "last": "หน้าสุดท้าย", "next": "ถัดไป", "previous": "ก่อนหน้า" },
                    "emptyTable": "ไม่พบข้อมูล"
                }
            });
        },
        error: function(xhr, status, error) {
            console.error(error);
            $('#record_table tbody').html('<tr><td colspan="8" class="text-center text-red-500 py-4">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>');
        }
    });
}

function animateValue(id, start, end, duration) {
    if (start === end) return;
    var range = end - start;
    var current = start;
    var increment = end > start ? 1 : -1;
    var stepTime = Math.abs(Math.floor(duration / range));
    var obj = document.getElementById(id);
    var timer = setInterval(function() {
        current += increment;
        obj.innerHTML = current;
        if (current == end) {
            clearInterval(timer);
        }
    }, stepTime);
}

function switchTab(tabId) {
    $('.tab-content').addClass('hidden');
    $('#' + tabId).removeClass('hidden');
    $('.tab-btn').removeClass('active border-primary-500 text-primary-600 dark:text-primary-400')
                 .addClass('border-transparent text-gray-500 hover:text-gray-700');
    $('button[onclick="switchTab(\'' + tabId + '\')"]').addClass('active border-primary-500 text-primary-600 dark:text-primary-400')
                                                    .removeClass('border-transparent text-gray-500 hover:text-gray-700');
}

$(document).ready(function() {
    loadTable();

    // Delete Logic
    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'ยืนยันลบ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'ลบ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'api/delete_student.php',
                    method: 'POST',
                    data: JSON.stringify({ id: id }),
                    contentType: 'application/json',
                    success: function(r) {
                        if (r.success) { Swal.fire('ลบแล้ว!', '', 'success'); loadTable(); }
                        else Swal.fire('ข้อผิดพลาด', r.message, 'error');
                    },
                    error: function() { Swal.fire('ข้อผิดพลาด', 'Server Error', 'error'); }
                });
            }
        });
    });

    // Edit Logic
    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            url: 'api/get_student.php',
            method: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function(r) {
                // Populate General Tab
                $('#editStudentId').val(r.id);
                $('#editCitizenId').val(r.citizenid);
                $('#editCitizenIdHidden').val(r.citizenid);
                $('#editTyperegis').val(r.typeregis);
                $('#editPrefix').val(r.stu_prefix);
                $('#editFirstName').val(r.stu_name);
                $('#editLastName').val(r.stu_lastname);
                $('#editSex').val(r.stu_sex);
                $('#editBirthday').val(r.birthday);
                $('#editBloodGroup').val(r.stu_blood_group);
                $('#editReligion').val(r.stu_religion);
                $('#editEthnicity').val(r.stu_ethnicity);
                $('#editNationality').val(r.stu_nationality);
                $('#editNowTel').val(r.now_tel);

                // Populate Address Tab
                $('#editNowAddr').val(r.now_addr);
                $('#editNowMoo').val(r.now_moo);
                $('#editNowSoy').val(r.now_soy);
                $('#editNowStreet').val(r.now_street);
                $('#editNowSubdistrict').val(r.now_subdistrict);
                $('#editNowDistrict').val(r.now_district);
                $('#editNowProvince').val(r.now_province);
                $('#editNowPost').val(r.now_post);

                // Populate Education Tab
                $('#editOldSchool').val(r.old_school);
                $('#editOldSchoolProvince').val(r.old_school_province);
                $('#editOldSchoolDistrict').val(r.old_school_district);
                $('#editOldSchoolStuid').val(r.old_school_stuid);
                $('#editGpaTotal').val(r.gpa_total);
                $('#editGpaSci').val(r.gpa_sci);
                $('#editGpaMath').val(r.gpa_math);
                $('#editGpaEng').val(r.gpa_eng);

                // Populate Family Tab
                // Dad
                $('#editDadPrefix').val(r.dad_prefix);
                $('#editDadName').val(r.dad_name);
                $('#editDadLastname').val(r.dad_lastname);
                $('#editDadJob').val(r.dad_job);
                $('#editDadTel').val(r.dad_tel);
                // Mom
                $('#editMomPrefix').val(r.mom_prefix);
                $('#editMomName').val(r.mom_name);
                $('#editMomLastname').val(r.mom_lastname);
                $('#editMomJob').val(r.mom_job);
                $('#editMomTel').val(r.mom_tel);
                // Parent
                $('#editParentRelation').val(r.parent_relation);
                $('#editParentPrefix').val(r.parent_prefix);
                $('#editParentName').val(r.parent_name);
                $('#editParentLastname').val(r.parent_lastname);
                $('#editParentJob').val(r.parent_job);
                $('#editParentTel').val(r.parent_tel);

                // Populate Plans Tab
                for (var i = 1; i <= 10; i++) {
                    $('#editNumber' + i).val(r['number' + i]);
                }

                // Reset Tabs
                switchTab('tab-general');
                openModal('editStudentModal');
            }
        });
    });

    // Update Form Submit
    $('#editStudentForm').on('submit', function(e) {
        e.preventDefault();
        
        // Split birthday if present
        let date_birth = '', month_birth = '', year_birth = '';
        const bday = $('#editBirthday').val();
        if (bday) {
            const parts = bday.split('-');
            if (parts.length === 3) {
                date_birth = parts[0];
                month_birth = parts[1];
                year_birth = parts[2];
            }
        }

        var data = {
            id: $('#editStudentId').val(),
            citizenid: $('#editCitizenId').val(),
            typeregis: $('#editTyperegis').val(),
            stu_prefix: $('#editPrefix').val(),
            stu_name: $('#editFirstName').val(),
            stu_lastname: $('#editLastName').val(),
            stu_sex: $('#editSex').val(),
            stu_blood_group: $('#editBloodGroup').val(),
            stu_religion: $('#editReligion').val(),
            stu_ethnicity: $('#editEthnicity').val(),
            stu_nationality: $('#editNationality').val(),
            date_birth: date_birth,
            month_birth: month_birth,
            year_birth: year_birth,
            now_tel: $('#editNowTel').val(),
            
            // Address
            now_addr: $('#editNowAddr').val(),
            now_moo: $('#editNowMoo').val(),
            now_soy: $('#editNowSoy').val(),
            now_street: $('#editNowStreet').val(),
            now_subdistrict: $('#editNowSubdistrict').val(),
            now_district: $('#editNowDistrict').val(),
            now_province: $('#editNowProvince').val(),
            now_post: $('#editNowPost').val(),
            
            // Education
            old_school: $('#editOldSchool').val(),
            old_school_province: $('#editOldSchoolProvince').val(),
            old_school_district: $('#editOldSchoolDistrict').val(),
            old_school_stuid: $('#editOldSchoolStuid').val(),
            gpa_total: $('#editGpaTotal').val(),
            gpa_sci: $('#editGpaSci').val(),
            gpa_math: $('#editGpaMath').val(),
            gpa_eng: $('#editGpaEng').val(),
            
            // Family
            dad_prefix: $('#editDadPrefix').val(),
            dad_name: $('#editDadName').val(),
            dad_lastname: $('#editDadLastname').val(),
            dad_job: $('#editDadJob').val(),
            dad_tel: $('#editDadTel').val(),
            
            mom_prefix: $('#editMomPrefix').val(),
            mom_name: $('#editMomName').val(),
            mom_lastname: $('#editMomLastname').val(),
            mom_job: $('#editMomJob').val(),
            mom_tel: $('#editMomTel').val(),
            
            parent_relation: $('#editParentRelation').val(),
            parent_prefix: $('#editParentPrefix').val(),
            parent_name: $('#editParentName').val(),
            parent_lastname: $('#editParentLastname').val(),
            parent_job: $('#editParentJob').val(),
            parent_tel: $('#editParentTel').val()
        };
        
        for (var i = 1; i <= 10; i++) data['number' + i] = $('#editNumber' + i).val();
        
        // Use standard update API
        fetch('api/update_student.php', {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'Content-Type': 'application/json' }
        }).then(r => r.json()).then(d => {
            if (d.success) { 
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกสำเร็จ',
                    showConfirmButton: false,
                    timer: 1500
                });
                closeModal('editStudentModal'); 
                loadTable(); 
            }
            else Swal.fire('ข้อผิดพลาด', d.message, 'error');
        }).catch(err => {
            console.error(err);
            Swal.fire('ข้อผิดพลาด', 'Server Error', 'error');
        });
    });
});

// Export CSV with Thai Support
function exportCSV() {
    if (!allStudentData.length) { Swal.fire('ไม่มีข้อมูล', '', 'warning'); return; }
    
    let csvContent = "\uFEFF";
    
    // Full Header List
    const headers = [
        "ลำดับ", "เลขบัตรประชาชน", "คำนำหน้า", "ชื่อ", "นามสกุล", "เพศ", "วันเกิด", 
        "เบอร์โทรนักเรียน", "ประเภทการสมัคร", "ศาสนา", "เชื้อชาติ", "สัญชาติ", "กรุ๊ปเลือด",
        "ที่อยู่ปัจจุบัน", "หมู่", "ซอย", "ถนน", "ตำบล", "อำเภอ", "จังหวัด", "รหัสไปรษณีย์",
        "โรงเรียนเดิม", "จังหวัด(รร.เดิม)", "อำเภอ(รร.เดิม)", "GPA รวม", "GPA วิทย์", "GPA คณิต", "GPA อังกฤษ",
        "ชื่อบิดา", "เบอร์โทรบิดา", "อาชีพบิดา",
        "ชื่อมารดา", "เบอร์โทรมารดา", "อาชีพมารดา",
        "ผู้ปกครอง", "ความสัมพันธ์", "เบอร์โทรผู้ปกครอง",
        "สถานะ", "แผนการเรียน"
    ];
    csvContent += headers.join(",") + "\n";
    
    allStudentData.forEach((row, index) => {
        let status = row.status == 1 ? "ยืนยันแล้ว" : "รอตรวจสอบ";
        
        // Format Plans
        let plans = '';
        if (row.plan_string) {
            row.plan_string.split(',').forEach(p => {
                let parts = p.split(':');
                if (parts.length === 2) plans += parts[0] + '.' + getPlanName(parts[1]) + ' ';
            });
        }

        // Helper to safe string
        const s = (val) => val ? '"' + String(val).replace(/"/g, '""') + '"' : '""';
        
        let stringRow = [
            index + 1,
            s(row.citizenid),
            s(row.stu_prefix),
            s(row.stu_name),
            s(row.stu_lastname),
            s(row.stu_sex),
            s(row.birthday),
            s(row.now_tel),
            s(row.typeregis),
            s(row.stu_religion),
            s(row.stu_ethnicity),
            s(row.stu_nationality),
            s(row.stu_blood_group),
            // Address
            s(row.now_addr), s(row.now_moo), s(row.now_soy), s(row.now_street),
            s(row.now_subdistrict), s(row.now_district), s(row.now_province), s(row.now_post),
            // Education
            s(row.old_school), s(row.old_school_province), s(row.old_school_district),
            s(row.gpa_total), s(row.gpa_sci), s(row.gpa_math), s(row.gpa_eng),
            // Family
            s((row.dad_prefix || '') + ' ' + (row.dad_name || '') + ' ' + (row.dad_lastname || '')),
            s(row.dad_tel), s(row.dad_job),
            s((row.mom_prefix || '') + ' ' + (row.mom_name || '') + ' ' + (row.mom_lastname || '')),
            s(row.mom_tel), s(row.mom_job),
            s((row.parent_prefix || '') + ' ' + (row.parent_name || '') + ' ' + (row.parent_lastname || '')),
            s(row.parent_relation), s(row.parent_tel),
            status,
            s(plans)
        ];
        csvContent += stringRow.join(",") + "\n";
    });
    
    const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
    const url = URL.createObjectURL(blob);
    const link = document.createElement("a");
    link.setAttribute("href", url);
    link.setAttribute("download", "student_data_full.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Export Excel (HTML Table)
function exportExcel() {
    if (!allStudentData.length) { Swal.fire('ไม่มีข้อมูล', '', 'warning'); return; }

    let table = `
    <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
    <head>
        <meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">
    </head>
    <body>
        <table border="1">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th>ลำดับ</th>
                    <th>เลขบัตรประชาชน</th>
                    <th>คำนำหน้า</th>
                    <th>ชื่อ</th>
                    <th>นามสกุล</th>
                    <th>แผนการเรียน</th>
                    <th>เบอร์โทร</th>
                    <th>GPA รวม</th>
                    <th>GPA วิทย์</th>
                    <th>GPA คณิต</th>
                    <th>GPA อังกฤษ</th>
                    <th>โรงเรียนเดิม</th>
                    <th>จังหวัด</th>
                    <th>ที่อยู่ปัจจุบัน</th>
                    <th>ตำบล</th>
                    <th>อำเภอ</th>
                    <th>จังหวัด</th>
                    <th>รหัสไปรษณีย์</th>
                    <th>ชื่อบิดา</th>
                    <th>เบอร์โทรบิดา</th>
                    <th>ชื่อมารดา</th>
                    <th>เบอร์โทรมารดา</th>
                    <th>ผู้ปกครอง</th>
                    <th>ความสัมพันธ์</th>
                    <th>เบอร์โทร</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
    `;

    allStudentData.forEach((row, index) => {
        let status = row.status == 1 ? "ยืนยันแล้ว" : "รอตรวจสอบ";
        
        // Plans
        let plans = '';
        if (row.plan_string) {
            row.plan_string.split(',').forEach(p => {
                let parts = p.split(':');
                if (parts.length === 2) plans += parts[0] + '.' + getPlanName(parts[1]) + '<br>';
            });
        }

        table += `
            <tr>
                <td>${index + 1}</td>
                <td style="mso-number-format:'@'">${row.citizenid || '-'}</td>
                <td>${row.stu_prefix || '-'}</td>
                <td>${row.stu_name || '-'}</td>
                <td>${row.stu_lastname || '-'}</td>
                <td>${plans}</td>
                <td style="mso-number-format:'@'">${row.now_tel || '-'}</td>
                <td>${row.gpa_total || '-'}</td>
                <td>${row.gpa_sci || '-'}</td>
                <td>${row.gpa_math || '-'}</td>
                <td>${row.gpa_eng || '-'}</td>
                <td>${row.old_school || '-'}</td>
                <td>${row.old_school_province || '-'}</td>
                <td>${row.now_addr || '-'} หมู่ ${row.now_moo || '-'} ซอย ${row.now_soy || '-'} ถนน ${row.now_street || '-'}</td>
                <td>${row.now_subdistrict || '-'}</td>
                <td>${row.now_district || '-'}</td>
                <td>${row.now_province || '-'}</td>
                <td style="mso-number-format:'@'">${row.now_post || '-'}</td>
                <td>${(row.dad_prefix||'')}${row.dad_name||''} ${row.dad_lastname||''}</td>
                <td style="mso-number-format:'@'">${row.dad_tel || '-'}</td>
                <td>${(row.mom_prefix||'')}${row.mom_name||''} ${row.mom_lastname||''}</td>
                <td style="mso-number-format:'@'">${row.mom_tel || '-'}</td>
                <td>${(row.parent_prefix||'')}${row.parent_name||''} ${row.parent_lastname||''}</td>
                <td>${row.parent_relation || '-'}</td>
                <td style="mso-number-format:'@'">${row.parent_tel || '-'}</td>
                <td>${status}</td>
            </tr>
        `;
    });

    table += `</tbody></table></body></html>`;

    const blob = new Blob([table], { type: "application/vnd.ms-excel;charset=utf-8" });
    const url = URL.createObjectURL(blob);
    const link = document.createElement("a");
    link.setAttribute("href", url);
    link.setAttribute("download", "student_data_full.xls");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

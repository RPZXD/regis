<!-- Admin Config Dashboard View -->
<div class="space-y-6">
    <!-- Header -->
    <div class="glass rounded-2xl p-6 border-l-4 border-indigo-500">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg">
                <i class="fas fa-cogs text-2xl text-white"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">ตั้งค่าระบบ</h2>
                <p class="text-gray-600 dark:text-gray-400">จัดการการตั้งค่าทั้งหมดของระบบรับสมัคร</p>
            </div>
        </div>
    </div>

    <!-- Config Tabs -->
    <div class="glass rounded-2xl overflow-hidden">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex -mb-px overflow-x-auto" id="configTabs">
                <button onclick="showTab('settings')" class="tab-btn active px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 border-indigo-500 text-indigo-600 dark:text-indigo-400" data-tab="settings">
                    <i class="fas fa-sliders-h mr-2"></i>ตั้งค่าทั่วไป
                </button>
                <button onclick="showTab('menus')" class="tab-btn px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700" data-tab="menus">
                    <i class="fas fa-bars mr-2"></i>ตั้งค่าเมนู
                </button>
                <button onclick="showTab('types')" class="tab-btn px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700" data-tab="types">
                    <i class="fas fa-layer-group mr-2"></i>ประเภทการสมัคร
                </button>
                <button onclick="showTab('plans')" class="tab-btn px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700" data-tab="plans">
                    <i class="fas fa-book mr-2"></i>แผนการเรียน
                </button>
            </nav>
        </div>

        <!-- Tab Contents -->
        <div class="p-6">
            <!-- Settings Tab -->
            <div id="tab-settings" class="tab-content">
                <form id="settingsForm" class="space-y-6 max-w-2xl">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ปีการศึกษา</label>
                            <input type="number" name="academic_year" id="academic_year" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-center text-2xl font-bold focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">เปิดระบบรับสมัคร</label>
                            <select name="registration_open" id="registration_open" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                                <option value="1">เปิด</option>
                                <option value="0">ปิด</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ชื่อโรงเรียน</label>
                        <input type="text" name="school_name" id="school_name" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                    </div>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                        <i class="fas fa-save mr-2"></i>บันทึกการตั้งค่า
                    </button>
                </form>
            </div>

            <!-- Menus Tab -->
            <div id="tab-menus" class="tab-content hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm" id="menusTable">
                        <thead><tr class="text-left text-gray-600 dark:text-gray-400 border-b">
                            <th class="px-4 py-3">เมนู</th>
                            <th class="px-4 py-3 text-center">เปิด/ปิด</th>
                            <th class="px-4 py-3 text-center">ใช้กำหนดเวลา</th>
                            <th class="px-4 py-3">เริ่มต้น</th>
                            <th class="px-4 py-3">สิ้นสุด</th>
                            <th class="px-4 py-3 text-center">บันทึก</th>
                        </tr></thead>
                        <tbody class="text-gray-700 dark:text-gray-300"></tbody>
                    </table>
                </div>
            </div>

            <!-- Types Tab -->
            <div id="tab-types" class="tab-content hidden">
                <div class="flex justify-end mb-4">
                    <button onclick="showAddTypeModal()" class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg shadow hover:shadow-lg transition-all">
                        <i class="fas fa-plus mr-2"></i>เพิ่มประเภท
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm" id="typesTable">
                        <thead><tr class="text-left text-gray-600 dark:text-gray-400 border-b">
                            <th class="px-4 py-3">ระดับชั้น</th>
                            <th class="px-4 py-3">รหัส</th>
                            <th class="px-4 py-3">ชื่อประเภท</th>
                            <th class="px-4 py-3">ลิงค์สมัคร</th>
                            <th class="px-4 py-3 text-center">เปิด/ปิด</th>
                            <th class="px-4 py-3 text-center">กำหนดเวลา</th>
                            <th class="px-4 py-3 text-center">จัดการ</th>
                        </tr></thead>
                        <tbody class="text-gray-700 dark:text-gray-300"></tbody>
                    </table>
                </div>
            </div>

            <!-- Plans Tab -->
            <div id="tab-plans" class="tab-content hidden">
                <div class="flex justify-between items-center mb-4">
                    <select id="filterType" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                        <option value="">-- เลือกประเภท --</option>
                    </select>
                    <button onclick="showAddPlanModal()" class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg shadow hover:shadow-lg transition-all">
                        <i class="fas fa-plus mr-2"></i>เพิ่มแผน
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm" id="plansTable">
                        <thead><tr class="text-left text-gray-600 dark:text-gray-400 border-b">
                            <th class="px-4 py-3">ประเภท</th>
                            <th class="px-4 py-3">รหัส</th>
                            <th class="px-4 py-3">ชื่อแผน</th>
                            <th class="px-4 py-3 text-center">จำนวนรับ</th>
                            <th class="px-4 py-3 text-center">สถานะ</th>
                            <th class="px-4 py-3 text-center">จัดการ</th>
                        </tr></thead>
                        <tbody class="text-gray-700 dark:text-gray-300"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Type Modal -->
<div id="addTypeModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black/50" onclick="closeModal('addTypeModal')"></div>
        <div class="relative glass rounded-2xl max-w-md w-full p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">เพิ่มประเภทการสมัคร</h3>
            <form id="addTypeForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">ระดับชั้น</label>
                    <select name="grade_level_id" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700" id="gradeSelect"></select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">รหัสประเภท</label>
                    <input type="text" name="code" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700" placeholder="special, general, quota">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">ชื่อประเภท</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">ลิงค์สมัคร (URL)</label>
                    <input type="text" name="url" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700" placeholder="regis1.php หรือ regis4_quota.php">
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('addTypeModal')" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">ยกเลิก</button>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Plan Modal -->
<div id="addPlanModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black/50" onclick="closeModal('addPlanModal')"></div>
        <div class="relative glass rounded-2xl max-w-md w-full p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">เพิ่มแผนการเรียน</h3>
            <form id="addPlanForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">ประเภทการสมัคร</label>
                    <select name="registration_type_id" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700" id="typeSelectPlan"></select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">รหัสแผน</label>
                    <input type="text" name="code" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">ชื่อแผน</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">จำนวนที่รับ</label>
                    <input type="number" name="quota" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700" value="0">
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('addPlanModal')" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">ยกเลิก</button>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Tab switching
function showTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('active', 'border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
        el.classList.add('border-transparent', 'text-gray-500');
    });
    document.getElementById('tab-' + tabName).classList.remove('hidden');
    document.querySelector('[data-tab="' + tabName + '"]').classList.add('active', 'border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
    
    if (tabName === 'menus') loadMenus();
    if (tabName === 'types') loadTypes();
    if (tabName === 'plans') loadPlans();
}

function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
function showAddTypeModal() { document.getElementById('addTypeModal').classList.remove('hidden'); }
function showAddPlanModal() { document.getElementById('addPlanModal').classList.remove('hidden'); }

// Load Settings
function loadSettings() {
    $.get('api/admin/settings.php', function(data) {
        data.forEach(s => {
            if (document.getElementById(s.key_name)) {
                document.getElementById(s.key_name).value = s.value;
            }
        });
    });
}

// Load Menus
function loadMenus() {
    $.get('api/admin/menu-config.php', function(data) {
        let html = '';
        data.forEach(m => {
            html += `<tr class="border-b border-gray-100 dark:border-gray-700">
                <td class="px-4 py-3"><i class="fas ${m.icon} mr-2 text-indigo-500"></i>${m.menu_name}</td>
                <td class="px-4 py-3 text-center">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer menu-enabled" data-id="${m.id}" ${m.is_enabled == 1 ? 'checked' : ''}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                    </label>
                    <span class="save-indicator ml-2 text-green-500 hidden" data-id="${m.id}"><i class="fas fa-check"></i></span>
                </td>
                <td class="px-4 py-3 text-center">
                    <input type="checkbox" class="w-4 h-4 menu-schedule" data-id="${m.id}" ${m.use_schedule == 1 ? 'checked' : ''}>
                </td>
                <td class="px-4 py-3"><input type="datetime-local" class="menu-start text-sm px-2 py-1 rounded border dark:bg-slate-700 dark:border-gray-600" data-id="${m.id}" value="${m.start_datetime ? m.start_datetime.replace(' ', 'T') : ''}"></td>
                <td class="px-4 py-3"><input type="datetime-local" class="menu-end text-sm px-2 py-1 rounded border dark:bg-slate-700 dark:border-gray-600" data-id="${m.id}" value="${m.end_datetime ? m.end_datetime.replace(' ', 'T') : ''}"></td>
                <td class="px-4 py-3 text-center"><button onclick="saveMenu(${m.id})" class="px-3 py-1 bg-indigo-500 text-white text-sm rounded hover:bg-indigo-600" title="บันทึกเวลา"><i class="fas fa-clock"></i></button></td>
            </tr>`;
        });
        $('#menusTable tbody').html(html);
        
        // Auto-save on toggle change (real-time)
        $('.menu-enabled').on('change', function() {
            const id = $(this).data('id');
            const isEnabled = $(this).prop('checked') ? 1 : 0;
            const indicator = $(`.save-indicator[data-id="${id}"]`);
            
            $.post('api/admin/menu-config.php', {
                id: id,
                is_enabled: isEnabled,
                use_schedule: $(`.menu-schedule[data-id="${id}"]`).prop('checked') ? 1 : 0,
                start_datetime: $(`.menu-start[data-id="${id}"]`).val().replace('T', ' '),
                end_datetime: $(`.menu-end[data-id="${id}"]`).val().replace('T', ' ')
            }, function() {
                // Show check indicator
                indicator.removeClass('hidden');
                setTimeout(() => indicator.addClass('hidden'), 1500);
            });
        });
        
        // Auto-save on schedule checkbox change
        $('.menu-schedule').on('change', function() {
            const id = $(this).data('id');
            saveMenu(id);
        });
    });
}

// Load Types
function loadTypes() {
    $.get('api/admin/registration-types.php', function(data) {
        let html = '';
        let gradeOpts = '<option value="">-- เลือก --</option>';
        let typeOpts = '<option value="">-- เลือก --</option>';
        let grades = {};
        data.forEach(t => {
            if (!grades[t.grade_level_id]) {
                grades[t.grade_level_id] = t.grade_name;
                gradeOpts += `<option value="${t.grade_level_id}">${t.grade_name}</option>`;
            }
            typeOpts += `<option value="${t.id}">${t.grade_name} - ${t.name}</option>`;
            html += `<tr class="border-b border-gray-100 dark:border-gray-700">
                <td class="px-4 py-3">${t.grade_name}</td>
                <td class="px-4 py-3 font-mono">${t.code}</td>
                <td class="px-4 py-3 font-semibold">${t.name}</td>
                <td class="px-4 py-3">
                    <input type="text" class="type-url text-sm px-2 py-1 rounded border dark:bg-slate-700 dark:border-gray-600 w-32" 
                           data-id="${t.id}" value="${t.url || ''}" placeholder="regis1.php">
                </td>
                <td class="px-4 py-3 text-center">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer type-active" data-id="${t.id}" ${t.is_active == 1 ? 'checked' : ''}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </td>
                <td class="px-4 py-3 text-center">${t.use_schedule == 1 ? '<i class="fas fa-clock text-amber-500"></i>' : '-'}</td>
                <td class="px-4 py-3 text-center">
                    <button onclick="deleteType(${t.id})" class="px-2 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600"><i class="fas fa-trash"></i></button>
                </td>
            </tr>`;
        });
        $('#typesTable tbody').html(html);
        $('#gradeSelect').html(gradeOpts);
        $('#typeSelectPlan, #filterType').html(typeOpts);
        
        // Auto-save on toggle change
        $('.type-active').on('change', function() {
            const id = $(this).data('id');
            saveType(id);
        });
        
        // Auto-save on URL change
        $('.type-url').on('change', function() {
            const id = $(this).data('id');
            saveType(id);
        });
    });
}

// Save Type
function saveType(id) {
    const row = $(`[data-id="${id}"]`).closest('tr');
    $.post('api/admin/registration-types.php', {
        id: id,
        action: 'update',
        url: $(`.type-url[data-id="${id}"]`).val(),
        is_active: $(`.type-active[data-id="${id}"]`).prop('checked') ? 1 : 0
    }, function() {
        // Visual feedback
        Swal.fire({ icon: 'success', title: 'บันทึกแล้ว', timer: 800, showConfirmButton: false });
    });
}

// Load Plans
function loadPlans() {
    let typeId = $('#filterType').val();
    $.get('api/admin/study-plans.php' + (typeId ? '?type_id=' + typeId : ''), function(data) {
        let html = '';
        data.forEach(p => {
            html += `<tr class="border-b border-gray-100 dark:border-gray-700">
                <td class="px-4 py-3">${p.grade_name} - ${p.type_name}</td>
                <td class="px-4 py-3 font-mono">${p.code}</td>
                <td class="px-4 py-3 font-semibold">${p.name}</td>
                <td class="px-4 py-3 text-center font-bold">${p.quota}</td>
                <td class="px-4 py-3 text-center">${p.is_active == 1 ? '<span class="px-2 py-1 text-xs bg-green-100 text-green-600 rounded-full">เปิด</span>' : '<span class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded-full">ปิด</span>'}</td>
                <td class="px-4 py-3 text-center">
                    <button onclick="deletePlan(${p.id})" class="px-2 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600"><i class="fas fa-trash"></i></button>
                </td>
            </tr>`;
        });
        $('#plansTable tbody').html(html || '<tr><td colspan="6" class="text-center py-8 text-gray-500">ไม่พบข้อมูล</td></tr>');
    });
}

// Save functions
function saveMenu(id) {
    let data = {
        id: id,
        is_enabled: $(`.menu-enabled[data-id="${id}"]`).prop('checked') ? 1 : 0,
        use_schedule: $(`.menu-schedule[data-id="${id}"]`).prop('checked') ? 1 : 0,
        start_datetime: $(`.menu-start[data-id="${id}"]`).val().replace('T', ' '),
        end_datetime: $(`.menu-end[data-id="${id}"]`).val().replace('T', ' ')
    };
    $.post('api/admin/menu-config.php', data, function() {
        Swal.fire({ icon: 'success', title: 'บันทึกแล้ว', timer: 1000, showConfirmButton: false });
    });
}

function deleteType(id) {
    Swal.fire({ title: 'ยืนยันลบ?', icon: 'warning', showCancelButton: true }).then(r => {
        if (r.isConfirmed) {
            $.ajax({ url: 'api/admin/registration-types.php?id=' + id, method: 'DELETE', success: () => loadTypes() });
        }
    });
}

function deletePlan(id) {
    Swal.fire({ title: 'ยืนยันลบ?', icon: 'warning', showCancelButton: true }).then(r => {
        if (r.isConfirmed) {
            $.ajax({ url: 'api/admin/study-plans.php?id=' + id, method: 'DELETE', success: () => loadPlans() });
        }
    });
}

// Form submissions
$('#settingsForm').on('submit', function(e) {
    e.preventDefault();
    let formData = $(this).serializeArray();
    formData.forEach(f => {
        $.post('api/admin/settings.php', { key: f.name, value: f.value });
    });
    Swal.fire({ icon: 'success', title: 'บันทึกการตั้งค่าแล้ว', timer: 1500, showConfirmButton: false });
});

$('#addTypeForm').on('submit', function(e) {
    e.preventDefault();
    $.post('api/admin/registration-types.php', $(this).serialize(), function() {
        closeModal('addTypeModal');
        loadTypes();
        Swal.fire({ icon: 'success', title: 'เพิ่มประเภทแล้ว', timer: 1000 });
    });
});

$('#addPlanForm').on('submit', function(e) {
    e.preventDefault();
    $.post('api/admin/study-plans.php', $(this).serialize(), function() {
        closeModal('addPlanModal');
        loadPlans();
        Swal.fire({ icon: 'success', title: 'เพิ่มแผนแล้ว', timer: 1000 });
    });
});

$('#filterType').on('change', loadPlans);

// Initial load
$(document).ready(function() {
    loadSettings();
});
</script>

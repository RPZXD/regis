<!-- Admin Config Dashboard View -->
<div class="space-y-6">
    <!-- Header -->
    <div class="glass rounded-2xl p-6 border-l-4 border-indigo-500">
        <div class="flex items-center space-x-4">
            <div
                class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg">
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
                <button onclick="showTab('settings')"
                    class="tab-btn active px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 border-indigo-500 text-indigo-600 dark:text-indigo-400"
                    data-tab="settings">
                    <i class="fas fa-sliders-h mr-2"></i>ตั้งค่าทั่วไป
                </button>
                <button onclick="showTab('menus')"
                    class="tab-btn px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700"
                    data-tab="menus">
                    <i class="fas fa-bars mr-2"></i>ตั้งค่าเมนู
                </button>
                <button onclick="showTab('types')"
                    class="tab-btn px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700"
                    data-tab="types">
                    <i class="fas fa-layer-group mr-2"></i>ประเภทการสมัคร
                </button>
                <button onclick="showTab('plans')"
                    class="tab-btn px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700"
                    data-tab="plans">
                    <i class="fas fa-book mr-2"></i>แผนการเรียน
                </button>
                <button onclick="showTab('documents')"
                    class="tab-btn px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700"
                    data-tab="documents">
                    <i class="fas fa-file-upload mr-2"></i>เอกสารที่ต้องอัพโหลด
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
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ปีการศึกษา</label>
                            <input type="number" name="academic_year" id="academic_year"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-center text-2xl font-bold focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">เปิดระบบรับสมัคร</label>
                            <select name="registration_open" id="registration_open"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                                <option value="1">เปิด</option>
                                <option value="0">ปิด</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ชื่อโรงเรียน</label>
                        <input type="text" name="school_name" id="school_name"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                    </div>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                        <i class="fas fa-save mr-2"></i>บันทึกการตั้งค่า
                    </button>
                </form>
            </div>

            <!-- Menus Tab -->
            <div id="tab-menus" class="tab-content hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm" id="menusTable">
                        <thead>
                            <tr class="text-left text-gray-600 dark:text-gray-400 border-b">
                                <th class="px-4 py-3">เมนู</th>
                                <th class="px-4 py-3 text-center">เปิด/ปิด</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300"></tbody>
                    </table>
                </div>
            </div>

            <!-- Types Tab -->
            <div id="tab-types" class="tab-content hidden">
                <div class="flex justify-end mb-4">
                    <button onclick="showAddTypeModal()"
                        class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg shadow hover:shadow-lg transition-all">
                        <i class="fas fa-plus mr-2"></i>เพิ่มประเภท
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm" id="typesTable">
                        <thead>
                            <tr class="text-left text-gray-600 dark:text-gray-400 border-b">
                                <th class="px-4 py-3">ระดับชั้น</th>
                                <th class="px-4 py-3">รหัส</th>
                                <th class="px-4 py-3">ชื่อประเภท</th>
                                <th class="px-4 py-3">ลิงค์สมัคร</th>
                                <th class="px-4 py-3 text-center">เปิด/ปิด</th>
                                <th class="px-4 py-3 text-center">กำหนดเวลา</th>
                                <th class="px-4 py-3 text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300"></tbody>
                    </table>
                </div>
            </div>

            <!-- Plans Tab -->
            <div id="tab-plans" class="tab-content hidden">
                <div class="flex justify-between items-center mb-4">
                    <select id="filterType"
                        class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                        <option value="">-- เลือกประเภท --</option>
                    </select>
                    <button onclick="showAddPlanModal()"
                        class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg shadow hover:shadow-lg transition-all">
                        <i class="fas fa-plus mr-2"></i>เพิ่มแผน
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm" id="plansTable">
                        <thead>
                            <tr class="text-left text-gray-600 dark:text-gray-400 border-b">
                                <th class="px-4 py-3">ประเภท</th>
                                <th class="px-4 py-3">รหัส</th>
                                <th class="px-4 py-3">ชื่อแผน</th>
                                <th class="px-4 py-3 text-center">จำนวนรับ</th>
                                <th class="px-4 py-3 text-center">สถานะ</th>
                                <th class="px-4 py-3 text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300"></tbody>
                    </table>
                </div>
            </div>

            <!-- Documents Tab -->
            <div id="tab-documents" class="tab-content hidden">
                <div class="flex justify-between items-center mb-4">
                    <select id="filterDocType"
                        class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                        <option value="">-- เลือกประเภทการสมัคร --</option>
                    </select>
                    <button onclick="showAddDocModal()"
                        class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg shadow hover:shadow-lg transition-all">
                        <i class="fas fa-plus mr-2"></i>เพิ่มเอกสาร
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm" id="docsTable">
                        <thead>
                            <tr class="text-left text-gray-600 dark:text-gray-400 border-b">
                                <th class="px-4 py-3">ประเภทการสมัคร</th>
                                <th class="px-4 py-3">ชื่อเอกสาร</th>
                                <th class="px-4 py-3 text-center">บังคับ</th>
                                <th class="px-4 py-3">ประเภทไฟล์</th>
                                <th class="px-4 py-3 text-center">ขนาดสูงสุด</th>
                                <th class="px-4 py-3 text-center">สถานะ</th>
                                <th class="px-4 py-3 text-center">จัดการ</th>
                            </tr>
                        </thead>
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
                    <select name="grade_level_id" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700"
                        id="gradeSelect"></select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">รหัสประเภท</label>
                    <input type="text" name="code" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700"
                        placeholder="special, general, quota">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">ชื่อประเภท</label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">ลิงค์สมัคร (URL)</label>
                    <input type="text" name="url" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700"
                        placeholder="regis1.php หรือ regis4_quota.php">
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('addTypeModal')"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">ยกเลิก</button>
                    <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg">บันทึก</button>
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
                    <select name="registration_type_id" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700"
                        id="typeSelectPlan"></select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">รหัสแผน</label>
                    <input type="text" name="code" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">ชื่อแผน</label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">จำนวนที่รับ</label>
                    <input type="number" name="quota"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700"
                        value="0">
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('addPlanModal')"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">ยกเลิก</button>
                    <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Plan Modal -->
<div id="editPlanModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black/50" onclick="closeModal('editPlanModal')"></div>
        <div class="relative glass rounded-2xl max-w-md w-full p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-edit text-amber-500 mr-2"></i>แก้ไขแผนการเรียน
            </h3>
            <form id="editPlanForm" class="space-y-4">
                <input type="hidden" name="id" id="editPlanId">
                <div>
                    <label class="block text-sm font-medium mb-2">ประเภทการสมัคร</label>
                    <select name="registration_type_id" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700"
                        id="editTypeSelectPlan"></select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">รหัสแผน</label>
                    <input type="text" name="code" id="editPlanCode" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">ชื่อแผน</label>
                    <input type="text" name="name" id="editPlanName" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">จำนวนที่รับ</label>
                    <input type="number" name="quota" id="editPlanQuota"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700"
                        value="0">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="editPlanActive" value="1" class="w-4 h-4 mr-2 text-green-600">
                    <label class="text-sm">เปิดใช้งาน</label>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('editPlanModal')"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">ยกเลิก</button>
                    <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-lg">บันทึกการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Document Modal -->
<div id="addDocModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black/50" onclick="closeModal('addDocModal')"></div>
        <div class="relative glass rounded-2xl max-w-md w-full p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-file-upload text-green-500 mr-2"></i>เพิ่มเอกสารที่ต้องอัพโหลด
            </h3>
            <form id="addDocForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">ประเภทการสมัคร</label>
                    <select name="registration_type_id" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700"
                        id="typeSelectDoc"></select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">ชื่อเอกสาร</label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700"
                        placeholder="เช่น สำเนาทะเบียนบ้าน">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">คำอธิบาย (ไม่บังคับ)</label>
                    <textarea name="description" rows="2"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700"
                        placeholder="คำอธิบายเพิ่มเติม"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">ประเภทไฟล์</label>
                        <input type="text" name="file_types" value="jpg,jpeg,png,pdf"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">ขนาดสูงสุด (MB)</label>
                        <input type="number" name="max_size_mb" value="5" min="1" max="20"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                    </div>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_required" value="1" checked class="w-4 h-4 mr-2 text-green-600">
                    <label class="text-sm">บังคับอัพโหลด</label>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('addDocModal')"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">ยกเลิก</button>
                    <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Document Modal -->
<div id="editDocModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black/50" onclick="closeModal('editDocModal')"></div>
        <div class="relative glass rounded-2xl max-w-md w-full p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-edit text-amber-500 mr-2"></i>แก้ไขเอกสาร
            </h3>
            <form id="editDocForm" class="space-y-4">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="editDocId">
                <div>
                    <label class="block text-sm font-medium mb-2">ชื่อเอกสาร</label>
                    <input type="text" name="name" id="editDocName" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">คำอธิบาย</label>
                    <textarea name="description" id="editDocDesc" rows="2"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">ประเภทไฟล์</label>
                        <input type="text" name="file_types" id="editDocFileTypes"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">ขนาดสูงสุด (MB)</label>
                        <input type="number" name="max_size_mb" id="editDocMaxSize" min="1" max="20"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_required" id="editDocRequired" value="1"
                            class="w-4 h-4 mr-2 text-green-600">
                        <span class="text-sm">บังคับอัพโหลด</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" id="editDocActive" value="1"
                            class="w-4 h-4 mr-2 text-green-600">
                        <span class="text-sm">เปิดใช้งาน</span>
                    </label>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('editDocModal')"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">ยกเลิก</button>
                    <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-lg">บันทึกการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Schedule Periods Modal -->
<div id="scheduleModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black/50" onclick="closeModal('scheduleModal')"></div>
        <div class="relative glass rounded-2xl max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-calendar-alt text-indigo-500 mr-2"></i>
                    ตั้งค่าช่วงเวลา: <span id="scheduleTypeName" class="text-indigo-600"></span>
                </h3>
                <button onclick="closeModal('scheduleModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="scheduleForm" class="space-y-4">
                <input type="hidden" name="id" id="scheduleTypeId">
                <input type="hidden" name="action" value="update_schedules">

                <!-- Registration Period -->
                <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-user-plus text-blue-500 mr-2"></i>
                        <span class="font-semibold text-gray-900 dark:text-white">สมัครเรียน</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">เริ่มต้น</label>
                            <input type="datetime-local" name="register_start"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">สิ้นสุด</label>
                            <input type="datetime-local" name="register_end"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                        </div>
                    </div>
                </div>

                <!-- Print Form Period -->
                <div
                    class="p-4 rounded-xl bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-print text-purple-500 mr-2"></i>
                        <span class="font-semibold text-gray-900 dark:text-white">พิมพ์ใบสมัคร</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">เริ่มต้น</label>
                            <input type="datetime-local" name="print_form_start"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">สิ้นสุด</label>
                            <input type="datetime-local" name="print_form_end"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                        </div>
                    </div>
                </div>

                <!-- Upload Documents Period -->
                <div
                    class="p-4 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-upload text-amber-500 mr-2"></i>
                        <span class="font-semibold text-gray-900 dark:text-white">อัพโหลดหลักฐาน</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">เริ่มต้น</label>
                            <input type="datetime-local" name="upload_start"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">สิ้นสุด</label>
                            <input type="datetime-local" name="upload_end"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                        </div>
                    </div>
                </div>

                <!-- Exam Card Period -->
                <div class="p-4 rounded-xl bg-cyan-50 dark:bg-cyan-900/20 border border-cyan-200 dark:border-cyan-800">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-id-card text-cyan-500 mr-2"></i>
                        <span class="font-semibold text-gray-900 dark:text-white">พิมพ์บัตรสอบ</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">เริ่มต้น</label>
                            <input type="datetime-local" name="exam_card_start"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">สิ้นสุด</label>
                            <input type="datetime-local" name="exam_card_end"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                        </div>
                    </div>
                </div>

                <!-- Report Period -->
                <div
                    class="p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span class="font-semibold text-gray-900 dark:text-white">รายงานตัว</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">เริ่มต้น</label>
                            <input type="datetime-local" name="report_start"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">สิ้นสุด</label>
                            <input type="datetime-local" name="report_end"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                        </div>
                    </div>
                </div>

                <!-- Announce Results Period -->
                <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-bullhorn text-rose-500 mr-2"></i>
                        <span class="font-semibold text-gray-900 dark:text-white">ประกาศผล</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">เริ่มต้น</label>
                            <input type="datetime-local" name="announce_start"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">สิ้นสุด</label>
                            <input type="datetime-local" name="announce_end"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" onclick="closeModal('scheduleModal')"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">ยกเลิก</button>
                    <button type="submit"
                        class="px-6 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-lg shadow hover:shadow-lg transition-all">
                        <i class="fas fa-save mr-2"></i>บันทึก
                    </button>
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
        if (tabName === 'documents') loadDocuments();
    }

    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
    function showAddTypeModal() { document.getElementById('addTypeModal').classList.remove('hidden'); }
    function showAddPlanModal() { document.getElementById('addPlanModal').classList.remove('hidden'); }
    function showAddDocModal() { document.getElementById('addDocModal').classList.remove('hidden'); }

    // Show Schedule Modal with existing data
    function showScheduleModal(id, name, regStart, regEnd, printStart, printEnd, uploadStart, uploadEnd, examStart, examEnd, reportStart, reportEnd, announceStart, announceEnd) {
        document.getElementById('scheduleTypeId').value = id;
        document.getElementById('scheduleTypeName').textContent = name;

        const formatDateTime = (dt) => dt ? dt.replace(' ', 'T') : '';

        $('[name="register_start"]').val(formatDateTime(regStart));
        $('[name="register_end"]').val(formatDateTime(regEnd));
        $('[name="print_form_start"]').val(formatDateTime(printStart));
        $('[name="print_form_end"]').val(formatDateTime(printEnd));
        $('[name="upload_start"]').val(formatDateTime(uploadStart));
        $('[name="upload_end"]').val(formatDateTime(uploadEnd));
        $('[name="exam_card_start"]').val(formatDateTime(examStart));
        $('[name="exam_card_end"]').val(formatDateTime(examEnd));
        $('[name="report_start"]').val(formatDateTime(reportStart));
        $('[name="report_end"]').val(formatDateTime(reportEnd));
        $('[name="announce_start"]').val(formatDateTime(announceStart));
        $('[name="announce_end"]').val(formatDateTime(announceEnd));

        document.getElementById('scheduleModal').classList.remove('hidden');
    }

    // Load Settings
    function loadSettings() {
        $.get('api/admin/settings.php', function (data) {
            data.forEach(s => {
                if (document.getElementById(s.key_name)) {
                    document.getElementById(s.key_name).value = s.value;
                }
            });
        });
    }

    // Load Menus
    function loadMenus() {
        $.get('api/admin/menu-config.php', function (data) {
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
            </tr>`;
            });
            $('#menusTable tbody').html(html);

            // Auto-save on toggle change (real-time)
            $('.menu-enabled').on('change', function () {
                const id = $(this).data('id');
                const isEnabled = $(this).prop('checked') ? 1 : 0;
                const indicator = $(`.save-indicator[data-id="${id}"]`);

                $.post('api/admin/menu-config.php', {
                    id: id,
                    is_enabled: isEnabled
                }, function () {
                    // Show check indicator
                    indicator.removeClass('hidden');
                    setTimeout(() => indicator.addClass('hidden'), 1500);
                });
            });
        });
    }

    // Load Types
    function loadTypes() {
        $.get('api/admin/registration-types.php', function (data) {
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
                    <button onclick="showScheduleModal(${t.id}, '${t.grade_name} - ${t.name}', 
                        '${t.register_start || ''}', '${t.register_end || ''}',
                        '${t.print_form_start || ''}', '${t.print_form_end || ''}',
                        '${t.upload_start || ''}', '${t.upload_end || ''}',
                        '${t.exam_card_start || ''}', '${t.exam_card_end || ''}',
                        '${t.report_start || ''}', '${t.report_end || ''}',
                        '${t.announce_start || ''}', '${t.announce_end || ''}')"
                        class="px-2 py-1 bg-indigo-500 text-white text-sm rounded hover:bg-indigo-600 mr-1" title="ตั้งค่าช่วงเวลา">
                        <i class="fas fa-calendar-alt"></i>
                    </button>
                    <button onclick="deleteType(${t.id})" class="px-2 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600"><i class="fas fa-trash"></i></button>
                </td>
            </tr>`;
            });
            $('#typesTable tbody').html(html);
            $('#gradeSelect').html(gradeOpts);
            $('#typeSelectPlan, #filterType').html(typeOpts);

            // Auto-save on toggle change
            $('.type-active').on('change', function () {
                const id = $(this).data('id');
                saveType(id);
            });

            // Auto-save on URL change
            $('.type-url').on('change', function () {
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
        }, function () {
            // Visual feedback
            Swal.fire({ icon: 'success', title: 'บันทึกแล้ว', timer: 800, showConfirmButton: false });
        });
    }

    // Load Plans
    function loadPlans() {
        let typeId = $('#filterType').val();
        $.get('api/admin/study-plans.php' + (typeId ? '?type_id=' + typeId : ''), function (data) {
            let html = '';
            data.forEach(p => {
                html += `<tr class="border-b border-gray-100 dark:border-gray-700">
                <td class="px-4 py-3">${p.grade_name} - ${p.type_name}</td>
                <td class="px-4 py-3 font-mono">${p.code}</td>
                <td class="px-4 py-3 font-semibold">${p.name}</td>
                <td class="px-4 py-3 text-center font-bold">${p.quota}</td>
                <td class="px-4 py-3 text-center">${p.is_active == 1 ? '<span class="px-2 py-1 text-xs bg-green-100 text-green-600 rounded-full">เปิด</span>' : '<span class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded-full">ปิด</span>'}</td>
                <td class="px-4 py-3 text-center">
                    <button onclick="showEditPlanModal(${p.id}, '${p.code}', '${p.name}', ${p.quota}, ${p.is_active}, ${p.registration_type_id})" 
                            class="px-2 py-1 bg-amber-500 text-white text-sm rounded hover:bg-amber-600 mr-1" title="แก้ไข">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deletePlan(${p.id})" class="px-2 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600" title="ลบ"><i class="fas fa-trash"></i></button>
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
            is_enabled: $(`.menu-enabled[data-id="${id}"]`).prop('checked') ? 1 : 0
        };
        $.post('api/admin/menu-config.php', data, function () {
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
    $('#settingsForm').on('submit', function (e) {
        e.preventDefault();
        let formData = $(this).serializeArray();
        formData.forEach(f => {
            $.post('api/admin/settings.php', { key: f.name, value: f.value });
        });
        Swal.fire({ icon: 'success', title: 'บันทึกการตั้งค่าแล้ว', timer: 1500, showConfirmButton: false });
    });

    $('#addTypeForm').on('submit', function (e) {
        e.preventDefault();
        $.post('api/admin/registration-types.php', $(this).serialize(), function () {
            closeModal('addTypeModal');
            loadTypes();
            Swal.fire({ icon: 'success', title: 'เพิ่มประเภทแล้ว', timer: 1000 });
        });
    });

    $('#addPlanForm').on('submit', function (e) {
        e.preventDefault();
        $.post('api/admin/study-plans.php', $(this).serialize(), function () {
            closeModal('addPlanModal');
            loadPlans();
            Swal.fire({ icon: 'success', title: 'เพิ่มแผนแล้ว', timer: 1000 });
        });
    });

    // Show Edit Plan Modal
    function showEditPlanModal(id, code, name, quota, isActive, typeId) {
        $('#editPlanId').val(id);
        $('#editPlanCode').val(code);
        $('#editPlanName').val(name);
        $('#editPlanQuota').val(quota);
        $('#editPlanActive').prop('checked', isActive == 1);
        
        // Load type options and set selected
        $.get('api/admin/registration-types.php', function(types) {
            let opts = '<option value="">-- เลือก --</option>';
            types.forEach(t => {
                opts += `<option value="${t.id}" ${t.id == typeId ? 'selected' : ''}>${t.grade_name} - ${t.name}</option>`;
            });
            $('#editTypeSelectPlan').html(opts);
        });
        
        document.getElementById('editPlanModal').classList.remove('hidden');
    }

    // Edit Plan Form Submit
    $('#editPlanForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize() + '&is_active=' + ($('#editPlanActive').is(':checked') ? 1 : 0);
        
        $.ajax({
            url: 'api/admin/study-plans.php',
            method: 'PUT',
            data: formData,
            success: function() {
                closeModal('editPlanModal');
                loadPlans();
                Swal.fire({ icon: 'success', title: 'บันทึกการแก้ไขแล้ว', timer: 1000, showConfirmButton: false });
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: 'ไม่สามารถบันทึกได้' });
            }
        });
    });

    $('#scheduleForm').on('submit', function (e) {
        e.preventDefault();
        $.post('api/admin/registration-types.php', $(this).serialize(), function (response) {
            closeModal('scheduleModal');
            loadTypes();
            Swal.fire({ icon: 'success', title: 'บันทึกช่วงเวลาแล้ว', timer: 1000, showConfirmButton: false });
        }).fail(function () {
            Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: 'ไม่สามารถบันทึกได้' });
        });
    });

    // Load Documents
    function loadDocuments() {
        const typeId = $('#filterDocType').val();
        const url = typeId ? `api/admin/document-requirements.php?type_id=${typeId}` : 'api/admin/document-requirements.php';

        $.get(url, function (data) {
            let html = '';
            let typeOpts = '<option value="">-- ทั้งหมด --</option>';
            let seenTypes = {};

            data.forEach(d => {
                const typeKey = d.registration_type_id;
                if (!seenTypes[typeKey]) {
                    seenTypes[typeKey] = true;
                    typeOpts += `<option value="${d.registration_type_id}">${d.grade_name} - ${d.type_name}</option>`;
                }

                html += `<tr class="border-b border-gray-100 dark:border-gray-700">
                <td class="px-4 py-3">${d.grade_name} - ${d.type_name}</td>
                <td class="px-4 py-3 font-semibold">${d.name}</td>
                <td class="px-4 py-3 text-center">${d.is_required == 1 ? '<i class="fas fa-check text-green-500"></i>' : '<i class="fas fa-minus text-gray-400"></i>'}</td>
                <td class="px-4 py-3 text-xs">${d.file_types}</td>
                <td class="px-4 py-3 text-center">${d.max_size_mb} MB</td>
                <td class="px-4 py-3 text-center">${d.is_active == 1 ? '<span class="text-green-500">เปิด</span>' : '<span class="text-gray-400">ปิด</span>'}</td>
                <td class="px-4 py-3 text-center">
                    <button onclick="showEditDocModal(${d.id}, '${d.name}', '${d.description || ''}', ${d.is_required}, '${d.file_types}', ${d.max_size_mb}, ${d.is_active})" 
                            class="px-2 py-1 bg-amber-500 text-white text-sm rounded hover:bg-amber-600 mr-1" title="แก้ไข">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deleteDoc(${d.id})" class="px-2 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600" title="ลบ"><i class="fas fa-trash"></i></button>
                </td>
            </tr>`;
            });
            $('#docsTable tbody').html(html || '<tr><td colspan="7" class="text-center py-4 text-gray-400">ไม่มีข้อมูล</td></tr>');

            // Populate type selects
            $('#filterDocType').html(typeOpts);

            // Also populate the modal select
            $.get('api/admin/registration-types.php', function (types) {
                let modalOpts = '<option value="">-- เลือก --</option>';
                types.forEach(t => {
                    modalOpts += `<option value="${t.id}">${t.grade_name} - ${t.name}</option>`;
                });
                $('#typeSelectDoc').html(modalOpts);
            });
        });
    }

    function deleteDoc(id) {
        Swal.fire({
            title: 'ยืนยันการลบ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonText: 'ยกเลิก',
            confirmButtonText: 'ลบ'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `api/admin/document-requirements.php?id=${id}`,
                    method: 'DELETE',
                    success: function () {
                        loadDocuments();
                        Swal.fire({ icon: 'success', title: 'ลบแล้ว', timer: 1000 });
                    }
                });
            }
        });
    }

    $('#addDocForm').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serialize() + '&is_required=' + ($('#addDocForm [name="is_required"]').is(':checked') ? 1 : 0);
        $.post('api/admin/document-requirements.php', formData, function () {
            closeModal('addDocModal');
            loadDocuments();
            $('#addDocForm')[0].reset();
            Swal.fire({ icon: 'success', title: 'เพิ่มเอกสารแล้ว', timer: 1000 });
        });
    });

    function showEditDocModal(id, name, description, isRequired, fileTypes, maxSize, isActive) {
        $('#editDocId').val(id);
        $('#editDocName').val(name);
        $('#editDocDesc').val(description);
        $('#editDocFileTypes').val(fileTypes);
        $('#editDocMaxSize').val(maxSize);
        $('#editDocRequired').prop('checked', isRequired == 1);
        $('#editDocActive').prop('checked', isActive == 1);
        document.getElementById('editDocModal').classList.remove('hidden');
    }

    $('#editDocForm').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serialize()
            + '&is_required=' + ($('#editDocRequired').is(':checked') ? 1 : 0)
            + '&is_active=' + ($('#editDocActive').is(':checked') ? 1 : 0);
        $.post('api/admin/document-requirements.php', formData, function () {
            closeModal('editDocModal');
            loadDocuments();
            Swal.fire({ icon: 'success', title: 'บันทึกการแก้ไขแล้ว', timer: 1000 });
        }).fail(function () {
            Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: 'ไม่สามารถบันทึกได้' });
        });
    });

    $('#filterDocType').on('change', loadDocuments);

    $('#filterType').on('change', loadPlans);

    // Initial load
    $(document).ready(function () {
        loadSettings();
    });
</script>
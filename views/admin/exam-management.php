<!-- Exam Management View -->
<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="glass rounded-2xl p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <i class="fas fa-chair text-8xl text-pink-600"></i>
        </div>
        <div class="relative z-10">
            <h2 class="text-3xl font-bold bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent">
                จัดการข้อมูลการสอบ
            </h2>
            <p class="text-gray-500 dark:text-gray-400 mt-2">
                กำหนดเลขที่นั่งสอบ ห้องสอบ และวันเวลาสอบ
            </p>
        </div>
    </div>

    <!-- Filter & Tools -->
    <div class="glass rounded-2xl p-6">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full md:w-auto">
                <div class="w-full md:w-64">
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">เลือกระดับชั้น/ประเภท</label>
                    <select id="typeFilter"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-pink-500 outline-none"
                        onchange="loadTable()">
                        <option value="">-- กรุณาเลือก --</option>
                        <optgroup label="มัธยมศึกษาปีที่ 1">
                            <?php
                            $m1Types = $adminConfig->getActiveRegistrationTypes('m1');
                            foreach ($m1Types as $type) {
                                echo '<option value="' . $type['id'] . '">ม.1 - ' . $type['name'] . '</option>';
                            }
                            ?>
                        </optgroup>
                        <optgroup label="มัธยมศึกษาปีที่ 4">
                            <?php
                            $m4Types = $adminConfig->getActiveRegistrationTypes('m4');
                            foreach ($m4Types as $type) {
                                echo '<option value="' . $type['id'] . '">ม.4 - ' . $type['name'] . '</option>';
                            }
                            ?>
                        </optgroup>
                    </select>
                </div>
                <button onclick="loadTable()"
                    class="mt-6 px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors shadow-lg shadow-pink-500/30">
                    <i class="fas fa-sync-alt mr-2"></i> โหลดข้อมูล
                </button>
            </div>

            <div id="bulkTools" class="hidden flex gap-2">
                <button onclick="openRoomManagementModal()"
                    class="px-6 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg shadow-lg shadow-purple-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all font-bold">
                    <i class="fas fa-door-open mr-2"></i> จัดการห้องสอบ
                </button>
                <button onclick="openAutoGenerateModal()"
                    class="px-6 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all font-bold">
                    <i class="fas fa-magic mr-2"></i> สร้างอัตโนมัติ
                </button>
                <button onclick="saveAll()"
                    class="px-6 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg shadow-lg shadow-green-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all font-bold">
                    <i class="fas fa-save mr-2"></i> บันทึกทั้งหมด
                </button>
                <button onclick="exportCSV()"
                    class="px-6 py-2 bg-gradient-to-r from-yellow-500 to-orange-600 text-white rounded-lg shadow-lg shadow-yellow-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all font-bold">
                    <i class="fas fa-file-csv mr-2"></i> CSV
                </button>
                <button onclick="exportExcel()"
                    class="px-6 py-2 bg-gradient-to-r from-blue-400 to-indigo-500 text-white rounded-lg shadow-lg shadow-blue-400/30 hover:shadow-xl hover:-translate-y-0.5 transition-all font-bold">
                    <i class="fas fa-file-excel mr-2"></i> Excel
                </button>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="glass rounded-2xl p-6 shadow-xl shadow-gray-200/50 dark:shadow-none">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="examTable">
                <thead>
                    <tr class="bg-gray-50 dark:bg-slate-700/50 text-gray-600 dark:text-gray-300">
                        <th class="px-4 py-4 text-center rounded-l-xl">#</th>
                        <th class="px-4 py-4 text-center">รหัสประจำตัว</th>
                        <th class="px-4 py-4 text-left">ชื่อ - นามสกุล</th>
                        <th class="px-4 py-4 text-left">แผนการเรียน</th>
                        <th class="px-4 py-4 text-center w-32">เลขที่นั่งสอบ</th>
                        <th class="px-4 py-4 text-center w-40">ห้องสอบ</th>
                        <th class="px-4 py-4 text-center w-48">วันสอบ</th>
                        <th class="px-4 py-4 text-center rounded-r-xl w-24">สถานะ</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-300 divide-y divide-gray-100 dark:divide-gray-800">
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-400">กรุณาเลือกประเภทการสมัครเพื่อแสดงข้อมูล
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Auto Generate Modal -->
<div id="autoGenerateModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('autoGenerateModal')"></div>
        <div class="relative glass rounded-2xl w-full max-w-lg flex flex-col shadow-2xl">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">สร้างข้อมูลอัตโนมัติ</h3>
                <button onclick="closeModal('autoGenerateModal')"
                    class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">เริ่มที่เลขที่นั่งสอบ</label>
                    <input type="text" id="autoSeatStart"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="เช่น 001">
                    <p class="text-xs text-gray-500 mt-1">ระบบจะรันตัวเลขต่อท้ายให้อัติโนมัติ</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ห้องสอบ</label>
                    <input type="text" id="autoRoom"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="เช่น อาคาร 1 ห้อง 101">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">วันสอบ</label>
                    <input type="text" id="autoDate"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="เช่น 24 มีนาคม 2567">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">จำนวนคนต่อห้อง
                        (ถ้ามี)</label>
                    <input type="number" id="autoRoomMax"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="เช่น 25">
                    <p class="text-xs text-gray-500 mt-1">หากระบุ ระบบจะเปลี่ยนห้องสอบให้อัตโนมัติเมื่อครบจำนวน</p>
                </div>
            </div>

            <div
                class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-800 flex justify-end space-x-3">
                <button onclick="closeModal('autoGenerateModal')"
                    class="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">ยกเลิก</button>
                <button onclick="applyAutoGenerate()"
                    class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-lg hover:shadow-lg transition-all">
                    <i class="fas fa-check mr-2"></i> ตกลง
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Room Management Modal -->
<div id="roomManagementModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('roomManagementModal')"></div>
        <div class="relative glass rounded-2xl w-full max-w-6xl flex flex-col shadow-2xl">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">จัดการห้องสอบ</h3>
                <button onclick="closeModal('roomManagementModal')"
                    class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-6 space-y-6">
                <!-- Room List -->
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">รายการห้องสอบ</h4>
                        <button onclick="addNewRoom()"
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm">
                            <i class="fas fa-plus mr-2"></i>เพิ่มห้องใหม่
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm" id="roomTable">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-slate-700/50 text-gray-600 dark:text-gray-300">
                                    <th class="px-4 py-3 text-left">ชื่อห้อง</th>
                                    <th class="px-4 py-3 text-left">อาคาร</th>
                                    <th class="px-4 py-3 text-center">จำนวนนั่ง</th>
                                    <th class="px-4 py-3 text-center">จำนวนที่นั่ง</th>
                                    <th class="px-4 py-3 text-center">สถานะ</th>
                                    <th class="px-4 py-3 text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody
                                class="text-gray-700 dark:text-gray-300 divide-y divide-gray-100 dark:divide-gray-800">
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-gray-400">ยังไม่มีข้อมูลห้องสอบ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Assignment Tools -->
                <div class="border-t pt-6">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">เครื่องมือการจัดสรร</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Manual Assignment -->
                        <div class="space-y-4">
                            <h5 class="font-medium text-gray-700 dark:text-gray-300">การจัดสรรแบบกำหนดเอง</h5>
                            <div class="space-y-3">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">เลือกห้องสอบ</label>
                                    <select id="manualRoomSelect"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none">
                                        <option value="">-- เลือกห้อง --</option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">นักเรียนที่เลือก</label>
                                    <select id="manualStudentSelect" multiple
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none"
                                        size="6">
                                        <option value="">-- กรุณาโหลดข้อมูลนักเรียนก่อน --</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">กดปุ่ม Ctrl หรือ Cmd เพื่อเลือกหลายคน</p>
                                </div>
                                <button onclick="assignToRoom()"
                                    class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                    <i class="fas fa-user-plus mr-2"></i>จัดนักเรียนลงห้อง
                                </button>
                            </div>
                        </div>

                        <!-- Random Assignment -->
                        <div class="space-y-4">
                            <h5 class="font-medium text-gray-700 dark:text-gray-300">การจัดสรรแบบสุ่ม</h5>
                            <div class="space-y-3">
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">เลือกห้องที่ใช้ในการสุ่ม</label>
                                        <button onclick="toggleSelectAllRooms(true)"
                                            class="text-xs text-purple-600 hover:underline">เลือกทั้งหมด</button>
                                    </div>
                                    <div id="randomRoomSelection"
                                        class="space-y-2 max-h-32 overflow-y-auto border border-gray-200 dark:border-gray-600 rounded-lg p-3">
                                        <p class="text-sm text-gray-400">-- กรุณาเพิ่มห้องสอบก่อน --</p>
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">จำนวนนักเรียนต่อห้อง
                                        (สูงสุด)</label>
                                    <input type="number" id="randomMaxPerRoom"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-purple-500 outline-none"
                                        placeholder="เช่น 25" value="25">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">เริ่มเลขที่นั่ง
                                        (ถ้ามี)</label>
                                    <input type="text" id="randomStartSeat"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-purple-500 outline-none"
                                        placeholder="เช่น A001">
                                </div>
                                <div class="flex items-center mt-6">
                                    <input type="checkbox" id="randomBalancePlans"
                                        class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                    <label for="randomBalancePlans"
                                        class="ml-2 text-sm text-gray-700 dark:text-gray-300">แผนการเรียนสมดุล</label>
                                </div>
                                <button onclick="assignRandomly()"
                                    class="w-full px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:shadow-lg transition-all">
                                    <i class="fas fa-random mr-2"></i>จัดสรรแบบสุ่ม
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-800 flex justify-end space-x-3">
                <button onclick="closeModal('roomManagementModal')"
                    class="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">ปิด</button>
                <button onclick="saveRoomChanges()"
                    class="px-6 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-lg hover:shadow-lg transition-all">
                    <i class="fas fa-save mr-2"></i> บันทึกการเปลี่ยนแปลง
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Room Modal -->
<div id="roomModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('roomModal')"></div>
        <div class="relative glass rounded-2xl w-full max-w-md flex flex-col shadow-2xl">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="roomModalTitle">เพิ่มห้องสอบใหม่</h3>
                <button onclick="closeModal('roomModal')"
                    class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <input type="hidden" id="roomId">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ชื่อห้อง</label>
                    <input type="text" id="roomName"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-purple-500 outline-none"
                        placeholder="เช่น ห้อง 101">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">อาคาร</label>
                    <input type="text" id="roomBuilding"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-purple-500 outline-none"
                        placeholder="เช่น อาคาร 1">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">จำนวนนั่ง</label>
                        <input type="number" id="roomSeats"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-purple-500 outline-none"
                            placeholder="เช่น 30">
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">จำนวนที่นั่ง</label>
                        <input type="number" id="roomCapacity"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-purple-500 outline-none"
                            placeholder="เช่น 25">
                    </div>
                </div>
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">รายละเอียดเพิ่มเติม</label>
                    <textarea id="roomDetails" rows="3"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-purple-500 outline-none"
                        placeholder="เช่น อยู่ชั้น 1 ใกล้ประตาทางเดิน"></textarea>
                </div>
                <div>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" id="roomActive"
                            class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500" checked>
                        <span class="text-sm text-gray-700 dark:text-gray-300">ใช้งาน</span>
                    </label>
                </div>
            </div>

            <div
                class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-800 flex justify-end space-x-3">
                <button onclick="closeModal('roomModal')"
                    class="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">ยกเลิก</button>
                <button onclick="saveRoom()"
                    class="px-6 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-lg hover:shadow-lg transition-all">
                    <i class="fas fa-save mr-2"></i> บันทึก
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Set base URL for API calls with cache-busting
    const API_BASE_URL = 'api/';
    const CACHE_BUSTER = Date.now(); // Add timestamp to prevent caching

    const plansMap = <?php echo json_encode($plansMap); ?>;
    let currentData = [];

    function getPlanName(id) {
        return plansMap[id] || id;
    }

    function loadTable() {
        const typeId = $('#typeFilter').val();
        if (!typeId) return;

        // Show loading
        $('#examTable tbody').html('<tr><td colspan="8" class="text-center py-8"><div class="loader mx-auto"></div><div class="mt-2 text-gray-500">กำลังโหลดข้อมูล...</div></td></tr>');
        $('#bulkTools').addClass('hidden');

        $.ajax({
            url: API_BASE_URL + 'fetch_students_dynamic.php?t=' + CACHE_BUSTER,
            method: 'GET',
            data: { type_id: typeId },
            dataType: 'json',
            success: function (response) {
                currentData = response;
                renderTable(response);
                updateStudentSelect();
                $('#bulkTools').removeClass('hidden');
            },
            error: function (xhr, status, error) {
                console.error(error);
                $('#examTable tbody').html('<tr><td colspan="8" class="text-center text-red-500 py-4">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>');
            }
        });
    }

    function updateStudentSelect() {
        const studentSelect = $('#manualStudentSelect');
        studentSelect.empty().append('<option value="">-- กรุณาโหลดข้อมูลนักเรียนก่อน --</option>');

        if (currentData.length > 0) {
            currentData.forEach(student => {
                studentSelect.append(`<option value="${student.id}">${student.fullname} (${student.citizenid})</option>`);
            });
        }
    }

    function renderTable(data) {
        if ($.fn.DataTable.isDataTable('#examTable')) {
            $('#examTable').DataTable().destroy();
        }

        const tbody = $('#examTable tbody');
        tbody.empty();

        if (data.length === 0) {
            tbody.html('<tr><td colspan="8" class="text-center py-8 text-gray-400">ไม่พบข้อมูล</td></tr>');
            return;
        }

        data.forEach((row, index) => {
            let plansHtml = '-';
            if (row.plan_string) {
                const planPairs = row.plan_string.split(',');
                const formattedPlans = planPairs.map(pair => {
                    const [priority, planId] = pair.split(':');
                    const color = priority == 1 ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30' : 'bg-gray-100 dark:bg-slate-700';
                    return `<span class="px-2 py-0.5 ${color} rounded text-[10px] block w-fit mb-1">${priority}. ${getPlanName(planId)}</span>`;
                });
                plansHtml = `<div class="flex flex-col">${formattedPlans.join('')}</div>`;
            }

            const seatVal = row.seat_number || '';
            const roomVal = row.exam_room || '';
            const dateVal = row.exam_date || '';

            const tr = `
            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors" data-id="${row.id}">
                <td class="px-4 py-3 text-center text-gray-500">${index + 1}</td>
                <td class="px-4 py-3 text-center font-mono text-indigo-600">${row.citizenid}</td>
                <td class="px-4 py-3">${row.fullname}</td>
                <td class="px-4 py-3">${plansHtml}</td>
                <td class="px-2 py-2">
                    <input type="text" class="w-full px-2 py-1 text-center border border-gray-200 dark:border-gray-600 rounded bg-white dark:bg-slate-800 focus:ring-2 focus:ring-pink-500 outline-none transition-all exam-input" 
                        name="seat_number" value="${seatVal}" placeholder="-">
                </td>
                <td class="px-2 py-2">
                    <input type="text" class="w-full px-2 py-1 text-center border border-gray-200 dark:border-gray-600 rounded bg-white dark:bg-slate-800 focus:ring-2 focus:ring-pink-500 outline-none transition-all exam-input" 
                        name="exam_room" value="${roomVal}" placeholder="รอประกาศ">
                </td>
                <td class="px-2 py-2">
                    <input type="text" class="w-full px-2 py-1 text-center border border-gray-200 dark:border-gray-600 rounded bg-white dark:bg-slate-800 focus:ring-2 focus:ring-pink-500 outline-none transition-all exam-input" 
                        name="exam_date" value="${dateVal}" placeholder="รอประกาศ">
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="save-status text-xs text-gray-400"><i class="fas fa-check-circle"></i></span>
                </td>
            </tr>
        `;
            tbody.append(tr);
        });

        // Initialize DataTable after rendering inputs
        $('#examTable').DataTable({
            "pageLength": 50,
            "lengthMenu": [10, 25, 50, 100],
            "responsive": true,
            "language": {
                "search": "ค้นหา:",
                "info": "แสดง _START_ ถึง _END_ จาก _TOTAL_",
                "paginate": { "first": "<<", "last": ">>", "next": ">", "previous": "<" }
            },
            "drawCallback": function () {
                // Re-bind events if needed, but using direct listeners on inputs is better
                bindInputEvents();
            }
        });
    }

    function bindInputEvents() {
        $('.exam-input').off('change').on('change', function () {
            const input = $(this);
            const name = input.attr('name');
            const val = input.val();
            const row = input.closest('tr');
            const id = row.data('id');
            const statusSpan = row.find('.save-status');

            // Update currentData
            const index = currentData.findIndex(s => s.id == id);
            if (index !== -1) {
                currentData[index][name] = val;
            }

            statusSpan.html('<i class="fas fa-spinner fa-spin text-blue-500"></i>');

            const data = {
                id: id,
                [name]: val
            };

            saveData(data, statusSpan);
        });
    }

    function saveData(data, statusSpan) {
        $.ajax({
            url: API_BASE_URL + 'update_exam_info.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (response) {
                if (response.success) {
                    statusSpan.html('<i class="fas fa-check text-green-500"></i>');
                    setTimeout(() => { statusSpan.html('<i class="fas fa-check-circle text-gray-400"></i>'); }, 2000);
                } else {
                    statusSpan.html('<i class="fas fa-exclamation-circle text-red-500" title="' + response.message + '"></i>');
                }
            },
            error: function () {
                statusSpan.html('<i class="fas fa-times text-red-500"></i>');
            }
        });
    }

    function saveAll() {
        if (!currentData.length) return;

        Swal.fire({
            title: 'กำลังบันทึก...',
            text: 'ระบบกำลังทยอยบันทึกข้อมูลทั้งหมด กรุณารอสักครู่',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        // We'll send requests in small batches to avoid flooding the server
        const batchSize = 10;
        const total = currentData.length;
        let processed = 0;

        async function processBatch() {
            const batch = currentData.slice(processed, processed + batchSize);
            const requests = batch.map(student => {
                return $.ajax({
                    url: API_BASE_URL + 'update_exam_info.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        id: student.id,
                        seat_number: student.seat_number,
                        exam_room: student.exam_room,
                        exam_date: student.exam_date
                    })
                });
            });

            await Promise.all(requests);
            processed += batchSize;

            if (processed < total) {
                Swal.update({ text: `บันทึกข้อมูลแล้ว ${processed} จาก ${total} รายการ...` });
                await processBatch();
            } else {
                Swal.fire('บันทึกสำเร็จ', `บันทึกข้อมูลทั้งหมด ${total} รายการเรียบร้อยแล้ว`, 'success');
                loadTable(); // Reload to refresh UI and status icons
            }
        }

        processBatch().catch(err => {
            console.error(err);
            Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถบันทึกข้อมูลบางรายการได้', 'error');
        });
    }

    function openAutoGenerateModal() {
        document.getElementById('autoGenerateModal').classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function applyAutoGenerate() {
        const startSeat = $('#autoSeatStart').val().trim();
        const room = $('#autoRoom').val().trim();
        const date = $('#autoDate').val().trim();
        const maxPerRoom = parseInt($('#autoRoomMax').val().trim()) || 0;

        if (!room && !date && !startSeat) {
            Swal.fire('แจ้งเตือน', 'กรุณากรอกข้อมูลอย่างน้อย 1 ช่อง', 'warning');
            return;
        }

        // Parse Start Seat logic
        let seatPrefix = "";
        let seatNumber = 0;
        let seatWidth = 0;
        if (startSeat) {
            const match = startSeat.match(/(\d+)$/);
            if (match) {
                seatNumber = parseInt(match[0]);
                seatWidth = match[0].length;
                seatPrefix = startSeat.slice(0, -match[0].length);
            } else {
                seatPrefix = startSeat;
            }
        }

        // Parse Room logic
        let roomPrefix = "";
        let roomStartNum = 0;
        let roomNumWidth = 0;
        if (room && maxPerRoom > 0) {
            const match = room.match(/(\d+)$/);
            if (match) {
                roomStartNum = parseInt(match[0]);
                roomNumWidth = match[0].length;
                roomPrefix = room.slice(0, -match[0].length);
            } else {
                roomPrefix = room;
            }
        }

        // We update the data model (currentData) first
        // Note: We follow the current order in the table if it's already rendered
        // But for simplicity and consistency with Seat Numbers, we'll use the current sort order of DataTable if it exists

        let rowsToUpdate = currentData;
        if ($.fn.DataTable.isDataTable('#examTable')) {
            const table = $('#examTable').DataTable();
            // Get data in current visual order
            const currentOrderIndices = table.rows({ order: 'current' }).indexes();
            rowsToUpdate = currentOrderIndices.map(idx => table.row(idx).data()).toArray();
        }

        rowsToUpdate.forEach((student, index) => {
            // Update Seat
            if (startSeat) {
                let currentNum = seatNumber + index;
                let currentNumStr = currentNum.toString();
                if (seatWidth > currentNumStr.length) {
                    currentNumStr = currentNumStr.padStart(seatWidth, '0');
                }
                student.seat_number = seatPrefix + currentNumStr;
            }

            // Update Room
            if (room) {
                if (maxPerRoom > 0 && roomStartNum > 0) {
                    const roomIdx = Math.floor(index / maxPerRoom);
                    let currentRn = roomStartNum + roomIdx;
                    let currentRnStr = currentRn.toString();
                    if (roomNumWidth > currentRnStr.length) {
                        currentRnStr = currentRnStr.padStart(roomNumWidth, '0');
                    }
                    student.exam_room = roomPrefix + currentRnStr;
                } else {
                    student.exam_room = room;
                }
            }

            // Update Date
            if (date) {
                student.exam_date = date;
            }
        });

        // Re-render table to show new values
        renderTable(currentData);
        closeModal('autoGenerateModal');

        Swal.fire({
            title: 'สร้างข้อมูลเรียบร้อย',
            text: 'สร้างข้อมูลอัตโนมัติสำเร็จเเล้ว กรุณากดปุ่ม "บันทึกทั้งหมด" เพื่อยืนยันการบันทึก',
            icon: 'success'
        });
    }

    // Room Management Functions
    let rooms = [];

    function openRoomManagementModal() {
        loadRooms();
        document.getElementById('roomManagementModal').classList.remove('hidden');
    }

    function loadRooms() {
        $.ajax({
            url: API_BASE_URL + 'get_exam_rooms.php?t=' + CACHE_BUSTER,
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                rooms = response;
                renderRoomTable();
                updateRoomSelects();
            },
            error: function () {
                Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถโหลดข้อมูลห้องสอบได้', 'error');
            }
        });
    }

    function renderRoomTable() {
        const tbody = $('#roomTable tbody');
        tbody.empty();

        if (rooms.length === 0) {
            tbody.html('<tr><td colspan="6" class="text-center py-4 text-gray-400">ยังไม่มีข้อมูลห้องสอบ</td></tr>');
            return;
        }

        rooms.forEach((room, index) => {
            const statusBadge = room.is_active ?
                '<span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 dark:bg-green-900/30 dark:text-green-400 rounded-full">ใช้งาน</span>' :
                '<span class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 rounded-full">ไม่ใช้งาน</span>';

            const tr = `
            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors" data-id="${room.id}">
                <td class="px-4 py-3 font-medium">${room.name}</td>
                <td class="px-4 py-3">${room.building}</td>
                <td class="px-4 py-3 text-center">${room.seats || '-'}</td>
                <td class="px-4 py-3 text-center">${room.capacity || '-'}</td>
                <td class="px-4 py-3 text-center">${statusBadge}</td>
                <td class="px-4 py-3 text-center">
                    <div class="flex justify-center space-x-2">
                        <button onclick="editRoom(${room.id})" class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors" title="แก้ไข">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteRoom(${room.id})" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors" title="ลบ">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
            tbody.append(tr);
        });
    }

    function updateRoomSelects() {
        // Update manual room select
        const manualSelect = $('#manualRoomSelect');
        manualSelect.empty().append('<option value="">-- เลือกห้อง --</option>');

        // Update random room selection
        const randomSelection = $('#randomRoomSelection');
        randomSelection.empty();

        const activeRooms = rooms.filter(room => room.is_active);

        activeRooms.forEach(room => {
            manualSelect.append(`<option value="${room.id}">${room.name} (${room.building})</option>`);
            randomSelection.append(`
            <label class="flex items-center space-x-2 p-2 hover:bg-gray-50 dark:hover:bg-slate-700 rounded cursor-pointer">
                <input type="checkbox" value="${room.id}" class="room-checkbox">
                <span class="text-sm">${room.name} (${room.building}) - ความจุ: ${room.capacity || room.seats || '-'}</span>
            </label>
        `);
        });
    }

    function addNewRoom() {
        document.getElementById('roomModalTitle').textContent = 'เพิ่มห้องสอบใหม่';
        document.getElementById('roomId').value = '';
        document.getElementById('roomName').value = '';
        document.getElementById('roomBuilding').value = '';
        document.getElementById('roomSeats').value = '';
        document.getElementById('roomCapacity').value = '';
        document.getElementById('roomDetails').value = '';
        document.getElementById('roomActive').checked = true;
        document.getElementById('roomModal').classList.remove('hidden');
    }

    function editRoom(roomId) {
        const room = rooms.find(r => r.id == roomId);
        if (!room) return;

        document.getElementById('roomModalTitle').textContent = 'แก้ไขข้อมูลห้องสอบ';
        document.getElementById('roomId').value = room.id;
        document.getElementById('roomName').value = room.name;
        document.getElementById('roomBuilding').value = room.building;
        document.getElementById('roomSeats').value = room.seats || '';
        document.getElementById('roomCapacity').value = room.capacity || '';
        document.getElementById('roomDetails').value = room.details || '';
        document.getElementById('roomActive').checked = room.is_active;
        document.getElementById('roomModal').classList.remove('hidden');
    }

    function deleteRoom(roomId) {
        const room = rooms.find(r => r.id == roomId);
        if (!room) return;

        Swal.fire({
            title: 'ยืนยันการลบ',
            html: `คุณต้องการลบห้อง <strong>${room.name}</strong> ใช่หรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'ลบ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: API_BASE_URL + 'delete_exam_room.php',
                    method: 'POST',
                    data: { id: roomId },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('ลบสำเร็จ', '', 'success');
                            loadRooms();
                        } else {
                            Swal.fire('เกิดข้อผิดพลาด', response.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถลบข้อมูลห้องได้', 'error');
                    }
                });
            }
        });
    }

    function saveRoom() {
        const roomId = document.getElementById('roomId').value;
        const roomData = {
            id: roomId || null,
            name: document.getElementById('roomName').value.trim(),
            building: document.getElementById('roomBuilding').value.trim(),
            seats: parseInt(document.getElementById('roomSeats').value) || null,
            capacity: parseInt(document.getElementById('roomCapacity').value) || null,
            details: document.getElementById('roomDetails').value.trim(),
            is_active: document.getElementById('roomActive').checked
        };

        if (!roomData.name || !roomData.building) {
            Swal.fire('กรุณากรอกข้อมูลให้ครบ', 'ชื่อห้องและอาคารเป็นข้อมูลที่จำเป็นต้องกรอก', 'warning');
            return;
        }

        const url = roomId ? API_BASE_URL + 'update_exam_room.php' : API_BASE_URL + 'create_exam_room.php';

        $.ajax({
            url: url,
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(roomData),
            success: function (response) {
                if (response.success) {
                    Swal.fire(roomId ? 'แก้ไขสำเร็จ' : 'เพิ่มสำเร็จ', '', 'success');
                    closeModal('roomModal');
                    loadRooms();
                } else {
                    Swal.fire('เกิดข้อผิดพลาด', response.message, 'error');
                }
            },
            error: function () {
                Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถบันทึกข้อมูลห้องได้', 'error');
            }
        });
    }

    function assignToRoom() {
        const roomId = $('#manualRoomSelect').val();
        const selectedStudents = $('#manualStudentSelect').val() || [];

        if (!roomId) {
            Swal.fire('กรุณาเลือกห้อง', 'กรุณาเลือกห้องสอบที่ต้องการจัดนักเรียน', 'warning');
            return;
        }

        if (selectedStudents.length === 0) {
            Swal.fire('กรุณาเลือกนักเรียน', 'กรุณาเลือกนักเรียนที่ต้องการจัดลงห้อง', 'warning');
            return;
        }

        const room = rooms.find(r => r.id == roomId);
        const roomName = room ? `${room.name} (${room.building})` : 'ห้องที่เลือก';

        Swal.fire({
            title: 'ยืนยันการจัดสรร',
            html: `จัดนักเรียน ${selectedStudents.length} คนลงห้อง <strong>${roomName}</strong> ใช่หรือไม่?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'ตกลง',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                const requests = selectedStudents.map(studentId => {
                    const student = currentData.find(s => s.id == studentId);
                    if (student) {
                        return $.ajax({
                            url: API_BASE_URL + 'update_exam_info.php',
                            method: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify({
                                id: studentId,
                                exam_room: roomName
                            })
                        });
                    }
                    return null;
                }).filter(req => req !== null);

                Promise.all(requests).then(() => {
                    Swal.fire('จัดสรรสำเร็จ', '', 'success');
                    loadTable(); // Reload main table
                }).catch(() => {
                    Swal.fire('เกิดข้อผิดพลาดบางรายการ', '', 'warning');
                });
            }
        });
    }

    function toggleSelectAllRooms(selected) {
        $('.room-checkbox').prop('checked', selected);
    }

    function assignRandomly() {
        const selectedRoomIds = $('.room-checkbox:checked').map(function () {
            return parseInt($(this).val());
        }).get();

        if (selectedRoomIds.length === 0) {
            Swal.fire('กรุณาเลือกห้อง', 'กรุณาเลือกห้องสอบที่ต้องการใช้ในการจัดสรรแบบสุ่ม', 'warning');
            return;
        }

        const maxPerRoom = parseInt($('#randomMaxPerRoom').val()) || 25;
        const balancePlans = $('#randomBalancePlans').is(':checked');
        const startSeat = $('#randomStartSeat').val().trim();

        // Parse Start Seat logic
        let seatPrefix = "";
        let seatBaseNumber = 0;
        let seatWidth = 0;
        if (startSeat) {
            const match = startSeat.match(/(\d+)$/);
            if (match) {
                seatBaseNumber = parseInt(match[0]);
                seatWidth = match[0].length;
                seatPrefix = startSeat.slice(0, -match[0].length);
            } else {
                seatPrefix = startSeat;
            }
        }

        Swal.fire({
            title: 'ยืนยันการจัดสรรแบบสุ่ม',
            html: `จัดนักเรียนลงห้องสอบ ${selectedRoomIds.length} ห้องโดยสุ่ม<br>
                จำนวนนักเรียนต่อห้อง: ${maxPerRoom} คน<br>
                ${startSeat ? `เริ่มรันที่นั่งจาก: <b>${startSeat}</b><br>` : ''}
                ${balancePlans ? 'พยายามพิจารณาแผนการเรียนให้สมดุล' : ''}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'ตกลง',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                const selectedRooms = rooms.filter(room => selectedRoomIds.includes(room.id));
                const unassignedStudents = [...currentData];
                const assignments = [];

                // Sort/Shuffle
                let studentsToAssign = balancePlans ?
                    [...unassignedStudents].sort(() => Math.random() - 0.5) :
                    [...unassignedStudents];

                selectedRooms.forEach(room => {
                    let roomCount = 0;
                    let localSeatIdx = 0; // Reset for each room
                    while (roomCount < maxPerRoom && studentsToAssign.length > 0) {
                        const student = studentsToAssign.shift();

                        let assignedSeat = null;
                        if (startSeat) {
                            let currNum = seatBaseNumber + localSeatIdx;
                            let currNumStr = currNum.toString();
                            if (seatWidth > currNumStr.length) {
                                currNumStr = currNumStr.padStart(seatWidth, '0');
                            }
                            assignedSeat = seatPrefix + currNumStr;
                        }

                        assignments.push({
                            studentId: student.id,
                            roomName: `${room.name} (${room.building})`,
                            seatNumber: assignedSeat
                        });
                        roomCount++;
                        localSeatIdx++;
                    }
                });

                // Apply locally for feedback
                assignments.forEach(assign => {
                    const student = currentData.find(s => s.id == assign.studentId);
                    if (student) {
                        student.exam_room = assign.roomName;
                        if (assign.seatNumber) student.seat_number = assign.seatNumber;
                    }
                });

                renderTable(currentData);

                // Batch update to DB
                const requests = assignments.map(assignment =>
                    $.ajax({
                        url: API_BASE_URL + 'update_exam_info.php',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            id: assignment.studentId,
                            exam_room: assignment.roomName,
                            seat_number: assignment.seatNumber
                        })
                    })
                );

                Promise.all(requests).then(() => {
                    const assigned = assignments.length;
                    const unassigned = studentsToAssign.length;
                    let message = `จัดสรรสำเร็จ ${assigned} คน`;
                    if (unassigned > 0) {
                        message += `<br>ยังคงเหลือ ${unassigned} คนที่ยังไม่ได้จัดเนื่องจากความจุเต็ม`;
                    }
                    Swal.fire('จัดสรรสำเร็จ', message, unassigned > 0 ? 'warning' : 'success');
                }).catch(() => {
                    Swal.fire('เกิดข้อผิดพลาดในการบันทึกฐานข้อมูล', '', 'error');
                });
            }
        });
    }

    function saveRoomChanges() {
        // This function can be used for bulk operations if needed
        closeModal('roomManagementModal');
        Swal.fire('บันทึกการเปลี่ยนแปลง', '', 'success');
    }

    // Export CSV with Thai Support
    function exportCSV() {
        // Sort by Room then Seat
        const sortedData = [...currentData].sort((a, b) => {
            const roomA = String(a.exam_room || "");
            const roomB = String(b.exam_room || "");
            if (roomA !== roomB) return roomA.localeCompare(roomB, 'th');

            const seatA = String(a.seat_number || "");
            const seatB = String(b.seat_number || "");
            return seatA.localeCompare(seatB, undefined, { numeric: true });
        });

        let csvContent = "\uFEFF";
        const headers = ["ลำดับ", "เลขที่ผู้สมัคร", "เลขบัตรประชาชน", "ชื่อ-นามสกุล", "แผนการเรียน", "เลขที่นั่งสอบ", "ห้องสอบ", "วันสอบ"];
        csvContent += headers.join(",") + "\n";

        sortedData.forEach((row, index) => {
            const s = (val) => val ? '"' + String(val).replace(/"/g, '""') + '"' : '""';
            
            let plans = '';
            if (row.plan_string) {
                row.plan_string.split(',').forEach(p => {
                    let parts = p.split(':');
                    if (parts.length === 2) plans += parts[0] + '.' + getPlanName(parts[1]) + ' ';
                });
            }

            let stringRow = [
                index + 1,
                s(row.numreg),
                s(row.citizenid),
                s(row.fullname),
                s(plans),
                s(row.seat_number),
                s(row.exam_room),
                s(row.exam_date)
            ];
            csvContent += stringRow.join(",") + "\n";
        });

        const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
        const url = URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.setAttribute("href", url);
        link.setAttribute("download", "exam_list_" + Date.now() + ".csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Export Excel (HTML Table)
    function exportExcel() {
        // Sort by Room then Seat
        const sortedData = [...currentData].sort((a, b) => {
            const roomA = String(a.exam_room || "");
            const roomB = String(b.exam_room || "");
            if (roomA !== roomB) return roomA.localeCompare(roomB, 'th');

            const seatA = String(a.seat_number || "");
            const seatB = String(b.seat_number || "");
            return seatA.localeCompare(seatB, undefined, { numeric: true });
        });

        let table = `
    <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
    <head>
        <meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">
    </head>
    <body>
        <h2 style="text-align:center">ใบรายชื่อผู้มีสิทธิ์สอบ</h2>
        <table border="1">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th>ลำดับ</th>
                    <th>เลขที่ผู้สมัคร</th>
                    <th>เลขบัตรประชาชน</th>
                    <th>ชื่อ - นามสกุล</th>
                    <th>แผนการเรียน</th>
                    <th>เลขที่นั่งสอบ</th>
                    <th>ห้องสอบ</th>
                    <th>วันสอบ</th>
                </tr>
            </thead>
            <tbody>
    `;

        sortedData.forEach((row, index) => {
            let plans = '';
            if (row.plan_string) {
                row.plan_string.split(',').forEach(p => {
                    let parts = p.split(':');
                    if (parts.length === 2) plans += parts[0] + '.' + getPlanName(parts[1]) + '<br>';
                });
            }

            table += `
            <tr>
                <td style="text-align:center">${index + 1}</td>
                <td style="mso-number-format:'@'">${row.numreg || '-'}</td>
                <td style="mso-number-format:'@'">${row.citizenid || '-'}</td>
                <td>${row.fullname || '-'}</td>
                <td>${plans}</td>
                <td style="text-align:center; font-weight:bold">${row.seat_number || '-'}</td>
                <td style="text-align:center">${row.exam_room || '-'}</td>
                <td style="text-align:center">${row.exam_date || '-'}</td>
            </tr>
        `;
        });

        table += `</tbody></table></body></html>`;

        const blob = new Blob([table], { type: "application/vnd.ms-excel;charset=utf-8" });
        const url = URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.setAttribute("href", url);
        link.setAttribute("download", "exam_attendance_list_" + Date.now() + ".xls");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
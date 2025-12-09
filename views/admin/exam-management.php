
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
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">เลือกระดับชั้น/ประเภท</label>
                    <select id="typeFilter" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-pink-500 outline-none" onchange="loadTable()">
                        <option value="">-- กรุณาเลือก --</option>
                        <optgroup label="มัธยมศึกษาปีที่ 1">
                            <?php 
                            $m1Types = $adminConfig->getActiveRegistrationTypes('m1');
                            foreach($m1Types as $type) {
                                echo '<option value="'.$type['id'].'">ม.1 - '.$type['name'].'</option>';
                            }
                            ?>
                        </optgroup>
                        <optgroup label="มัธยมศึกษาปีที่ 4">
                            <?php 
                            $m4Types = $adminConfig->getActiveRegistrationTypes('m4');
                            foreach($m4Types as $type) {
                                echo '<option value="'.$type['id'].'">ม.4 - '.$type['name'].'</option>';
                            }
                            ?>
                        </optgroup>
                    </select>
                </div>
                <button onclick="loadTable()" class="mt-6 px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors shadow-lg shadow-pink-500/30">
                    <i class="fas fa-sync-alt mr-2"></i> โหลดข้อมูล
                </button>
            </div>
            
            <div id="bulkTools" class="hidden flex gap-2">
                <button onclick="openAutoGenerateModal()" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all font-bold">
                    <i class="fas fa-magic mr-2"></i> สร้างอัตโนมัติ
                </button>
                <button onclick="saveAll()" class="px-6 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg shadow-lg shadow-green-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all font-bold">
                    <i class="fas fa-save mr-2"></i> บันทึกทั้งหมด
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
                    <tr><td colspan="8" class="text-center py-8 text-gray-400">กรุณาเลือกประเภทการสมัครเพื่อแสดงข้อมูล</td></tr>
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
                <button onclick="closeModal('autoGenerateModal')" class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">เริ่มที่เลขที่นั่งสอบ</label>
                    <input type="text" id="autoSeatStart" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="เช่น 001">
                    <p class="text-xs text-gray-500 mt-1">ระบบจะรันตัวเลขต่อท้ายให้อัติโนมัติ</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ห้องสอบ</label>
                    <input type="text" id="autoRoom" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="เช่น อาคาร 1 ห้อง 101">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">วันสอบ</label>
                    <input type="text" id="autoDate" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="เช่น 24 มีนาคม 2567">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">จำนวนคนต่อห้อง (ถ้ามี)</label>
                    <input type="number" id="autoRoomMax" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="เช่น 25">
                    <p class="text-xs text-gray-500 mt-1">หากระบุ ระบบจะเปลี่ยนห้องสอบให้อัตโนมัติเมื่อครบจำนวน</p>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-800 flex justify-end space-x-3">
                <button onclick="closeModal('autoGenerateModal')" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">ยกเลิก</button>
                <button onclick="applyAutoGenerate()" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-lg hover:shadow-lg transition-all">
                    <i class="fas fa-check mr-2"></i> ตกลง
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentData = [];

function loadTable() {
    const typeId = $('#typeFilter').val();
    if (!typeId) return;

    // Show loading
    $('#examTable tbody').html('<tr><td colspan="8" class="text-center py-8"><div class="loader mx-auto"></div><div class="mt-2 text-gray-500">กำลังโหลดข้อมูล...</div></td></tr>');
    $('#bulkTools').addClass('hidden');

    $.ajax({
        url: 'api/admin/fetch_students_dynamic.php',
        method: 'GET',
        data: { type_id: typeId },
        dataType: 'json',
        success: function(response) {
            currentData = response;
            renderTable(response);
            $('#bulkTools').removeClass('hidden');
        },
        error: function(xhr, status, error) {
            console.error(error);
            $('#examTable tbody').html('<tr><td colspan="8" class="text-center text-red-500 py-4">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>');
        }
    });
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
        // Plans
        let plansHtml = '-';
        if (row.plan_string) {
            const planParts = row.plan_string.split(',')[0].split(':'); // Show first choice
            if (planParts.length === 2) {
                 // We don't have getPlanName map here easily without fetching, 
                 // but typically filtering by type implies a plan context.
                 // For now just show "Plan ID" or maybe I can embed the map in PHP.
                 plansHtml = '<span class="px-2 py-0.5 bg-gray-100 dark:bg-slate-700 rounded text-xs">' + row.plan_string.split(',')[0] + '...</span>';
            }
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
        "drawCallback": function() {
            // Re-bind events if needed, but using direct listeners on inputs is better
            bindInputEvents();
        }
    });
}

function bindInputEvents() {
    $('.exam-input').off('change').on('change', function() {
        const row = $(this).closest('tr');
        const id = row.data('id');
        const statusSpan = row.find('.save-status');
        
        statusSpan.html('<i class="fas fa-spinner fa-spin text-blue-500"></i>');

        const data = {
            id: id,
            seat_number: row.find('input[name="seat_number"]').val(),
            exam_room: row.find('input[name="exam_room"]').val(),
            exam_date: row.find('input[name="exam_date"]').val()
        };

        saveData(data, statusSpan);
    });
}

function saveData(data, statusSpan) {
    $.ajax({
        url: 'api/update_exam_info.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(response) {
            if (response.success) {
                statusSpan.html('<i class="fas fa-check text-green-500"></i>');
                setTimeout(() => { statusSpan.html('<i class="fas fa-check-circle text-gray-400"></i>'); }, 2000);
            } else {
                statusSpan.html('<i class="fas fa-exclamation-circle text-red-500" title="'+response.message+'"></i>');
            }
        },
        error: function() {
            statusSpan.html('<i class="fas fa-times text-red-500"></i>');
        }
    });
}

function saveAll() {
    Swal.fire({
        title: 'กำลังบันทึก...',
        text: 'กรุณารอสักครู่',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    const requests = [];
    $('#examTable tbody tr').each(function() {
        const row = $(this);
        const data = {
            id: row.data('id'),
            seat_number: row.find('input[name="seat_number"]').val(),
            exam_room: row.find('input[name="exam_room"]').val(),
            exam_date: row.find('input[name="exam_date"]').val()
        };
        
        // Only push request, individual rows will trigger their own change event if I used trigger, 
        // but here let's just send all.
        // Actually, to avoid flooding, we could just send the ones that changed. 
        // For simplicity of "Save All" button (which might be redundant if we save on change),
        // let's make it a bulk update loop.
        
        requests.push($.ajax({
            url: 'api/update_exam_info.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data)
        }));
    });

    Promise.all(requests).then(() => {
        Swal.fire('บันทึกสำเร็จ', '', 'success');
    }).catch(() => {
        Swal.fire('เกิดข้อผิดพลาบางรายการ', '', 'warning');
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
    let room = $('#autoRoom').val().trim(); // Let is mutable
    const date = $('#autoDate').val().trim();
    const maxPerRoom = parseInt($('#autoRoomMax').val().trim()) || 0;

    if (!room && !date && !startSeat) {
        Swal.fire('แจ้งเตือน', 'กรุณากรอกข้อมูลอย่างน้อย 1 ช่อง', 'warning');
        return;
    }

    // Parse Start Seat logic
    let prefix = "";
    let number = 0;
    let width = 0;

    if (startSeat) {
        const match = startSeat.match(/(\d+)$/);
        if (match) {
            number = parseInt(match[0]);
            width = match[0].length;
            prefix = startSeat.slice(0, -match[0].length);
        } else {
            prefix = startSeat; 
        }
    }

    // Parse Room logic
    let roomPrefix = "";
    let roomNumber = 0;
    let roomWidth = 0;
    
    if (room && maxPerRoom > 0) {
        const match = room.match(/(\d+)$/);
        if (match) {
            roomNumber = parseInt(match[0]);
            roomWidth = match[0].length;
            roomPrefix = room.slice(0, -match[0].length);
        } else {
            roomPrefix = room; // No number to increment
        }
    }

    let currentRoomCount = 0;

    // Loop through table inputs
    $('#examTable tbody tr').each(function(index) {
        const row = $(this);
        
        // Seat
        if (startSeat) {
            let currentNum = number + index;
            let currentNumStr = currentNum.toString();
            if (width > currentNumStr.length) {
                currentNumStr = currentNumStr.padStart(width, '0');
            }
            row.find('input[name="seat_number"]').val(prefix + currentNumStr).trigger('change');
        }

        // Room
        if (room) {
            // Check capacity
            if (maxPerRoom > 0 && roomNumber > 0) {
                // Determine next room number based on index
                const roomIndex = Math.floor(index / maxPerRoom);
                let currentRn = roomNumber + roomIndex;
                let currentRnStr = currentRn.toString();
                if (roomWidth > currentRnStr.length) {
                    currentRnStr = currentRnStr.padStart(roomWidth, '0');
                }
                const effectiveRoom = roomPrefix + currentRnStr;
                row.find('input[name="exam_room"]').val(effectiveRoom).trigger('change');
            } else {
                row.find('input[name="exam_room"]').val(room).trigger('change');
            }
        }

        // Date
        if (date) {
            row.find('input[name="exam_date"]').val(date).trigger('change');
        }
    });

    closeModal('autoGenerateModal');
    
    // Notify user to save
    Swal.fire({
        title: 'สร้างข้อมูลเรียบร้อย',
        text: 'ข้อมูลถูกกรอกลงในตารางแล้ว กรุณาตรวจสอบก่อนกด "บันทึกทั้งหมด" (หรือระบบได้บันทึกให้อัตโนมัติแล้วหากทำทีละรายการ)',
        icon: 'success',
        timer: 3000
    });
}
</script>

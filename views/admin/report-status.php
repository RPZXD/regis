
<!-- Admin Report Status View -->
<div class="space-y-6">
    <!-- Page Header -->
    <div class="glass rounded-2xl p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl shadow-lg">
                    <i class="fas fa-chart-pie text-2xl text-white"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">สถานะการรายงานตัว</h2>
                    <p class="text-gray-600 dark:text-gray-400">ดูสถานะการยืนยัน/สละสิทธิ์ และจัดการตัวสำรอง</p>
                </div>
            </div>
            <div class="flex gap-2">
                <select id="filterType" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-sm">
                    <option value="">-- ประเภททั้งหมด --</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="glass rounded-xl p-4 flex items-center space-x-3">
            <div class="w-12 h-12 flex items-center justify-center bg-green-100 dark:bg-green-900/30 rounded-lg">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-green-600" id="confirmedCount">0</p>
                <p class="text-sm text-gray-500">ยืนยันแล้ว</p>
            </div>
        </div>
        <div class="glass rounded-xl p-4 flex items-center space-x-3">
            <div class="w-12 h-12 flex items-center justify-center bg-red-100 dark:bg-red-900/30 rounded-lg">
                <i class="fas fa-user-times text-red-500 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-red-600" id="cancelledCount">0</p>
                <p class="text-sm text-gray-500">สละสิทธิ์</p>
            </div>
        </div>
        <div class="glass rounded-xl p-4 flex items-center space-x-3">
            <div class="w-12 h-12 flex items-center justify-center bg-amber-100 dark:bg-amber-900/30 rounded-lg">
                <i class="fas fa-clock text-amber-500 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-amber-600" id="pendingCount">0</p>
                <p class="text-sm text-gray-500">รอรายงานตัว</p>
            </div>
        </div>
        <div class="glass rounded-xl p-4 flex items-center space-x-3">
            <div class="w-12 h-12 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                <i class="fas fa-users text-blue-500 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-blue-600" id="totalCount">0</p>
                <p class="text-sm text-gray-500">ผ่านตรวจทั้งหมด</p>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="glass rounded-2xl overflow-hidden">
        <div class="flex border-b border-gray-200 dark:border-gray-700">
            <button onclick="showTab('confirmed')" id="tab-confirmed" class="flex-1 py-4 px-6 text-center font-bold transition-all tab-active">
                <i class="fas fa-check-circle mr-2 text-green-500"></i>ยืนยันแล้ว
            </button>
            <button onclick="showTab('cancelled')" id="tab-cancelled" class="flex-1 py-4 px-6 text-center font-bold transition-all">
                <i class="fas fa-user-times mr-2 text-red-500"></i>สละสิทธิ์
            </button>
            <button onclick="showTab('pending')" id="tab-pending" class="flex-1 py-4 px-6 text-center font-bold transition-all">
                <i class="fas fa-clock mr-2 text-amber-500"></i>รอรายงานตัว
            </button>
        </div>

        <!-- Tables -->
        <div class="p-6">
            <div id="content-confirmed" class="tab-content">
                <table class="w-full text-sm" id="confirmedTable">
                    <thead>
                        <tr class="text-left text-gray-600 dark:text-gray-400 border-b border-gray-200">
                            <th class="px-3 py-3">ลำดับ</th>
                            <th class="px-3 py-3">ชื่อ-นามสกุล</th>
                            <th class="px-3 py-3">เลขบัตร</th>
                            <th class="px-3 py-3">ประเภท</th>
                            <th class="px-3 py-3">แผนการเรียน</th>
                            <th class="px-3 py-3">วันที่ยืนยัน</th>
                        </tr>
                    </thead>
                    <tbody id="confirmedTableBody"></tbody>
                </table>
            </div>

            <div id="content-cancelled" class="tab-content hidden">
                <table class="w-full text-sm" id="cancelledTable">
                    <thead>
                        <tr class="text-left text-gray-600 dark:text-gray-400 border-b border-gray-200">
                            <th class="px-3 py-3">ลำดับ</th>
                            <th class="px-3 py-3">ชื่อ-นามสกุล</th>
                            <th class="px-3 py-3">เลขบัตร</th>
                            <th class="px-3 py-3">ประเภท</th>
                            <th class="px-3 py-3">แผนการเรียน</th>
                            <th class="px-3 py-3">วันที่สละสิทธิ์</th>
                        </tr>
                    </thead>
                    <tbody id="cancelledTableBody"></tbody>
                </table>
            </div>

            <div id="content-pending" class="tab-content hidden">
                <table class="w-full text-sm" id="pendingTable">
                    <thead>
                        <tr class="text-left text-gray-600 dark:text-gray-400 border-b border-gray-200">
                            <th class="px-3 py-3">ลำดับ</th>
                            <th class="px-3 py-3">ชื่อ-นามสกุล</th>
                            <th class="px-3 py-3">เลขบัตร</th>
                            <th class="px-3 py-3">ประเภท</th>
                            <th class="px-3 py-3">แผนการเรียน</th>
                            <th class="px-3 py-3">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="pendingTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.tab-active {
    background: linear-gradient(to right, rgba(139, 92, 246, 0.1), rgba(99, 102, 241, 0.1));
    border-bottom: 3px solid #8b5cf6;
    color: #7c3aed;
}
</style>

<script>
let currentTab = 'confirmed';
let allData = { confirmed: [], cancelled: [], pending: [] };

function showTab(tab) {
    currentTab = tab;
    $('.tab-content').addClass('hidden');
    $(`#content-${tab}`).removeClass('hidden');
    
    $('[id^="tab-"]').removeClass('tab-active');
    $(`#tab-${tab}`).addClass('tab-active');
}

function loadData() {
    const typeId = $('#filterType').val();
    
    $.get('api/get_report_status.php', { type_id: typeId }, function(response) {
        allData = response;
        
        // Update stats
        $('#confirmedCount').text(response.confirmed.length);
        $('#cancelledCount').text(response.cancelled.length);
        $('#pendingCount').text(response.pending.length);
        $('#totalCount').text(response.confirmed.length + response.cancelled.length + response.pending.length);
        
        // Populate types filter
        if (response.types && $('#filterType option').length <= 1) {
            response.types.forEach(t => {
                $('#filterType').append(`<option value="${t.id}">${t.grade_name} - ${t.name}</option>`);
            });
        }
        
        renderTables();
    });
}

function renderTables() {
    // Confirmed Table
    let html = '';
    if (allData.confirmed.length === 0) {
        html = '<tr><td colspan="6" class="text-center py-8 text-gray-400">ไม่มีข้อมูล</td></tr>';
    } else {
        allData.confirmed.forEach((s, i) => {
            html += `<tr class="border-b border-gray-100 hover:bg-gray-50 dark:hover:bg-slate-700/50">
                <td class="px-3 py-3">${i+1}</td>
                <td class="px-3 py-3 font-medium">${s.fullname}</td>
                <td class="px-3 py-3 font-mono text-sm">${s.citizenid}</td>
                <td class="px-3 py-3 text-xs">${s.typeregis || '-'}</td>
                <td class="px-3 py-3">${s.plan_name || '-'}</td>
                <td class="px-3 py-3 text-xs text-gray-500">${s.update_at || '-'}</td>
            </tr>`;
        });
    }
    $('#confirmedTableBody').html(html);
    
    // Cancelled Table
    html = '';
    if (allData.cancelled.length === 0) {
        html = '<tr><td colspan="6" class="text-center py-8 text-gray-400">ไม่มีข้อมูล</td></tr>';
    } else {
        allData.cancelled.forEach((s, i) => {
            html += `<tr class="border-b border-gray-100 hover:bg-gray-50 dark:hover:bg-slate-700/50">
                <td class="px-3 py-3">${i+1}</td>
                <td class="px-3 py-3 font-medium">${s.fullname}</td>
                <td class="px-3 py-3 font-mono text-sm">${s.citizenid}</td>
                <td class="px-3 py-3 text-xs">${s.typeregis || '-'}</td>
                <td class="px-3 py-3">${s.plan_name || '-'}</td>
                <td class="px-3 py-3 text-xs text-gray-500">${s.update_at || '-'}</td>
            </tr>`;
        });
    }
    $('#cancelledTableBody').html(html);
    
    // Pending Table
    html = '';
    if (allData.pending.length === 0) {
        html = '<tr><td colspan="6" class="text-center py-8 text-gray-400">ไม่มีข้อมูล</td></tr>';
    } else {
        allData.pending.forEach((s, i) => {
            html += `<tr class="border-b border-gray-100 hover:bg-gray-50 dark:hover:bg-slate-700/50">
                <td class="px-3 py-3">${i+1}</td>
                <td class="px-3 py-3 font-medium">${s.fullname}</td>
                <td class="px-3 py-3 font-mono text-sm">${s.citizenid}</td>
                <td class="px-3 py-3 text-xs">${s.typeregis || '-'}</td>
                <td class="px-3 py-3">${s.plan_name || '-'}</td>
                <td class="px-3 py-3">
                    <button onclick="callReserve(${s.id})" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded-lg">
                        <i class="fas fa-phone mr-1"></i>เรียกตัวสำรอง
                    </button>
                </td>
            </tr>`;
        });
    }
    $('#pendingTableBody').html(html);
}

function callReserve(id) {
    Swal.fire({
        title: 'เรียกตัวสำรอง',
        text: 'ต้องการแจ้งเตือนนักเรียนให้มารายงานตัวหรือไม่?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'ใช่',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            // TODO: Implement notification logic (SMS/Email)
            Swal.fire('สำเร็จ', 'แจ้งเตือนนักเรียนเรียบร้อยแล้ว (จำลอง)', 'success');
        }
    });
}

$('#filterType').on('change', loadData);

$(document).ready(function() {
    loadData();
});
</script>

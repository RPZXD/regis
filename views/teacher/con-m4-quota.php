<!-- Con M4 Quota View - Report confirmation -->
<div class="space-y-6">
    <div class="glass rounded-2xl p-6 border-l-4 border-amber-500">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg shadow-amber-500/30">
                <i class="fas fa-tasks text-2xl text-white"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">รายงานตัว ม.4 (โควต้า ม.3 เดิม)</h2>
                <p class="text-gray-600 dark:text-gray-400">จัดการสถานะการยืนยันสิทธิ์</p>
            </div>
        </div>
    </div>
    <div class="glass rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="record_table">
                <thead><tr class="text-white text-xs">
                    <th class="px-2 py-3">ลำดับ</th>
                    <th class="px-2 py-3">รายงานตัว</th>
                    <th class="px-2 py-3">เลขบัตร</th>
                    <th class="px-2 py-3">ชื่อ-นามสกุล</th>
                    <th class="px-2 py-3">วันเกิด</th>
                    <th class="px-2 py-3">เบอร์โทร</th>
                    <th class="px-2 py-3">GPA</th>
                    <th class="px-2 py-3">เลขประจำตัว</th>
                    <th class="px-2 py-3">แผน1</th>
                    <th class="px-2 py-3">แผน2</th>
                    <th class="px-2 py-3">แผน3</th>
                    <th class="px-2 py-3">จัดการ</th>
                </tr></thead>
                <tbody class="text-gray-700 dark:text-gray-300"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black/50" onclick="closeModal()"></div>
        <div class="relative glass rounded-2xl max-w-md w-full p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">แก้ไขสถานะรายงานตัว</h3>
            <form id="editForm">
                <input type="hidden" id="editNumreg" name="numreg">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">สถานะการรายงานตัว:</label>
                    <select id="editStatus" name="status" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                        <option value="0">รอการยืนยัน</option>
                        <option value="1">ยืนยันสิทธิ์</option>
                        <option value="9">สละสิทธิ์</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">ปิด</button>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-primary-500 to-indigo-600 text-white rounded-lg">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal() { document.getElementById('editModal').classList.remove('hidden'); }
function closeModal() { document.getElementById('editModal').classList.add('hidden'); }

function getPlanName(n) {
    const plans = { 1:'ห้อง2-Coding', 2:'ห้อง3-วิทย์', 3:'ห้อง4-วิทย์คณิต', 4:'ห้อง5-ศิลป์สังคม', 5:'ห้อง6-ภาษา', 6:'ห้อง7-อาหาร', 7:'ห้อง7-เกษตร', 8:'ห้อง7-อุตสาหกรรม' };
    return plans[n] || '-';
}

function loadTable() {
    $.ajax({
        url: 'api/fetch_m4quota_con.php', method: 'GET', dataType: 'json',
        success: function(response) {
            $('#record_table').DataTable().clear().destroy();
            $('#record_table tbody').empty();
            if (response.length === 0) {
                $('#record_table tbody').append('<tr><td colspan="12" class="text-center py-8">ไม่พบข้อมูล</td></tr>');
            } else {
                $.each(response, function(i, r) {
                    var statusBadge = r.confirm_status == 1 ? '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-600">ยืนยัน</span>' :
                                      r.confirm_status == 9 ? '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-600">สละสิทธิ์</span>' :
                                      '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-600">รอยืนยัน</span>';
                    $('#record_table tbody').append('<tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 text-xs">' +
                        '<td class="px-2 py-2 text-center">' + r.no + '</td>' +
                        '<td class="px-2 py-2 text-center">' + statusBadge + '</td>' +
                        '<td class="px-2 py-2 text-center font-mono">' + r.citizenid + '</td>' +
                        '<td class="px-2 py-2">' + r.fullname + '</td>' +
                        '<td class="px-2 py-2 text-center">' + r.birthday + '</td>' +
                        '<td class="px-2 py-2 text-center">' + r.now_tel + '</td>' +
                        '<td class="px-2 py-2 text-center font-bold">' + r.gpa_total + '</td>' +
                        '<td class="px-2 py-2 text-center">' + r.old_school_stuid + '</td>' +
                        '<td class="px-2 py-2 text-center">' + getPlanName(r.number1) + '</td>' +
                        '<td class="px-2 py-2 text-center">' + getPlanName(r.number2) + '</td>' +
                        '<td class="px-2 py-2 text-center">' + getPlanName(r.number3) + '</td>' +
                        '<td class="px-2 py-2 text-center"><button class="px-2 py-1 bg-blue-500 text-white text-xs rounded edit-btn" data-numreg="' + r.numreg + '" data-status="' + r.confirm_status + '">แก้ไข</button></td></tr>');
                });
            }
            $('#record_table').DataTable({ pageLength: 10, responsive: true, dom: 'Bfrtip', buttons: [{ extend: 'excelHtml5', text: 'Export', className: 'px-3 py-1 bg-green-500 text-white rounded text-sm' }] });
        }
    });
}

$(document).ready(function() {
    loadTable();
    $(document).on('click', '.edit-btn', function() {
        $('#editNumreg').val($(this).data('numreg'));
        $('#editStatus').val($(this).data('status'));
        openModal();
    });
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        $.post('api/update_confirm_status.php', $(this).serialize(), function() {
            closeModal();
            Swal.fire('สำเร็จ', '', 'success');
            loadTable();
        });
    });
});
</script>

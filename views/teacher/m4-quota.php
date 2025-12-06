<!-- M4 Quota Student List View -->
<div class="space-y-6">
    <div class="glass rounded-2xl p-6 border-l-4 border-amber-500">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg shadow-amber-500/30">
                <i class="fas fa-award text-2xl text-white"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">นักเรียนที่สมัครชั้นมัธยมศึกษาปีที่ 4</h2>
                <p class="text-gray-600 dark:text-gray-400">โควต้า ม.3 เดิม</p>
            </div>
        </div>
    </div>
    <div class="glass rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="record_table">
                <thead><tr class="text-white">
                    <th class="px-3 py-3 text-center">ลำดับ</th>
                    <th class="px-3 py-3 text-center">เลขบัตรประชาชน</th>
                    <th class="px-3 py-3 text-center">ชื่อ - นามสกุล</th>
                    <th class="px-3 py-3 text-center">ห้องเดิม</th>
                    <th class="px-3 py-3 text-center">สถานะ</th>
                    <th class="px-3 py-3 text-center">จัดการ</th>
                </tr></thead>
                <tbody class="text-gray-700 dark:text-gray-300"></tbody>
            </table>
        </div>
    </div>
</div>
<script>
function loadTable() {
    $.ajax({
        url: 'api/fetch_m4quota.php', method: 'GET', dataType: 'json',
        success: function(response) {
            $('#record_table').DataTable().clear().destroy();
            $('#record_table tbody').empty();
            if (response.length === 0) {
                $('#record_table tbody').append('<tr><td colspan="6" class="text-center py-8">ไม่พบข้อมูล</td></tr>');
            } else {
                $.each(response, function(i, r) {
                    var statusBadge = r.status_confirm == 1 ? '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-600">ยืนยัน</span>' : 
                                      r.status_confirm == 9 ? '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-600">สละสิทธิ์</span>' :
                                      '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-600">รอยืนยัน</span>';
                    $('#record_table tbody').append('<tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">' +
                        '<td class="px-3 py-2 text-center">' + (i + 1) + '</td>' +
                        '<td class="px-3 py-2 text-center font-mono">' + r.citizenid + '</td>' +
                        '<td class="px-3 py-2">' + r.fullname + '</td>' +
                        '<td class="px-3 py-2 text-center">' + (r.old_room || '-') + '</td>' +
                        '<td class="px-3 py-2 text-center">' + statusBadge + '</td>' +
                        '<td class="px-3 py-2 text-center"><button class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg edit-btn" data-id="' + r.id + '"><i class="fas fa-edit"></i></button></td></tr>');
                });
            }
            $('#record_table').DataTable({ pageLength: 10, responsive: true, dom: 'Bfrtip', buttons: [{ extend: 'excelHtml5', text: '<i class="fas fa-file-excel mr-2"></i>Export', className: 'px-4 py-2 bg-green-500 text-white rounded-lg' }] });
        }
    });
}
$(document).ready(function() { loadTable(); });
</script>

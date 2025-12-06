<!-- Check M4 Evidence View -->
<div class="space-y-6">
    <div class="glass rounded-2xl p-6 border-l-4 border-cyan-500">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-cyan-500 to-teal-600 rounded-xl shadow-lg shadow-cyan-500/30">
                    <i class="fas fa-check-double text-2xl text-white"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">ตรวจหลักฐาน ม.4 รอบทั่วไป</h2>
                    <p class="text-gray-600 dark:text-gray-400">คลิกที่ภาพเพื่อตรวจสอบหลักฐาน</p>
                </div>
            </div>
        </div>
    </div>
    <div class="glass rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-xs" id="record_table">
                <thead><tr class="text-white text-[10px]">
                    <th class="px-2 py-2">ลำดับ</th>
                    <th class="px-2 py-2">เลขบัตร</th>
                    <th class="px-2 py-2">สถานะ</th>
                    <th class="px-2 py-2">ชื่อ-นามสกุล</th>
                    <th class="px-2 py-2">เบอร์โทร</th>
                    <th class="px-2 py-2">1.ใบสมัคร</th>
                    <th class="px-2 py-2">2.บัตรปชช</th>
                    <th class="px-2 py-2">3.ทะเบียนบ้าน</th>
                    <th class="px-2 py-2">4.ผลการเรียน</th>
                    <th class="px-2 py-2">5.รูปถ่าย</th>
                    <th class="px-2 py-2">จัดการ</th>
                </tr></thead>
                <tbody class="text-gray-700 dark:text-gray-300"></tbody>
            </table>
        </div>
    </div>
</div>
<script>
function loadTable() {
    $.ajax({
        url: 'api/fetch_m4nomal_check.php', method: 'GET', dataType: 'json',
        success: function(response) {
            $('#record_table').DataTable().clear().destroy();
            $('#record_table tbody').empty();
            if (response.length === 0) {
                $('#record_table tbody').append('<tr><td colspan="11" class="text-center py-8">ไม่พบข้อมูล</td></tr>');
            } else {
                $.each(response, function(i, r) {
                    var statusBadge = r.status == 0 ? '<span class="px-2 py-1 text-[10px] rounded-full bg-yellow-400 text-white">รอตรวจ</span>' :
                                      r.status == 1 ? '<span class="px-2 py-1 text-[10px] rounded-full bg-green-500 text-white">เรียบร้อย</span>' :
                                      '<span class="px-2 py-1 text-[10px] rounded-full bg-red-500 text-white">แก้ไข</span>';
                    var row = '<tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 text-[11px]">' +
                        '<td class="px-2 py-1 text-center">' + (i + 1) + '</td>' +
                        '<td class="px-2 py-1 text-center font-mono">' + r.citizenid + '</td>' +
                        '<td class="px-2 py-1 text-center">' + statusBadge + '</td>' +
                        '<td class="px-2 py-1">' + r.fullname + '</td>' +
                        '<td class="px-2 py-1 text-center">' + r.now_tel + '</td>';
                    for (var j = 1; j <= 5; j++) {
                        var path = r['upload_path' + j];
                        if (path) {
                            row += '<td class="px-1 py-1 text-center"><img src="../uploads/' + r.citizenid + '/' + path + '" class="w-16 h-16 object-cover mx-auto rounded"/></td>';
                        } else {
                            row += '<td class="px-1 py-1 text-center">-</td>';
                        }
                    }
                    row += '<td class="px-2 py-1 text-center"><button class="px-2 py-1 bg-blue-500 text-white text-[10px] rounded">แก้ไข</button></td></tr>';
                    $('#record_table tbody').append(row);
                });
            }
            $('#record_table').DataTable({ pageLength: 10, responsive: true });
        }
    });
}
$(document).ready(function() { loadTable(); });
</script>

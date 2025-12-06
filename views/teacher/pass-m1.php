<!-- Pass M1 Normal View - Students who passed verification -->
<div class="space-y-6">
    <div class="glass rounded-2xl p-6 border-l-4 border-emerald-500">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-clipboard-check text-2xl text-white"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">นักเรียนที่ผ่านการตรวจ ม.1</h2>
                    <p class="text-gray-600 dark:text-gray-400">รอบทั่วไป</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <label class="text-sm text-gray-600 dark:text-gray-400">กรองตามวันที่:</label>
                <input type="date" id="filter_date" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
            </div>
        </div>
    </div>
    <div class="glass rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="record_table">
                <thead><tr class="text-white">
                    <th class="px-3 py-3">ลำดับ</th>
                    <th class="px-3 py-3">เลขบัตร</th>
                    <th class="px-3 py-3">สถานะ</th>
                    <th class="px-3 py-3">ประเภท</th>
                    <th class="px-3 py-3">ชื่อ-นามสกุล</th>
                    <th class="px-3 py-3">เบอร์โทร</th>
                    <th class="px-3 py-3">เบอร์ผู้ปกครอง</th>
                    <th class="px-3 py-3">วันสมัคร</th>
                    <th class="px-3 py-3">รร.เดิม</th>
                    <th class="px-3 py-3">เวลาผ่านตรวจ</th>
                </tr></thead>
                <tbody class="text-gray-700 dark:text-gray-300"></tbody>
            </table>
        </div>
    </div>
</div>
<script>
$(document).ready(function() { $('#filter_date').on('change', loadTable); loadTable(); });

function formatThaiDate(date) {
    if (!date) return '-';
    const months = ["ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
    const d = new Date(date);
    return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear() + 543}`;
}

function loadTable() {
    $.ajax({
        url: 'api/fetch_m1nomal_pass.php', method: 'GET', data: { date: $('#filter_date').val() }, dataType: 'json',
        success: function(response) {
            if ($.fn.DataTable.isDataTable('#record_table')) $('#record_table').DataTable().clear().destroy();
            $('#record_table tbody').empty();
            if (response.length === 0) {
                $('#record_table tbody').append('<tr><td colspan="10" class="text-center py-8">ไม่พบข้อมูล</td></tr>');
            } else {
                $.each(response, function(i, r) {
                    var statusBadge = r.status == 1 ? '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-600">เรียบร้อย</span>' :
                                      r.status == 0 ? '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-600">รอตรวจสอบ</span>' :
                                      '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-600">แก้ไข</span>';
                    $('#record_table tbody').append('<tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">' +
                        '<td class="px-3 py-2 text-center">' + (i + 1) + '</td>' +
                        '<td class="px-3 py-2 text-center font-mono">' + (r.citizenid || '') + '</td>' +
                        '<td class="px-3 py-2 text-center">' + statusBadge + '</td>' +
                        '<td class="px-3 py-2 text-center">' + (r.typeregis || '') + '</td>' +
                        '<td class="px-3 py-2">' + (r.fullname || '') + '</td>' +
                        '<td class="px-3 py-2 text-center">' + (r.now_tel || '') + '</td>' +
                        '<td class="px-3 py-2 text-center">' + (r.parent_tel || '') + '</td>' +
                        '<td class="px-3 py-2 text-center">' + (r.create_at || '') + '</td>' +
                        '<td class="px-3 py-2 text-center">' + (r.old_school || '') + '</td>' +
                        '<td class="px-3 py-2 text-center">' + formatThaiDate(r.update_at) + '</td></tr>');
                });
            }
            $('#record_table').DataTable({ pageLength: 10, responsive: true, dom: 'Bfrtip', buttons: [{ extend: 'excelHtml5', text: '<i class="fas fa-file-excel mr-2"></i>Export', className: 'px-4 py-2 bg-green-500 text-white rounded-lg' }] });
        }
    });
}
</script>

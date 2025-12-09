
<!-- Dynamic Pass Students View -->
<div class="space-y-6">
    <!-- Page Header -->
    <div class="glass rounded-2xl p-6 border-l-4 border-green-500">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg">
                <i class="fas fa-clipboard-check text-2xl text-white"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">ผ่านการตรวจ <?php echo $regisType['grade_name']; ?></h2>
                <p class="text-gray-600 dark:text-gray-400"><?php echo $regisType['name']; ?></p>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="glass rounded-2xl p-6">
        <div class="flex justify-end mb-4">
            <div class="flex items-center space-x-2">
                <label for="dateFilter" class="text-sm font-medium text-gray-700 dark:text-gray-300">วันที่อนุมัติ:</label>
                <input type="date" id="dateFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                <button onclick="loadTable()" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-md transition-colors">
                    <i class="fas fa-search me-1"></i> ค้นหา
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="pass_table">
                <thead>
                    <tr class="text-white">
                        <th class="px-3 py-3 text-center">ลำดับ</th>
                        <th class="px-3 py-3 text-center">รหัสประจำตัว</th>
                        <th class="px-3 py-3 text-center">ชื่อ - นามสกุล</th>
                        <th class="px-3 py-3 text-center">เบอร์โทร</th>
                        <th class="px-3 py-3 text-center">GPA</th>
                        <th class="px-3 py-3 text-center">แผนการเรียนที่ได้</th>
                        <th class="px-3 py-3 text-center">วันที่และเวลาอนุมัติ</th>
                        <th class="px-3 py-3 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-300"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
const typeId = <?php echo $typeId; ?>;
const availablePlans = <?php echo json_encode($availablePlans); ?>;

function loadTable() {
    const date = $('#dateFilter').val();
    let url = 'api/admin/fetch_students_pass_dynamic.php?type_id=' + typeId;
    if (date) {
        url += '&date=' + date;
    }

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#pass_table').DataTable().clear().destroy();
            $('#pass_table tbody').empty();

            if (response.length === 0) {
                $('#pass_table tbody').append('<tr><td colspan="8" class="text-center py-8">ไม่พบข้อมูล</td></tr>');
            } else {
                $.each(response, function(index, record) {
                    // Plan Dropdown
                    let planSelect = `<select class="w-full text-xs border rounded p-1 plan-select" data-id="${record.id}" onchange="updateFinalPlan(${record.id}, this.value)">`;
                    planSelect += `<option value="">- เลือก -</option>`;
                    availablePlans.forEach(plan => {
                        const selected = (record.final_plan_id == plan.id) ? 'selected' : '';
                        planSelect += `<option value="${plan.id}" ${selected}>${plan.name}</option>`;
                    });
                    planSelect += `</select>`;

                    var row = '<tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">' +
                    '<td class="px-3 py-2 text-center">' + (index + 1) + '</td>' +
                    '<td class="px-3 py-2 text-center font-mono">' + (record.citizenid || '-') + '</td>' +
                    '<td class="px-3 py-2">' + (record.fullname || '-') + '</td>' +
                    '<td class="px-3 py-2 text-center">' + (record.now_tel || '-') + '</td>' +
                    '<td class="px-3 py-2 text-center font-bold">' + (record.gpa_total || '-') + '</td>' +
                    '<td class="px-3 py-2" style="min-width: 150px;">' + planSelect + '</td>' +
                    '<td class="px-3 py-2 text-center">' + (record.update_at || '-') + '</td>' + 
                    '<td class="px-3 py-2 text-center">' + 
                        '<a href="../print.php?id=' + record.id + '" target="_blank" class="px-3 py-1 bg-gray-500 hover:bg-gray-600 text-white text-xs rounded-lg mr-1"><i class="fas fa-print"></i> พิมพ์ใบสมัคร</a>' +
                    '</td></tr>';
                    $('#pass_table tbody').append(row);
                });
            }

            $('#pass_table').DataTable({
                "pageLength": 10,
                "responsive": true,
                "dom": 'Bfrtip',
                "buttons": ['excelHtml5', 'print']
            });
        },
        error: function() { /* Handle error gracefully */ }
    });
}

function updateFinalPlan(studentId, planId) {
    $.ajax({
        url: 'api/update_final_plan.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ id: studentId, final_plan_id: planId }),
        success: function(response) {
            if (response.success) {
                const Toast = Swal.mixin({
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 1500, timerProgressBar: true
                });
                Toast.fire({ icon: 'success', title: 'บันทึกสำเร็จ' });
            } else {
                Swal.fire('Error', 'บันทึกไม่สำเร็จ', 'error');
            }
        }
    });
}

$(document).ready(function() {
    loadTable();
});
</script>

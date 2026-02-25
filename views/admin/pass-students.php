<!-- Dynamic Pass Students View -->
<div class="space-y-6">
    <!-- Page Header -->
    <div class="glass rounded-2xl p-6 border-l-4 border-green-500">
        <div class="flex items-center space-x-4">
            <div
                class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg">
                <i class="fas fa-clipboard-check text-2xl text-white"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">ผ่านการตรวจ
                    <?php echo $regisType['grade_name']; ?>
                </h2>
                <p class="text-gray-600 dark:text-gray-400"><?php echo $regisType['name']; ?></p>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="glass rounded-2xl p-6">
        <div class="flex justify-end mb-4">
            <div class="flex items-center space-x-2">
                <label for="dateFilter"
                    class="text-sm font-medium text-gray-700 dark:text-gray-300">วันที่อนุมัติ:</label>
                <input type="date" id="dateFilter"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                <button onclick="loadTable()"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-md transition-colors">
                    <i class="fas fa-search me-1"></i> ค้นหา
                </button>
                <button onclick="document.getElementById('csvUploadInput').click()"
                    class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg shadow-md transition-colors"
                    title="อัพโหลดไฟล์ CSV ที่มีคอลัมน์ citizenid, final_plan_id, pass_rank, is_called เพื่ออัพเดตข้อมูลรวดเดียว">
                    <i class="fas fa-file-upload me-1"></i> นำเข้า CSV
                </button>
                <input type="file" id="csvUploadInput" accept=".csv" class="hidden" onchange="uploadCSV(this)">

                <button onclick="exportCSV()"
                    class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg shadow-md transition-colors"
                    title="ดาวน์โหลดข้อมูลเป็นไฟล์ CSV เพื่อนำไปแก้ไข">
                    <i class="fas fa-file-download me-1"></i> ส่งออก CSV
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
                        <th class="px-3 py-3 text-center">ลำดับที่สอบได้</th>
                        <th class="px-3 py-3 text-center">สถานะ/เรียกตัว</th>
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
            cache: false,
            success: function (response) {
                $('#pass_table').DataTable().clear().destroy();
                $('#pass_table tbody').empty();

                if (response.length === 0) {
                    $('#pass_table tbody').append('<tr><td colspan="10" class="text-center py-8">ไม่พบข้อมูล</td></tr>');
                } else {
                    $.each(response, function (index, record) {
                        // Plan Dropdown
                        let planSelect = `<select class="w-full text-xs border rounded p-1 plan-select" data-id="${record.id}" onchange="updateFinalPlan(${record.id}, this.value)">`;
                        planSelect += `<option value="">- เลือก -</option>`;
                        availablePlans.forEach(plan => {
                            const selected = (record.final_plan_id == plan.id) ? 'selected' : '';
                            planSelect += `<option value="${plan.id}" ${selected}>${plan.name}</option>`;
                        });
                        planSelect += `</select>`;

                        // Checkbox for Called Status
                        let calledToggle = '';
                        if (record.status == 2) {
                            calledToggle = '<span class="px-2 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold whitespace-nowrap"><i class="fas fa-check-circle mr-1"></i> ยืนยันสิทธิ์แล้ว</span>';
                        } else if (record.status == 3) {
                            calledToggle = '<span class="px-2 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-bold whitespace-nowrap"><i class="fas fa-times-circle mr-1"></i> สละสิทธิ์</span>';
                        } else {
                            const isCalledChecked = record.is_called == 1 ? 'checked' : '';
                            calledToggle = `
                            <label class="inline-flex relative items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" ${isCalledChecked} onchange="toggleCallStatus(${record.id}, this.checked)">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            </label>`;
                        }

                        var row = '<tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">' +
                            '<td class="px-3 py-2 text-center">' + (index + 1) + '</td>' +
                            '<td class="px-3 py-2 text-center font-mono">' + (record.citizenid || '-') + '</td>' +
                            '<td class="px-3 py-2">' + (record.fullname || '-') + '</td>' +
                            '<td class="px-3 py-2 text-center">' + (record.now_tel || '-') + '</td>' +
                            '<td class="px-3 py-2 text-center font-bold">' + (record.gpa_total || '-') + '</td>' +
                            '<td class="px-3 py-2" style="min-width: 150px;">' + planSelect + '</td>' +
                            '<td class="px-3 py-2 text-center font-bold text-blue-600">' + (record.pass_rank || '-') + '</td>' +
                            '<td class="px-3 py-2 text-center">' + calledToggle + '</td>' +
                            '<td class="px-3 py-2 text-center">' + (record.update_at || '-') + '</td>' +
                            '<td class="px-3 py-2 text-center">' +
                            '<a href="../print.php?id=' + record.id + '" target="_blank" class="px-3 py-1 bg-gray-500 hover:bg-gray-600 text-white text-xs rounded-lg mr-1"><i class="fas fa-print"></i> พิมพ์</a>' +
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
            error: function () { /* Handle error gracefully */ }
        });
    }

    function updateFinalPlan(studentId, planId) {
        $.ajax({
            url: 'api/update_final_plan.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ id: studentId, final_plan_id: planId }),
            success: function (response) {
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

    function uploadCSV(input) {
        if (!input.files || !input.files[0]) return;

        let formData = new FormData();
        formData.append('csv_file', input.files[0]);
        formData.append('type_id', typeId);

        Swal.fire({
            title: 'กำลังอัพโหลด...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: 'api/upload_ranks_csv.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                input.value = ''; // Reset input
                if (response.success) {
                    Swal.fire('สำเร็จ', 'อัพเดตข้อมูลแผนและลำดับเรียบร้อย จำนวน ' + response.updated + ' รายการ', 'success').then(() => {
                        loadTable();
                    });
                } else {
                    Swal.fire('Error', response.message || 'เกิดข้อผิดพลาดในการอัพโหลด', 'error');
                }
            },
            error: function (xhr) {
                input.value = ''; // Reset input
                Swal.fire('Error', 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์', 'error');
            }
        });
    }

    function exportCSV() {
        const date = $('#dateFilter').val();
        let url = 'api/export_ranks_csv.php?type_id=' + typeId;
        if (date) {
            url += '&date=' + date;
        }
        window.location.href = url;
    }

    function toggleCallStatus(studentId, isCalled) {
        $.ajax({
            url: 'api/update_call_status.php',
            method: 'POST',
            data: JSON.stringify({ id: studentId, is_called: isCalled ? 1 : 0 }),
            contentType: 'application/json',
            success: function (response) {
                if (response.success) {
                    const Toast = Swal.mixin({
                        toast: true, position: 'top-end', showConfirmButton: false, timer: 1500, timerProgressBar: true
                    });
                    Toast.fire({ icon: 'success', title: 'อัพเดตสถานะการเรียกตัวแล้ว' });
                } else {
                    Swal.fire('Error', 'บันทึกไม่สำเร็จ', 'error');
                    loadTable(); // Revert toggle on error
                }
            },
            error: function () {
                Swal.fire('Error', 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์', 'error');
                loadTable(); // Revert toggle on error
            }
        });
    }

    $(document).ready(function () {
        loadTable();
    });
</script>
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
                <button onclick="exportExamCSV()"
                    class="px-6 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg shadow-lg shadow-green-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all font-bold">
                    <i class="fas fa-file-export mr-2"></i> Export CSV
                </button>
                <div class="relative">
                    <input type="file" id="importCSVInput" accept=".csv" class="hidden" onchange="importExamCSV(event)">
                    <button onclick="document.getElementById('importCSVInput').click()"
                        class="px-6 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all font-bold">
                        <i class="fas fa-file-import mr-2"></i> Import CSV
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="glass rounded-2xl p-6 shadow-xl shadow-gray-200/50 dark:shadow-none">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="examTable">
                <thead>
                    <tr class="bg-gray-50 dark:bg-slate-700/50 text-gray-600 dark:text-gray-300">
                        <th class="px-4 py-4 text-center rounded-l-xl w-16">#</th>
                        <th class="px-4 py-4 text-center w-32">เลขประจำตัวผู้สมัคร</th>
                        <th class="px-4 py-4 text-center w-40">เลขบัตรประชาชน</th>
                        <th class="px-4 py-4 text-left">ชื่อ-นามสกุล</th>
                        <th class="px-4 py-4 text-center w-32">เลขที่นั่งสอบ</th>
                        <th class="px-4 py-4 text-center w-40">ห้องสอบ</th>
                        <th class="px-4 py-4 text-center w-32">วันสอบ</th>
                        <th class="px-4 py-4 text-center rounded-r-xl w-32">สถานะ</th>
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

            let statusHtml = '';
            // 0=รอตรวจสอบ, 1=ยืนยันแล้ว, 2=สละสิทธิ์
            if (row.status == 1) {
                statusHtml = '<span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">ยืนยันแล้ว</span>';
            } else if (row.status == 2) {
                statusHtml = '<span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">สละสิทธิ์</span>';
            } else {
                statusHtml = '<span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-semibold">รอตรวจสอบ</span>';
            }

            const tr = `
            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors" data-id="${row.id}">
                <td class="px-4 py-3 text-center text-gray-500">${index + 1}</td>
                <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">${row.numreg || '-'}</td>
                <td class="px-4 py-3 text-center font-mono text-indigo-600">${row.citizenid}</td>
                <td class="px-4 py-3 text-left">${row.fullname}</td>
                <td class="px-4 py-3 text-center font-semibold text-blue-600">${seatVal || '-'}</td>
                <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">${roomVal || '-'}</td>
                <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">${dateVal || '-'}</td>
                <td class="px-4 py-3 text-center">${statusHtml}</td>
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
            }
        });
    }

    function exportExamCSV() {
        const typeId = $('#typeFilter').val();
        if (!typeId) {
            Swal.fire('แจ้งเตือน', 'กรุณาเลือกระดับชั้น/ประเภทก่อน Export', 'warning');
            return;
        }
        window.location.href = API_BASE_URL + 'export_exam_csv.php?type_id=' + typeId;
    }

    function importExamCSV(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Reset input so the same file can be selected again if needed
        event.target.value = '';

        if (!file.name.toLowerCase().endsWith('.csv')) {
            Swal.fire('ข้อผิดพลาด', 'กรุณาอัปโหลดไฟล์นามสกุล .csv เท่านั้น', 'error');
            return;
        }

        Swal.fire({
            title: 'กำลังนำเข้าข้อมูล',
            text: 'รอสักครู่...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const formData = new FormData();
        formData.append('csv_file', file);

        $.ajax({
            url: API_BASE_URL + 'import_exam_csv.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                try {
                    const res = typeof response === 'string' ? JSON.parse(response) : response;
                    if (res.success) {
                        let htmlMsg = `<p>อัปเดตข้อมูลสำเร็จ <b>${res.updated_count}</b> รายการ</p>`;
                        if (res.errors && res.errors.length > 0) {
                            htmlMsg += `<hr class="my-2"><div class="text-xs text-red-500 text-left max-h-40 overflow-y-auto"><b>พบปัญหา:</b><br>${res.errors.join('<br>')}</div>`;
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'นำเข้าสำเร็จ!',
                            html: htmlMsg,
                            confirmButtonColor: '#3085d6',
                        }).then((result) => {
                            if (result.isConfirmed || result.dismiss) {
                                loadTable(); // Refresh table
                            }
                        });
                    } else {
                        Swal.fire('เกิดข้อผิดพลาด', res.error || 'ไม่สามารถนำเข้าข้อมูลได้', 'error');
                    }
                } catch (e) {
                    console.error(e);
                    Swal.fire('เกิดข้อผิดพลาด', 'รูปแบบการตอบกลับจากเซิร์ฟเวอร์ไม่ถูกต้อง', 'error');
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
                let errMsg = 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errMsg = xhr.responseJSON.error;
                }
                Swal.fire('เกิดข้อผิดพลาดระบบ', errMsg, 'error');
            }
        });
    }
</script>
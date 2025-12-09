<!-- M1 Normal Student List View -->
<div class="space-y-6">
    <!-- Page Header -->
    <div class="glass rounded-2xl p-6 border-l-4 border-blue-500">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg shadow-blue-500/30">
                <i class="fas fa-user-graduate text-2xl text-white"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">นักเรียนที่สมัครชั้นมัธยมศึกษาปีที่ 1</h2>
                <p class="text-gray-600 dark:text-gray-400">รอบทั่วไป</p>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="glass rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="record_table">
                <thead>
                    <tr class="text-white">
                        <th class="px-3 py-3 text-center">ลำดับ</th>
                        <th class="px-3 py-3 text-center">เลขบัตรประชาชน</th>
                        <th class="px-3 py-3 text-center">ประเภท</th>
                        <th class="px-3 py-3 text-center">ชื่อ - นามสกุล</th>
                        <th class="px-3 py-3 text-center">วันเกิด</th>
                        <th class="px-3 py-3 text-center">เบอร์โทร</th>
                        <th class="px-3 py-3 text-center">เบอร์ผู้ปกครอง</th>
                        <th class="px-3 py-3 text-center">วันที่สมัคร</th>
                        <th class="px-3 py-3 text-center">GPA</th>
                        <th class="px-3 py-3 text-center">แผน 1</th>
                        <th class="px-3 py-3 text-center">แผน 2</th>
                        <th class="px-3 py-3 text-center">แผน 3</th>
                        <th class="px-3 py-3 text-center">แผน 4</th>
                        <th class="px-3 py-3 text-center">แผน 5</th>
                        <th class="px-3 py-3 text-center">แผน 6</th>
                        <th class="px-3 py-3 text-center">แผน 7</th>
                        <th class="px-3 py-3 text-center">แผน 8</th>
                        <th class="px-3 py-3 text-center">แผน 9</th>
                        <th class="px-3 py-3 text-center">แผน 10</th>
                        <th class="px-3 py-3 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-300"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div id="editStudentModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50" onclick="closeModal('editStudentModal')"></div>
        <div class="relative glass rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">แก้ไขข้อมูลนักเรียน</h3>
                <button onclick="closeModal('editStudentModal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="editStudentForm" class="space-y-4">
                <input type="hidden" id="editStudentId">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">เลขบัตรประชาชน</label>
                        <input type="text" id="editCitizenId" maxlength="13" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ประเภท</label>
                        <select id="editTyperegis" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                            <option value="ในเขต">ในเขตพื้นที่บริการ</option>
                            <option value="นอกเขต">นอกเขตพื้นที่บริการ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">คำนำหน้า</label>
                        <select id="editPrefix" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                            <option value="เด็กชาย">เด็กชาย</option>
                            <option value="เด็กหญิง">เด็กหญิง</option>
                            <option value="นาย">นาย</option>
                            <option value="นางสาว">นางสาว</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ชื่อ</label>
                        <input type="text" id="editFirstName" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">นามสกุล</label>
                        <input type="text" id="editLastName" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">วันเดือนปีเกิด</label>
                        <input type="text" id="editBirthday" placeholder="DD-MM-YYYY" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">เบอร์โทรศัพท์</label>
                        <input type="tel" id="editNowTel" maxlength="10" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">เบอร์ผู้ปกครอง</label>
                        <input type="tel" id="editParentTel" maxlength="10" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">GPA</label>
                        <input type="text" id="editGpaTotal" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <?php for ($i = 1; $i <= 10; $i++): ?>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">แผน <?php echo $i; ?></label>
                        <select id="editNumber<?php echo $i; ?>" class="w-full px-2 py-1 text-xs rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white">
                            <option value="1">ห้อง 3</option>
                            <option value="2">ห้อง 4</option>
                            <option value="3">ห้อง 5</option>
                            <option value="4">ห้อง 6</option>
                            <option value="5">ห้อง 7</option>
                            <option value="6">ห้อง 8</option>
                            <option value="7">ห้อง 9-อุต</option>
                            <option value="8">ห้อง 9-พณ</option>
                            <option value="9">ห้อง 10-กษ</option>
                            <option value="10">ห้อง 10-คห</option>
                            <option value="11">ห้อง 11-ศิลปะ</option>
                            <option value="12">ห้อง 11-ดนตรี</option>
                            <option value="13">ห้อง 11-นาฏ</option>
                            <option value="14">ห้อง 12-ฟุตบอล</option>
                            <option value="15">ห้อง 12-วู้ดบอล</option>
                        </select>
                    </div>
                    <?php endfor; ?>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeModal('editStudentModal')" class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">ยกเลิก</button>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-primary-500 to-indigo-600 text-white font-bold rounded-lg hover:shadow-lg transition-all">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

function loadTable() {
    $.ajax({
        url: 'api/fetch_m1nomal.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#record_table').DataTable().clear().destroy();
            $('#record_table tbody').empty();

            if (response.length === 0) {
                $('#record_table tbody').append('<tr><td colspan="20" class="text-center py-8">ไม่พบข้อมูล</td></tr>');
            } else {
                $.each(response, function(index, record) {
                    var row = '<tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">' +
                    '<td class="px-3 py-2 text-center">' + (index + 1) + '</td>' +
                    '<td class="px-3 py-2 text-center font-mono">' + record.citizenid + '</td>' +
                    '<td class="px-3 py-2 text-center"><span class="px-2 py-1 text-xs rounded-full ' + (record.typeregis === 'ในเขต' ? 'bg-blue-100 text-blue-600' : 'bg-pink-100 text-pink-600') + '">' + record.typeregis + '</span></td>' +
                    '<td class="px-3 py-2">' + record.fullname + '</td>' +
                    '<td class="px-3 py-2 text-center">' + record.birthday + '</td>' +
                    '<td class="px-3 py-2 text-center">' + record.now_tel + '</td>' +
                    '<td class="px-3 py-2 text-center">' + record.parent_tel + '</td>' +
                    '<td class="px-3 py-2 text-center">' + record.create_at + '</td>' +
                    '<td class="px-3 py-2 text-center font-bold">' + record.gpa_total + '</td>';
                    for (var i = 1; i <= 10; i++) {
                        row += '<td class="px-3 py-2 text-center text-xs">' + getPlanName(record['number' + i]) + '</td>';
                    }
                    row += '<td class="px-3 py-2 text-center">' + 
                        '<button class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg mr-1 edit-btn" data-id="' + record.id + '"><i class="fas fa-edit"></i></button>' +
                        '<button class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg delete-btn" data-id="' + record.id + '"><i class="fas fa-trash"></i></button>' +
                    '</td></tr>';
                    $('#record_table tbody').append(row);
                });
            }

            $('#record_table').DataTable({
                "pageLength": 10,
                "responsive": true,
                "dom": 'Bfrtip',
                "buttons": [{
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel mr-2"></i>Export Excel',
                    className: 'px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600',
                    exportOptions: { columns: ':not(:last-child)' }
                }]
            });
        },
        error: function() { Swal.fire('ข้อผิดพลาด', 'ไม่สามารถดึงข้อมูลได้', 'error'); }
    });
}

function getPlanName(num) {
    const plans = { 1:'3-Coding', 2:'4-วิทย์', 3:'5-อังกฤษ', 4:'6-จีน', 5:'7-ไทย', 6:'8-สังคม', 7:'9-อุต', 8:'9-พณ', 9:'10-กษ', 10:'10-คห', 11:'11-ศิลปะ', 12:'11-ดนตรี', 13:'11-นาฏ', 14:'12-ฟุต', 15:'12-วู้ด' };
    return plans[num] || '-';
}

$(document).ready(function() {
    loadTable();
    
    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            url: 'api/get_student.php',
            method: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function(r) {
                $('#editStudentId').val(r.id);
                $('#editCitizenId').val(r.citizenid);
                $('#editTyperegis').val(r.typeregis);
                $('#editPrefix').val(r.stu_prefix);
                $('#editFirstName').val(r.stu_name);
                $('#editLastName').val(r.stu_lastname);
                $('#editBirthday').val(r.birthday);
                $('#editNowTel').val(r.now_tel);
                $('#editParentTel').val(r.parent_tel);
                $('#editGpaTotal').val(r.gpa_total);
                for (var i = 1; i <= 10; i++) $('#editNumber' + i).val(r['number' + i]);
                openModal('editStudentModal');
            }
        });
    });

    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'ยืนยันลบ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'ลบ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'api/delete_student.php',
                    method: 'POST',
                    data: JSON.stringify({ id: id }),
                    contentType: 'application/json',
                    success: function(r) {
                        if (r.success) { Swal.fire('ลบแล้ว!', '', 'success'); loadTable(); }
                        else Swal.fire('ข้อผิดพลาด', r.message, 'error');
                    }
                });
            }
        });
    });

    $('#editStudentForm').on('submit', function(e) {
        e.preventDefault();
        var data = {
            id: $('#editStudentId').val(),
            citizenid: $('#editCitizenId').val(),
            typeregis: $('#editTyperegis').val(),
            stu_prefix: $('#editPrefix').val(),
            stu_name: $('#editFirstName').val(),
            stu_lastname: $('#editLastName').val(),
            year_birth: $('#editBirthday').val().split('-')[2],
            month_birth: $('#editBirthday').val().split('-')[1],
            date_birth: $('#editBirthday').val().split('-')[0],
            now_tel: $('#editNowTel').val(),
            parent_tel: $('#editParentTel').val(),
            gpa_total: $('#editGpaTotal').val()
        };
        for (var i = 1; i <= 10; i++) data['number' + i] = $('#editNumber' + i).val();
        
        fetch('api/update_studentm1.php', {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'Content-Type': 'application/json' }
        }).then(r => r.json()).then(d => {
            if (d.success) { Swal.fire('สำเร็จ!', '', 'success'); closeModal('editStudentModal'); loadTable(); }
            else Swal.fire('ข้อผิดพลาด', d.message, 'error');
        });
    });
});
</script>

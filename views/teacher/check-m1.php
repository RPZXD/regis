<!-- Check M1 Evidence View -->
<div class="space-y-6">
    <div class="glass rounded-2xl p-6 border-l-4 border-emerald-500">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-check-double text-2xl text-white"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">ตรวจหลักฐาน ม.1 รอบทั่วไป</h2>
                    <p class="text-gray-600 dark:text-gray-400">คลิกที่ภาพเพื่อตรวจสอบหลักฐาน</p>
                </div>
            </div>
            <span class="px-4 py-2 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-lg text-sm font-medium">
                <i class="fas fa-info-circle mr-1"></i>คลิกที่ภาพแล้วสามารถตรวจหลักฐานได้
            </span>
        </div>
    </div>
    <div class="glass rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-xs" id="record_table">
                <thead><tr class="text-white text-[10px]">
                    <th class="px-2 py-2">ลำดับ</th>
                    <th class="px-2 py-2">เลขบัตร</th>
                    <th class="px-2 py-2">สถานะ</th>
                    <th class="px-2 py-2">ประเภท</th>
                    <th class="px-2 py-2">ชื่อ-นามสกุล</th>
                    <th class="px-2 py-2">เบอร์โทร</th>
                    <th class="px-2 py-2">รร.เดิม</th>
                    <th class="px-2 py-2">1.ใบสมัคร</th>
                    <th class="px-2 py-2">2.บัตรปชช</th>
                    <th class="px-2 py-2">3.ทะเบียนบ้าน</th>
                    <th class="px-2 py-2">4.ทบ.บิดา</th>
                    <th class="px-2 py-2">5.ทบ.มารดา</th>
                    <th class="px-2 py-2">6.1ผลการเรียน</th>
                    <th class="px-2 py-2">6.2ผลการเรียน</th>
                    <th class="px-2 py-2">7.รูปถ่าย</th>
                    <th class="px-2 py-2">8.หนังสือรับรอง</th>
                    <th class="px-2 py-2">จัดการ</th>
                </tr></thead>
                <tbody class="text-gray-700 dark:text-gray-300"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black/70" onclick="closeImageModal()"></div>
        <div class="relative glass rounded-2xl max-w-4xl w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">ตรวจสอบหลักฐาน</h3>
                <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <a href="" id="modalImageLink" target="_blank">
                <img id="modalImage" src="" class="w-full max-h-[60vh] object-contain rounded-xl bg-gray-100 dark:bg-slate-800">
            </a>
            <div class="mt-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">ผลการตรวจ:</label>
                    <select id="statusDropdown" onchange="toggleErrorField()" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700">
                        <option value="0">⏰ รอการตรวจสอบ</option>
                        <option value="1">✅ ผ่าน</option>
                        <option value="2">❌ ไม่ผ่าน</option>
                    </select>
                </div>
                <div id="errorDetailField" class="hidden">
                    <label class="block text-sm font-medium mb-2">ระบุเหตุผล:</label>
                    <textarea id="errorDetail" rows="3" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700"></textarea>
                </div>
                <input type="hidden" id="uploadFileName">
                <input type="hidden" id="citizenIdField">
                <button onclick="updateStatus()" class="w-full py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold rounded-lg hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i>บันทึกผล
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function loadTable() {
    $.ajax({
        url: 'api/fetch_m1nomal_check.php', method: 'GET', dataType: 'json',
        success: function(response) {
            $('#record_table').DataTable().clear().destroy();
            $('#record_table tbody').empty();
            if (response.length === 0) {
                $('#record_table tbody').append('<tr><td colspan="17" class="text-center py-8">ไม่พบข้อมูล</td></tr>');
            } else {
                $.each(response, function(i, r) {
                    var statusBadge = r.status == 0 ? '<span class="px-2 py-1 text-[10px] rounded-full bg-yellow-400 text-white">รอตรวจ</span>' :
                                      r.status == 1 ? '<span class="px-2 py-1 text-[10px] rounded-full bg-green-500 text-white">เรียบร้อย</span>' :
                                      r.status == 9 ? '<span class="px-2 py-1 text-[10px] rounded-full bg-red-500 text-white">แก้ไข</span>' :
                                      '<span class="px-2 py-1 text-[10px] rounded-full bg-purple-500 text-white">รออัพโหลด</span>';
                    var row = '<tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 text-[11px]">' +
                        '<td class="px-2 py-1 text-center">' + (i + 1) + '</td>' +
                        '<td class="px-2 py-1 text-center font-mono">' + r.citizenid + '</td>' +
                        '<td class="px-2 py-1 text-center">' + statusBadge + '</td>' +
                        '<td class="px-2 py-1 text-center">' + r.typeregis + '</td>' +
                        '<td class="px-2 py-1">' + r.fullname + '</td>' +
                        '<td class="px-2 py-1 text-center">' + r.now_tel + '</td>' +
                        '<td class="px-2 py-1 text-center">' + (r.old_school || '-') + '</td>';
                    for (var j = 1; j <= 9; j++) {
                        var path = r['upload_path' + j];
                        var upStatus = r['status' + j];
                        var bgColor = upStatus == '1' ? 'bg-green-100' : upStatus == '2' ? 'bg-red-100' : upStatus == '0' ? 'bg-yellow-100' : '';
                        if (path) {
                            row += '<td class="px-1 py-1 text-center ' + bgColor + '"><a href="javascript:void(0)" onclick="showImage(\'' + r.citizenid + '\',\'' + path + '\',\'document' + j + '\',\'' + encodeURIComponent(r['error_detail' + j] || '') + '\')"><img src="../uploads/' + r.citizenid + '/' + path + '" class="w-16 h-16 object-cover mx-auto rounded"/></a></td>';
                        } else {
                            row += '<td class="px-1 py-1 text-center">-</td>';
                        }
                    }
                    row += '<td class="px-2 py-1 text-center"><button class="px-2 py-1 bg-blue-500 text-white text-[10px] rounded edit-btn" data-id="' + r.id + '" data-status="' + r.status + '">แก้ไข</button></td></tr>';
                    $('#record_table tbody').append(row);
                });
            }
            $('#record_table').DataTable({ pageLength: 10, responsive: true, dom: 'Bfrtip', buttons: [{ extend: 'excelHtml5', text: 'Export', className: 'px-3 py-1 bg-green-500 text-white rounded text-sm' }] });
        }
    });
}

function showImage(citizenid, path, name, errorDetail) {
    $('#modalImage').attr('src', '../uploads/' + citizenid + '/' + path);
    $('#modalImageLink').attr('href', '../uploads/' + citizenid + '/' + path);
    $('#uploadFileName').val(name);
    $('#citizenIdField').val(citizenid);
    $('#errorDetail').val(decodeURIComponent(errorDetail));
    
    $.get('api/get_statusImg.php', { citizenid: citizenid, upload_name: name }, function(res) {
        var r = JSON.parse(res);
        if (r.success) { $('#statusDropdown').val(r.status); toggleErrorField(); }
    });
    $('#imageModal').removeClass('hidden');
}

function closeImageModal() { $('#imageModal').addClass('hidden'); }
function toggleErrorField() { $('#errorDetailField').toggleClass('hidden', $('#statusDropdown').val() != '2'); }

function updateStatus() {
    var status = $('#statusDropdown').val();
    var errorDetail = $('#errorDetail').val();
    if (status == '2' && !errorDetail) { Swal.fire('ข้อผิดพลาด', 'กรุณาระบุเหตุผล', 'error'); return; }
    $.post('api/update_upload_status.php', {
        citizenid: $('#citizenIdField').val(),
        upload_name: $('#uploadFileName').val(),
        status: status,
        error_detail: errorDetail
    }, function(res) {
        var r = JSON.parse(res);
        if (r.success) { Swal.fire('สำเร็จ', '', 'success'); closeImageModal(); loadTable(); }
        else Swal.fire('ข้อผิดพลาด', '', 'error');
    });
}

$(document).ready(function() { loadTable(); });
</script>

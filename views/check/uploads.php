<!-- Check Uploads Status View -->
<div class="space-y-6">
    <!-- Page Header -->
    <div class="text-center">
        <h1 class="text-3xl font-bold gradient-text">ตรวจสอบสถานะอัพโหลดหลักฐาน</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">ตรวจสอบสถานะการอัพโหลดเอกสารของคุณ</p>
    </div>

    <!-- Search Card -->
    <div class="max-w-2xl mx-auto">
        <div class="glass rounded-2xl p-8">
            <form id="searchForm" class="space-y-6">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl shadow-lg shadow-teal-500/30 mb-4">
                        <i class="fas fa-clipboard-check text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">กรอกข้อมูลเพื่อค้นหา</h3>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">เลขบัตรประชาชน หรือ ชื่อ-นามสกุล</label>
                    <input type="text" id="search_input" name="search_input" 
                           class="w-full px-4 py-3 text-center text-lg rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                           placeholder="กรอกเลขบัตรประชาชน 13 หลัก หรือ ชื่อ" required>
                </div>
                
                <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 text-white font-bold rounded-xl shadow-lg shadow-teal-500/30 hover:shadow-teal-500/50 transition-all transform hover:-translate-y-1">
                    <i class="fas fa-search mr-2"></i>ค้นหา
                </button>
            </form>
        </div>
    </div>

    <!-- Student Info Result -->
    <div id="studentInfo" class="max-w-3xl mx-auto hidden">
        <div class="glass rounded-2xl p-6">
            <div class="flex items-start space-x-4 mb-6">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 flex items-center justify-center bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 rounded-xl">
                        <i class="fas fa-user text-xl"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">ข้อมูลผู้สมัคร</h3>
                    <pre id="studentDetails" class="text-gray-600 dark:text-gray-300 whitespace-pre-line font-mali text-sm"></pre>
                    <span id="showTextStr" class="inline-block mt-3 px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full font-bold">-</span>
                </div>
            </div>

            <!-- Upload Status Table -->
            <div id="uploadStatus" class="hidden mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-bold text-red-600 dark:text-red-400 mb-4">
                    <i class="fas fa-list-check mr-2"></i>ตรวจสอบสถานะ
                </h4>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                            <tr>
                                <th class="px-4 py-3 text-left rounded-tl-lg">ชื่อเอกสาร</th>
                                <th class="px-4 py-3 text-center">สถานะ</th>
                                <th class="px-4 py-3 text-left rounded-tr-lg">รายละเอียดข้อผิดพลาด</th>
                            </tr>
                        </thead>
                        <tbody id="uploadStatusBody" class="divide-y divide-gray-200 dark:divide-gray-700">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('searchForm').addEventListener('submit', function(event) {
    event.preventDefault();
    var searchInput = document.getElementById('search_input').value;
    const statusMap = { 0: '⏰ รอการตรวจสอบ', 1: '✅ ผ่าน', 2: '❌ ไม่ผ่าน กรุณาแก้ไข' };

    Swal.fire({ title: 'กำลังค้นหา...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    fetch('api/fetch_reg.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ search_input: searchInput }) })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        if (data.exists) {
            Swal.fire({ icon: 'success', title: 'พบข้อมูล', confirmButtonColor: '#3b82f6' });
            document.getElementById('studentDetails').innerText = `ชื่อ: ${data.fullname}\nประเภท: ${data.typeregis}\nระดับ: ม.${data.level}`;
            document.getElementById('studentInfo').classList.remove('hidden');

            var showTextStr = data.status == 0 ? 'รอการตรวจสอบ' : data.status == 1 ? 'เสร็จสมบูรณ์' : data.status == 9 ? 'กรุณาแก้ไขหลักฐาน' : 'รอดำเนินการ';
            document.getElementById('showTextStr').innerText = showTextStr;

            fetch('api/fetch_upload_status.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ citizenid: data.citizenid }) })
            .then(r => r.json())
            .then(uploadData => {
                var tbody = document.getElementById('uploadStatusBody');
                if (uploadData.length > 0) {
                    var rows = '';
                    uploadData.forEach(function(upload) {
                        var statusMessage = statusMap[upload.status] || 'ไม่ระบุ';
                        var statusClass = upload.status == 1 ? 'bg-green-100 text-green-600' : upload.status == 2 ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600';
                        rows += `<tr class="bg-white dark:bg-slate-800"><td class="px-4 py-3">${upload.label}</td><td class="px-4 py-3 text-center"><span class="px-3 py-1 rounded-full text-sm font-medium ${statusClass}">${statusMessage}</span></td><td class="px-4 py-3">${upload.status === 2 && upload.error_detail ? upload.error_detail : '-'}</td></tr>`;
                    });
                    tbody.innerHTML = rows;
                    document.getElementById('uploadStatus').classList.remove('hidden');
                } else {
                    document.getElementById('uploadStatus').classList.add('hidden');
                }
            });
        } else {
            Swal.fire({ icon: 'error', title: 'ไม่พบข้อมูล', confirmButtonColor: '#ef4444' });
            document.getElementById('studentInfo').classList.add('hidden');
        }
    })
    .catch(error => { Swal.close(); Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', confirmButtonColor: '#ef4444' }); });
});
</script>

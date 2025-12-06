<!-- Print Registration View -->
<div class="space-y-6">
    <!-- Page Header -->
    <div class="text-center">
        <h1 class="text-3xl font-bold gradient-text">พิมพ์ใบสมัคร</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">ค้นหาข้อมูลเพื่อพิมพ์ใบสมัครเรียน</p>
    </div>

    <!-- Search Card -->
    <div class="max-w-2xl mx-auto">
        <div class="glass rounded-2xl p-8">
            <form id="searchForm" class="space-y-6">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-lg shadow-purple-500/30 mb-4">
                        <i class="fas fa-print text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">กรอกข้อมูลเพื่อค้นหา</h3>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">เลขบัตรประชาชน หรือ ชื่อ-นามสกุล</label>
                    <input type="text" id="search_input" name="search_input" 
                           class="w-full px-4 py-3 text-center text-lg rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                           placeholder="กรอกเลขบัตรประชาชน 13 หลัก หรือ ชื่อ" required>
                </div>
                
                <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white font-bold rounded-xl shadow-lg shadow-purple-500/30 hover:shadow-purple-500/50 transition-all transform hover:-translate-y-1">
                    <i class="fas fa-search mr-2"></i>ค้นหา
                </button>
                
                <p class="text-center text-sm text-red-500">*** กรุณากรอกเลขบัตรประชาชน 13 หลัก หรือ ชื่อเพื่อค้นหาข้อมูล ***</p>
            </form>
        </div>
    </div>

    <!-- Student Info Result Card -->
    <div id="studentInfo" class="max-w-2xl mx-auto hidden">
        <div class="glass rounded-2xl p-6 border-l-4 border-purple-500">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 flex items-center justify-center bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-xl">
                        <i class="fas fa-user text-xl"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">ข้อมูลผู้สมัคร</h3>
                    <pre id="studentDetails" class="text-gray-600 dark:text-gray-300 whitespace-pre-line font-mali"></pre>
                    
                    <button id="printButton" class="hidden mt-4 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-green-500/30 transition-all">
                        <i class="fas fa-print mr-2"></i>พิมพ์ใบสมัคร
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('searchForm').addEventListener('submit', function(event) {
    event.preventDefault();
    var searchInput = document.getElementById('search_input').value;

    Swal.fire({ title: 'กำลังค้นหา...', text: 'กรุณารอสักครู่', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    fetch('api/fetch_reg.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ search_input: searchInput }) })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        if (data.exists) {
            Swal.fire({ icon: 'success', title: 'พบข้อมูล', text: 'ข้อมูลผู้สมัครที่ค้นหา', confirmButtonColor: '#3b82f6' });
            document.getElementById('studentDetails').innerText = `ชื่อ: ${data.fullname}\nประเภทการสมัคร: ${data.typeregis}\nระดับชั้นที่สมัคร: ชั้นมัธยมศึกษาปีที่ ${data.level}\nวันเกิด: ${data.birthday}\nเบอร์โทร: ${data.now_tel}\nเบอร์โทรผู้ปกครอง: ${data.parent_tel}`;
            document.getElementById('studentInfo').classList.remove('hidden');
            document.getElementById('printButton').classList.remove('hidden');
            document.getElementById('printButton').onclick = function() { window.location.href = `print_reginfo.php?citizenid=${data.citizenid}`; };
        } else {
            Swal.fire({ icon: 'error', title: 'ไม่พบข้อมูล', text: 'ไม่พบข้อมูลผู้สมัครที่ค้นหา', confirmButtonColor: '#ef4444' });
            document.getElementById('studentInfo').classList.add('hidden');
        }
    })
    .catch(error => {
        Swal.close();
        Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: 'ไม่สามารถตรวจสอบข้อมูลได้', confirmButtonColor: '#ef4444' });
        document.getElementById('studentInfo').classList.add('hidden');
    });
});
</script>

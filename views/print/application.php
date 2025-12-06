<!-- Print Application View -->
<div class="space-y-6">
    <!-- Page Header -->
    <div class="text-center">
        <h1 class="text-3xl font-bold gradient-text">พิมพ์ใบสมัครเรียน</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">ค้นหาข้อมูลเพื่อพิมพ์ใบสมัคร</p>
    </div>

    <!-- Search Card -->
    <div class="max-w-2xl mx-auto">
        <div class="glass rounded-2xl p-8">
            <form id="searchForm" class="space-y-6">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-2xl shadow-lg shadow-indigo-500/30 mb-4">
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
                
                <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all transform hover:-translate-y-1">
                    <i class="fas fa-search mr-2"></i>ค้นหา
                </button>
            </form>
        </div>
    </div>

    <!-- Student Info Result Card -->
    <div id="studentInfo" class="max-w-2xl mx-auto hidden">
        <div class="glass rounded-2xl p-6 border-l-4 border-indigo-500">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 flex items-center justify-center bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-xl">
                        <i class="fas fa-user-graduate text-xl"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">ข้อมูลผู้สมัคร</h3>
                    <pre id="studentDetails" class="text-gray-600 dark:text-gray-300 whitespace-pre-line font-mali"></pre>
                    
                    <div class="mt-6 flex flex-col sm:flex-row gap-4">
                        <button id="printAppButton" class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all text-center">
                            <i class="fas fa-file-alt mr-2"></i>พิมพ์ใบสมัคร
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('searchForm').addEventListener('submit', function(event) {
    event.preventDefault();
    var searchInput = document.getElementById('search_input').value;

    Swal.fire({ title: 'กำลังค้นหา...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    fetch('api/fetch_reg.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ search_input: searchInput }) })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        if (data.exists) {
            Swal.fire({ icon: 'success', title: 'พบข้อมูล', confirmButtonColor: '#3b82f6' });
            
            let plansText = '';
            if (data.plans && data.plans.length > 0) {
                plansText = '\n\nแผนการเรียนที่เลือก:\n' + data.plans.map((p, index) => `   ${index + 1}. ${p}`).join('\n');
            }

            document.getElementById('studentDetails').innerText = `ชื่อ: ${data.fullname}\nประเภท: ${data.typeregis}\nระดับ: ชั้นมัธยมศึกษาปีที่ ${data.level}\nวันเกิด: ${data.birthday}${plansText}`;
            document.getElementById('studentInfo').classList.remove('hidden');
            
            // Setup Print Button
            let printUrl = '';
            // Determine print URL based on level
            // Assuming 1 = M1, 4 = M4. If there are other levels fitting these categories, logic could differ.
            // But currently the system seems built around M1 and M4 flows.
            if (data.level == 1) {
                printUrl = `print_pdf_m1.php?stu_id=${data.id}`;
            } else if (data.level == 4) {
                printUrl = `print_pdf_m4.php?stu_id=${data.id}`;
            } else {
                 // Try to fallback or show error if level is unexpected
            }
            
            document.getElementById('printAppButton').onclick = function() { 
                if(printUrl) {
                     window.open(printUrl, '_blank'); 
                } else {
                    Swal.fire('ขออภัย', 'ระบบยังไม่รองรับการพิมพ์ใบสมัครสำหรับระดับชั้นนี้ หรือ ข้อมูลระดับชั้นไม่ถูกต้อง', 'warning');
                }
            };

        } else {
            Swal.fire({ icon: 'error', title: 'ไม่พบข้อมูล', confirmButtonColor: '#ef4444' });
            document.getElementById('studentInfo').classList.add('hidden');
        }
    })
    .catch(error => { Swal.close(); Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', confirmButtonColor: '#ef4444' }); });
});
</script>

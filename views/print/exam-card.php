<!-- Print Exam Card View -->
<div class="space-y-6">
    <!-- Page Header -->
    <div class="text-center">
        <h1 class="text-3xl font-bold gradient-text">พิมพ์บัตรประจำตัวสอบ</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">ค้นหาข้อมูลเพื่อพิมพ์บัตรสอบ</p>
    </div>

    <!-- Search Card -->
    <div class="max-w-2xl mx-auto">
        <div class="glass rounded-2xl p-8">
            <form id="searchForm" class="space-y-6">
                <div class="text-center">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-rose-500 to-pink-600 rounded-2xl shadow-lg shadow-rose-500/30 mb-4">
                        <i class="fas fa-id-card text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">กรอกข้อมูลเพื่อค้นหา</h3>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">เลขบัตรประชาชน หรือ
                        ชื่อ-นามสกุล</label>
                    <input type="text" id="search_input" name="search_input"
                        class="w-full px-4 py-3 text-center text-lg rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                        placeholder="กรอกเลขบัตรประชาชน 13 หลัก หรือ ชื่อ" required>
                </div>

                <button type="submit"
                    class="w-full py-4 px-6 bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 text-white font-bold rounded-xl shadow-lg shadow-rose-500/30 hover:shadow-rose-500/50 transition-all transform hover:-translate-y-1">
                    <i class="fas fa-search mr-2"></i>ค้นหา
                </button>
            </form>
        </div>
    </div>

    <!-- Student Info Result Card -->
    <div id="studentInfo" class="max-w-2xl mx-auto hidden">
        <div class="glass rounded-2xl p-6 border-l-4 border-rose-500">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 flex items-center justify-center bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-xl">
                        <i class="fas fa-user text-xl"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">ข้อมูลผู้สมัคร</h3>
                    <pre id="studentDetails"
                        class="text-gray-600 dark:text-gray-300 whitespace-pre-line font-mali"></pre>

                    <button id="printButton"
                        class="hidden mt-4 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-green-500/30 transition-all">
                        <i class="fas fa-id-card mr-2"></i>พิมพ์บัตรประจำตัวสอบ
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let now = new Date();
        let startTime = new Date('2025-03-27T08:00:00');

        if (now < startTime) {
            Swal.fire({
                icon: "warning",
                title: "ยังไม่ถึงเวลาพิมพ์บัตรสอบ",
                text: "ระบบจะเปิดให้พิมพ์บัตรสอบได้ในวันที่ 27 มีนาคม 2025 เวลา 08:00 น.",
                confirmButtonText: "ตกลง",
                confirmButtonColor: '#f59e0b'
            }).then(() => { window.location.href = "index.php"; });
        }
    });

    document.getElementById('searchForm').addEventListener('submit', function (event) {
        event.preventDefault();
        var searchInput = document.getElementById('search_input').value;

        Swal.fire({ title: 'กำลังค้นหา...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

        fetch('api/fetch_reg.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ search_input: searchInput }) })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.exists && data.multiple) {
                    let html = '<div style="text-align:left">';
                    data.registrations.forEach((reg, i) => {
                        html += `<label style="display:flex;align-items:center;padding:10px;margin:5px 0;border:2px solid #e5e7eb;border-radius:12px;cursor:pointer;transition:all 0.2s" 
                    onmouseover="this.style.borderColor='#f43f5e';this.style.background='#fff1f2'" 
                    onmouseout="this.style.borderColor=this.querySelector('input').checked?'#f43f5e':'#e5e7eb';this.style.background=this.querySelector('input').checked?'#fff1f2':''">
                    <input type="radio" name="reg_choice" value="${reg.id}" ${i === 0 ? 'checked' : ''} style="margin-right:10px;accent-color:#f43f5e">
                    <div>
                        <div style="font-weight:600;color:#1f2937">${reg.typeregis}</div>
                        <div style="font-size:0.85em;color:#6b7280">ม.${reg.level} | เลขที่ ${reg.numreg || '-'}</div>
                    </div>
                </label>`;
                    });
                    html += '</div>';
                    Swal.fire({
                        title: `${data.fullname}`,
                        html: `<p style="margin-bottom:12px;color:#6b7280">พบการสมัคร ${data.registrations.length} ประเภท กรุณาเลือก:</p>${html}`,
                        icon: 'question',
                        confirmButtonText: '<i class="fas fa-check mr-2"></i>เลือกประเภทนี้',
                        confirmButtonColor: '#f43f5e',
                        showCancelButton: true,
                        cancelButtonText: 'ยกเลิก'
                    }).then(result => {
                        if (result.isConfirmed) {
                            const selectedId = document.querySelector('input[name="reg_choice"]:checked').value;
                            // Re-fetch with specific reg_id
                            Swal.fire({ title: 'กำลังค้นหา...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
                            fetch('api/fetch_reg.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ search_input: searchInput, reg_id: selectedId }) })
                                .then(r => r.json())
                                .then(d => {
                                    Swal.close();
                                    handleResult(d);
                                });
                        }
                    });
                    return;
                }
                handleResult(data);
            })
            .catch(error => { Swal.close(); Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', confirmButtonColor: '#ef4444' }); });
    });

    function handleResult(data) {
        if (data.exists) {
            if (data.status !== 1) {
                Swal.fire({ icon: 'warning', title: 'คุณสมบัติไม่ผ่านเกณฑ์', text: 'ผู้สมัครไม่ผ่านเงื่อนไขที่กำหนด', confirmButtonColor: '#f59e0b' });
                document.getElementById('studentInfo').classList.add('hidden');
                return;
            }
            Swal.fire({ icon: 'success', title: 'พบข้อมูล', confirmButtonColor: '#3b82f6' });
            document.getElementById('studentDetails').innerText = `ชื่อ: ${data.fullname}\nประเภท: ${data.typeregis}\nระดับ: ม.${data.level}\nวันเกิด: ${data.birthday}`;
            document.getElementById('studentInfo').classList.remove('hidden');
            document.getElementById('printButton').classList.remove('hidden');
            document.getElementById('printButton').onclick = function () { window.location.href = `print_card.php?citizenid=${data.citizenid}`; };
        } else {
            Swal.fire({ icon: 'error', title: 'ไม่พบข้อมูล', confirmButtonColor: '#ef4444' });
            document.getElementById('studentInfo').classList.add('hidden');
        }
    }
</script>
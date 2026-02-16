<!-- Print Exam Card View -->
<?php
$citizenidParam = $_GET['citizenid'] ?? '';
?>
<div class="space-y-8">
    <!-- Page Header with Gradient -->
    <div class="text-center relative">
        <div
            class="absolute inset-0 bg-gradient-to-r from-blue-500/10 via-cyan-500/10 to-teal-500/10 rounded-3xl blur-3xl -z-10">
        </div>
        <div
            class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-3xl shadow-2xl shadow-blue-500/40 mb-6 animate-bounce-slow">
            <i class="fas fa-id-card text-3xl text-white"></i>
        </div>
        <h1
            class="text-4xl font-bold bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-600 bg-clip-text text-transparent">
            พิมพ์บัตรประจำตัวสอบ
        </h1>
        <p class="mt-3 text-gray-600 dark:text-gray-400 max-w-lg mx-auto">
            ค้นหาข้อมูลและพิมพ์บัตรประจำตัวผู้เข้าสอบ
        </p>
    </div>

    <!-- Search Card -->
    <div class="max-w-xl mx-auto">
        <div class="glass rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-shadow duration-300">
            <form id="searchForm" class="space-y-6">
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-id-card mr-2 text-blue-500"></i>เลขบัตรประชาชน
                    </label>
                    <input type="text" id="search_input" name="search_input"
                        class="w-full px-5 py-4 text-center text-xl font-mono tracking-widest rounded-2xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                        placeholder="X-XXXX-XXXXX-XX-X" value="<?php echo htmlspecialchars($citizenidParam); ?>"
                        maxlength="13" required>
                </div>

                <button type="submit"
                    class="w-full py-4 px-6 bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-500 hover:from-blue-600 hover:via-cyan-600 hover:to-teal-600 text-white font-bold text-lg rounded-2xl shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 transition-all transform hover:-translate-y-1 hover:scale-[1.02] group">
                    <i class="fas fa-search mr-3 group-hover:animate-pulse"></i>ค้นหาข้อมูล
                </button>
            </form>
        </div>
    </div>

    <!-- Student Info Result -->
    <div id="studentInfo" class="max-w-3xl mx-auto hidden animate-fade-in">
        <!-- Exam Card Preview -->
        <div class="glass rounded-3xl overflow-hidden shadow-xl">
            <div class="bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-500 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 flex items-center justify-center bg-white/20 backdrop-blur rounded-2xl">
                            <i class="fas fa-user-graduate text-3xl text-white"></i>
                        </div>
                        <div class="text-white">
                            <h3 class="text-xl font-bold" id="studentName">-</h3>
                            <p class="text-blue-100" id="studentTypeLevel">-</p>
                        </div>
                    </div>
                    <div class="text-right text-white">
                        <p class="text-xs text-blue-100">เลขที่นั่งสอบ</p>
                        <p class="text-3xl font-bold" id="seatNumber">-</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Info Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-center">
                        <i class="fas fa-id-card text-2xl text-blue-500 mb-2"></i>
                        <p class="text-xs text-gray-500">เลขบัตร</p>
                        <p class="font-mono font-bold text-gray-900 dark:text-white text-xs" id="val_citizenid">-</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-center">
                        <i class="fas fa-layer-group text-2xl text-cyan-500 mb-2"></i>
                        <p class="text-xs text-gray-500">ประเภท</p>
                        <p class="font-bold text-gray-900 dark:text-white text-sm" id="val_type">-</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-center">
                        <i class="fas fa-graduation-cap text-2xl text-teal-500 mb-2"></i>
                        <p class="text-xs text-gray-500">ระดับชั้น</p>
                        <p class="font-bold text-gray-900 dark:text-white text-sm" id="val_level">-</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-center" id="scheduleCard">
                        <i class="fas fa-calendar-check text-2xl text-green-500 mb-2" id="scheduleIcon"></i>
                        <p class="text-xs text-gray-500">สถานะ</p>
                        <p class="font-bold text-sm" id="scheduleStatus">-</p>
                    </div>
                </div>

                <!-- Exam Info -->
                <div
                    class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-5 mb-6 border border-blue-100 dark:border-blue-800">
                    <h4 class="font-semibold text-blue-800 dark:text-blue-300 mb-3 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>ข้อมูลการสอบ
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">ห้องสอบ</p>
                            <p class="font-bold text-gray-900 dark:text-white" id="examRoom">รอประกาศ</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">วันที่สอบ</p>
                            <p class="font-bold text-gray-900 dark:text-white" id="examDate">รอประกาศ</p>
                        </div>
                    </div>
                </div>

                <!-- Print Schedule Status -->
                <div id="printStatusCard" class="mb-6 p-4 rounded-2xl border-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-print text-2xl mr-3" id="printIcon"></i>
                            <div>
                                <p class="font-semibold" id="printTitle">สถานะการพิมพ์บัตร</p>
                                <p class="text-sm" id="printMessage">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Print Button -->
                <button id="printCardButton"
                    class="w-full py-5 bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-500 hover:from-blue-600 hover:via-cyan-600 hover:to-teal-600 text-white font-bold text-lg rounded-2xl shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-1 hover:scale-[1.02] group">
                    <div class="flex items-center justify-center">
                        <div class="bg-white/20 rounded-full p-3 mr-4 group-hover:rotate-12 transition-transform">
                            <i class="fas fa-id-card text-2xl"></i>
                        </div>
                        <div class="text-left">
                            <span class="block text-xl">พิมพ์บัตรประจำตัวสอบ</span>
                            <span class="block text-sm text-blue-100 font-normal">ดาวน์โหลดไฟล์ PDF</span>
                        </div>
                    </div>
                </button>

                <!-- Quick Links -->
                <div class="mt-6 text-center">
                    <a href="checkreg.php" class="text-gray-500 hover:text-blue-600 transition-colors mr-6">
                        <i class="fas fa-search mr-2"></i>ค้นหาสถานะ
                    </a>
                    <a href="print.php" class="text-gray-500 hover:text-blue-600 transition-colors">
                        <i class="fas fa-print mr-2"></i>พิมพ์ใบสมัคร
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes bounce-slow {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    .animate-bounce-slow {
        animation: bounce-slow 3s infinite;
    }

    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    // Auto-search if citizenid is provided
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search_input').value;
        if (searchInput && searchInput.length >= 10) {
            document.getElementById('searchForm').dispatchEvent(new Event('submit'));
        }
    });

    document.getElementById('searchForm').addEventListener('submit', function (event) {
        event.preventDefault();
        var searchInput = document.getElementById('search_input').value;

        Swal.fire({ title: 'กำลังค้นหา...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

        fetchAndDisplay(searchInput);
    });

    function fetchAndDisplay(searchInput, regId) {
        Swal.fire({ title: 'กำลังค้นหา...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

        const payload = { search_input: searchInput };
        if (regId) payload.reg_id = regId;

        fetch('api/fetch_reg.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.exists && data.multiple) {
                    let html = '<div style="text-align:left">';
                    data.registrations.forEach((reg, i) => {
                        html += `<label style="display:flex;align-items:center;padding:10px;margin:5px 0;border:2px solid #e5e7eb;border-radius:12px;cursor:pointer;transition:all 0.2s" 
                    onmouseover="this.style.borderColor='#3b82f6';this.style.background='#eff6ff'" 
                    onmouseout="this.style.borderColor=this.querySelector('input').checked?'#3b82f6':'#e5e7eb';this.style.background=this.querySelector('input').checked?'#eff6ff':''">
                    <input type="radio" name="reg_choice" value="${reg.id}" ${i === 0 ? 'checked' : ''} style="margin-right:10px;accent-color:#3b82f6">
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
                        confirmButtonColor: '#3b82f6',
                        showCancelButton: true,
                        cancelButtonText: 'ยกเลิก'
                    }).then(result => {
                        if (result.isConfirmed) {
                            const selectedId = document.querySelector('input[name="reg_choice"]:checked').value;
                            fetchAndDisplay(searchInput, selectedId);
                        }
                    });
                    return;
                }
                if (data.exists) {
                    Swal.fire({ icon: 'success', title: 'พบข้อมูล', timer: 1500, showConfirmButton: false });

                    // Populate Data
                    document.getElementById('studentName').textContent = data.fullname;
                    document.getElementById('studentTypeLevel').textContent = data.typeregis + ' | ม.' + data.level;
                    document.getElementById('val_citizenid').textContent = data.citizenid;
                    document.getElementById('val_type').textContent = data.typeregis;
                    document.getElementById('val_level').textContent = 'ม.' + data.level;

                    // Seat number (if available)
                    document.getElementById('seatNumber').textContent = data.seat_number || '-';
                    document.getElementById('examRoom').textContent = data.exam_room || 'รอประกาศ';
                    document.getElementById('examDate').textContent = data.exam_date || 'รอประกาศ';

                    // Document status
                    let statusText = data.docStatusText || 'รอดำเนินการ';
                    let statusClass = 'text-gray-500';
                    if (data.docStatus === 'complete') { statusClass = 'text-green-600'; }
                    else if (data.docStatus === 'pending') { statusClass = 'text-amber-600'; }
                    else if (data.docStatus === 'rejected') { statusClass = 'text-red-600'; }
                    document.getElementById('scheduleStatus').textContent = statusText;
                    document.getElementById('scheduleStatus').className = 'font-bold text-sm ' + statusClass;

                    const printBtn = document.getElementById('printCardButton');
                    const statusCard = document.getElementById('printStatusCard');
                    const printIcon = document.getElementById('printIcon');
                    const printTitle = document.getElementById('printTitle');
                    const printMessage = document.getElementById('printMessage');

                    const canPrint = data.canPrintCard !== undefined ? data.canPrintCard : data.canPrint;
                    const printMsg = data.printCardMessage || data.printMessage || 'พร้อมพิมพ์บัตรประจำตัวสอบ';

                    if (canPrint !== false) {
                        printBtn.classList.remove('!bg-gray-400', '!from-gray-400', '!to-gray-500', 'cursor-not-allowed', 'opacity-60');
                        printBtn.disabled = false;
                        printBtn.onclick = function () { window.open(`print_card_pdf.php?citizenid=${data.citizenid}`, '_blank'); };

                        statusCard.className = 'mb-6 p-4 rounded-2xl border-2 border-green-300 dark:border-green-700 bg-green-50 dark:bg-green-900/20';
                        printIcon.className = 'fas fa-check-circle text-2xl mr-3 text-green-500';
                        printTitle.textContent = 'พร้อมพิมพ์บัตร';
                        printTitle.className = 'font-semibold text-green-700 dark:text-green-400';
                        printMessage.textContent = printMsg;
                        printMessage.className = 'text-sm text-green-600 dark:text-green-300';
                    } else {
                        printBtn.classList.add('!bg-gray-400', '!from-gray-400', '!to-gray-500', 'cursor-not-allowed', 'opacity-60');
                        printBtn.disabled = true;
                        printBtn.onclick = function () {
                            Swal.fire({ icon: 'warning', title: 'ยังไม่สามารถพิมพ์ได้', text: printMsg, confirmButtonColor: '#f59e0b' });
                        };

                        statusCard.className = 'mb-6 p-4 rounded-2xl border-2 border-amber-300 dark:border-amber-700 bg-amber-50 dark:bg-amber-900/20';
                        printIcon.className = 'fas fa-clock text-2xl mr-3 text-amber-500';
                        printTitle.textContent = 'ยังไม่ถึงช่วงเวลาพิมพ์';
                        printTitle.className = 'font-semibold text-amber-700 dark:text-amber-400';
                        printMessage.textContent = printMsg;
                        printMessage.className = 'text-sm text-amber-600 dark:text-amber-300';
                    }

                    document.getElementById('studentInfo').classList.remove('hidden');
                    document.getElementById('studentInfo').scrollIntoView({ behavior: 'smooth', block: 'start' });

                } else {
                    Swal.fire({ icon: 'error', title: 'ไม่พบข้อมูล', text: 'กรุณาตรวจสอบเลขบัตรประชาชน', confirmButtonColor: '#ef4444' });
                    document.getElementById('studentInfo').classList.add('hidden');
                }
            })
            .catch(error => { Swal.close(); Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', confirmButtonColor: '#ef4444' }); });
    }
</script>
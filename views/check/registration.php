<!-- Check Registration Status View - Enhanced -->
<div class="space-y-8">
    <!-- Page Header with Gradient -->
    <div class="text-center relative">
        <div
            class="absolute inset-0 bg-gradient-to-r from-emerald-500/10 via-green-500/10 to-teal-500/10 rounded-3xl blur-3xl -z-10">
        </div>
        <div
            class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-emerald-500 to-green-600 rounded-3xl shadow-2xl shadow-emerald-500/40 mb-6 animate-bounce-slow">
            <i class="fas fa-search text-3xl text-white"></i>
        </div>
        <h1
            class="text-4xl font-bold bg-gradient-to-r from-emerald-600 via-green-600 to-teal-600 bg-clip-text text-transparent">
            ค้นหาสถานะการสมัคร
        </h1>
        <p class="mt-3 text-gray-600 dark:text-gray-400 max-w-lg mx-auto">
            ตรวจสอบข้อมูลการสมัครและสถานะเอกสารของคุณ
        </p>
    </div>

    <!-- Search Card -->
    <div class="max-w-xl mx-auto">
        <div class="glass rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-shadow duration-300">
            <form id="searchForm" class="space-y-6">
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-id-card mr-2 text-emerald-500"></i>เลขบัตรประชาชน หรือ ชื่อ-นามสกุล
                    </label>
                    <input type="text" id="search_input" name="search_input"
                        class="w-full px-5 py-4 text-center text-xl font-mono tracking-widest rounded-2xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"
                        placeholder="X-XXXX-XXXXX-XX-X" maxlength="13" required>
                </div>

                <button type="submit"
                    class="w-full py-4 px-6 bg-gradient-to-r from-emerald-500 via-green-500 to-teal-500 hover:from-emerald-600 hover:via-green-600 hover:to-teal-600 text-white font-bold text-lg rounded-2xl shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 transition-all transform hover:-translate-y-1 hover:scale-[1.02] group">
                    <i class="fas fa-search mr-3 group-hover:animate-pulse"></i>ค้นหาข้อมูล
                </button>

                <p class="text-center text-xs text-gray-400">กรุณากรอกเลขบัตรประชาชน 13 หลัก หรือ ชื่อเพื่อค้นหา</p>
            </form>
        </div>
    </div>

    <!-- Student Info Result -->
    <div id="studentInfo" class="max-w-3xl mx-auto hidden animate-fade-in">
        <!-- Student Card -->
        <div class="glass rounded-3xl overflow-hidden shadow-xl mb-6">
            <div class="bg-gradient-to-r from-emerald-500 via-green-500 to-teal-500 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 flex items-center justify-center bg-white/20 backdrop-blur rounded-2xl">
                        <i class="fas fa-user-graduate text-3xl text-white"></i>
                    </div>
                    <div class="text-white">
                        <h3 class="text-xl font-bold" id="studentName">-</h3>
                        <p class="text-emerald-100" id="studentTypeLevel">-</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Info Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-center">
                        <i class="fas fa-id-card text-2xl text-emerald-500 mb-2"></i>
                        <p class="text-xs text-gray-500">เลขบัตร</p>
                        <p class="font-mono font-bold text-gray-900 dark:text-white text-sm" id="val_citizenid">-</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-center">
                        <i class="fas fa-birthday-cake text-2xl text-pink-500 mb-2"></i>
                        <p class="text-xs text-gray-500">วันเกิด</p>
                        <p class="font-bold text-gray-900 dark:text-white text-sm" id="val_birthday">-</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-center">
                        <i class="fas fa-phone text-2xl text-blue-500 mb-2"></i>
                        <p class="text-xs text-gray-500">โทรศัพท์</p>
                        <p class="font-bold text-gray-900 dark:text-white text-sm" id="val_tel">-</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-center" id="statusCard">
                        <i class="fas fa-clipboard-check text-2xl text-amber-500 mb-2" id="statusIcon"></i>
                        <p class="text-xs text-gray-500">สถานะเอกสาร</p>
                        <p class="font-bold text-sm" id="statusText">-</p>
                    </div>
                </div>

                <!-- Registration Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 p-5 rounded-2xl border border-blue-100 dark:border-blue-800">
                        <p class="text-xs text-blue-600 dark:text-blue-400 mb-1">ประเภทการสมัคร</p>
                        <p id="val_type" class="text-xl font-bold text-blue-700 dark:text-blue-300">-</p>
                    </div>
                    <div
                        class="bg-purple-50 dark:bg-purple-900/20 p-5 rounded-2xl border border-purple-100 dark:border-purple-800">
                        <p class="text-xs text-purple-600 dark:text-purple-400 mb-1">ระดับชั้น</p>
                        <p id="val_level" class="text-xl font-bold text-purple-700 dark:text-purple-300">-</p>
                    </div>
                </div>

                <!-- Plans -->
                <div class="bg-gray-50 dark:bg-slate-800/50 rounded-2xl p-5">
                    <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-3 flex items-center">
                        <i class="fas fa-list-ol text-orange-500 mr-2"></i>แผนการเรียนที่เลือก
                    </h4>
                    <ul id="val_plans" class="space-y-2"></ul>
                </div>

                <!-- Quick Actions -->
                <div
                    class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 grid grid-cols-1 md:grid-cols-3 gap-3">
                    <a id="uploadLink" href="#"
                        class="flex items-center justify-center px-4 py-3 bg-amber-100 dark:bg-amber-900/30 hover:bg-amber-200 dark:hover:bg-amber-900/50 text-amber-700 dark:text-amber-400 rounded-xl transition-colors">
                        <i class="fas fa-upload mr-2"></i>อัพโหลดเอกสาร
                    </a>
                    <a id="printLink" href="#"
                        class="flex items-center justify-center px-4 py-3 bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-400 rounded-xl transition-colors">
                        <i class="fas fa-print mr-2"></i>พิมพ์ใบสมัคร
                    </a>
                    <a id="checkDocsLink" href="#"
                        class="flex items-center justify-center px-4 py-3 bg-teal-100 dark:bg-teal-900/30 hover:bg-teal-200 dark:hover:bg-teal-900/50 text-teal-700 dark:text-teal-400 rounded-xl transition-colors">
                        <i class="fas fa-clipboard-check mr-2"></i>สถานะเอกสาร
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
    document.getElementById('searchForm').addEventListener('submit', function (event) {
        event.preventDefault();
        var searchInput = document.getElementById('search_input').value.trim();

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
                    onmouseover="this.style.borderColor='#10b981';this.style.background='#ecfdf5'" 
                    onmouseout="this.style.borderColor=this.querySelector('input').checked?'#10b981':'#e5e7eb';this.style.background=this.querySelector('input').checked?'#ecfdf5':''">
                    <input type="radio" name="reg_choice" value="${reg.id}" ${i === 0 ? 'checked' : ''} style="margin-right:10px;accent-color:#10b981">
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
                        confirmButtonColor: '#10b981',
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

                    document.getElementById('studentName').textContent = data.fullname;
                    document.getElementById('studentTypeLevel').textContent = data.typeregis + ' | ม.' + data.level;
                    document.getElementById('val_citizenid').textContent = data.citizenid;
                    document.getElementById('val_birthday').textContent = data.birthday || '-';
                    document.getElementById('val_tel').textContent = data.now_tel || '-';
                    document.getElementById('val_type').textContent = data.typeregis;
                    document.getElementById('val_level').textContent = 'มัธยมศึกษาปีที่ ' + data.level;

                    let statusText = data.docStatusText || 'รอดำเนินการ';
                    let statusClass = 'text-gray-500';
                    let iconClass = 'text-gray-500';

                    if (data.docStatus === 'complete') { statusClass = 'text-green-600'; iconClass = 'text-green-500'; }
                    else if (data.docStatus === 'pending') { statusClass = 'text-amber-600'; iconClass = 'text-amber-500'; }
                    else if (data.docStatus === 'rejected') { statusClass = 'text-red-600'; iconClass = 'text-red-500'; }
                    else if (data.docStatus === 'incomplete') { statusClass = 'text-blue-600'; iconClass = 'text-blue-500'; }

                    document.getElementById('statusText').textContent = statusText;
                    document.getElementById('statusText').className = 'font-bold text-sm ' + statusClass;
                    document.getElementById('statusIcon').className = 'fas fa-clipboard-check text-2xl mb-2 ' + iconClass;

                    const plansList = document.getElementById('val_plans');
                    plansList.innerHTML = '';
                    if (data.plans && data.plans.length > 0) {
                        data.plans.forEach((plan, index) => {
                            plansList.innerHTML += `<li class="flex items-center text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 p-3 rounded-xl shadow-sm">
                        <span class="w-7 h-7 flex items-center justify-center bg-orange-100 dark:bg-orange-900/30 text-orange-600 rounded-full text-sm font-bold mr-3">${index + 1}</span>${plan}
                    </li>`;
                        });
                    } else {
                        plansList.innerHTML = '<li class="text-gray-400 italic text-sm">ไม่พบข้อมูลแผนการเรียน</li>';
                    }

                    document.getElementById('uploadLink').href = 'upload.php?citizenid=' + data.citizenid;
                    document.getElementById('printLink').href = 'print.php?citizenid=' + data.citizenid;
                    document.getElementById('checkDocsLink').href = 'check_uploads.php?citizenid=' + data.citizenid;

                    document.getElementById('studentInfo').classList.remove('hidden');
                    document.getElementById('studentInfo').scrollIntoView({ behavior: 'smooth', block: 'start' });

                } else {
                    Swal.fire({ icon: 'error', title: 'ไม่พบข้อมูล', text: 'ไม่พบข้อมูลผู้สมัครที่ค้นหา', confirmButtonColor: '#ef4444' });
                    document.getElementById('studentInfo').classList.add('hidden');
                }
            })
            .catch(error => {
                Swal.close();
                Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', confirmButtonColor: '#ef4444' });
            });
    }
</script>
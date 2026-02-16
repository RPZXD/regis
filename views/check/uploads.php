<!-- Check Uploads Status View - Modern Design -->
<div class="space-y-8">
    <!-- Page Header with Gradient -->
    <div class="text-center relative">
        <div
            class="absolute inset-0 bg-gradient-to-r from-teal-500/10 via-cyan-500/10 to-blue-500/10 rounded-3xl blur-3xl -z-10">
        </div>
        <div
            class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-3xl shadow-2xl shadow-teal-500/40 mb-6 animate-bounce-slow">
            <i class="fas fa-clipboard-check text-3xl text-white"></i>
        </div>
        <h1
            class="text-4xl font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-600 bg-clip-text text-transparent">
            ตรวจสอบสถานะเอกสาร
        </h1>
        <p class="mt-3 text-gray-600 dark:text-gray-400 max-w-lg mx-auto">
            ตรวจสอบสถานะการอัพโหลดและการตรวจเอกสารของคุณ
        </p>
    </div>

    <!-- Search Card -->
    <div class="max-w-xl mx-auto">
        <div class="glass rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-shadow duration-300">
            <form id="searchForm" class="space-y-6">
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-id-card mr-2 text-teal-500"></i>เลขบัตรประชาชน
                    </label>
                    <input type="text" id="search_input" name="search_input"
                        class="w-full px-5 py-4 text-center text-xl font-mono tracking-widest rounded-2xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 transition-all"
                        placeholder="X-XXXX-XXXXX-XX-X" maxlength="13" required>
                </div>

                <button type="submit"
                    class="w-full py-4 px-6 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-500 hover:from-teal-600 hover:via-cyan-600 hover:to-blue-600 text-white font-bold text-lg rounded-2xl shadow-lg shadow-teal-500/30 hover:shadow-xl hover:shadow-teal-500/40 transition-all transform hover:-translate-y-1 hover:scale-[1.02] group">
                    <i class="fas fa-search mr-3 group-hover:animate-pulse"></i>ค้นหาข้อมูล
                </button>
            </form>
        </div>
    </div>

    <!-- Results Section -->
    <div id="studentInfo" class="max-w-3xl mx-auto hidden animate-fade-in">
        <!-- Student Card -->
        <div class="glass rounded-3xl overflow-hidden shadow-xl mb-6">
            <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-500 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 flex items-center justify-center bg-white/20 backdrop-blur rounded-2xl">
                        <i class="fas fa-user-graduate text-3xl text-white"></i>
                    </div>
                    <div class="text-white">
                        <h3 class="text-xl font-bold" id="studentName">-</h3>
                        <p class="text-teal-100">
                            <span id="studentTypeLevel">-</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4">
                        <i class="fas fa-id-card text-2xl text-teal-500 mb-2"></i>
                        <p class="text-xs text-gray-500 dark:text-gray-400">เลขบัตร</p>
                        <p class="font-mono font-bold text-gray-900 dark:text-white" id="studentCitizenId">-</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4">
                        <i class="fas fa-layer-group text-2xl text-blue-500 mb-2"></i>
                        <p class="text-xs text-gray-500 dark:text-gray-400">ประเภท</p>
                        <p class="font-bold text-gray-900 dark:text-white" id="studentType">-</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4">
                        <i class="fas fa-graduation-cap text-2xl text-indigo-500 mb-2"></i>
                        <p class="text-xs text-gray-500 dark:text-gray-400">ระดับชั้น</p>
                        <p class="font-bold text-gray-900 dark:text-white" id="studentLevel">-</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4" id="statusCard">
                        <i class="fas fa-clipboard-check text-2xl text-amber-500 mb-2" id="statusIcon"></i>
                        <p class="text-xs text-gray-500 dark:text-gray-400">สถานะ</p>
                        <p class="font-bold" id="statusText">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Status Card -->
        <div class="glass rounded-3xl p-6 shadow-xl" id="uploadStatus">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-file-alt mr-3 text-teal-500"></i>สถานะเอกสาร
                </h4>
                <div class="flex gap-2">
                    <span
                        class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full text-xs font-medium"
                        id="approvedCount">0 อนุมัติ</span>
                    <span
                        class="px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-full text-xs font-medium"
                        id="pendingCount">0 รอตรวจ</span>
                </div>
            </div>

            <div id="documentsList" class="space-y-3">
                <!-- Documents will be populated here -->
            </div>

            <div id="noDocuments" class="text-center py-12 text-gray-400 hidden">
                <i class="fas fa-folder-open text-5xl mb-4"></i>
                <p>ไม่พบเอกสารที่อัพโหลด</p>
                <a href="upload.php"
                    class="inline-block mt-4 px-6 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-xl transition-colors">
                    <i class="fas fa-upload mr-2"></i>อัพโหลดเอกสาร
                </a>
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
                    onmouseover="this.style.borderColor='#14b8a6';this.style.background='#f0fdfa'" 
                    onmouseout="this.style.borderColor=this.querySelector('input').checked?'#14b8a6':'#e5e7eb';this.style.background=this.querySelector('input').checked?'#f0fdfa':''">
                    <input type="radio" name="reg_choice" value="${reg.id}" ${i === 0 ? 'checked' : ''} style="margin-right:10px;accent-color:#14b8a6">
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
                        confirmButtonColor: '#14b8a6',
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
                    document.getElementById('studentCitizenId').textContent = data.citizenid;
                    document.getElementById('studentType').textContent = data.typeregis;
                    document.getElementById('studentLevel').textContent = 'ม.' + data.level;
                    document.getElementById('studentTypeLevel').textContent = data.typeregis + ' | ม.' + data.level;

                    let statusText = data.docStatusText || 'รอดำเนินการ';
                    let statusClass = 'text-gray-500';
                    let iconClass = 'text-gray-500';

                    if (data.docStatus === 'complete') { statusClass = 'text-green-600'; iconClass = 'text-green-500'; }
                    else if (data.docStatus === 'pending') { statusClass = 'text-amber-600'; iconClass = 'text-amber-500'; }
                    else if (data.docStatus === 'rejected') { statusClass = 'text-red-600'; iconClass = 'text-red-500'; }
                    else if (data.docStatus === 'incomplete') { statusClass = 'text-blue-600'; iconClass = 'text-blue-500'; }

                    document.getElementById('statusText').textContent = statusText;
                    document.getElementById('statusText').className = 'font-bold ' + statusClass;
                    document.getElementById('statusIcon').className = 'fas fa-clipboard-check text-2xl mb-2 ' + iconClass;

                    document.getElementById('studentInfo').classList.remove('hidden');
                    loadDocuments(data.citizenid);
                    document.getElementById('studentInfo').scrollIntoView({ behavior: 'smooth', block: 'start' });

                } else {
                    Swal.fire({ icon: 'error', title: 'ไม่พบข้อมูล', text: 'กรุณาตรวจสอบเลขบัตรประชาชน', confirmButtonColor: '#ef4444' });
                    document.getElementById('studentInfo').classList.add('hidden');
                }
            })
            .catch(error => { Swal.close(); Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', confirmButtonColor: '#ef4444' }); });
    }

    function loadDocuments(citizenid) {
        fetch(`api/get-document-requirements.php?citizenid=${citizenid}`)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('documentsList');
                const noDocsDiv = document.getElementById('noDocuments');

                if (data.requirements && data.requirements.length > 0) {
                    let html = '';
                    let approvedCount = 0;
                    let pendingCount = 0;

                    data.requirements.forEach(req => {
                        const uploaded = data.uploaded && data.uploaded[req.id];
                        let statusBadge = '';
                        let borderClass = 'border-gray-200 dark:border-gray-700';
                        let bgClass = 'bg-gray-50 dark:bg-slate-800';

                        if (uploaded) {
                            if (uploaded.status === 'approved') {
                                statusBadge = '<span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full text-sm font-medium"><i class="fas fa-check-circle mr-1"></i>อนุมัติ</span>';
                                borderClass = 'border-green-300 dark:border-green-700';
                                bgClass = 'bg-green-50 dark:bg-green-900/20';
                                approvedCount++;
                            } else if (uploaded.status === 'rejected') {
                                statusBadge = '<span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full text-sm font-medium"><i class="fas fa-times-circle mr-1"></i>ไม่อนุมัติ</span>';
                                borderClass = 'border-red-300 dark:border-red-700';
                                bgClass = 'bg-red-50 dark:bg-red-900/20';
                            } else {
                                statusBadge = '<span class="px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-full text-sm font-medium"><i class="fas fa-clock mr-1"></i>รอตรวจสอบ</span>';
                                borderClass = 'border-amber-300 dark:border-amber-700';
                                bgClass = 'bg-amber-50 dark:bg-amber-900/20';
                                pendingCount++;
                            }
                        } else {
                            statusBadge = '<span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-500 rounded-full text-sm font-medium"><i class="fas fa-upload mr-1"></i>ยังไม่อัพโหลด</span>';
                        }

                        html += `
                <div class="flex items-center justify-between p-4 ${bgClass} border ${borderClass} rounded-2xl transition-all hover:shadow-md">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 flex items-center justify-center ${uploaded ? (uploaded.status === 'approved' ? 'bg-green-100 text-green-600' : uploaded.status === 'rejected' ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-600') : 'bg-gray-100 text-gray-400'} rounded-xl">
                            <i class="fas ${uploaded ? 'fa-file-alt' : 'fa-file-upload'} text-xl"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">${req.name}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">${req.description || 'ไม่มีคำอธิบาย'}</p>
                            ${uploaded && uploaded.status === 'rejected' && uploaded.reject_reason ? `<p class="text-xs text-red-500 mt-1"><i class="fas fa-exclamation-circle mr-1"></i>${uploaded.reject_reason}</p>` : ''}
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        ${statusBadge}
                        ${!uploaded ? `<a href="upload.php?citizenid=${citizenid}" class="px-3 py-1 bg-teal-500 hover:bg-teal-600 text-white text-sm rounded-lg transition-colors"><i class="fas fa-upload mr-1"></i>อัพโหลด</a>` : ''}
                    </div>
                </div>`;
                    });

                    container.innerHTML = html;
                    noDocsDiv.classList.add('hidden');
                    document.getElementById('approvedCount').textContent = approvedCount + ' อนุมัติ';
                    document.getElementById('pendingCount').textContent = pendingCount + ' รอตรวจ';
                } else {
                    container.innerHTML = '';
                    noDocsDiv.classList.remove('hidden');
                }
            });
    }
</script>
<!-- Document Upload View - Enhanced -->
<?php
$citizenidParam = $_GET['citizenid'] ?? '';
?>
<div class="space-y-8">
    <!-- Page Header with Gradient -->
    <div class="text-center relative">
        <div
            class="absolute inset-0 bg-gradient-to-r from-amber-500/10 via-orange-500/10 to-red-500/10 rounded-3xl blur-3xl -z-10">
        </div>
        <div
            class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-amber-500 to-orange-600 rounded-3xl shadow-2xl shadow-amber-500/40 mb-6 animate-bounce-slow">
            <i class="fas fa-cloud-upload-alt text-3xl text-white"></i>
        </div>
        <h1
            class="text-4xl font-bold bg-gradient-to-r from-amber-600 via-orange-600 to-red-600 bg-clip-text text-transparent">
            อัพโหลดหลักฐานการสมัคร
        </h1>
        <p class="mt-3 text-gray-600 dark:text-gray-400 max-w-lg mx-auto">
            อัพโหลดเอกสารประกอบการสมัครเรียน รองรับไฟล์รูปภาพและ PDF
        </p>
    </div>

    <!-- Search Card -->
    <div class="max-w-xl mx-auto">
        <div class="glass rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-shadow duration-300">
            <form id="searchForm" class="space-y-6">
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-id-card mr-2 text-amber-500"></i>เลขบัตรประชาชน
                    </label>
                    <input type="text" id="search_citizenid" name="search_citizenid"
                        class="w-full px-5 py-4 text-center text-xl font-mono tracking-widest rounded-2xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 transition-all"
                        placeholder="X-XXXX-XXXXX-XX-X" value="<?php echo htmlspecialchars($citizenidParam); ?>"
                        maxlength="13" required>
                </div>

                <button type="submit"
                    class="w-full py-4 px-6 bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 hover:from-amber-600 hover:via-orange-600 hover:to-red-600 text-white font-bold text-lg rounded-2xl shadow-lg shadow-amber-500/30 hover:shadow-xl hover:shadow-amber-500/40 transition-all transform hover:-translate-y-1 hover:scale-[1.02] group">
                    <i class="fas fa-search mr-3 group-hover:animate-pulse"></i>ค้นหาข้อมูล
                </button>
            </form>
        </div>
    </div>

    <!-- Upload Area (hidden until search) -->
    <div id="uploadArea" class="max-w-4xl mx-auto hidden animate-fade-in">
        <!-- Student Info Card -->
        <div class="glass rounded-3xl overflow-hidden shadow-xl mb-6">
            <div class="bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 flex items-center justify-center bg-white/20 backdrop-blur rounded-2xl">
                            <i class="fas fa-user-graduate text-3xl text-white"></i>
                        </div>
                        <div class="text-white">
                            <h3 class="text-xl font-bold" id="studentName">-</h3>
                            <p class="text-amber-100" id="studentType">-</p>
                        </div>
                    </div>
                    <div class="text-right text-white">
                        <div id="progressBadge" class="px-4 py-2 bg-white/20 rounded-full">
                            <i class="fas fa-clipboard-list mr-2"></i>
                            <span id="progressText">0/0</span>
                        </div>
                    </div>
                </div>
            </div>

            <div id="uploadStatus" class="hidden p-4 text-center"></div>
        </div>

        <!-- Documents Grid -->
        <div class="glass rounded-3xl p-6 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-file-alt mr-3 text-amber-500"></i>เอกสารที่ต้องอัพโหลด
                </h4>
                <div class="flex gap-2">
                    <span
                        class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full text-xs font-medium">
                        <i class="fas fa-asterisk mr-1"></i>เอกสารบังคับ
                    </span>
                </div>
            </div>

            <div id="documentList" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Dynamically populated -->
            </div>
        </div>

        <!-- Quick Links -->
        <div class="mt-6 text-center">
            <a href="check_uploads.php" class="text-gray-500 hover:text-amber-600 transition-colors mr-6">
                <i class="fas fa-clipboard-check mr-2"></i>ตรวจสอบสถานะ
            </a>
            <a href="checkreg.php" class="text-gray-500 hover:text-amber-600 transition-colors">
                <i class="fas fa-search mr-2"></i>ค้นหาข้อมูลการสมัคร
            </a>
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
    let currentCitizenid = '';
    let currentTypeId = 0;

    // Auto-search if citizenid is provided in URL
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search_citizenid').value;
        if (searchInput && searchInput.length === 13) {
            document.getElementById('searchForm').dispatchEvent(new Event('submit'));
        }
    });

    document.getElementById('searchForm').addEventListener('submit', function (event) {
        event.preventDefault();
        const citizenid = document.getElementById('search_citizenid').value.trim();

        fetchAndDisplay(citizenid);
    });

    function fetchAndDisplay(citizenid, regId) {
        Swal.fire({ title: 'กำลังค้นหา...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

        const payload = { search_input: citizenid };
        if (regId) payload.reg_id = regId;

        fetch('api/fetch_reg.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.exists && data.multiple) {
                    let html = '<div style="text-align:left">';
                    data.registrations.forEach((reg, i) => {
                        html += `<label style="display:flex;align-items:center;padding:10px;margin:5px 0;border:2px solid #e5e7eb;border-radius:12px;cursor:pointer;transition:all 0.2s" 
                    onmouseover="this.style.borderColor='#f59e0b';this.style.background='#fffbeb'" 
                    onmouseout="this.style.borderColor=this.querySelector('input').checked?'#f59e0b':'#e5e7eb';this.style.background=this.querySelector('input').checked?'#fffbeb':''">
                    <input type="radio" name="reg_choice" value="${reg.id}" ${i === 0 ? 'checked' : ''} style="margin-right:10px;accent-color:#f59e0b">
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
                        confirmButtonColor: '#f59e0b',
                        showCancelButton: true,
                        cancelButtonText: 'ยกเลิก'
                    }).then(result => {
                        if (result.isConfirmed) {
                            const selectedId = document.querySelector('input[name="reg_choice"]:checked').value;
                            fetchAndDisplay(citizenid, selectedId);
                        }
                    });
                    return;
                }
                if (data.exists) {
                    currentCitizenid = data.citizenid;
                    document.getElementById('studentName').textContent = data.fullname;
                    document.getElementById('studentType').textContent = data.typeregis + ' | ม.' + data.level;

                    window.canUpload = (data.canUpload !== false);
                    window.uploadMessage = data.uploadMessage || 'ไม่อยู่ในช่วงเวลาอัพโหลด';

                    const uploadStatusDiv = document.getElementById('uploadStatus');
                    if (!window.canUpload) {
                        uploadStatusDiv.innerHTML = '<i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i>' + window.uploadMessage;
                        uploadStatusDiv.className = 'p-4 text-center bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 font-medium border-2 border-amber-300 rounded-xl';
                        uploadStatusDiv.classList.remove('hidden');
                    } else if (data.uploadSchedule) {
                        uploadStatusDiv.innerHTML = '<i class="fas fa-check-circle text-green-500 mr-2"></i>' + data.uploadMessage;
                        uploadStatusDiv.className = 'p-4 text-center bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 font-medium';
                        uploadStatusDiv.classList.remove('hidden');
                    } else {
                        uploadStatusDiv.className = 'hidden';
                    }

                    loadDocumentRequirements(citizenid);
                    document.getElementById('uploadArea').classList.remove('hidden');
                    document.getElementById('uploadArea').scrollIntoView({ behavior: 'smooth', block: 'start' });

                } else {
                    Swal.fire({ icon: 'error', title: 'ไม่พบข้อมูล', text: 'กรุณาตรวจสอบเลขบัตรประชาชน', confirmButtonColor: '#ef4444' });
                    document.getElementById('uploadArea').classList.add('hidden');
                }
            })
            .catch(error => { Swal.close(); Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', confirmButtonColor: '#ef4444' }); });
    }

    function loadDocumentRequirements(citizenid) {
        fetch(`api/get-document-requirements.php?citizenid=${citizenid}`)
            .then(response => response.json())
            .then(data => {
                let html = '';
                let uploadedCount = 0;
                let totalRequired = 0;

                if (data.requirements && data.requirements.length > 0) {
                    data.requirements.forEach((req, index) => {
                        const uploaded = data.uploaded && data.uploaded[req.id];
                        if (req.is_required == 1) totalRequired++;
                        if (uploaded) uploadedCount++;

                        const isImage = uploaded && /\.(jpg|jpeg|png|gif|webp)$/i.test(uploaded.file_path);
                        const isPdf = uploaded && /\.pdf$/i.test(uploaded.file_path);

                        let cardClass = 'bg-gray-50 dark:bg-slate-800 border-gray-200 dark:border-gray-700';
                        let statusIcon = '<i class="fas fa-cloud-upload-alt text-3xl text-gray-300"></i>';
                        let statusText = '<span class="text-xs text-gray-400">ยังไม่ได้อัพโหลด</span>';

                        if (uploaded) {
                            if (uploaded.status === 'approved') {
                                cardClass = 'bg-green-50 dark:bg-green-900/20 border-green-300 dark:border-green-700';
                                statusIcon = '<i class="fas fa-check-circle text-3xl text-green-500"></i>';
                                statusText = '<span class="text-xs text-green-600">อนุมัติแล้ว</span>';
                            } else if (uploaded.status === 'rejected') {
                                cardClass = 'bg-red-50 dark:bg-red-900/20 border-red-300 dark:border-red-700';
                                statusIcon = '<i class="fas fa-times-circle text-3xl text-red-500"></i>';
                                statusText = '<span class="text-xs text-red-600">ไม่อนุมัติ</span>';
                            } else {
                                cardClass = 'bg-amber-50 dark:bg-amber-900/20 border-amber-300 dark:border-amber-700';
                                statusIcon = '<i class="fas fa-clock text-3xl text-amber-500"></i>';
                                statusText = '<span class="text-xs text-amber-600">รอตรวจสอบ</span>';
                            }
                        }

                        html += `
                <div class="p-5 border-2 rounded-2xl ${cardClass} hover:shadow-lg transition-all">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-16 h-16 flex flex-col items-center justify-center bg-white dark:bg-slate-700 rounded-xl shadow-sm">
                            ${uploaded && isImage ?
                                `<img src="${uploaded.file_path}" class="w-full h-full object-cover rounded-xl cursor-pointer hover:scale-105 transition-transform" onclick="viewFile('${uploaded.file_path}', '${uploaded.original_name}')">` :
                                statusIcon}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-gray-900 dark:text-white truncate">${req.name}</span>
                                ${req.is_required == 1 ? '<span class="px-2 py-0.5 bg-red-500 text-white text-xs rounded-full">บังคับ</span>' : '<span class="px-2 py-0.5 bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 text-xs rounded-full">ไม่บังคับ</span>'}
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 line-clamp-2">${req.description || 'ไม่มีคำอธิบาย'}</p>
                            <div class="flex items-center justify-between">
                                <div>
                                    ${statusText}
                                    ${uploaded ? `<p class="text-xs text-gray-400 mt-1 truncate max-w-[150px]">${uploaded.original_name}</p>` : ''}
                                </div>
                                <div class="flex gap-2">
                                    ${uploaded ? `
                                        <button onclick="viewFile('${uploaded.file_path}', '${uploaded.original_name}')" 
                                                class="p-2 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 rounded-lg transition-colors" title="ดู">
                                            <i class="fas fa-eye text-gray-600 dark:text-gray-300"></i>
                                        </button>
                                    ` : ''}
                                    <input type="file" id="file_${req.id}" class="hidden" 
                                           accept="${req.file_types.split(',').map(t => '.' + t.trim()).join(',')}"
                                           onchange="previewAndUpload(${req.id}, this, '${req.name}')">
                                    <label for="file_${req.id}" class="p-2 ${uploaded ? 'bg-amber-500 hover:bg-amber-600' : 'bg-orange-500 hover:bg-orange-600'} text-white rounded-lg cursor-pointer transition-colors" title="${uploaded ? 'เปลี่ยนไฟล์' : 'อัพโหลด'}">
                                        <i class="fas fa-${uploaded ? 'sync-alt' : 'upload'}"></i>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    ${uploaded && uploaded.status === 'rejected' && uploaded.reject_reason ? `
                        <div class="mt-3 p-2 bg-red-100 dark:bg-red-900/30 rounded-lg text-xs text-red-600 dark:text-red-400">
                            <i class="fas fa-exclamation-triangle mr-1"></i>${uploaded.reject_reason}
                        </div>
                    ` : ''}
                </div>`;
                    });

                    document.getElementById('progressText').textContent = `${uploadedCount}/${data.requirements.length}`;
                } else {
                    html = `<div class="col-span-2 text-center py-12 text-gray-400">
                <i class="fas fa-folder-open text-5xl mb-4"></i>
                <p class="text-lg">ไม่มีเอกสารที่ต้องอัพโหลด</p>
            </div>`;
                }
                document.getElementById('documentList').innerHTML = html;
            });
    }

    function previewAndUpload(requirementId, input, docName) {
        if (!input.files.length) return;

        // Check if upload is allowed by schedule
        if (!window.canUpload) {
            Swal.fire({
                icon: 'warning',
                title: 'ไม่สามารถอัพโหลดได้',
                text: window.uploadMessage || 'ไม่อยู่ในช่วงเวลาอัพโหลด',
                confirmButtonColor: '#f59e0b'
            });
            input.value = ''; // Clear file input
            return;
        }

        const file = input.files[0];
        const isImage = file.type.startsWith('image/');

        if (isImage) {
            const reader = new FileReader();
            reader.onload = function (e) {
                Swal.fire({
                    title: 'ตัวอย่างไฟล์',
                    html: `
                    <p class="text-sm text-gray-500 mb-3">${docName}</p>
                    <img src="${e.target.result}" class="max-w-full max-h-64 mx-auto rounded-lg shadow">
                    <p class="text-xs text-gray-400 mt-2">${file.name} (${(file.size / 1024).toFixed(1)} KB)</p>
                `,
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-upload mr-1"></i>อัพโหลด',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonColor: '#f59e0b'
                }).then((result) => {
                    if (result.isConfirmed) uploadFile(requirementId, file);
                });
            };
            reader.readAsDataURL(file);
        } else {
            Swal.fire({
                title: 'ยืนยันการอัพโหลด',
                html: `
                <p class="text-sm text-gray-500 mb-3">${docName}</p>
                <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                    <i class="fas fa-file-pdf text-4xl text-red-500 mb-2"></i>
                    <p class="text-sm">${file.name}</p>
                    <p class="text-xs text-gray-400">${(file.size / 1024).toFixed(1)} KB</p>
                </div>
            `,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-upload mr-1"></i>อัพโหลด',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: '#f59e0b'
            }).then((result) => {
                if (result.isConfirmed) uploadFile(requirementId, file);
            });
        }
    }

    function uploadFile(requirementId, file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('citizenid', currentCitizenid);
        formData.append('requirement_id', requirementId);

        Swal.fire({ title: 'กำลังอัพโหลด...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

        fetch('api/upload-document.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'อัพโหลดสำเร็จ!', text: 'เอกสารถูกบันทึกเรียบร้อย', timer: 1500, showConfirmButton: false });
                    loadDocumentRequirements(currentCitizenid);
                } else {
                    Swal.fire({ icon: 'error', title: 'อัพโหลดไม่สำเร็จ', text: data.error || 'กรุณาลองใหม่อีกครั้ง', confirmButtonColor: '#ef4444' });
                }
            })
            .catch(error => { Swal.close(); Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', confirmButtonColor: '#ef4444' }); });
    }

    function viewFile(filePath, fileName) {
        const isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(filePath);

        if (isImage) {
            Swal.fire({
                title: fileName,
                imageUrl: filePath,
                imageAlt: fileName,
                showCloseButton: true,
                showConfirmButton: false,
                width: 'auto',
                customClass: { image: 'max-h-[80vh] rounded-lg' }
            });
        } else {
            window.open(filePath, '_blank');
        }
    }
</script>
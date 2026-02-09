<!-- Admin Document Review View -->
<?php
// Get typeId from controller
$currentTypeId = $typeId ?? 0;
?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="glass rounded-2xl p-6 border-l-4 border-amber-500">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div
                    class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg">
                    <i class="fas fa-file-alt text-2xl text-white"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">ตรวจสอบเอกสาร</h2>
                    <p class="text-gray-600 dark:text-gray-400">ตรวจสอบและอนุมัติเอกสารที่ผู้สมัครอัพโหลด</p>
                </div>
            </div>
            <div class="flex gap-2">
                <select id="filterDocStatus"
                    class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-sm">
                    <option value="">-- สถานะเอกสาร --</option>
                    <option value="no_upload">ยังไม่อัพโหลด</option>
                    <option value="pending">รอตรวจสอบ</option>
                    <option value="complete">ครบถ้วน</option>
                    <option value="rejected">มีไม่ผ่าน</option>
                </select>
                <select id="filterStudentStatus"
                    class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-sm">
                    <option value="">-- สถานะผู้สมัคร --</option>
                    <option value="0">รอตรวจสอบ</option>
                    <option value="1">ยืนยันแล้ว</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4" id="statsRow">
        <div class="glass rounded-xl p-4 flex items-center space-x-3">
            <div class="w-10 h-10 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                <i class="fas fa-users text-blue-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white" id="totalStudents">0</p>
                <p class="text-xs text-gray-500">ผู้สมัครทั้งหมด</p>
            </div>
        </div>
        <div class="glass rounded-xl p-4 flex items-center space-x-3">
            <div class="w-10 h-10 flex items-center justify-center bg-gray-100 dark:bg-gray-900/30 rounded-lg">
                <i class="fas fa-file-upload text-gray-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-600" id="noUploadCount">0</p>
                <p class="text-xs text-gray-500">ยังไม่อัพโหลด</p>
            </div>
        </div>
        <div class="glass rounded-xl p-4 flex items-center space-x-3">
            <div class="w-10 h-10 flex items-center justify-center bg-amber-100 dark:bg-amber-900/30 rounded-lg">
                <i class="fas fa-clock text-amber-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-amber-600" id="pendingCount">0</p>
                <p class="text-xs text-gray-500">รอตรวจเอกสาร</p>
            </div>
        </div>
        <div class="glass rounded-xl p-4 flex items-center space-x-3">
            <div class="w-10 h-10 flex items-center justify-center bg-teal-100 dark:bg-teal-900/30 rounded-lg">
                <i class="fas fa-file-check text-teal-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-teal-600" id="completeCount">0</p>
                <p class="text-xs text-gray-500">เอกสารครบ</p>
            </div>
        </div>
        <div class="glass rounded-xl p-4 flex items-center space-x-3">
            <div class="w-10 h-10 flex items-center justify-center bg-green-100 dark:bg-green-900/30 rounded-lg">
                <i class="fas fa-user-check text-green-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-green-600" id="approvedCount">0</p>
                <p class="text-xs text-gray-500">อนุมัติแล้ว</p>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="glass rounded-2xl p-6">
        <div class="mb-4">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="ค้นหาชื่อ, เลขบัตร, เลขที่ผู้สมัคร..."
                    class="w-full md:w-96 px-4 py-2 pl-10 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="docsTable">
                <thead>
                    <tr
                        class="text-left text-gray-600 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-3 py-3">#</th>
                        <th class="px-3 py-3">เลขที่ผู้สมัคร</th>
                        <th class="px-3 py-3">ผู้สมัคร</th>
                        <th class="px-3 py-3">เลขบัตร</th>
                        <th class="px-3 py-3">เอกสาร</th>
                        <th class="px-3 py-3 text-center">สถานะเอกสาร</th>
                        <th class="px-3 py-3 text-center">สถานะผู้สมัคร</th>
                        <th class="px-3 py-3 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-300" id="docsTableBody">
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-400">กำลังโหลด...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black/70" onclick="closePreviewModal()"></div>
        <div class="relative glass rounded-2xl max-w-4xl w-full p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white" id="previewTitle">ตรวจสอบเอกสาร</h3>
                <button onclick="closePreviewModal()" class="text-gray-500 hover:text-gray-700"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <div class="mb-4 p-3 bg-gray-100 dark:bg-slate-800 rounded-lg">
                <p class="text-sm"><strong>ผู้สมัคร:</strong> <span id="previewStudent"></span></p>
                <p class="text-sm"><strong>เอกสาร:</strong> <span id="previewDocName"></span></p>
            </div>
            <!-- Zoom Controls -->
            <div class="flex items-center justify-center gap-2 mb-3" id="zoomControls">
                <button onclick="zoomOut()"
                    class="px-3 py-1 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 rounded-lg text-sm">
                    <i class="fas fa-search-minus"></i>
                </button>
                <span class="text-sm font-medium" id="zoomLevel">100%</span>
                <button onclick="zoomIn()"
                    class="px-3 py-1 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 rounded-lg text-sm">
                    <i class="fas fa-search-plus"></i>
                </button>
                <button onclick="resetZoom()"
                    class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm ml-2">
                    <i class="fas fa-undo mr-1"></i>รีเซ็ต
                </button>
                <button onclick="openFullscreen()"
                    class="px-3 py-1 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg text-sm">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
            <div id="previewContent"
                class="bg-gray-200 dark:bg-slate-700 rounded-lg p-2 flex items-center justify-center min-h-[400px] max-h-[60vh] overflow-auto cursor-grab"
                onwheel="handleZoomWheel(event)">
                <!-- Preview content here -->
            </div>
            <div class="flex justify-between items-center mt-6 gap-4">
                <input type="text" id="rejectReason" placeholder="เหตุผลที่ไม่อนุมัติ (ถ้ามี)"
                    class="flex-1 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-sm">
                <div class="flex gap-2">
                    <button onclick="updateDocStatus('rejected')"
                        class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg">
                        <i class="fas fa-times mr-1"></i>ไม่อนุมัติ
                    </button>
                    <button onclick="updateDocStatus('approved')"
                        class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg">
                        <i class="fas fa-check mr-1"></i>อนุมัติ
                    </button>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                <button onclick="approveStudent()"
                    class="w-full px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all">
                    <i class="fas fa-user-check mr-2"></i>อนุมัตินักเรียน (เปลี่ยนสถานะเป็น "ผ่าน")
                </button>
            </div>
            <input type="hidden" id="currentDocId">
            <input type="hidden" id="currentUserId">
        </div>
    </div>
</div>

<script>
    const currentTypeId = <?= $currentTypeId ?>;
    let allStudents = [];
    let allDocs = [];
    let requirements = [];

    function getDocStatusBadge(status) {
        switch (status) {
            case 'complete': return '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">ครบถ้วน</span>';
            case 'pending': return '<span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs rounded-full">รอตรวจ</span>';
            case 'rejected': return '<span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">มีไม่ผ่าน</span>';
            default: return '<span class="px-2 py-1 bg-gray-100 text-gray-500 text-xs rounded-full">ยังไม่อัพโหลด</span>';
        }
    }

    function getStudentStatusBadge(status) {
        if (status == 1) {
            return '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-semibold"><i class="fas fa-check-circle mr-1"></i>ยืนยันแล้ว</span>';
        }
        return '<span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">รอตรวจสอบ</span>';
    }

    function loadStudents() {
        if (!currentTypeId) {
            $('#docsTableBody').html('<tr><td colspan="8" class="text-center py-8 text-gray-400">กรุณาเลือกประเภทการสมัครจากเมนู</td></tr>');
            return;
        }

        $.get('api/admin/get-students-documents.php?type_id=' + currentTypeId, function (data) {
            if (data.error) {
                Swal.fire('เกิดข้อผิดพลาด', data.error, 'error');
                return;
            }

            allStudents = data.students || [];
            requirements = data.requirements || [];

            // Build allDocs from students' documents for preview functionality
            allDocs = [];
            allStudents.forEach(s => {
                s.documents.forEach(d => {
                    allDocs.push({ ...d, citizenid: s.citizenid, fullname: s.fullname, user_id: s.user_id });
                });
            });

            updateStats(data.stats || {});
            renderTable();
        });
    }

    function updateStats(stats) {
        $('#totalStudents').text(stats.total || 0);
        $('#noUploadCount').text(stats.no_upload || 0);
        $('#pendingCount').text(stats.pending || 0);
        $('#completeCount').text(stats.complete || 0);
        $('#approvedCount').text(stats.approved || 0);
    }

    function renderTable() {
        const filterDocStatus = $('#filterDocStatus').val();
        const filterStudentStatus = $('#filterStudentStatus').val();
        const searchQuery = $('#searchInput').val().toLowerCase().trim();
        let filtered = allStudents;

        // Filter by document status
        if (filterDocStatus) {
            filtered = filtered.filter(s => s.doc_status === filterDocStatus);
        }

        // Filter by student status
        if (filterStudentStatus !== '') {
            filtered = filtered.filter(s => String(s.status) === filterStudentStatus);
        }

        // Search filter
        if (searchQuery) {
            filtered = filtered.filter(s => {
                const name = (s.fullname || '').toLowerCase();
                const citizenid = (s.citizenid || '').toLowerCase();
                const numreg = (s.numreg || '').toString().toLowerCase();
                return name.includes(searchQuery) || citizenid.includes(searchQuery) || numreg.includes(searchQuery);
            });
        }

        if (filtered.length === 0) {
            $('#docsTableBody').html('<tr><td colspan="8" class="text-center py-8 text-gray-400">ไม่พบข้อมูล</td></tr>');
            return;
        }

        let html = '';
        filtered.forEach((student, index) => {
            // Build document list
            let docList = '<div class="space-y-1">';
            if (student.documents.length > 0) {
                student.documents.forEach(d => {
                    const icon = d.status === 'approved' ? '<i class="fas fa-check-circle text-green-500"></i>' :
                        d.status === 'rejected' ? '<i class="fas fa-times-circle text-red-500"></i>' :
                            '<i class="fas fa-clock text-amber-500"></i>';
                    docList += `<div class="flex items-center gap-2 text-xs cursor-pointer hover:text-blue-500" onclick="openPreview(${d.id}, ${student.user_id})">
                    ${icon}
                    <span class="truncate max-w-[200px]">${d.doc_name}</span>
                </div>`;
                });
            } else {
                // Show required documents that are missing
                if (requirements.length > 0) {
                    requirements.forEach(r => {
                        docList += `<div class="flex items-center gap-2 text-xs text-gray-400">
                        <i class="fas fa-file text-gray-300"></i>
                        <span class="truncate max-w-[200px]">${r.name}</span>
                        <span class="text-red-400">(ยังไม่อัพโหลด)</span>
                    </div>`;
                    });
                } else {
                    docList += '<span class="text-gray-400 text-xs">ยังไม่มีเอกสาร</span>';
                }
            }
            docList += '</div>';

            html += `<tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700/50">
            <td class="px-3 py-3 text-center text-gray-500">${index + 1}</td>
            <td class="px-3 py-3 font-mono text-sm font-bold text-blue-600">${student.numreg || '-'}</td>
            <td class="px-3 py-3 font-medium">${student.fullname || '-'}</td>
            <td class="px-3 py-3 font-mono text-sm">${student.citizenid}</td>
            <td class="px-3 py-3">${docList}</td>
            <td class="px-3 py-3 text-center">${getDocStatusBadge(student.doc_status)}</td>
            <td class="px-3 py-3 text-center">${getStudentStatusBadge(student.status)}</td>
            <td class="px-3 py-3 text-center">
                <div class="flex flex-col gap-1">
                    ${student.documents.length > 0 ? `<button onclick="openStudentDocs('${student.citizenid}')" class="px-3 py-1 bg-indigo-500 hover:bg-indigo-600 text-white text-xs rounded-lg">
                        <i class="fas fa-folder-open mr-1"></i>ตรวจสอบ
                    </button>` : ''}
                    ${student.status != 1 ? `<button onclick="approveStudentDirect(${student.user_id}, '${student.fullname}')" class="px-3 py-1 bg-emerald-500 hover:bg-emerald-600 text-white text-xs rounded-lg">
                        <i class="fas fa-user-check mr-1"></i>อนุมัติ
                    </button>` : ''}
                </div>
            </td>
        </tr>`;
        });

        $('#docsTableBody').html(html);
    }

    // Preview and approval functions
    function openPreview(docId, userId) {
        const doc = allDocs.find(d => d.id == docId);
        if (!doc) return;

        currentZoom = 100;
        updateZoomDisplay();

        $('#currentDocId').val(docId);
        $('#currentUserId').val(userId);
        $('#previewTitle').text('ตรวจสอบเอกสาร');
        $('#previewStudent').text(doc.fullname + ' (' + doc.citizenid + ')');
        $('#previewDocName').text(doc.doc_name);
        $('#rejectReason').val(doc.reject_reason || '');

        const isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(doc.file_path);
        const isPdf = /\.pdf$/i.test(doc.file_path);

        if (isImage) {
            currentImageSrc = '../' + doc.file_path;
            $('#previewContent').html(`<img id="zoomableImage" src="${currentImageSrc}" class="max-w-none transition-transform duration-200" style="transform: scale(1);">`);
            $('#zoomControls').show();
        } else if (isPdf) {
            currentImageSrc = '../' + doc.file_path;
            $('#previewContent').html(`<iframe src="${currentImageSrc}" class="w-full h-[60vh] rounded-lg"></iframe>`);
            $('#zoomControls').hide();
        } else {
            currentImageSrc = '../' + doc.file_path;
            $('#previewContent').html(`<a href="${currentImageSrc}" target="_blank" class="text-blue-500 hover:underline"><i class="fas fa-download mr-2"></i>ดาวน์โหลดไฟล์</a>`);
            $('#zoomControls').hide();
        }

        document.getElementById('previewModal').classList.remove('hidden');
    }

    function closePreviewModal() {
        document.getElementById('previewModal').classList.add('hidden');
    }

    function updateDocStatus(status) {
        const docId = $('#currentDocId').val();
        const reason = $('#rejectReason').val();

        $.post('api/admin/update-document-status.php', {
            id: docId,
            status: status,
            reject_reason: reason
        }, function (response) {
            if (response.success) {
                closePreviewModal();
                loadStudents();
                Swal.fire({ icon: 'success', title: status === 'approved' ? 'อนุมัติแล้ว' : 'ไม่อนุมัติ', timer: 1000, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: response.error });
            }
        }, 'json');
    }

    function approveStudent() {
        const userId = $('#currentUserId').val();
        if (!userId) return;

        const student = allStudents.find(s => s.user_id == userId);
        if (!student) return;

        Swal.fire({
            title: 'ยืนยันการอนุมัตินักเรียน?',
            html: `<p>คุณต้องการเปลี่ยนสถานะของ <strong>${student.fullname}</strong> เป็น "ผ่านการตรวจสอบ" ใช่หรือไม่?</p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            confirmButtonText: 'ใช่, อนุมัติ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('api/admin/approve-student.php', {
                    student_id: userId
                }, function (response) {
                    if (response.success) {
                        closePreviewModal();
                        loadStudents();
                        Swal.fire({
                            icon: 'success',
                            title: 'อนุมัติสำเร็จ!',
                            text: 'นักเรียนถูกเปลี่ยนสถานะเป็น "ผ่านการตรวจสอบ" เรียบร้อยแล้ว',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: response.error });
                    }
                }, 'json');
            }
        });
    }

    function approveStudentDirect(userId, fullname) {
        Swal.fire({
            title: 'ยืนยันการอนุมัตินักเรียน?',
            html: `<p>คุณต้องการเปลี่ยนสถานะของ <strong>${fullname}</strong> เป็น "ผ่านการตรวจสอบ" ใช่หรือไม่?</p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            confirmButtonText: 'ใช่, อนุมัติ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('api/admin/approve-student.php', {
                    student_id: userId
                }, function (response) {
                    if (response.success) {
                        loadStudents();
                        Swal.fire({
                            icon: 'success',
                            title: 'อนุมัติสำเร็จ!',
                            text: 'นักเรียนถูกเปลี่ยนสถานะเป็น "ผ่านการตรวจสอบ" เรียบร้อยแล้ว',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: response.error });
                    }
                }, 'json');
            }
        });
    }

    // Student documents navigation
    let currentStudentDocs = [];
    let currentDocIndex = 0;

    function openStudentDocs(citizenid) {
        currentStudentDocs = allDocs.filter(d => d.citizenid === citizenid);
        currentDocIndex = 0;
        if (currentStudentDocs.length > 0) {
            openPreviewWithNav(currentDocIndex);
        }
    }

    function openPreviewWithNav(index) {
        if (index < 0 || index >= currentStudentDocs.length) return;
        currentDocIndex = index;
        const doc = currentStudentDocs[index];

        openPreview(doc.id, doc.user_id);

        setTimeout(() => {
            if ($('#docNavigation').length === 0) {
                $('#previewModal .relative.glass').prepend(`
                <div id="docNavigation" class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg mb-4">
                    <button onclick="prevDoc()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg disabled:opacity-50" ${index === 0 ? 'disabled' : ''}>
                        <i class="fas fa-chevron-left mr-1"></i>ก่อนหน้า
                    </button>
                    <span class="font-bold text-blue-700 dark:text-blue-300">
                        เอกสารที่ ${index + 1} / ${currentStudentDocs.length}
                    </span>
                    <button onclick="nextDoc()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg disabled:opacity-50" ${index === currentStudentDocs.length - 1 ? 'disabled' : ''}>
                        ถัดไป<i class="fas fa-chevron-right ml-1"></i>
                    </button>
                </div>
            `);
            } else {
                $('#docNavigation').html(`
                <button onclick="prevDoc()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg disabled:opacity-50" ${index === 0 ? 'disabled' : ''}>
                    <i class="fas fa-chevron-left mr-1"></i>ก่อนหน้า
                </button>
                <span class="font-bold text-blue-700 dark:text-blue-300">
                    เอกสารที่ ${index + 1} / ${currentStudentDocs.length}
                </span>
                <button onclick="nextDoc()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg disabled:opacity-50" ${index === currentStudentDocs.length - 1 ? 'disabled' : ''}>
                    ถัดไป<i class="fas fa-chevron-right ml-1"></i>
                </button>
            `);
            }
        }, 100);
    }

    function prevDoc() {
        if (currentDocIndex > 0) openPreviewWithNav(currentDocIndex - 1);
    }

    function nextDoc() {
        if (currentDocIndex < currentStudentDocs.length - 1) openPreviewWithNav(currentDocIndex + 1);
    }

    // Zoom functionality
    let currentZoom = 100;
    let currentImageSrc = '';

    function updateZoomDisplay() {
        $('#zoomLevel').text(currentZoom + '%');
        const img = document.getElementById('zoomableImage');
        if (img) {
            img.style.transform = `scale(${currentZoom / 100})`;
            img.style.transformOrigin = 'center center';
        }
    }

    function zoomIn() { if (currentZoom < 300) { currentZoom += 25; updateZoomDisplay(); } }
    function zoomOut() { if (currentZoom > 25) { currentZoom -= 25; updateZoomDisplay(); } }
    function resetZoom() { currentZoom = 100; updateZoomDisplay(); }
    function handleZoomWheel(event) { event.preventDefault(); event.deltaY < 0 ? zoomIn() : zoomOut(); }
    function openFullscreen() { if (currentImageSrc) window.open(currentImageSrc, '_blank'); }

    $('#filterDocStatus').on('change', renderTable);
    $('#filterStudentStatus').on('change', renderTable);
    $('#searchInput').on('keyup', renderTable);

    $(document).ready(function () {
        loadStudents();
    });
</script>
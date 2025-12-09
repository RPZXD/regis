
<!-- Admin Document Review View -->
<div class="space-y-6">
    <!-- Page Header -->
    <div class="glass rounded-2xl p-6 border-l-4 border-amber-500">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg">
                    <i class="fas fa-file-alt text-2xl text-white"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">ตรวจสอบเอกสาร</h2>
                    <p class="text-gray-600 dark:text-gray-400">ตรวจสอบและอนุมัติเอกสารที่ผู้สมัครอัพโหลด</p>
                </div>
            </div>
            <div class="flex gap-2">
                <select id="filterStatus" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-sm">
                    <option value="">-- สถานะทั้งหมด --</option>
                    <option value="pending">รอตรวจสอบ</option>
                    <option value="approved">อนุมัติแล้ว</option>
                    <option value="rejected">ไม่อนุมัติ</option>
                </select>
                <select id="filterType" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-sm">
                    <option value="">-- ประเภททั้งหมด --</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4" id="statsRow">
        <div class="glass rounded-xl p-4 flex items-center space-x-3">
            <div class="w-10 h-10 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                <i class="fas fa-file-upload text-blue-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white" id="totalDocs">0</p>
                <p class="text-xs text-gray-500">เอกสารทั้งหมด</p>
            </div>
        </div>
        <div class="glass rounded-xl p-4 flex items-center space-x-3">
            <div class="w-10 h-10 flex items-center justify-center bg-amber-100 dark:bg-amber-900/30 rounded-lg">
                <i class="fas fa-clock text-amber-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-amber-600" id="pendingDocs">0</p>
                <p class="text-xs text-gray-500">รอตรวจสอบ</p>
            </div>
        </div>
        <div class="glass rounded-xl p-4 flex items-center space-x-3">
            <div class="w-10 h-10 flex items-center justify-center bg-green-100 dark:bg-green-900/30 rounded-lg">
                <i class="fas fa-check-circle text-green-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-green-600" id="approvedDocs">0</p>
                <p class="text-xs text-gray-500">อนุมัติแล้ว</p>
            </div>
        </div>
        <div class="glass rounded-xl p-4 flex items-center space-x-3">
            <div class="w-10 h-10 flex items-center justify-center bg-red-100 dark:bg-red-900/30 rounded-lg">
                <i class="fas fa-times-circle text-red-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-red-600" id="rejectedDocs">0</p>
                <p class="text-xs text-gray-500">ไม่อนุมัติ</p>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="glass rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="docsTable">
                <thead>
                    <tr class="text-left text-gray-600 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-3 py-3">ผู้สมัคร</th>
                        <th class="px-3 py-3">เลขบัตร</th>
                        <th class="px-3 py-3">ประเภท</th>
                        <th class="px-3 py-3">เอกสาร</th>
                        <th class="px-3 py-3 text-center">สถานะ</th>
                        <th class="px-3 py-3 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-300" id="docsTableBody">
                    <tr><td colspan="7" class="text-center py-8 text-gray-400">กำลังโหลด...</td></tr>
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
                <button onclick="closePreviewModal()" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="mb-4 p-3 bg-gray-100 dark:bg-slate-800 rounded-lg">
                <p class="text-sm"><strong>ผู้สมัคร:</strong> <span id="previewStudent"></span></p>
                <p class="text-sm"><strong>เอกสาร:</strong> <span id="previewDocName"></span></p>
            </div>
            <!-- Zoom Controls -->
            <div class="flex items-center justify-center gap-2 mb-3" id="zoomControls">
                <button onclick="zoomOut()" class="px-3 py-1 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 rounded-lg text-sm">
                    <i class="fas fa-search-minus"></i>
                </button>
                <span class="text-sm font-medium" id="zoomLevel">100%</span>
                <button onclick="zoomIn()" class="px-3 py-1 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 rounded-lg text-sm">
                    <i class="fas fa-search-plus"></i>
                </button>
                <button onclick="resetZoom()" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm ml-2">
                    <i class="fas fa-undo mr-1"></i>รีเซ็ต
                </button>
                <button onclick="openFullscreen()" class="px-3 py-1 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg text-sm">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
            <div id="previewContent" class="bg-gray-200 dark:bg-slate-700 rounded-lg p-2 flex items-center justify-center min-h-[400px] max-h-[60vh] overflow-auto cursor-grab" 
                 onwheel="handleZoomWheel(event)">
                <!-- Preview content here -->
            </div>
            <div class="flex justify-between items-center mt-6 gap-4">
                <input type="text" id="rejectReason" placeholder="เหตุผลที่ไม่อนุมัติ (ถ้ามี)" class="flex-1 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-sm">
                <div class="flex gap-2">
                    <button onclick="updateDocStatus('rejected')" class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg">
                        <i class="fas fa-times mr-1"></i>ไม่อนุมัติ
                    </button>
                    <button onclick="updateDocStatus('approved')" class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg">
                        <i class="fas fa-check mr-1"></i>อนุมัติ
                    </button>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                <button onclick="approveStudent()" class="w-full px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all">
                    <i class="fas fa-user-check mr-2"></i>อนุมัตินักเรียน (เปลี่ยนสถานะเป็น "ผ่าน")
                </button>
            </div>
            <input type="hidden" id="currentDocId">
        </div>
    </div>
</div>

<script>
let allDocs = [];

function getStatusBadge(status) {
    if (status === 'approved') return '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">อนุมัติ</span>';
    if (status === 'rejected') return '<span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">ไม่อนุมัติ</span>';
    return '<span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs rounded-full">รอตรวจ</span>';
}

function loadDocuments() {
    const status = $('#filterStatus').val();
    const typeId = $('#filterType').val();
    let url = 'api/admin/get-uploaded-documents.php?';
    if (status) url += 'status=' + status + '&';
    if (typeId) url += 'type_id=' + typeId;
    
    $.get(url, function(data) {
        allDocs = data.documents || [];
        renderTable(allDocs);
        updateStats(data.stats || {});
        
        // Populate type filter on first load
        if (data.types && $('#filterType option').length <= 1) {
            data.types.forEach(t => {
                $('#filterType').append(`<option value="${t.id}">${t.grade_name} - ${t.name}</option>`);
            });
        }
    });
}

function renderTable(docs) {
    let html = '';
    if (docs.length === 0) {
        html = '<tr><td colspan="6" class="text-center py-8 text-gray-400">ไม่พบเอกสาร</td></tr>';
    } else {
        // Group documents by citizenid (student)
        const grouped = {};
        docs.forEach(d => {
            if (!grouped[d.citizenid]) {
                grouped[d.citizenid] = {
                    citizenid: d.citizenid,
                    fullname: d.fullname,
                    type_name: d.type_name,
                    user_id: d.user_id,
                    documents: []
                };
            }
            grouped[d.citizenid].documents.push(d);
        });

        // Render each student as one row
        Object.values(grouped).forEach(student => {
            const allApproved = student.documents.every(d => d.status === 'approved');
            const hasPending = student.documents.some(d => d.status === 'pending' || !d.status);
            const hasRejected = student.documents.some(d => d.status === 'rejected');
            
            let overallStatus = '';
            if (allApproved) {
                overallStatus = '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">ครบถ้วน</span>';
            } else if (hasRejected) {
                overallStatus = '<span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">มีไม่ผ่าน</span>';
            } else if (hasPending) {
                overallStatus = '<span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs rounded-full">รอตรวจ</span>';
            }

            // Document list with status badges
            let docList = '<div class="space-y-1">';
            student.documents.forEach(d => {
                const statusIcon = d.status === 'approved' ? '<i class="fas fa-check-circle text-green-500"></i>' : 
                                   d.status === 'rejected' ? '<i class="fas fa-times-circle text-red-500"></i>' : 
                                   '<i class="fas fa-clock text-amber-500"></i>';
                docList += `<div class="flex items-center gap-2 text-xs cursor-pointer hover:text-blue-500" onclick="openPreview(${d.id})">
                    ${statusIcon}
                    <span class="truncate max-w-[200px]">${d.doc_name}</span>
                </div>`;
            });
            docList += '</div>';

            html += `<tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700/50">
                <td class="px-3 py-3 font-medium">${student.fullname || '-'}</td>
                <td class="px-3 py-3 font-mono text-sm">${student.citizenid}</td>
                <td class="px-3 py-3 text-xs">${student.type_name || '-'}</td>
                <td class="px-3 py-3">${docList}</td>
                <td class="px-3 py-3 text-center">${overallStatus}</td>
                <td class="px-3 py-3 text-center">
                    <div class="flex flex-col gap-1">
                        <button onclick="openStudentDocs('${student.citizenid}')" class="px-3 py-1 bg-indigo-500 hover:bg-indigo-600 text-white text-xs rounded-lg">
                            <i class="fas fa-folder-open mr-1"></i>ตรวจสอบทั้งหมด
                        </button>
                        ${allApproved ? `<button onclick="approveStudentDirect(${student.user_id}, '${student.fullname}')" class="px-3 py-1 bg-emerald-500 hover:bg-emerald-600 text-white text-xs rounded-lg">
                            <i class="fas fa-user-check mr-1"></i>อนุมัตินักเรียน
                        </button>` : ''}
                    </div>
                </td>
            </tr>`;
        });
    }
    $('#docsTableBody').html(html);
}

function updateStats(stats) {
    $('#totalDocs').text(stats.total || 0);
    $('#pendingDocs').text(stats.pending || 0);
    $('#approvedDocs').text(stats.approved || 0);
    $('#rejectedDocs').text(stats.rejected || 0);
}

function openPreview(docId) {
    const doc = allDocs.find(d => d.id == docId);
    if (!doc) return;
    
    $('#currentDocId').val(docId);
    $('#previewTitle').text('ตรวจสอบเอกสาร');
    $('#previewStudent').text(doc.fullname + ' (' + doc.citizenid + ')');
    $('#previewDocName').text(doc.doc_name);
    $('#rejectReason').val(doc.reject_reason || '');
    
    const isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(doc.file_path);
    const isPdf = /\.pdf$/i.test(doc.file_path);
    
    if (isImage) {
        $('#previewContent').html(`<img src="../${doc.file_path}" class="max-w-full max-h-[60vh] rounded-lg">`);
    } else if (isPdf) {
        $('#previewContent').html(`<iframe src="../${doc.file_path}" class="w-full h-[60vh] rounded-lg"></iframe>`);
    } else {
        $('#previewContent').html(`<a href="../${doc.file_path}" target="_blank" class="text-blue-500 hover:underline"><i class="fas fa-download mr-2"></i>ดาวน์โหลดไฟล์</a>`);
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
    }, function(response) {
        if (response.success) {
            closePreviewModal();
            loadDocuments();
            Swal.fire({ icon: 'success', title: status === 'approved' ? 'อนุมัติแล้ว' : 'ไม่อนุมัติ', timer: 1000, showConfirmButton: false });
        } else {
            Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: response.error });
        }
    }, 'json');
}

function approveStudent() {
    const docId = $('#currentDocId').val();
    const doc = allDocs.find(d => d.id == docId);
    if (!doc) return;
    
    Swal.fire({
        title: 'ยืนยันการอนุมัตินักเรียน?',
        html: `<p>คุณต้องการเปลี่ยนสถานะของ <strong>${doc.fullname}</strong> เป็น "ผ่านการตรวจสอบ" ใช่หรือไม่?</p>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        confirmButtonText: 'ใช่, อนุมัติ',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('api/admin/approve-student.php', {
                student_id: doc.user_id
            }, function(response) {
                if (response.success) {
                    closePreviewModal();
                    loadDocuments();
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

// Open all documents of a student for review
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
    
    openPreview(doc.id);
    
    // Update navigation info
    setTimeout(() => {
        // Add navigation buttons if not exists
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
            // Update existing navigation
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
    if (currentDocIndex > 0) {
        openPreviewWithNav(currentDocIndex - 1);
    }
}

function nextDoc() {
    if (currentDocIndex < currentStudentDocs.length - 1) {
        openPreviewWithNav(currentDocIndex + 1);
    }
}

// Approve student directly from table row
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
            }, function(response) {
                if (response.success) {
                    loadDocuments();
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

$('#filterStatus, #filterType').on('change', loadDocuments);

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

function zoomIn() {
    if (currentZoom < 300) {
        currentZoom += 25;
        updateZoomDisplay();
    }
}

function zoomOut() {
    if (currentZoom > 25) {
        currentZoom -= 25;
        updateZoomDisplay();
    }
}

function resetZoom() {
    currentZoom = 100;
    updateZoomDisplay();
}

function handleZoomWheel(event) {
    event.preventDefault();
    if (event.deltaY < 0) {
        zoomIn();
    } else {
        zoomOut();
    }
}

function openFullscreen() {
    if (currentImageSrc) {
        window.open(currentImageSrc, '_blank');
    }
}

// Override openPreview to set up zoomable image
const originalOpenPreview = openPreview;
openPreview = function(docId) {
    const doc = allDocs.find(d => d.id == docId);
    if (!doc) return;
    
    currentZoom = 100;
    updateZoomDisplay();
    
    $('#currentDocId').val(docId);
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
};

$(document).ready(function() {
    loadDocuments();
});
</script>


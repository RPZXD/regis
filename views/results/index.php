<!-- Results Announcement View -->
<div class="space-y-8">
    <!-- Page Header -->
    <div class="text-center">
        <h1 class="text-3xl font-bold gradient-text">ประกาศผลการคัดเลือก</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">โรงเรียนพิชัย ปีการศึกษา <?php echo $academicYear; ?></p>
    </div>

    <!-- Search Box -->
    <div class="max-w-2xl mx-auto glass rounded-2xl p-6">
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg shadow-indigo-500/30">
                <i class="fas fa-search text-white"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">ค้นหาผลการคัดเลือก</h3>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3">
            <input type="text" id="searchInput" 
                   class="flex-1 px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                   placeholder="กรอกเลขบัตรประชาชน หรือ ชื่อ-นามสกุล" 
                   maxlength="50">
            <button onclick="searchResult()" 
                    class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all transform hover:-translate-y-1">
                <i class="fas fa-search mr-2"></i>ค้นหา
            </button>
        </div>
        
        <!-- Search Result -->
        <div id="searchResult" class="mt-6 hidden">
            <!-- Result will be populated by JavaScript -->
        </div>
    </div>

    <!-- Result Types Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- M.1 Results -->
        <div class="glass rounded-2xl p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-12 h-12 flex items-center justify-center bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl shadow-lg shadow-blue-500/30">
                    <span class="text-xl font-bold text-white">ม.1</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">ประกาศผล ม.1</h3>
            </div>
            
            <div class="space-y-3">
                <?php if (!empty($m1Results)): ?>
                    <?php foreach ($m1Results as $result): ?>
                    <a href="<?php echo $result['file_url']; ?>" target="_blank" 
                       class="flex items-center justify-between p-4 bg-white dark:bg-slate-700 rounded-xl hover:bg-blue-50 dark:hover:bg-slate-600 transition-colors group">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                            <span class="text-gray-700 dark:text-gray-300"><?php echo htmlspecialchars($result['title']); ?></span>
                        </div>
                        <i class="fas fa-external-link-alt text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                    </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="flex flex-col items-center justify-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="fas fa-clock text-4xl mb-3 opacity-30"></i>
                        <p>ยังไม่มีประกาศผล</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- M.4 Results -->
        <div class="glass rounded-2xl p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-12 h-12 flex items-center justify-center bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg shadow-purple-500/30">
                    <span class="text-xl font-bold text-white">ม.4</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">ประกาศผล ม.4</h3>
            </div>
            
            <div class="space-y-3">
                <?php if (!empty($m4Results)): ?>
                    <?php foreach ($m4Results as $result): ?>
                    <a href="<?php echo $result['file_url']; ?>" target="_blank" 
                       class="flex items-center justify-between p-4 bg-white dark:bg-slate-700 rounded-xl hover:bg-purple-50 dark:hover:bg-slate-600 transition-colors group">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                            <span class="text-gray-700 dark:text-gray-300"><?php echo htmlspecialchars($result['title']); ?></span>
                        </div>
                        <i class="fas fa-external-link-alt text-gray-400 group-hover:text-purple-500 transition-colors"></i>
                    </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="flex flex-col items-center justify-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="fas fa-clock text-4xl mb-3 opacity-30"></i>
                        <p>ยังไม่มีประกาศผล</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- PDF Viewer (if any) -->
    <?php if (isset($media_sets['file_result1']) && !empty($media_sets['file_result1'])): ?>
    <div class="glass rounded-2xl p-6">
        <div class="flex items-center space-x-3 mb-6">
            <div class="w-12 h-12 flex items-center justify-center bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl shadow-lg shadow-emerald-500/30">
                <i class="fas fa-trophy text-xl text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">ประกาศผลคัดเลือก</h3>
        </div>
        
        <div class="rounded-xl overflow-hidden bg-white dark:bg-slate-800 shadow-inner">
            <iframe src="<?php echo $media_sets['file_result1']; ?>" 
                    class="w-full" 
                    style="height: 800px;" 
                    allow="autoplay"
                    loading="lazy"></iframe>
        </div>
    </div>
    <?php endif; ?>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="confirm.php" class="group glass rounded-2xl p-6 hover:shadow-xl transition-all transform hover:-translate-y-2">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                    <i class="fas fa-check-circle text-2xl text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">ยืนยันสิทธิ์</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">สำหรับผู้ผ่านการคัดเลือก</p>
                </div>
                <i class="fas fa-arrow-right text-gray-400 ml-auto group-hover:text-emerald-500 transition-colors"></i>
            </div>
        </a>

        <a href="checkreg.php" class="group glass rounded-2xl p-6 hover:shadow-xl transition-all transform hover:-translate-y-2">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                    <i class="fas fa-search text-2xl text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">ตรวจสอบสถานะ</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">ดูสถานะการสมัคร</p>
                </div>
                <i class="fas fa-arrow-right text-gray-400 ml-auto group-hover:text-blue-500 transition-colors"></i>
            </div>
        </a>

        <a href="contact.php" class="group glass rounded-2xl p-6 hover:shadow-xl transition-all transform hover:-translate-y-2">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform">
                    <i class="fas fa-phone-alt text-2xl text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">ติดต่อสอบถาม</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">มีคำถาม? ติดต่อเรา</p>
                </div>
                <i class="fas fa-arrow-right text-gray-400 ml-auto group-hover:text-amber-500 transition-colors"></i>
            </div>
        </a>
    </div>
</div>

<script>
function searchResult() {
    const searchInput = document.getElementById('searchInput').value.trim();
    const resultDiv = document.getElementById('searchResult');
    
    if (!searchInput) {
        Swal.fire({
            icon: 'warning',
            title: 'กรุณากรอกข้อมูล',
            text: 'กรอกเลขบัตรประชาชน หรือ ชื่อ-นามสกุล',
            confirmButtonColor: '#6366f1'
        });
        return;
    }
    
    resultDiv.classList.remove('hidden');
    resultDiv.innerHTML = '<div class="flex justify-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-primary-500"></i></div>';
    
    fetch('api/check-result.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ search: searchInput })
    })
    .then(res => res.json())
    .then(data => {
        if (data.found) {
            let statusClass = data.status === 'passed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 
                             data.status === 'failed' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : 
                             'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
            let statusIcon = data.status === 'passed' ? 'fa-check-circle text-green-500' : 
                            data.status === 'failed' ? 'fa-times-circle text-red-500' : 
                            'fa-clock text-yellow-500';
            let statusText = data.status === 'passed' ? 'ผ่านการคัดเลือก' : 
                            data.status === 'failed' ? 'ไม่ผ่านการคัดเลือก' : 
                            'รอประกาศผล';
            
            resultDiv.innerHTML = `
                <div class="p-6 bg-white dark:bg-slate-700 rounded-xl border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center space-x-4 mb-4">
                        <i class="fas ${statusIcon} text-4xl"></i>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white">${data.fullname}</h4>
                            <p class="text-gray-600 dark:text-gray-400">${data.level} - ${data.typeregis}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="px-4 py-2 rounded-full ${statusClass} font-medium">
                            ${statusText}
                        </span>
                        ${data.exam_room ? `<span class="text-gray-600 dark:text-gray-400"><i class="fas fa-door-open mr-1"></i>ห้องสอบ: ${data.exam_room}</span>` : ''}
                        ${data.seat_number ? `<span class="text-gray-600 dark:text-gray-400"><i class="fas fa-chair mr-1"></i>เลขที่นั่ง: ${data.seat_number}</span>` : ''}
                    </div>
                    ${data.status === 'passed' ? `
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                        <a href="confirm.php" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-lg hover:from-emerald-600 hover:to-green-700 transition-colors">
                            <i class="fas fa-check-circle mr-2"></i>ดำเนินการยืนยันสิทธิ์
                        </a>
                    </div>
                    ` : ''}
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="p-6 bg-gray-100 dark:bg-slate-700 rounded-xl text-center">
                    <i class="fas fa-search text-4xl text-gray-400 mb-3"></i>
                    <p class="text-gray-600 dark:text-gray-400">${data.message || 'ไม่พบข้อมูล กรุณาตรวจสอบข้อมูลอีกครั้ง'}</p>
                </div>
            `;
        }
    })
    .catch(error => {
        resultDiv.innerHTML = `
            <div class="p-6 bg-red-100 dark:bg-red-900/30 rounded-xl text-center">
                <i class="fas fa-exclamation-circle text-4xl text-red-500 mb-3"></i>
                <p class="text-red-600 dark:text-red-400">เกิดข้อผิดพลาด: ${error.message}</p>
            </div>
        `;
    });
}

// Enter key to search
document.getElementById('searchInput')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') searchResult();
});
</script>

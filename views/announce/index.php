<!-- Announcement View -->
<div class="space-y-8">
    <!-- Page Header -->
    <div class="text-center">
        <h1 class="text-3xl font-bold gradient-text">ประกาศรับสมัครนักเรียน</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">โรงเรียนพิชัย ปีการศึกษา <?php echo $academicYear; ?></p>
    </div>

    <!-- Main Announcement -->
    <div class="glass rounded-2xl p-6">
        <div class="flex items-center space-x-3 mb-6">
            <div class="w-12 h-12 flex items-center justify-center bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg shadow-amber-500/30">
                <i class="fas fa-bullhorn text-xl text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">ประกาศรับสมัคร</h3>
        </div>
        
        <div class="rounded-xl overflow-hidden bg-white dark:bg-slate-800 shadow-inner">
            <?php if (isset($media_sets['file_reg1']) && !empty($media_sets['file_reg1'])): ?>
            <iframe src="<?php echo $media_sets['file_reg1']; ?>" 
                    class="w-full" 
                    style="height: 800px;" 
                    allow="autoplay"
                    loading="lazy"></iframe>
            <?php else: ?>
            <div class="flex flex-col items-center justify-center py-16 text-gray-500 dark:text-gray-400">
                <i class="fas fa-file-pdf text-6xl mb-4 opacity-30"></i>
                <p class="text-lg">ยังไม่มีประกาศในขณะนี้</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="regis.php" class="group glass rounded-2xl p-6 hover:shadow-xl transition-all transform hover:-translate-y-2">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-plus text-2xl text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">สมัครเรียน</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">เริ่มกรอกใบสมัครเลย</p>
                </div>
                <i class="fas fa-arrow-right text-gray-400 ml-auto group-hover:text-emerald-500 transition-colors"></i>
            </div>
        </a>

        <a href="contact.php" class="group glass rounded-2xl p-6 hover:shadow-xl transition-all transform hover:-translate-y-2">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                    <i class="fas fa-phone-alt text-2xl text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">ติดต่อสอบถาม</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">มีคำถาม? ติดต่อเราได้</p>
                </div>
                <i class="fas fa-arrow-right text-gray-400 ml-auto group-hover:text-blue-500 transition-colors"></i>
            </div>
        </a>
    </div>
</div>

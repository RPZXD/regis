<!-- Upload Selection View -->
<div class="space-y-8">
    <!-- Page Header -->
    <div class="text-center">
        <h1 class="text-3xl font-bold gradient-text">อัพโหลดหลักฐานการสมัคร</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">เลือกระดับชั้นที่ต้องการอัพโหลดเอกสาร</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
        <!-- M.1 Upload Card -->
        <a href="upload_m1.php" class="group glass rounded-2xl p-6 hover:shadow-xl transition-all transform hover:-translate-y-2">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl shadow-lg shadow-emerald-500/30 mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-upload text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">มัธยมศึกษาปีที่ 1</h3>
                <p class="text-gray-600 dark:text-gray-400 mt-2">อัพโหลดหลักฐาน ม.1</p>
                <div class="mt-4 inline-flex items-center px-4 py-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full text-sm font-medium group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                    <span>คลิกเพื่ออัพโหลด</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
        </a>

        <!-- M.4 Upload Card -->
        <a href="upload_m4.php" class="group glass rounded-2xl p-6 hover:shadow-xl transition-all transform hover:-translate-y-2">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg shadow-blue-500/30 mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-upload text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">มัธยมศึกษาปีที่ 4</h3>
                <p class="text-gray-600 dark:text-gray-400 mt-2">อัพโหลดหลักฐาน ม.4</p>
                <div class="mt-4 inline-flex items-center px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-sm font-medium group-hover:bg-blue-500 group-hover:text-white transition-colors">
                    <span>คลิกเพื่ออัพโหลด</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Instructions -->
    <div class="max-w-4xl mx-auto glass rounded-2xl p-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
            <i class="fas fa-info-circle text-primary-500 mr-2"></i>คำแนะนำการอัพโหลด
        </h3>
        <ul class="space-y-2 text-gray-600 dark:text-gray-300">
            <li class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                <span>ไฟล์ที่รองรับ: PDF, JPG, PNG ขนาดไม่เกิน 5MB</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                <span>ตรวจสอบความชัดเจนของเอกสารก่อนอัพโหลด</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                <span>หากมีข้อผิดพลาดสามารถอัพโหลดใหม่ได้</span>
            </li>
        </ul>
    </div>
</div>

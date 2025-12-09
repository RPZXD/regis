
<!-- Cancel Report View -->
<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-red-600 to-rose-600 bg-clip-text text-transparent">
            สละสิทธิ์การรายงานตัว
        </h1>
        <p class="text-gray-500 mt-2">กรุณาตรวจสอบข้อมูลก่อนยืนยันการสละสิทธิ์</p>
    </div>

    <?php if (empty($studentData)): ?>
    <!-- Search Form -->
    <div class="max-w-md mx-auto glass rounded-2xl p-8 shadow-xl">
        <form method="POST" action="" class="space-y-6">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fas fa-user-times"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">ค้นหาข้อมูลสละสิทธิ์</h3>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">เลขประจำตัวประชาชน</label>
                <input type="text" name="citizenid" required maxlength="13" 
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-red-500 outline-none transition-all text-center text-lg tracking-wider"
                       placeholder="เลขบัตรประชาชน 13 หลัก">
            </div>

            <button type="submit" name="search" class="w-full py-3 bg-gradient-to-r from-red-600 to-rose-600 text-white rounded-xl font-bold hover:shadow-lg hover:scale-[1.02] transition-all">
                ค้นหาข้อมูล
            </button>
        </form>
        <?php if(isset($error)): ?>
            <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-lg text-center text-sm">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php else: ?>
    
    <?php 
    $currentStatus = intval($studentData['status'] ?? 0);
    ?>
    
    <?php if ($currentStatus == 2): ?>
    <!-- Already Confirmed -->
    <div class="max-w-2xl mx-auto glass rounded-2xl p-8 shadow-xl text-center">
        <div class="w-24 h-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-check-circle text-5xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-green-700 mb-4">ยืนยันการรายงานตัวแล้ว</h2>
        <p class="text-gray-600 mb-6">
            <strong><?php echo $studentData['stu_prefix'].$studentData['stu_name'].' '.$studentData['stu_lastname']; ?></strong>
        </p>
        <div class="p-4 bg-green-50 rounded-xl border border-green-200 mb-6">
            <p class="text-green-700"><i class="fas fa-info-circle mr-2"></i>ท่านได้ยืนยันสิทธิ์แล้ว ไม่สามารถสละสิทธิ์ได้</p>
        </div>
        <a href="index.php" class="inline-block px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-bold">
            <i class="fas fa-home mr-2"></i>กลับหน้าหลัก
        </a>
    </div>
    
    <?php elseif ($currentStatus == 3): ?>
    <!-- Already Cancelled -->
    <div class="max-w-2xl mx-auto glass rounded-2xl p-8 shadow-xl text-center">
        <div class="w-24 h-24 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-user-times text-5xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-red-700 mb-4">สละสิทธิ์แล้ว</h2>
        <p class="text-gray-600 mb-6">
            <strong><?php echo $studentData['stu_prefix'].$studentData['stu_name'].' '.$studentData['stu_lastname']; ?></strong>
        </p>
        <div class="p-4 bg-red-50 rounded-xl border border-red-200 mb-6">
            <p class="text-red-700"><i class="fas fa-exclamation-triangle mr-2"></i>ท่านได้สละสิทธิ์ไปแล้ว</p>
        </div>
        <a href="index.php" class="inline-block px-8 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-xl font-bold">
            <i class="fas fa-home mr-2"></i>กลับหน้าหลัก
        </a>
    </div>
    
    <?php elseif ($currentStatus != 1): ?>
    <!-- Not eligible (status 0 = not passed yet) -->
    <div class="max-w-2xl mx-auto glass rounded-2xl p-8 shadow-xl text-center">
        <div class="w-24 h-24 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-clock text-5xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-amber-700 mb-4">ยังไม่สามารถดำเนินการได้</h2>
        <p class="text-gray-600 mb-6">
            <strong><?php echo $studentData['stu_prefix'].$studentData['stu_name'].' '.$studentData['stu_lastname']; ?></strong>
        </p>
        <div class="p-4 bg-amber-50 rounded-xl border border-amber-200 mb-6">
            <p class="text-amber-700"><i class="fas fa-info-circle mr-2"></i>ข้อมูลของท่านยังอยู่ระหว่างการตรวจสอบ</p>
        </div>
        <a href="index.php" class="inline-block px-8 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-xl font-bold">
            <i class="fas fa-home mr-2"></i>กลับหน้าหลัก
        </a>
    </div>
    
    <?php else: ?>
    <!-- Can cancel (status = 1) -->
    <div class="max-w-2xl mx-auto glass rounded-2xl p-8 shadow-xl shadow-red-500/10 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-red-500/20 to-rose-500/20 rounded-bl-full -mr-8 -mt-8"></div>
        
        <div class="flex flex-col md:flex-row gap-8 items-center">
            <div class="flex-shrink-0">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 p-1 shadow-inner">
                    <img src="<?php echo !empty($studentData['img_profile']) ? 'uploads/profile/'.$studentData['img_profile'] : 'https://api.dicebear.com/7.x/avataaars/svg?seed='.$studentData['citizenid']; ?>" 
                         class="w-full h-full rounded-full object-cover border-4 border-white" alt="Profile">
                </div>
            </div>
            
            <div class="flex-grow text-center md:text-left">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                    <?php echo $studentData['stu_prefix'].$studentData['stu_name'].' '.$studentData['stu_lastname']; ?>
                </h2>
                <div class="flex flex-wrap gap-3 justify-center md:justify-start mt-2">
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                        <i class="fas fa-id-card mr-1"></i> <?php echo $studentData['citizenid']; ?>
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Warning -->
        <div class="mt-6 p-4 bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-200 dark:border-red-800">
            <h3 class="font-bold text-red-700 dark:text-red-400 mb-2">
                <i class="fas fa-exclamation-triangle mr-2"></i>คำเตือน
            </h3>
            <p class="text-red-600 dark:text-red-300 text-sm">
                การสละสิทธิ์จะทำให้ท่านสูญเสียสิทธิ์ในการเข้าศึกษาต่อ และไม่สามารถเปลี่ยนแปลงได้ภายหลัง
                กรุณาตรวจสอบให้แน่ใจก่อนดำเนินการ
            </p>
        </div>

        <!-- Reason -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                เหตุผลในการสละสิทธิ์ (ไม่บังคับ)
            </label>
            <textarea id="cancelReason" rows="3" 
                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-red-500 outline-none"
                placeholder="ระบุเหตุผล..."></textarea>
        </div>

        <!-- Actions -->
        <div class="flex flex-col md:flex-row gap-4 justify-center mt-8">
            <a href="confirm_report.php" class="px-8 py-3 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-50 font-bold transition-all text-center">
                <i class="fas fa-arrow-left mr-2"></i>ย้อนกลับ
            </a>
            <button onclick="cancelReport()" class="px-12 py-3 rounded-xl bg-gradient-to-r from-red-500 to-rose-600 text-white font-bold shadow-lg shadow-red-500/30 hover:scale-105 transition-all">
                <i class="fas fa-user-times mr-2"></i>ยืนยันสละสิทธิ์
            </button>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<script>
function cancelReport() {
    Swal.fire({
        title: 'ยืนยันการสละสิทธิ์',
        html: `<div class="text-left">
            <p class="text-red-600 font-bold mb-2">⚠️ คำเตือน</p>
            <p class="text-gray-600 text-sm">การสละสิทธิ์จะไม่สามารถย้อนกลับได้ คุณแน่ใจหรือไม่?</p>
        </div>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'ยืนยัน สละสิทธิ์',
        cancelButtonText: 'ยกเลิก',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'api/cancel_student.php',
                method: 'POST',
                data: { 
                    id: <?php echo $studentData['id'] ?? 0; ?>,
                    reason: $('#cancelReason').val()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'สละสิทธิ์เรียบร้อยแล้ว',
                            text: 'ขอบคุณที่แจ้งให้ทราบ',
                            confirmButtonColor: '#10b981'
                        }).then(() => {
                            window.location.href = 'index.php';
                        });
                    } else {
                        Swal.fire('เกิดข้อผิดพลาด', response.message || 'กรุณาลองใหม่อีกครั้ง', 'error');
                    }
                },
                error: function() {
                    Swal.fire('เกิดข้อผิดพลาด', 'กรุณาลองใหม่อีกครั้ง', 'error');
                }
            });
        }
    });
}
</script>

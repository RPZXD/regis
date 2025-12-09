
<!-- Confirm Report View -->
<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
            ยืนยันการรายงานตัว
        </h1>
        <p class="text-gray-500 mt-2">ตรวจสอบข้อมูลและค่าใช้จ่ายก่อนยืนยันสิทธิ์</p>
    </div>

    <?php if (empty($studentData)): ?>
    <!-- Search Form -->
    <div class="max-w-md mx-auto glass rounded-2xl p-8 shadow-xl">
        <form method="POST" action="" class="space-y-6">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">ค้นหาข้อมูลรายงานตัว</h3>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">เลขประจำตัวประชาชน</label>
                <input type="text" name="citizenid" required maxlength="13" 
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-blue-500 outline-none transition-all text-center text-lg tracking-wider"
                       placeholder="เลขบัตรประชาชน 13 หลัก">
            </div>

            <button type="submit" name="search" class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-bold hover:shadow-lg hover:scale-[1.02] transition-all">
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
    // Check if student has already confirmed or cancelled
    $currentStatus = intval($studentData['status'] ?? 0);
    ?>
    
    <?php if ($currentStatus == 2): ?>
    <!-- Already Confirmed State -->
    <div class="max-w-2xl mx-auto glass rounded-2xl p-8 shadow-xl text-center">
        <div class="w-24 h-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-check-circle text-5xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-green-700 mb-4">ยืนยันการรายงานตัวเรียบร้อยแล้ว</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-2">
            <strong><?php echo $studentData['stu_prefix'].$studentData['stu_name'].' '.$studentData['stu_lastname']; ?></strong>
        </p>
        <p class="text-gray-500 text-sm mb-6">เลขบัตร: <?php echo $studentData['citizenid']; ?></p>
        <div class="p-4 bg-green-50 rounded-xl border border-green-200 mb-6">
            <p class="text-green-700"><i class="fas fa-info-circle mr-2"></i>ท่านได้ยืนยันสิทธิ์การเข้าศึกษาต่อเรียบร้อยแล้ว</p>
        </div>
        <a href="index.php" class="inline-block px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-bold hover:shadow-lg transition-all">
            <i class="fas fa-home mr-2"></i>กลับหน้าหลัก
        </a>
    </div>
    
    <?php elseif ($currentStatus == 3): ?>
    <!-- Already Cancelled State -->
    <div class="max-w-2xl mx-auto glass rounded-2xl p-8 shadow-xl text-center">
        <div class="w-24 h-24 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-user-times text-5xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-red-700 mb-4">สละสิทธิ์แล้ว</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-2">
            <strong><?php echo $studentData['stu_prefix'].$studentData['stu_name'].' '.$studentData['stu_lastname']; ?></strong>
        </p>
        <p class="text-gray-500 text-sm mb-6">เลขบัตร: <?php echo $studentData['citizenid']; ?></p>
        <div class="p-4 bg-red-50 rounded-xl border border-red-200 mb-6">
            <p class="text-red-700"><i class="fas fa-exclamation-triangle mr-2"></i>ท่านได้สละสิทธิ์การเข้าศึกษาต่อแล้ว ไม่สามารถดำเนินการใดๆ ได้อีก</p>
        </div>
        <a href="index.php" class="inline-block px-8 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-xl font-bold hover:shadow-lg transition-all">
            <i class="fas fa-home mr-2"></i>กลับหน้าหลัก
        </a>
    </div>
    
    <?php elseif ($currentStatus == 0): ?>
    <!-- Not passed yet -->
    <div class="max-w-2xl mx-auto glass rounded-2xl p-8 shadow-xl text-center">
        <div class="w-24 h-24 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-clock text-5xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-amber-700 mb-4">รอการตรวจสอบ</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-2">
            <strong><?php echo $studentData['stu_prefix'].$studentData['stu_name'].' '.$studentData['stu_lastname']; ?></strong>
        </p>
        <p class="text-gray-500 text-sm mb-6">เลขบัตร: <?php echo $studentData['citizenid']; ?></p>
        <div class="p-4 bg-amber-50 rounded-xl border border-amber-200 mb-6">
            <p class="text-amber-700"><i class="fas fa-info-circle mr-2"></i>ข้อมูลของท่านอยู่ระหว่างการตรวจสอบ กรุณารอผลการประกาศ</p>
        </div>
        <a href="index.php" class="inline-block px-8 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-xl font-bold hover:shadow-lg transition-all">
            <i class="fas fa-home mr-2"></i>กลับหน้าหลัก
        </a>
    </div>
    
    <?php else: ?>
    <!-- Student Info & Plan (status = 1, can confirm/cancel) -->
    <div class="max-w-4xl mx-auto glass rounded-2xl p-8 shadow-xl shadow-blue-500/10 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/20 to-indigo-500/20 rounded-bl-full -mr-8 -mt-8"></div>
        
        <div class="flex flex-col md:flex-row gap-8 items-start">
            <div class="flex-shrink-0 mx-auto md:mx-0">
                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 p-1 shadow-inner">
                    <img src="<?php echo !empty($studentData['img_profile']) ? 'uploads/profile/'.$studentData['img_profile'] : 'https://api.dicebear.com/7.x/avataaars/svg?seed='.$studentData['citizenid']; ?>" 
                         class="w-full h-full rounded-full object-cover border-4 border-white" alt="Profile">
                </div>
            </div>
            
            <div class="flex-grow text-center md:text-left space-y-2">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                    <?php echo $studentData['stu_prefix'].$studentData['stu_name'].' '.$studentData['stu_lastname']; ?>
                </h2>
                <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                        <i class="fas fa-id-card mr-1"></i> <?php echo $studentData['citizenid']; ?>
                    </span>
                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium">
                        <i class="fas fa-school mr-1"></i> <?php echo $plan['name'] ?? 'ยังไม่ระบุแผนการเรียน'; ?>
                    </span>
                </div>
                
                <?php if ($plan): ?>
                <div class="mt-4 p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-800">
                    <h3 class="font-bold text-emerald-700 dark:text-emerald-400 mb-1">
                        <i class="fas fa-check-circle mr-1"></i> แผนการเรียนที่ได้รับคัดเลือก
                    </h3>
                    <p class="text-emerald-600 dark:text-emerald-300"><?php echo $plan['type_name'] . ' - ' . $plan['name']; ?></p>
                </div>
                <?php else: ?>
                <div class="mt-4 p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-100 dark:border-amber-800">
                    <h3 class="font-bold text-amber-700 dark:text-amber-400 mb-1">
                        <i class="fas fa-clock mr-1"></i> รอการจัดสรรแผนการเรียน
                    </h3>
                    <p class="text-amber-600 dark:text-amber-300">กรุณารอเจ้าหน้าที่ดำเนินการจัดสรรแผนการเรียน</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($plan): ?>
    <!-- Fees Section -->
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Maintenance Fees -->
        <?php 
        $maintenance = array_filter($fees, function($f) { return $f['category'] == 'maintenance'; });
        $mTotal1 = 0; $mTotal2 = 0;
        foreach($maintenance as $f) { $mTotal1 += $f['term1_amount']; $mTotal2 += $f['term2_amount']; }
        ?>
        <div class="glass rounded-2xl p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-l-4 border-emerald-500 pl-3">1. เงินบำรุงการศึกษา</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600">
                            <th class="px-4 py-3 text-left">รายการ</th>
                            <th class="px-4 py-3 text-right">ภาคเรียนที่ 1</th>
                            <th class="px-4 py-3 text-right">ภาคเรียนที่ 2</th>
                            <th class="px-4 py-3 text-right">รวม</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach($maintenance as $fee): ?>
                        <tr>
                            <td class="px-4 py-3"><?php echo $fee['item_name']; ?></td>
                            <td class="px-4 py-3 text-right"><?php echo number_format($fee['term1_amount'], 2); ?></td>
                            <td class="px-4 py-3 text-right"><?php echo number_format($fee['term2_amount'], 2); ?></td>
                            <td class="px-4 py-3 text-right font-medium"><?php echo number_format($fee['term1_amount'] + $fee['term2_amount'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="bg-emerald-50/50 font-bold text-emerald-800">
                            <td class="px-4 py-3">รวมเงินบำรุงการศึกษา</td>
                            <td class="px-4 py-3 text-right"><?php echo number_format($mTotal1, 2); ?></td>
                            <td class="px-4 py-3 text-right"><?php echo number_format($mTotal2, 2); ?></td>
                            <td class="px-4 py-3 text-right"><?php echo number_format($mTotal1 + $mTotal2, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Support Fees -->
        <?php 
        $support = array_filter($fees, function($f) { return $f['category'] == 'support'; });
        $sTotal1 = 0; $sTotal2 = 0;
        foreach($support as $f) { $sTotal1 += $f['term1_amount']; $sTotal2 += $f['term2_amount']; }
        ?>
        <div class="glass rounded-2xl p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-l-4 border-blue-500 pl-3">2. ค่าใช้จ่ายสนับสนุน</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600">
                            <th class="px-4 py-3 text-left">รายการ</th>
                            <th class="px-4 py-3 text-right">ภาคเรียนที่ 1</th>
                            <th class="px-4 py-3 text-right">ภาคเรียนที่ 2</th>
                            <th class="px-4 py-3 text-right">รวม</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach($support as $fee): ?>
                        <tr>
                            <td class="px-4 py-3"><?php echo $fee['item_name']; ?></td>
                            <td class="px-4 py-3 text-right"><?php echo number_format($fee['term1_amount'], 2); ?></td>
                            <td class="px-4 py-3 text-right"><?php echo number_format($fee['term2_amount'], 2); ?></td>
                            <td class="px-4 py-3 text-right font-medium"><?php echo number_format($fee['term1_amount'] + $fee['term2_amount'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="bg-blue-50/50 font-bold text-blue-800">
                            <td class="px-4 py-3">รวมเงินค่าใช้จ่ายสนับสนุน</td>
                            <td class="px-4 py-3 text-right"><?php echo number_format($sTotal1, 2); ?></td>
                            <td class="px-4 py-3 text-right"><?php echo number_format($sTotal2, 2); ?></td>
                            <td class="px-4 py-3 text-right"><?php echo number_format($sTotal1 + $sTotal2, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Grand Total -->
        <div class="glass rounded-xl p-6 flex justify-between items-center bg-gradient-to-r from-gray-900 to-gray-800 text-white">
            <div class="text-lg opacity-90">รวมเงินทั้งสิ้น (เงินบำรุงการศึกษา + ค่าใช้จ่ายสนับสนุน)</div>
            <div class="text-3xl font-bold text-yellow-400">
                <?php echo number_format(($mTotal1 + $mTotal2 + $sTotal1 + $sTotal2), 2); ?> บาท
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-col md:flex-row gap-4 justify-center pt-8">
            <a href="cancel_report.php" class="px-8 py-3 rounded-xl border border-red-200 text-red-600 hover:bg-red-50 font-bold transition-all text-center">
                สละสิทธิ์
            </a>
            <button onclick="confirmReport()" class="px-12 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold shadow-lg shadow-emerald-500/30 hover:scale-105 transition-all">
                <i class="fas fa-check-circle mr-2"></i> ยืนยันการรายงานตัว
            </button>
        </div>
    </div>
    <?php else: ?>
    <!-- No Plan Assigned State -->
     <div class="text-center py-12">
        <a href="index.php" class="text-blue-500 hover:underline">กลับหน้าหลัก</a>
    </div>
    <?php endif; ?>
    <?php endif; // end status check ?>
    <?php endif; // end if studentData ?>
</div>

<script>
function confirmReport() {
    Swal.fire({
        title: 'ยืนยันการรายงานตัว',
        text: "คุณตรวจสอบข้อมูลและยอมรับเงื่อนไขค่าใช้จ่ายแล้วใช่หรือไม่?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'api/confirm_student.php',
                method: 'POST',
                data: { id: <?php echo $studentData['id']; ?> },
                success: function(response) {
                    Swal.fire('สำเร็จ', 'ยืนยันการรายงานตัวเรียบร้อยแล้ว', 'success').then(() => {
                        window.location.reload();
                    });
                },
                error: function() {
                    Swal.fire('เกิดข้อผิดพลาด', 'กรุณาลองใหม่อีกครั้ง', 'error');
                }
            });
        }
    });
}
</script>

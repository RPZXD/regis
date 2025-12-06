<!-- Config Year View -->
<div class="space-y-6">
    <div class="glass rounded-2xl p-6 border-l-4 border-cyan-500">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-cyan-500 to-teal-600 rounded-xl shadow-lg">
                <i class="fas fa-cog text-2xl text-white"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">ตั้งค่าปีการศึกษา</h2>
                <p class="text-gray-600 dark:text-gray-400">กำหนดปีการศึกษาที่จะรับสมัคร</p>
            </div>
        </div>
    </div>
    
    <div class="glass rounded-2xl p-8 max-w-md mx-auto">
        <form id="yearConfigForm" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ปีการศึกษา</label>
                <input type="number" id="academicYear" name="year" value="<?php echo date('Y') + 543; ?>" 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-center text-xl font-bold focus:ring-2 focus:ring-cyan-500">
            </div>
            <div class="text-center">
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-cyan-500 to-teal-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                    <i class="fas fa-save mr-2"></i>บันทึก
                </button>
            </div>
        </form>
    </div>
    
    <div class="glass rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">ปีการศึกษาปัจจุบัน</h3>
        <div class="text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-teal-600 text-center" id="currentYear">
            <?php echo $pee ?? (date('Y') + 543); ?>
        </div>
    </div>
</div>

<script>
$('#yearConfigForm').on('submit', function(e) {
    e.preventDefault();
    $.post('api/update_year.php', $(this).serialize(), function(response) {
        Swal.fire('สำเร็จ', 'บันทึกปีการศึกษาเรียบร้อย', 'success').then(() => location.reload());
    });
});
</script>

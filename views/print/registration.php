<!-- Print Registration View - Enhanced -->
<?php
$citizenidParam = $_GET['citizenid'] ?? '';
?>
<div class="space-y-8">
    <!-- Page Header with Gradient -->
    <div class="text-center relative">
        <div class="absolute inset-0 bg-gradient-to-r from-purple-500/10 via-pink-500/10 to-rose-500/10 rounded-3xl blur-3xl -z-10"></div>
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-600 rounded-3xl shadow-2xl shadow-purple-500/40 mb-6 animate-bounce-slow">
            <i class="fas fa-print text-3xl text-white"></i>
        </div>
        <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 via-pink-600 to-rose-600 bg-clip-text text-transparent">
            พิมพ์ใบสมัครเรียน
        </h1>
        <p class="mt-3 text-gray-600 dark:text-gray-400 max-w-lg mx-auto">
            ค้นหาข้อมูลและพิมพ์ใบสมัครเรียน
        </p>
    </div>

    <!-- Search Card -->
    <div class="max-w-xl mx-auto">
        <div class="glass rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-shadow duration-300">
            <form id="searchForm" class="space-y-6">
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-id-card mr-2 text-purple-500"></i>เลขบัตรประชาชน หรือ ชื่อ-นามสกุล
                    </label>
                    <input type="text" id="search_input" name="search_input" 
                           class="w-full px-5 py-4 text-center text-xl font-mono tracking-widest rounded-2xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                           placeholder="X-XXXX-XXXXX-XX-X" 
                           value="<?php echo htmlspecialchars($citizenidParam); ?>"
                           required>
                </div>
                
                <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-purple-500 via-pink-500 to-rose-500 hover:from-purple-600 hover:via-pink-600 hover:to-rose-600 text-white font-bold text-lg rounded-2xl shadow-lg shadow-purple-500/30 hover:shadow-xl hover:shadow-purple-500/40 transition-all transform hover:-translate-y-1 hover:scale-[1.02] group">
                    <i class="fas fa-search mr-3 group-hover:animate-pulse"></i>ค้นหาข้อมูล
                </button>
            </form>
        </div>
    </div>

    <!-- Student Info Result -->
    <div id="studentInfo" class="max-w-3xl mx-auto hidden animate-fade-in">
        <!-- Student Card -->
        <div class="glass rounded-3xl overflow-hidden shadow-xl">
            <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-rose-500 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 flex items-center justify-center bg-white/20 backdrop-blur rounded-2xl">
                        <i class="fas fa-user-graduate text-3xl text-white"></i>
                    </div>
                    <div class="text-white">
                        <h3 class="text-xl font-bold" id="studentName">-</h3>
                        <p class="text-purple-100" id="studentTypeLevel">-</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Info Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-center">
                        <i class="fas fa-id-card text-2xl text-purple-500 mb-2"></i>
                        <p class="text-xs text-gray-500">เลขบัตร</p>
                        <p class="font-mono font-bold text-gray-900 dark:text-white text-sm" id="val_citizenid">-</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-center">
                        <i class="fas fa-birthday-cake text-2xl text-pink-500 mb-2"></i>
                        <p class="text-xs text-gray-500">วันเกิด</p>
                        <p class="font-bold text-gray-900 dark:text-white text-sm" id="val_birthday">-</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-center">
                        <i class="fas fa-phone text-2xl text-rose-500 mb-2"></i>
                        <p class="text-xs text-gray-500">โทรศัพท์</p>
                        <p class="font-bold text-gray-900 dark:text-white text-sm" id="val_tel">-</p>
                    </div>
                </div>

                <!-- Registration Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-purple-50 dark:bg-purple-900/20 p-5 rounded-2xl border border-purple-100 dark:border-purple-800">
                        <p class="text-xs text-purple-600 dark:text-purple-400 mb-1">ประเภทการสมัคร</p>
                        <p id="val_type" class="text-xl font-bold text-purple-700 dark:text-purple-300">-</p>
                    </div>
                    <div class="bg-pink-50 dark:bg-pink-900/20 p-5 rounded-2xl border border-pink-100 dark:border-pink-800">
                        <p class="text-xs text-pink-600 dark:text-pink-400 mb-1">ระดับชั้น</p>
                        <p id="val_level" class="text-xl font-bold text-pink-700 dark:text-pink-300">-</p>
                    </div>
                </div>

                <!-- Plans -->
                <div class="bg-gray-50 dark:bg-slate-800/50 rounded-2xl p-5 mb-6">
                    <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-3 flex items-center">
                        <i class="fas fa-list-ol text-rose-500 mr-2"></i>แผนการเรียนที่เลือก
                    </h4>
                    <ul id="val_plans" class="space-y-2"></ul>
                </div>

                <!-- Print Button -->
                <button id="printButton" class="w-full py-5 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 hover:from-green-600 hover:via-emerald-600 hover:to-teal-600 text-white font-bold text-lg rounded-2xl shadow-lg shadow-green-500/30 transition-all transform hover:-translate-y-1 hover:scale-[1.02] group">
                    <div class="flex items-center justify-center">
                        <div class="bg-white/20 rounded-full p-3 mr-4 group-hover:rotate-12 transition-transform">
                            <i class="fas fa-print text-2xl"></i>
                        </div>
                        <div class="text-left">
                            <span class="block text-xl">พิมพ์ใบสมัคร</span>
                            <span class="block text-sm text-green-100 font-normal">คลิกเพื่อไปหน้าพิมพ์</span>
                        </div>
                    </div>
                </button>

                <!-- Quick Links -->
                <div class="mt-6 text-center">
                    <a href="checkreg.php" class="text-gray-500 hover:text-purple-600 transition-colors mr-6">
                        <i class="fas fa-search mr-2"></i>ค้นหาสถานะ
                    </a>
                    <a href="upload.php" class="text-gray-500 hover:text-purple-600 transition-colors">
                        <i class="fas fa-upload mr-2"></i>อัพโหลดเอกสาร
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes bounce-slow { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
.animate-bounce-slow { animation: bounce-slow 3s infinite; }
.animate-fade-in { animation: fadeIn 0.5s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
// Auto-search if citizenid is provided
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search_input').value;
    if (searchInput && searchInput.length >= 10) {
        document.getElementById('searchForm').dispatchEvent(new Event('submit'));
    }
});

document.getElementById('searchForm').addEventListener('submit', function(event) {
    event.preventDefault();
    var searchInput = document.getElementById('search_input').value;

    Swal.fire({ title: 'กำลังค้นหา...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    fetch('api/fetch_reg.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ search_input: searchInput }) })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        if (data.exists) {
            Swal.fire({ icon: 'success', title: 'พบข้อมูล', timer: 1500, showConfirmButton: false });
            
            // Populate Data
            document.getElementById('studentName').textContent = data.fullname;
            document.getElementById('studentTypeLevel').textContent = data.typeregis + ' | ม.' + data.level;
            document.getElementById('val_citizenid').textContent = data.citizenid;
            document.getElementById('val_birthday').textContent = data.birthday || '-';
            document.getElementById('val_tel').textContent = data.now_tel || '-';
            document.getElementById('val_type').textContent = data.typeregis;
            document.getElementById('val_level').textContent = 'มัธยมศึกษาปีที่ ' + data.level;

            // Plans
            const plansList = document.getElementById('val_plans');
            plansList.innerHTML = '';
            if (data.plans && data.plans.length > 0) {
                data.plans.forEach((plan, index) => {
                    plansList.innerHTML += `<li class="flex items-center text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 p-3 rounded-xl shadow-sm">
                        <span class="w-7 h-7 flex items-center justify-center bg-rose-100 dark:bg-rose-900/30 text-rose-600 rounded-full text-sm font-bold mr-3">${index + 1}</span>${plan}
                    </li>`;
                });
            } else {
                plansList.innerHTML = '<li class="text-gray-400 italic text-sm">ไม่พบข้อมูลแผนการเรียน</li>';
            }

            // Print button
            document.getElementById('printButton').onclick = function() { 
                window.location.href = `print_reginfo.php?citizenid=${data.citizenid}`; 
            };

            document.getElementById('studentInfo').classList.remove('hidden');
            document.getElementById('studentInfo').scrollIntoView({ behavior: 'smooth', block: 'start' });
            
        } else {
            Swal.fire({ icon: 'error', title: 'ไม่พบข้อมูล', text: 'ไม่พบข้อมูลผู้สมัครที่ค้นหา', confirmButtonColor: '#ef4444' });
            document.getElementById('studentInfo').classList.add('hidden');
        }
    })
    .catch(error => {
        Swal.close();
        Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', confirmButtonColor: '#ef4444' });
    });
});
</script>

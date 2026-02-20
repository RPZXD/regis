<!-- Exam Room Layout View -->
<div class="space-y-8 animate-fade-in-up">
    <!-- Header Section -->
    <div class="glass rounded-3xl p-8 relative overflow-hidden shadow-2xl border-white/20 dark:border-slate-700/50">
        <div class="absolute top-0 right-0 p-6 opacity-10">
            <i class="fas fa-school text-9xl text-indigo-600 transform rotate-12"></i>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2
                    class="text-4xl font-extrabold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                    ผังที่นั่งห้องสอบ
                </h2>
                <p class="text-gray-500 dark:text-gray-400 mt-2 text-lg">
                    บริหารจัดการและตรวจสอบการจัดที่นั่งในแต่ละสนามสอบ
                </p>
            </div>
            <div class="flex gap-3">
                <div
                    class="px-6 py-3 bg-white/50 dark:bg-slate-800/50 backdrop-blur-md rounded-2xl border border-white/20 dark:border-slate-700 shadow-sm">
                    <span
                        class="block text-xs uppercase tracking-wider text-gray-400 font-bold mb-1">จำนวนห้องสอบ</span>
                    <span class="text-2xl font-black text-indigo-600">
                        <?php echo count($roomData); ?>
                    </span>
                </div>
                <?php
                $totalStudents = array_reduce($roomData, function ($carry, $item) {
                    return $carry + count($item['students']);
                }, 0);
                ?>
                <div
                    class="px-6 py-3 bg-white/50 dark:bg-slate-800/50 backdrop-blur-md rounded-2xl border border-white/20 dark:border-slate-700 shadow-sm">
                    <span
                        class="block text-xs uppercase tracking-wider text-gray-400 font-bold mb-1">นักเรียนที่จัดแล้ว</span>
                    <span class="text-2xl font-black text-purple-600">
                        <?php echo $totalStudents; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Filter Selection -->
    <div
        class="glass rounded-3xl p-6 border-white/20 dark:border-slate-700/50 shadow-xl relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
            <i class="fas fa-calendar-check text-7xl text-indigo-600"></i>
        </div>
        <div class="relative z-10">
            <h3
                class="text-sm font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest mb-4 flex items-center">
                <i class="fas fa-filter mr-2"></i> เลือกแสดงตามวันที่สอบ
            </h3>
            <div class="flex flex-wrap gap-3">
                <?php foreach ($availableDates as $date): ?>
                    <a href="?date=<?php echo urlencode($date); ?>"
                        class="relative overflow-hidden px-6 py-3.5 rounded-2xl transition-all duration-300 font-black text-sm flex items-center group/btn <?php echo $selectedDate == $date ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-500/40 ring-4 ring-indigo-500/20 translate-y-[-2px]' : 'bg-white/80 dark:bg-slate-800/80 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-slate-700 hover:shadow-lg border border-white dark:border-slate-700 hover:translate-y-[-2px]'; ?>">
                        <div
                            class="mr-3 w-8 h-8 rounded-lg flex items-center justify-center transition-colors <?php echo $selectedDate == $date ? 'bg-white/20' : 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-500'; ?>">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <span class="relative z-10"><?php echo htmlspecialchars($date); ?></span>
                        <?php if ($selectedDate == $date): ?>
                            <div class="absolute bottom-0 left-0 h-1 bg-white/30 w-full animate-pulse"></div>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Quick Stats & Search -->
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-96">
            <input type="text" id="roomSearch" placeholder="ค้นหาชื่อห้อง หรือ อาคาร..."
                class="w-full pl-12 pr-4 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all shadow-sm"
                onkeyup="filterRooms()">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>

        <div class="flex items-center gap-2 overflow-x-auto pb-2 w-full md:w-auto">
            <button onclick="filterByBuilding('all')"
                class="building-filter px-4 py-2 bg-indigo-600 text-white rounded-xl shadow-lg shadow-indigo-500/30 transition-all font-bold whitespace-nowrap active"
                data-building="all">
                ทั้งหมด
            </button>
            <?php
            $buildings = array_unique(array_column($roomData, 'building'));
            sort($buildings);
            foreach ($buildings as $b):
                ?>
                <button onclick="filterByBuilding('<?php echo htmlspecialchars($b); ?>')"
                    class="building-filter px-4 py-2 bg-white dark:bg-slate-800 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 border border-gray-100 dark:border-slate-700 transition-all font-bold whitespace-nowrap shadow-sm"
                    data-building="<?php echo htmlspecialchars($b); ?>">
                    <?php echo htmlspecialchars($b); ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Multi-Room Selection Toggle -->
    <div id="noRoomsFound" class="hidden glass rounded-3xl p-12 text-center">
        <div class="text-6xl text-gray-300 mb-4"><i class="fas fa-search"></i></div>
        <h3 class="text-xl font-bold text-gray-500">ไม่พบห้องสอบที่ท่านค้นหา</h3>
    </div>

    <!-- Rooms Grid -->
    <?php if (empty($roomData)): ?>
        <div class="glass rounded-[3rem] p-20 text-center border-white/20 shadow-2xl">
            <div class="relative inline-block mb-8">
                <div class="absolute inset-0 bg-indigo-500 blur-3xl opacity-20 animate-pulse"></div>
                <div class="w-24 h-24 bg-gradient-to-tr from-indigo-500 to-purple-600 rounded-3xl flex items-center justify-center text-white text-4xl shadow-xl relative z-10">
                    <i class="fas fa-calendar-times"></i>
                </div>
            </div>
            <h3 class="text-3xl font-black text-gray-800 dark:text-white mb-4">ไม่มีข้อมูลการจัดสอบ</h3>
            <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto text-lg leading-relaxed">
                ไม่พบรายชื่อนักเรียนที่ถูกจัดลงห้องสอบในวันที่ <span class="font-bold text-indigo-600"><?php echo htmlspecialchars($selectedDate); ?></span> 
                กรุณาเลือกวันที่อื่นหรือตรวจสอบการจัดห้องในเมนูจัดการห้องสอบ
            </p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="roomsOuterGrid">
            <?php foreach ($roomData as $room): ?>
            <div class="room-card group" data-building="<?php echo htmlspecialchars($room['building']); ?>"
                data-name="<?php echo htmlspecialchars($room['name']); ?>">

                <div
                    class="glass rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 border border-white/30 dark:border-slate-700/50 bg-white/70 dark:bg-slate-800/70">
                    <!-- Card Header -->
                    <div
                        class="p-6 bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 relative border-b border-gray-100 dark:border-slate-700">
                        <div class="flex justify-between items-start mb-2">
                            <span
                                class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-lg text-xs font-black tracking-widest uppercase">
                                <?php echo htmlspecialchars($room['building']); ?>
                            </span>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-users text-gray-400 text-xs"></i>
                                <span class="text-xs font-bold text-gray-500">
                                    <?php echo count($room['students']); ?>/
                                    <?php echo $room['capacity'] ?: ($room['seats'] ?: '-'); ?>
                                </span>
                            </div>
                        </div>
                        <h3
                            class="text-2xl font-black text-gray-800 dark:text-white group-hover:text-indigo-600 transition-colors">
                            <?php echo htmlspecialchars($room['name']); ?>
                        </h3>
                        <p class="text-xs text-gray-400 mt-1 line-clamp-1">
                            <?php echo $room['details'] ?: 'ไม่มีรายละเอียดเพิ่มเติม'; ?>
                        </p>
                    </div>

                    <!-- Capacity Progress -->
                    <?php
                    $capacity = (int) ($room['capacity'] ?: ($room['seats'] ?: 30));
                    $count = count($room['students']);
                    $percent = $capacity > 0 ? min(100, ($count / $capacity) * 100) : 0;
                    $barColor = $percent >= 100 ? 'bg-pink-500' : ($percent > 80 ? 'bg-orange-500' : 'bg-indigo-600');
                    ?>
                    <div class="h-1.5 w-full bg-gray-100 dark:bg-slate-700 overflow-hidden">
                        <div class="h-full <?php echo $barColor; ?> transition-all duration-1000"
                            style="width: <?php echo $percent; ?>%"></div>
                    </div>

                    <!-- Student List Preview (First 5) -->
                    <div class="p-6">
                        <div class="space-y-3 mb-6">
                            <?php if (empty($room['students'])): ?>
                                <div class="py-10 text-center">
                                    <i class="fas fa-user-slash text-4xl text-gray-200 mb-2"></i>
                                    <p class="text-sm text-gray-400">ยังไม่มีผู้เข้าสอบ</p>
                                </div>
                            <?php else: ?>
                                <?php
                                $previewCount = 5;
                                $previews = array_slice($room['students'], 0, $previewCount);
                                foreach ($previews as $std):
                                    ?>
                                    <div
                                        class="flex items-center gap-3 p-2 rounded-xl hover:bg-white dark:hover:bg-slate-700/50 transition-all border border-transparent hover:border-gray-100 dark:hover:border-slate-600">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-xs font-black text-gray-500">
                                            <?php echo $std['seat_number'] ?: '?'; ?>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold text-gray-700 dark:text-gray-200 truncate">
                                                <?php echo $std['fullname']; ?>
                                            </p>
                                            <p class="text-[10px] text-gray-500 uppercase font-bold tracking-tighter">
                                                <?php echo (in_array($std['level'], ['1', 'm1'])) ? 'ม.1' : 'ม.4'; ?> -
                                                <?php echo $std['typeregis']; ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <?php if ($count > $previewCount): ?>
                                    <div class="text-center pt-2">
                                        <p class="text-xs text-indigo-500 font-bold tracking-widest uppercase">
                                            + อีก
                                            <?php echo $count - $previewCount; ?> รายชื่อ
                                        </p>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        <button
                            onclick="viewRoomDetail(<?php echo htmlspecialchars(json_encode($room, JSON_UNESCAPED_UNICODE)); ?>)"
                            class="w-full py-4 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-200 rounded-2xl font-black text-sm hover:bg-indigo-600 hover:text-white transition-all transform active:scale-95 shadow-sm">
                            ดูรายชื่อทั้งหมด <i class="fas fa-arrow-right ml-2 text-[10px]"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
</div>

<!-- Room Detail Modal -->
<div id="roomDetailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-12">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xl transition-opacity animate-fade-in"
            onclick="closeRoomModal()"></div>
        <div
            class="relative glass rounded-[2.5rem] w-full max-w-5xl shadow-2xl overflow-hidden animate-slide-up border border-white/20">
            <!-- Modal Header -->
            <div class="p-8 pb-0 flex items-start justify-between relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-600/10 rounded-full blur-3xl"></div>
                <div class="relative z-10 w-full">
                    <div class="flex items-center gap-3 mb-2">
                        <span id="modalBuilding"
                            class="px-4 py-1.5 bg-indigo-600 text-white rounded-xl text-xs font-black tracking-widest uppercase shadow-lg shadow-indigo-600/20">
                            อาคาร...
                        </span>
                        <span id="modalCapacity"
                            class="px-4 py-1.5 bg-white dark:bg-slate-800 text-gray-500 rounded-xl text-xs font-black tracking-widest uppercase border border-gray-100 dark:border-slate-700 shadow-sm">
                            0/0 ที่นั่ง
                        </span>
                        <span
                            class="px-4 py-1.5 bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 rounded-xl text-xs font-black tracking-widest uppercase border border-purple-200 dark:border-purple-800 shadow-sm">
                            <i class="fas fa-calendar-day mr-1"></i> <?php echo htmlspecialchars($selectedDate); ?>
                        </span>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <h3 id="modalRoomName" class="text-4xl font-black text-gray-800 dark:text-white tracking-tight">
                            ห้องสอบ...
                        </h3>
                        <div class="flex gap-2">
                            <button onclick="printRoomList()"
                                class="hidden md:flex items-center gap-2 px-6 py-3 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-bold rounded-2xl hover:bg-indigo-600 hover:text-white transition-all">
                                <i class="fas fa-print text-sm"></i> พิมพ์ใบรายชื่อ
                            </button>
                            <button onclick="closeRoomModal()"
                                class="p-3 bg-gray-100 dark:bg-slate-800 text-gray-500 rounded-2xl hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>
                    <p id="modalDetails" class="text-gray-400 mt-2 font-medium">รายละเอียด...</p>
                </div>
            </div>

            <div class="p-8">
                <!-- Search within room -->
                <div class="mb-6">
                    <div class="relative w-full">
                        <input type="text" id="studentSearchInRoom" placeholder="ค้นหารายชื่อในห้องนี้..."
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-800/50 border border-gray-100 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                            onkeyup="filterStudentsInRoom()">
                        <i class="fas fa-user-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <!-- Students Table -->
                <div
                    class="bg-white/50 dark:bg-slate-800/50 rounded-3xl border border-gray-100 dark:border-slate-700 overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse" id="modalStudentTable">
                            <thead>
                                <tr
                                    class="bg-gray-50 dark:bg-slate-700/50 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">
                                    <th class="px-6 py-5 text-center w-24">ลำดับ</th>
                                    <th class="px-6 py-5 text-center w-32">เลขที่นั่ง</th>
                                    <th class="px-6 py-5 text-left">ชื่อ-นามสกุล</th>
                                    <th class="px-6 py-5 text-left">แผนการเรียน</th>
                                    <th class="px-6 py-5 text-left">ประเภท</th>
                                    <th class="px-6 py-5 text-center rounded-tr-3xl">ระดับชั้น</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50" id="modalStudentBody">
                                <!-- Dynamic Rows -->
                            </tbody>
                        </table>
                    </div>
                    <div id="noStudentsInRoom" class="hidden py-20 text-center">
                        <i class="fas fa-search text-5xl text-gray-100 dark:text-gray-700 mb-4"></i>
                        <p class="text-gray-400 font-bold">ไม่พบรายชื่อที่ค้นหา</p>
                    </div>
                </div>
            </div>

            <div class="p-8 pt-0 flex justify-end">
                <button onclick="closeRoomModal()"
                    class="px-8 py-3 bg-gray-800 dark:bg-white text-white dark:text-gray-800 rounded-2xl font-black text-sm hover:opacity-90 transition-all shadow-lg">
                    ตกลง
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const plansMap = <?php echo $plansMapJson; ?>;
    let currentRoom = null;

    function filterRooms() {
        const query = document.getElementById('roomSearch').value.toLowerCase();
        const cards = document.querySelectorAll('.room-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name').toLowerCase();
            const b = card.getAttribute('data-building').toLowerCase();

            // Check current building filter too
            const currentB = document.querySelector('.building-filter.active').getAttribute('data-building');
            const matchesBuilding = currentB === 'all' || card.getAttribute('data-building') === currentB;

            if ((name.includes(query) || b.includes(query)) && matchesBuilding) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        document.getElementById('noRoomsFound').classList.toggle('hidden', visibleCount > 0);
    }

    function filterByBuilding(building) {
        // Update UI buttons
        document.querySelectorAll('.building-filter').forEach(btn => {
            if (btn.getAttribute('data-building') === building) {
                btn.classList.add('bg-indigo-600', 'text-white', 'shadow-lg', 'shadow-indigo-500/30', 'active');
                btn.classList.remove('bg-white', 'dark:bg-slate-800', 'text-gray-600', 'dark:text-gray-300');
            } else {
                btn.classList.remove('bg-indigo-600', 'text-white', 'shadow-lg', 'shadow-indigo-500/30', 'active');
                btn.classList.add('bg-white', 'dark:bg-slate-800', 'text-gray-600', 'dark:text-gray-300');
            }
        });

        const cards = document.querySelectorAll('.room-card');
        const query = document.getElementById('roomSearch').value.toLowerCase();
        let visibleCount = 0;

        cards.forEach(card => {
            const b = card.getAttribute('data-building');
            const name = card.getAttribute('data-name').toLowerCase();

            const matchesQuery = name.includes(query) || b.toLowerCase().includes(query);

            if ((building === 'all' || b === building) && matchesQuery) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        document.getElementById('noRoomsFound').classList.toggle('hidden', visibleCount > 0);
    }

    function viewRoomDetail(room) {
        currentRoom = room;
        document.getElementById('modalRoomName').textContent = room.name;
        document.getElementById('modalBuilding').textContent = room.building;
        document.getElementById('modalCapacity').textContent = `${room.students.length}/${room.capacity || room.seats || '?'}`;
        document.getElementById('modalDetails').textContent = room.details || 'ไม่มีรายละเอียดเพิ่มเติม';

        renderModalTable(room.students);

        document.getElementById('roomDetailModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function renderModalTable(students) {
        const tbody = document.getElementById('modalStudentBody');
        tbody.innerHTML = '';

        if (students.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="py-20 text-center text-gray-400">ยังไม่มีผู้เข้าสอบในห้องนี้</td></tr>`;
            return;
        }

        students.forEach((std, index) => {
            // Format plans
            let plansHtml = '<span class="text-gray-400 text-[10px] italic">ไม่ได้เลือก</span>';
            if (std.plan_string) {
                const planPairs = std.plan_string.split(',');
                const formattedPlans = planPairs.map(pair => {
                    const [priority, planId] = pair.split(':');
                    const planName = plansMap[planId] || `แผนที่ ${planId}`;
                    const color = priority == 1 ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-400' : 'bg-gray-100 dark:bg-slate-700 text-gray-500';
                    return `<span class="px-2 py-0.5 ${color} rounded text-[10px] font-bold block w-fit mb-1">${priority}. ${planName}</span>`;
                });
                plansHtml = `<div class="flex flex-col">${formattedPlans.join('')}</div>`;
            }

            const tr = document.createElement('tr');
            tr.className = 'group hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors student-row';
            tr.innerHTML = `
                <td class="px-6 py-5 text-center text-gray-400 font-bold">${index + 1}</td>
                <td class="px-6 py-5 text-center">
                    <span class="px-3 py-1 bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 rounded-lg text-sm font-black border border-indigo-100 dark:border-indigo-900/50 shadow-sm">
                        ${std.seat_number || '-'}
                    </span>
                </td>
                <td class="px-6 py-5 font-bold text-gray-700 dark:text-gray-200">
                    ${std.fullname}
                </td>
                <td class="px-6 py-5">
                    ${plansHtml}
                </td>
                <td class="px-6 py-5">
                    <span class="text-xs uppercase font-extrabold text-gray-400 tracking-tighter">
                        ${std.typeregis}
                    </span>
                </td>
                <td class="px-6 py-5 text-center">
                    <span class="px-3 py-1 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 rounded-lg text-[10px] font-black uppercase">
                        ${(std.level == '1' || std.level == 'm1') ? 'มัธยม 1' : 'มัธยม 4'}
                    </span>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function filterStudentsInRoom() {
        const query = document.getElementById('studentSearchInRoom').value.toLowerCase();
        const rows = document.querySelectorAll('.student-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            if (text.includes(query)) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });

        document.getElementById('noStudentsInRoom').classList.toggle('hidden', visibleCount > 0);
        document.getElementById('modalStudentTable').classList.toggle('hidden', visibleCount === 0);
    }

    function closeRoomModal() {
        document.getElementById('roomDetailModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('studentSearchInRoom').value = '';
    }

    function printRoomList() {
        if (!currentRoom) return;

        // Simple print logic - in real world you might want a specialized print template
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
            <head>
                <title>รายชื่อผู้สอบห้อง ${currentRoom.name}</title>
                <style>
                    body { font-family: 'Sarabun', sans-serif; padding: 20px; }
                    h1 { text-align: center; margin-bottom: 5px; }
                    p { text-align: center; margin-top: 0; color: #666; }
                    table { width: 100%; border-collapse: collapse; margin-top: 14px; }
                    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
                    th { bg-color: #f8f9fa; }
                    .center { text-align: center; }
                </style>
            </head>
            <body>
                <h1>โรงเรียนพิชัย - รายชื่อผู้สอบ</h1>
                <p>อาคาร ${currentRoom.building} ห้องสอบ ${currentRoom.name} | <b>วันที่สอบ: ${<?php echo json_encode($selectedDate); ?>}</b></p>
                <table>
                    <thead>
                        <tr>
                            <th class="center" width="10%">ลำดับ</th>
                            <th class="center" width="15%">เลขที่นั่ง</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>ประเภท</th>
                            <th>หมายเหตุ</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${currentRoom.students.map((s, i) => {
            let plansText = '-';
            if (s.plan_string) {
                const pairs = s.plan_string.split(',');
                plansText = pairs.map(p => {
                    const [rank, id] = p.split(':');
                    return `${rank}. ${plansMap[id] || id}`;
                }).join(', ');
            }
            return `
                                <tr>
                                    <td class="center">${i + 1}</td>
                                    <td class="center">${s.seat_number || '-'}</td>
                                    <td>${s.fullname}</td>
                                    <td>${s.typeregis}</td>
                                    <td></td>
                                </tr>
                            `;
        }).join('')}
                    </tbody>
                </table>
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
</script>

<style>
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fade-in-up 0.6s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .room-card {
        transition-delay: 0.1s;
    }

    .glass {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }

    .dark .glass {
        background: rgba(30, 41, 59, 0.7);
    }

    #modalStudentTable thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background: inherit;
    }

    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .dark ::-webkit-scrollbar-thumb {
        background: #475569;
    }
</style>
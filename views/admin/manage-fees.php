
<!-- Manage Fees View -->
<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="glass rounded-2xl p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <i class="fas fa-coins text-8xl text-yellow-600"></i>
        </div>
        <div class="relative z-10">
            <h2 class="text-3xl font-bold bg-gradient-to-r from-yellow-600 to-amber-600 bg-clip-text text-transparent">
                จัดการค่าใช้จ่าย
            </h2>
            <p class="text-gray-500 dark:text-gray-400 mt-2">
                กำหนดรายละเอียดค่าเทอมและค่าใช้จ่ายอื่นๆ สำหรับแต่ละแผนการเรียน
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar: Select Plan -->
        <div class="lg:col-span-1 space-y-4">
             <div class="glass rounded-2xl p-4">
                <h3 class="font-bold text-gray-800 dark:text-gray-200 mb-3">เลือกแผนการเรียน</h3>
                <div class="space-y-2">
                    <?php
                    // Group by Registration Type
                    $currentType = null;
                    foreach ($allPlans as $plan) {
                        if ($currentType !== $plan['type_name']) {
                            $currentType = $plan['type_name'];
                            echo '<div class="text-xs font-bold text-gray-400 uppercase mt-4 mb-2 pl-2">'.$currentType.'</div>';
                        }
                        echo '
                        <button onclick="loadFees('.$plan['id'].', \''.htmlspecialchars($plan['name'], ENT_QUOTES).'\')" class="w-full text-left px-4 py-3 rounded-xl transition-all hover:bg-white dark:hover:bg-slate-700 focus:ring-2 focus:ring-yellow-500 plan-btn" data-id="'.$plan['id'].'">
                            <div class="font-medium text-gray-800 dark:text-gray-200">'.$plan['name'].'</div>
                            <div class="text-xs text-gray-500">'.$plan['grade_code'].'</div>
                        </button>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Main Content: Fee Items -->
        <div class="lg:col-span-2">
            <div id="feeContent" class="hidden space-y-6">
                <div class="glass rounded-2xl p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="selectedPlanName">ชื่อแผนการเรียน</h3>
                            <p class="text-sm text-gray-500">จัดการรายการค่าใช้จ่าย</p>
                        </div>
                        <button onclick="openAddModal()" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg shadow hover:shadow-lg transition-all">
                            <i class="fas fa-plus mr-2"></i> เพิ่มรายการ
                        </button>
                    </div>

                    <!-- Maintenance Fees -->
                    <div class="mb-8">
                        <h4 class="font-bold text-emerald-600 mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">
                            1. เงินบำรุงการศึกษา (Maintenance Fees)
                        </h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-slate-700/50 text-gray-600 dark:text-gray-300">
                                        <th class="px-4 py-3 text-left rounded-l-lg">รายการ</th>
                                        <th class="px-4 py-3 text-right">ภาคเรียนที่ 1</th>
                                        <th class="px-4 py-3 text-right">ภาคเรียนที่ 2</th>
                                        <th class="px-4 py-3 text-right">รวม</th>
                                        <th class="px-4 py-3 text-center rounded-r-lg" width="100">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody id="maintenanceTableBody" class="divide-y divide-gray-100 dark:divide-slate-700"></tbody>
                                <tfoot>
                                    <tr class="bg-emerald-50/50 dark:bg-emerald-900/10 font-bold text-emerald-800 dark:text-emerald-400">
                                        <td class="px-4 py-3">รวมเงินบำรุงการศึกษา</td>
                                        <td class="px-4 py-3 text-right" id="mTotal1">0</td>
                                        <td class="px-4 py-3 text-right" id="mTotal2">0</td>
                                        <td class="px-4 py-3 text-right" id="mTotalAll">0</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Support Fees -->
                    <div>
                        <h4 class="font-bold text-blue-600 mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">
                            2. ค่าใช้จ่ายสนับสนุน (Support Fees)
                        </h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-slate-700/50 text-gray-600 dark:text-gray-300">
                                        <th class="px-4 py-3 text-left rounded-l-lg">รายการ</th>
                                        <th class="px-4 py-3 text-right">ภาคเรียนที่ 1</th>
                                        <th class="px-4 py-3 text-right">ภาคเรียนที่ 2</th>
                                        <th class="px-4 py-3 text-right">รวม</th>
                                        <th class="px-4 py-3 text-center rounded-r-lg" width="100">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody id="supportTableBody" class="divide-y divide-gray-100 dark:divide-slate-700"></tbody>
                                <tfoot>
                                    <tr class="bg-blue-50/50 dark:bg-blue-900/10 font-bold text-blue-800 dark:text-blue-400">
                                        <td class="px-4 py-3">รวมเงินค่าใช้จ่ายสนับสนุน</td>
                                        <td class="px-4 py-3 text-right" id="sTotal1">0</td>
                                        <td class="px-4 py-3 text-right" id="sTotal2">0</td>
                                        <td class="px-4 py-3 text-right" id="sTotalAll">0</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Grand Total -->
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-200">รวมเงินทั้งสิ้น</span>
                            <span class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-600 to-amber-600" id="grandTotal">0 บาท</span>
                        </div>
                    </div>
                </div>
            </div>

            <div id="emptyState" class="glass rounded-2xl p-12 text-center text-gray-400">
                <i class="fas fa-arrow-left text-4xl mb-4 animate-bounce-x"></i>
                <p>กรุณาเลือกแผนการเรียนจากเมนูซ้ายมือ</p>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<!-- Add/Edit Modal -->
<div id="feeModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="relative glass rounded-2xl w-full max-w-md flex flex-col shadow-2xl">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="modalTitle">เพิ่มรายการ</h3>
                <button onclick="closeModal()" class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="feeForm" onsubmit="saveFee(event)" class="p-6 space-y-4">
                <input type="hidden" name="id" id="feeId">
                <input type="hidden" name="plan_id" id="feePlanId">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">หมวดหมู่</label>
                    <select name="category" id="feeCategory" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-yellow-500 outline-none" required>
                        <option value="maintenance">1. เงินบำรุงการศึกษา</option>
                        <option value="support">2. ค่าใช้จ่ายสนับสนุน</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">รายการ</label>
                    <input type="text" name="item_name" id="feeName" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-yellow-500 outline-none" required placeholder="เช่น ค่าประกันอุบัติเหตุ">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">เทอม 1 (บาท)</label>
                        <input type="number" step="0.01" name="term1_amount" id="feeTerm1" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-yellow-500 outline-none" placeholder="0.00">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">เทอม 2 (บาท)</label>
                        <input type="number" step="0.01" name="term2_amount" id="feeTerm2" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-yellow-500 outline-none" placeholder="0.00">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ลำดับการแสดงผล</label>
                    <input type="number" name="sort_order" id="feeOrder" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-yellow-500 outline-none" value="0">
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">ยกเลิก</button>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-amber-600 text-white rounded-lg shadow hover:shadow-lg">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentPlanId = null;

function loadFees(planId, planName) {
    console.log('Loading fees for:', planId, planName);
    currentPlanId = planId;
    $('#selectedPlanName').text(planName);
    $('#feePlanId').val(planId);
    
    // UI toggle
    $('.plan-btn').removeClass('bg-yellow-50 dark:bg-yellow-900/20 ring-2 ring-yellow-500');
    $(`.plan-btn[data-id="${planId}"]`).addClass('bg-yellow-50 dark:bg-yellow-900/20 ring-2 ring-yellow-500');
    
    $('#emptyState').addClass('hidden');
    $('#feeContent').removeClass('hidden').addClass('animate-fade-in');

    $.ajax({
        url: 'api/manage_fees.php',
        method: 'GET',
        data: { plan_id: planId },
        dataType: 'json',
        success: function(response) {
            console.log('Fees loaded:', response);
            renderFees(response);
        },
        error: function(xhr, status, error) {
            console.error('API Error:', error);
            Swal.fire('Error', 'ไม่สามารถโหลดข้อมูลได้: ' + error, 'error');
        }
    });
}

function renderFees(fees) {
    const maintenance = fees.filter(f => f.category === 'maintenance');
    const support = fees.filter(f => f.category === 'support');
    
    // Calculate Totals Helpers
    const sum = (items) => {
        let t1 = 0, t2 = 0;
        items.forEach(i => { t1 += parseFloat(i.term1_amount||0); t2 += parseFloat(i.term2_amount||0); });
        return { t1, t2, total: t1 + t2 };
    };

    const renderRow = (item) => `
        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors group">
            <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-200">${item.item_name}</td>
            <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400">${parseFloat(item.term1_amount).toLocaleString()}</td>
            <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400">${parseFloat(item.term2_amount).toLocaleString()}</td>
            <td class="px-4 py-3 text-right font-bold text-gray-700 dark:text-gray-300">${(parseFloat(item.term1_amount) + parseFloat(item.term2_amount)).toLocaleString()}</td>
            <td class="px-4 py-3 text-center">
                <div class="opacity-0 group-hover:opacity-100 transition-opacity flex justify-center gap-2">
                    <button onclick='editFee(${JSON.stringify(item)})' class="p-1.5 text-blue-500 hover:bg-blue-50 rounded transition-colors"><i class="fas fa-edit"></i></button>
                    <button onclick="deleteFee(${item.id})" class="p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors"><i class="fas fa-trash"></i></button>
                </div>
            </td>
        </tr>
    `;

    $('#maintenanceTableBody').html(maintenance.map(renderRow).join(''));
    $('#supportTableBody').html(support.map(renderRow).join(''));

    const mTotal = sum(maintenance);
    const sTotal = sum(support);
    
    $('#mTotal1').text(mTotal.t1.toLocaleString());
    $('#mTotal2').text(mTotal.t2.toLocaleString());
    $('#mTotalAll').text(mTotal.total.toLocaleString());

    $('#sTotal1').text(sTotal.t1.toLocaleString());
    $('#sTotal2').text(sTotal.t2.toLocaleString());
    $('#sTotalAll').text(sTotal.total.toLocaleString());

    $('#grandTotal').text((mTotal.total + sTotal.total).toLocaleString() + ' บาท');
}

function openAddModal() {
    $('#feeForm')[0].reset();
    $('#feeId').val('');
    $('#feePlanId').val(currentPlanId);
    $('#modalTitle').text('เพิ่มรายการ');
    $('#feeModal').removeClass('hidden');
}

function editFee(item) {
    $('#feeId').val(item.id);
    $('#feePlanId').val(item.plan_id);
    $('#feeCategory').val(item.category);
    $('#feeName').val(item.item_name);
    $('#feeTerm1').val(item.term1_amount);
    $('#feeTerm2').val(item.term2_amount);
    $('#feeOrder').val(item.sort_order);
    
    $('#modalTitle').text('แก้ไขรายการ');
    $('#feeModal').removeClass('hidden');
}

function closeModal() {
    $('#feeModal').addClass('hidden');
}

function saveFee(e) {
    e.preventDefault();
    const formData = $('#feeForm').serializeArray();
    const id = $('#feeId').val();
    
    // Convert to object
    let data = {};
    formData.forEach(item => data[item.name] = item.value);

    // If ID exists, it's an EDIT (PUT), otherwise ADD (POST)
    const method = id ? 'PUT' : 'POST';
    
    $.ajax({
        url: 'api/manage_fees.php',
        method: method,
        // For PUT, we often need to send JSON or form-urlencoded string manually if backend uses parse_str(file_get_contents('php://input'))
        // The backend uses parse_str, so we should send a query string or form data.
        // For jQuery, data object is automatically converted to query string for non-GET unless processData is false.
        // However, for consistency and ensuring parse_str works, let's pass the object directly.
        data: data, 
        success: function() {
            closeModal();
            loadFees(currentPlanId, $('#selectedPlanName').text());
            Swal.fire({ icon: 'success', title: 'บันทึกสำเร็จ', timer: 1500, showConfirmButton: false });
        },
        error: function(xhr, status, error) {
            console.error('Save Error:', error);
            console.log('Response:', xhr.responseText);
            Swal.fire('Error', 'บันทึกไม่สำเร็จ: ' + (xhr.responseJSON?.error || error), 'error');
        }
    });
}

function deleteFee(id) {
    Swal.fire({
        title: 'ยืนยันการลบ',
        text: "คุณต้องการลบรายการนี้ใช่หรือไม่?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'ลบ',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'api/manage_fees.php?id=' + id,
                method: 'DELETE',
                success: function() {
                    loadFees(currentPlanId, $('#selectedPlanName').text());
                }
            });
        }
    });
}
</script>

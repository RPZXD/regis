<?php
function createNavItem($href, $iconClass, $text) {
    return '
    <li class="nav-item">
        <a href="' . htmlspecialchars($href) . '" class="nav-link ">
            <i class="nav-icon fas ' . htmlspecialchars($iconClass) . '"></i>
            <p>' . htmlspecialchars($text) . '</p>
        </a>
    </li>';
}

function createNavItemName($avatar, $text) {
    return '
    <li class="nav-item">
        <div class="nav-link text-center">
            <img src="' . $avatar .'" alt="User Avatar" class="user-avatar rounded-full w-28 h-28 mx-auto">
        </div>
        <div class="nav-link text-center">
            <p class="text-white font-bold">'. $text . '</p>
        </div>
        <div class="nav-link text-center">
            <p class="text-white font-bold">ตำแหน่ง : ครู</p>
        </div>
    </li>';
}

function createNavSubMenu($iconClass, $text, $subItems) {
    $subMenu = '
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="nav-icon fas ' . htmlspecialchars($iconClass) . '"></i>
            <p>
                ' . htmlspecialchars($text) . '
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">';
    foreach ($subItems as $item) {
        $subMenu .= createNavItem($item['href'], $item['iconClass'], $item['text']);
    }
    $subMenu .= '</ul></li>';
    return $subMenu;
}

echo createNavItemName(htmlspecialchars($setting->getImgProfile().$userData['Teach_photo']), htmlspecialchars($userData['Teach_name']));

echo "<hr>";

echo createNavItem('index.php', 'fas fa-home', 'หน้าหลัก');

$subItems = [
    ['href' => 'm1_nomal.php', 'iconClass' => 'fa-user-plus', 'text' => 'ม1 (สอบคัดเลือกทั่วไป)'],
    ['href' => 'm4_quota.php', 'iconClass' => 'fa-user-plus', 'text' => 'ม4 (โควต้า ม3เดิม)'],
    ['href' => 'm4_nomal.php', 'iconClass' => 'fa-user-plus', 'text' => 'ม4 (สอบคัดเลือกทั่วไป)']
];
$subItems2 = [
    ['href' => 'con_m1_nomal.php', 'iconClass' => 'fa-user-plus', 'text' => 'ม1 (สอบคัดเลือกทั่วไป)'],
    ['href' => 'con_m4_quota.php', 'iconClass' => 'fa-user-plus', 'text' => 'ม4 (โควต้า ม3เดิม)'],
    ['href' => 'con_m4_nomal.php', 'iconClass' => 'fa-user-plus', 'text' => 'ม4 (สอบคัดเลือกทั่วไป)']
];
echo createNavSubMenu('fa-user', 'ข้อมูลสมัคร', $subItems);
echo createNavSubMenu('fa-tasks', 'รายงานตัว', $subItems2);

// echo createNavItem('m4_esc.php', 'fas fa-user-plus', 'ม4 (ห้องเรียนพิเศษ)');

echo createNavItem('../logout.php', 'fas fa-sign-out-alt', 'ออกจากระบบ');
?>
<?php
function createNavItem($href, $iconClass, $text) {
    return '
    <li class="nav-item">
        <a href="' . htmlspecialchars($href) . '" class="nav-link">
            <i class="nav-icon fas ' . htmlspecialchars($iconClass) . '"></i>
            <p>' . htmlspecialchars($text) . '</p>
        </a>
    </li>';
}

function createNavItem2($href, $iconClass, $text, $number) {
    return '
    <li class="nav-item">
        <a href="' . htmlspecialchars($href) . '" class="nav-link">
            <i class="nav-icon fas ' . htmlspecialchars($iconClass) . '"></i>
            <p>' . htmlspecialchars($text) . '&nbsp;&nbsp;<span class="badge text-white bg-red-700"> '.htmlspecialchars($number).' </span></p>
        </a>
    </li>';
}

echo createNavItem('index.php', 'fas fa-home', 'หน้าหลัก');
// echo createNavItem('annouce.php', 'fas fa-bullhorn', 'ประกาศรับสมัคร');
// echo createNavItem('detail.php', 'fas fa-info', 'รายละเอียดการรับสมัคร');
echo createNavItem2('regis.php', 'fas fa-user-plus', 'สมัครเรียน', '1');
echo createNavItem('checkreg.php', 'fas fa-check', 'เช็คการสมัคร');
echo createNavItem2('print_reg.php', 'fas fa-print', 'พิมพ์ใบสมัคร', '2'); // New menu item
echo createNavItem2('upload.php', 'fas fa-upload', 'อัพโหลดหลักฐาน', '3'); // New menu item
echo createNavItem2('check_uploads.php', 'fas fa-check', 'ตรวจสอบสถานะอัพโหลดหลักฐาน', '4'); // New menu item
// echo createNavItem('login_student.php', 'fas fa-arrow-right', 'เข้าสู่ระบบ');
// echo createNavItem2('print.php', 'fas fa-credit-card', 'พิมพ์บัตรสอบ', '6');
echo createNavItem('confirm.php', 'fas fa-file-signature', 'รายงานตัว');
echo createNavItem('contact.php', 'fas fa-address-book', 'ติดต่อ-สอบถาม');

// echo createNavItem('faq.php', 'fas fa-question', 'วิธีการใช้งาน');
echo createNavItem('login.php', 'fas fa-sign-in-alt', 'ผู้ดูแลระบบ');
?>


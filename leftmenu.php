
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

echo createNavItem('index.php', 'fas fa-home', 'หน้าหลัก');
// echo createNavItem('annouce.php', 'fas fa-bullhorn', 'ประกาศรับสมัคร');
// echo createNavItem('detail.php', 'fas fa-info', 'รายละเอียดการรับสมัคร');
echo createNavItem('regis.php', 'fas fa-user-plus', 'สมัครเรียน');
echo createNavItem('checkreg.php', 'fas fa-check', 'เช็คการสมัคร');
echo createNavItem('print.php', 'fas fa-print', 'พิมพ์บัตรสอบ');
echo createNavItem('confirm.php', 'fas fa-file-signature', 'รายงานตัว');
// echo createNavItem('login_student.php', 'fas fa-arrow-right', 'เข้าสู่ระบบ');
echo createNavItem('contact.php', 'fas fa-address-book', 'ติดต่อ-สอบถาม');

// echo createNavItem('faq.php', 'fas fa-question', 'วิธีการใช้งาน');
echo createNavItem('login.php', 'fas fa-sign-in-alt', 'ผู้ดูแลระบบ');
?>
<?php
/**
 * Dynamic Left Menu - reads from menu_config table
 */
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/class/AdminConfig.php';

// Get database connection and config
$connectDB = new Database_Regis();
$db = $connectDB->getConnection();
$adminConfig = new AdminConfig($db);

// Get active menus from config
$menus = $adminConfig->getActiveMenus();

// Helper function to create nav item
function createNavItem($href, $iconClass, $text, $badge = null) {
    $badgeHtml = $badge ? '&nbsp;&nbsp;<span class="badge text-white bg-red-700">' . htmlspecialchars($badge) . '</span>' : '';
    return '
    <li class="nav-item">
        <a href="' . htmlspecialchars($href) . '" class="nav-link">
            <i class="nav-icon fas ' . htmlspecialchars($iconClass) . '"></i>
            <p>' . htmlspecialchars($text) . $badgeHtml . '</p>
        </a>
    </li>';
}

// Badge mapping for certain menus (can be moved to database later)
$badges = [
    'register' => '1',
    'print_form' => '2',
    'upload' => '3',
    'check_status' => '4',
    'exam_card' => '6'
];

// Always show home
echo createNavItem('index.php', 'fa-home', 'หน้าหลัก');

// Dynamic menus from database
foreach ($menus as $menu) {
    $badge = $badges[$menu['menu_key']] ?? null;
    echo createNavItem($menu['menu_url'], $menu['icon'], $menu['menu_name'], $badge);
}

// Always show contact and admin login
echo createNavItem('contact.php', 'fa-address-book', 'ติดต่อ-สอบถาม');
echo createNavItem('login.php', 'fa-sign-in-alt', 'ผู้ดูแลระบบ');
?>

<?php 
/**
 * Logout Page
 * Destroys session and redirects to home with success message
 */
session_start();
session_destroy();

require_once 'config/Setting.php';
require_once 'class/Utils.php';

$bs = new Bootstrap();
$sw2 = new SweetAlert2(
    'คุณได้ทำการออกจากระบบเรียบร้อยแล้ว',
    'success',
    'index.php'
);
$sw2->renderAlert();
<?php 

session_start();
session_destroy();

require_once('header.php');
include_once("class/Utils.php");
$bs = new Bootstrap();

    // displaySuccessMessage('คุณได้ทำการออกจากระบบเรียบร้อยแล้ว', 'index.php');
    $sw2 = new SweetAlert2(
        'คุณได้ทำการออกจากระบบเรียบร้อยแล้ว',
        'success',
        'index.php' // Redirect URL
    );
    $sw2->renderAlert();

?>
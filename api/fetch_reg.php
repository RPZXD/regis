<?php
require_once('../config/Database.php');
require_once('../class/StudentRegis.php');

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"));

if (isset($data->search_input)) {
    $database = new Database_Regis();
    $db = $database->getConnection();

    $studentRegis = new StudentRegis($db);
    $result = $studentRegis->getStudentBySearchInput($data->search_input);

    if ($result) {
        // แปลงวันเกิดเป็นรูปแบบ วัน เดือน ปี พ.ศ.
        $dateParts = explode('-', $result['birthday']);
        $day = $dateParts[0];
        $month = str_pad($dateParts[1], 2, '0', STR_PAD_LEFT); // เพิ่ม 0 ข้างหน้าเดือนถ้าจำเป็น
        $year = $dateParts[2];

        $thaiMonths = [
            '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน',
            '05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฎาคม', '08' => 'สิงหาคม',
            '09' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม'
        ];

        $birthdayThai = $day . ' ' . $thaiMonths[$month] . ' ' . $year;

        echo json_encode([
            'exists' => true,
            'citizenid' => $result['citizenid'],
            'fullname' => $result['stu_prefix'] . $result['stu_name'] . ' ' . $result['stu_lastname'],
            'birthday' => $birthdayThai,
            'now_tel' => $result['now_tel'],
            'level' => $result['level'],
            'typeregis' => $result['typeregis'],
            'parent_tel' => $result['parent_tel'],
            'numreg' => $result['numreg'],
            'status' => $result['status']
        ]);
    } else {
        echo json_encode(['exists' => false]);
    }
} else {
    echo json_encode(['exists' => false]);
}
?>

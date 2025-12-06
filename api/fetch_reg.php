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

        // Fetch Study Plans
        $plans = $studentRegis->getStudentPlans($result['citizenid']);
        $planNames = [];
        
        function getRoomNameM4_API($number) {
            switch ($number) {
                case 1: return "ห้อง 2 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)";
                case 2: return "ห้อง 3 : วิทยาศาสตร์พลังสิบ";
                case 3: return "ห้อง 4 : วิทยาศาสตร์ คณิตศาสตร์";
                case 4: return "ห้อง 5 : สังคมศาสตร์และภาษาไทย";
                case 5: return "ห้อง 6 : ภาษาศาสตร์ (ภาษาอังกฤษ, ภาษาจีน)";
                case 6: return "ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอาหาร";
                case 7: return "ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการเกษตร";
                case 8: return "ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอุตสาหกรรม";
                default: return "";
            }
        }

        function getRoomNameM1_API($number) {
            switch ($number) {
                case 1: return "ห้อง 3 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)";
                case 2: return "ห้อง 4 : วิทยาศาสตร์พลังสิบ";
                case 3: return "ห้อง 5 : ภาษาต่างประเทศ (ภาษาอังกฤษ)";
                case 4: return "ห้อง 6 : ภาษาต่างประเทศ (ภาษาจีน)";
                case 5: return "ห้อง 7 : ภาษาไทย";
                case 6: return "ห้อง 8 : สังคมศึกษา";
                case 7: return "ห้อง 9 : อุตสาหกรรม - พาณิชยกรรม แผน - อุตสาหกรรม";
                case 8: return "ห้อง 9 : อุตสาหกรรม - พาณิชยกรรม แผน - พาณิชยกรรม";
                case 9: return "ห้อง 10 : เกษตรกรรม - คหกรรม แผน - เกษตรกรรม";
                case 10: return "ห้อง 10 : เกษตรกรรม - คหกรรม แผน – คหกรรม";
                case 11: return "ห้อง 11 : ศิลปะ - ดนตรี แผน - ศิลปะ";
                case 12: return "ห้อง 11 : ศิลปะ - ดนตรี แผน - ดนตรี";
                case 13: return "ห้อง 11 : ศิลปะ - ดนตรี แผน - นาฏศิลป์";
                case 14: return "ห้อง 12 : กีฬา แผน - ฟุตบอล";
                case 15: return "ห้อง 12 : กีฬา แผน - วู้ดบอล";
                default: return "";
            }
        }

        if (!empty($plans)) {
            foreach ($plans as $p) {
                $pName = ($result['level'] == 4) ? getRoomNameM4_API($p['plan_id']) : getRoomNameM1_API($p['plan_id']);
                if ($pName) $planNames[] = $pName;
            }
        } else {
             // Fallback for legacy columns if strict mode off, but we are fixing it.
             // Assume migrated.
        }

        echo json_encode([
            'exists' => true,
            'id' => $result['id'],
            'citizenid' => $result['citizenid'],
            'fullname' => $result['stu_prefix'] . $result['stu_name'] . ' ' . $result['stu_lastname'],
            'birthday' => $birthdayThai,
            'now_tel' => $result['now_tel'],
            'level' => $result['level'],
            'typeregis' => $result['typeregis'],
            'parent_tel' => $result['parent_tel'],
            'numreg' => $result['numreg'],
            'status' => $result['status'],
            'plans' => $planNames
        ]);
    } else {
        echo json_encode(['exists' => false]);
    }
} else {
    echo json_encode(['exists' => false]);
}
?>

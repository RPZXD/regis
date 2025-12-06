<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once "config/Database.php";
require_once "function.php";
$dbRegis = new Database_Regis();
$db = $dbRegis->getConnection();
session_start();


$uid = $_GET['stu_id'];


$db->exec("set names utf8");
$select_stmt = $db->prepare("SELECT * FROM users
WHERE users.id = :uid");
$select_stmt->execute(array(':uid' => $uid));
$row = $select_stmt->fetch(PDO::FETCH_ASSOC);

$select_stmt = $db->prepare("SELECT * FROM setting");
$select_stmt->execute();
// Map setting keys to array for compatibility if needed, or just fetch row
// The original code fetched 's.year, m.*'. m.* likely had logo_school.
// Let's assume setting table has relevant config or we use defaults.
// Actually, list_tables.php showed 'setting' and 'settings'.
$settings = $select_stmt->fetch(PDO::FETCH_ASSOC);

// Polyfill if missing keys
if (!isset($settings['logo_school'])) $settings['logo_school'] = 'logo-phicha.png';
if (!isset($settings['year'])) $settings['year'] = '2567';

$space_bar = '&nbsp;&nbsp;&nbsp;&nbsp;';
$space_bar = '&nbsp;&nbsp;&nbsp;&nbsp;';

$mpdf = new \Mpdf\Mpdf([
	'default_font_size' => 14,
	'default_font' => 'sarabun'
]);

$mpdf->SetTitle('ใบสมัครเรียนโรงเรียนพิชัยระดับมัธยมศึกษาปีที่ 1 ( ทั่วไป ในเขต/นอกเขต ) ของ'.$row["stu_prefix"].$row["stu_name"].' '.$row["stu_lastname"]);


// logo && header
$html = '<div style="position:absolute;top:30px;left:50px;"><img src="dist/img/'.$settings['logo_school'].'" alt="" style="width:65px;height:65px;"></div>';
$html .= '<div style="position:absolute;top:30px;left:230px;font-weight: bold;">ใบสมัครเข้าศึกษาต่อระดับชั้น มัธยมศึกษาปีที่ '.ck_level2($row['level']).' ปีการศึกษา '.$settings['year'].'</div>';
$html .= '<div style="position:absolute;top:55px;left:290px;font-weight: bold;">โรงเรียนพิชัย &nbsp;&nbsp;อำเภอพิชัย &nbsp;&nbsp;จังหวัดอุตรดิตถ์</div>';
$html .= '<div style="position:absolute;top:80px;left:340px;font-weight: bold;">ประเภทห้องเรียนปกติ</div>';


// ขวาบน เฉพาะเจ้าหน้าที่
$html .= '<div style="position:absolute;top:10px;left:600px;width: 180px; height: 80px; border: 1px solid black;"></div>';
$html .= '<div style="position:absolute;top:15px;left:700px;font-size: 14px;font-weight: bold;">(เฉพาะเจ้าหน้าที่)</div>';
$html .= '<div style="position:absolute;top:70px;left:610px;font-size: 16px;font-weight: bold;">เลขที่ผู้สมัคร......................................</div>';


// กรอบติดรูป
$html .= '<div style="position:absolute;top:110px;left:630px;width: 110px; height: 150px; border: 1.5px solid black;font-weight: bold;text-align: center;">
            <div style="display: flex; justify-content: center; align-items: center;margin-top: 50px;font-size: 16px;">
            รูปถ่าย <br> ขนาด 1.5 นิ้ว
            </div>
            </div>
            ';


// กรอบ ประเภทของการสมัคร
$html .= '<div style="position:absolute;top:110px;left:180px;width: 420px; height: 35px; border: 2px solid black;font-weight: bold;text-align: center;">
            <div style="display: flex; justify-content: center; align-items: center;margin-top: 8px;">
            โปรดระบุ'. $space_bar . ck_typeregis($row['typeregis']) .'
            </div>
            </div>
            ';
//312 425
if($row['typeregis'] == 'ในเขต'){
$html .= '<div style="position:absolute;top:116px;left:312px;font-weight:">
            /
            </div>
            ';
}else if ($row['typeregis'] == 'นอกเขต') {
    $html .= '<div style="position:absolute;top:116px;left:425px;font-weight:">
    /
    </div>
    ';
}


// รายละเอียดผู้สมัคร #1 
$html .= '<div style="position:absolute;top:160px;left:50px;width: 570px; height: 35px; border: 0px solid black;font-weight: bold;text-align: left;">
            <div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;text-decoration: underline;">
            รายละเอียดข้อมูลผู้สมัคร 
            </div>
            </div>
            ';

$str = $row["citizenid"];
$part1 = substr($str, 0, 1);
$part2 = substr($str, 1, 4);
$part3 = substr($str, 5, 5);
$part4 = substr($str, 10, 2);
$part5 = substr($str, 12, 1);
$formattedStr = $part1 . "-" . $part2 . "-" . $part3 . "-" . $part4 . "-" . $part5;

$html .= '<div style="position:absolute;top:185px;left:50px;width: 570px; height: 90px; border: 0px solid black;text-align: left;">
            <div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;">
            ชื่อผู้สมัคร'. $space_bar . $row['stu_prefix'] . $row['stu_name'] . $space_bar .'นามสกุล' . $space_bar . $row['stu_lastname'] 
            .'<br>เดิมเป็นนักเรียนโรงเรียน'. $space_bar . $row['old_school'] . $space_bar . 'อำเภอ' . $space_bar . $row['old_school_district'] 
            . $space_bar .'จังหวัด' . $space_bar . $row['old_school_province'] 
            . '<br>เลขประจำตัวประชาชน' . $space_bar . $formattedStr
            . '</div></div>';

$month = array(
    "", 
    "มกราคม", 
    "กุมภาพันธ์", 
    "มีนาคม",
    "เมษายน",
    "พฤษภาคม",
    "มิถุนายน",
    "กรกฎาคม",
    "สิงหาคม",
    "กันยายน",
    "ตุลาคม",
    "พฤศจิกายน",
    "ธันวาคม"
);

$html .= '<div style="position:absolute;top:260px;left:50px;width: 660px; height: 90px; border: 0px solid black;text-align: left;">
            <div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;">
            เพศ'. $space_bar . $row['stu_sex'] . $space_bar .'เกิดวันที่' . $space_bar . $row['date_birth'] . ' / ' . $month[$row['month_birth']] . ' / พ.ศ. ' . $row['year_birth'] . $space_bar
            .'กรุ๊ปเลือด'. $space_bar . $row['stu_blood_group'] . $space_bar . 'ศาสนา' . $space_bar . $row['stu_religion'] . $space_bar 
            . 'เชื้อชาติ' . $space_bar . $row['stu_ethnicity'] . 'สัญชาติ' . $space_bar . $row['stu_nationality']
            . '<br>ที่อยู่ปัจจุบัน' . $space_bar . $row['now_addr'] . $space_bar . 'หมู่ที่' . $space_bar . $row['now_moo'] . $space_bar . 'ซอย' . $space_bar . $row['now_soy']
            . $space_bar . 'ถนน' . $space_bar . $row['now_street'] . $space_bar . 'ตำบล/แขวง' . $space_bar . $row['now_subdistrict'] 
            . '<br>อำเภอ/เขต' . $space_bar . $row['now_district'] . $space_bar . 'จังหวัด' . $space_bar . $row['now_province'] . $space_bar . 'รหัสไปรษณีย์' . $space_bar . $row['now_post'] 
            . $space_bar . 'โทรศัพท์' . $space_bar . $row['now_tel'] 
            . '</div></div>';

$html .= '<div style="position:absolute;top:335px;left:50px;width: 660px; height: 60px; border: 0px solid black;text-align: left;">
            <div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;">
            ที่อยู่ตามทะเบียนบ้าน บ้านเลขที่' . $space_bar . $row['old_addr'] . $space_bar . 'หมู่ที่' . $space_bar . $row['old_moo'] . $space_bar . 'ซอย' . $space_bar . $row['old_soy']
            . $space_bar . 'ถนน' . $space_bar . $row['old_street'] . $space_bar . 'ตำบล/แขวง' . $space_bar . $row['old_subdistrict'] 
            . '<br>อำเภอ/เขต' . $space_bar . $row['old_district'] . $space_bar . 'จังหวัด' . $space_bar .  $row['old_province'] . $space_bar . 'รหัสไปรษณีย์' . $space_bar . $row['old_post'] 
            . $space_bar . 'โทรศัพท์' . $space_bar . $row['old_tel'] 
            . '</div></div>';

$html .= '<div style="position:absolute;top:385px;left:50px;width: 660px; height: 90px; border: 0px solid black;text-align: left;">
            <div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;">
            ชื่อบิดา' . $space_bar . $row['dad_prefix'] . $row['dad_name'] . $space_bar . 'นามสกุล' . $space_bar . $row['dad_lastname'] . $space_bar 
            . 'อาชีพ' . $space_bar . $row['dad_job'] . $space_bar . 'โทรศัพท์' . $space_bar . $row['dad_tel']            
            .'<br>ชื่อมารดา' . $space_bar . $row['mom_prefix'] . $row['mom_name'] . $space_bar . 'นามสกุล' . $space_bar . $row['mom_lastname'] . $space_bar 
            . 'อาชีพ' . $space_bar . $row['mom_job'] . $space_bar . 'โทรศัพท์' . $space_bar . $row['mom_tel']
            .'<br>ผู้ปกครองที่สามารถติดต่อได้ในกรณีฉุกเฉิน  ชื่อ' . $space_bar . $row['parent_prefix'] . $row['parent_name'] . $space_bar . 'นามสกุล' . $space_bar . $row['parent_lastname'] . $space_bar 
            . 'โทรศัพท์' . $space_bar . $row['parent_tel']
            . '</div></div>';

$html .= '<div style="position:absolute;top:470px;left:50px;width: 660px; height: 40px; border: 0px solid black;text-align: left;">
            <div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 16px;font-weight: bold;">
            นักเรียนมีความประสงค์จะเข้าเรียนในแผนการเรียน (เรียงลำดับตามความต้องการ 1- 10)' 
            . '</div></div>';

$html .= '<div style="position:absolute;top:495px;left:50px;width: 660px; height: 280px; border: 0px solid black;text-align: left;">
<div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;">'; 

// ลำดับแผนการเรียน
// ลำดับแผนการเรียน
$planStmt = $db->prepare("SELECT plan_id FROM student_study_plans WHERE citizenid = :citizenid ORDER BY priority ASC");
$planStmt->execute([':citizenid' => $row['citizenid']]);
$plans = $planStmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($plans)) {
    foreach ($plans as $index => $plan) {
         $html .= 'ลำดับที่ ' . ($index + 1) . $space_bar . getRoomNameM1($plan["plan_id"]) . "<br>"; 
    }
} else {
    // Fallback/Legacy
    for ($i = 1; $i < 11; $i++) {
        if (isset($row["number" . $i]) && !empty($row["number" . $i])) {
            $html .= 'ลำดับที่ ' . $i . $space_bar . getRoomNameM1($row["number" . $i]) . "<br>"; 
        }
    }
}

$html .= '</div></div>';
          

$html .= '<div style="position:absolute;top:770px;left:50px;width: 700px; height: 90px; border: 0px solid black;text-align: left;">
            <div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;font-weight: bold;">
            ลงชื่อผู้สมัคร......................................................(นักเรียน)' . str_repeat($space_bar, 7)
            . 'ลงชื่อ......................................................(ผู้ปกครอง)'
            . '<br>วันที่ยื่นใบสมัคร......................................................'
            . '</div></div>';

$html .= '<div style="position:absolute;top:840px;left:65px;width: 660px; height: 140px; border: 1px solid black;text-align: left;">
            <div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;font-weight: bold;">
            (เฉพาะเจ้าหน้าที่)'
            . '<br>ตรวจสอบการสมัคร' . $space_bar . '☐' . $space_bar . 'ครบถ้วน'
            . '<br> ' . str_repeat($space_bar, 7) . '☐' . $space_bar . 'ไม่ครบครบถ้วน' . $space_bar . 'ขาด' . str_repeat(".", 115)
            . '<br><br>' . str_repeat($space_bar, 20) . 'ลงชื่อผู้รับสมัคร' . str_repeat(".", 50) . '(ครู)'
            . '</div></div>';

$html .= '<div style="position:absolute;top:890px;left:530px;"><img src="dist/img/signal.png" alt="" style="width:60%;height:60%"></div>';



$mpdf->WriteHTML($html);

$mpdf->Output();

?>

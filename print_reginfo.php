<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once 'config/Database.php';
require_once 'config/Setting.php';
require_once 'class/StudentRegis.php';

session_start();

// Get the citizenid from the GET request
$uid = isset($_GET['citizenid']) ? $_GET['citizenid'] : '';

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();
$studentRegis = new StudentRegis($db);

// รับค่าการตั้งค่าปีการศึกษา
$settings_stmt = $db->prepare("SELECT value FROM setting WHERE config_name = 'year'");
$settings_stmt->execute();
$settings = $settings_stmt->fetch(PDO::FETCH_ASSOC);
$year = $settings['value'];

$student = $studentRegis->getStudentByCitizId($uid);

$space_bar = '&nbsp;&nbsp;&nbsp;&nbsp;';


function DateThai1($strDate) {
    // Set the time zone to Thailand
    $timeZone = new DateTimeZone('Asia/Bangkok');
    // Create a new DateTime object with the provided date in the default time zone
    $date = new DateTime($strDate);
    // Set the time zone for the DateTime object to Thailand
    $date->setTimezone($timeZone);
    $strYear = $date->format("Y") + 543;
    $strMonth = $date->format("n");
    $strDay = $date->format("j");
    $strWeekday = $date->format("N");
    $strWeekCut = Array("", "จันทร์", "อังคาร", "พุธ", "พฤหัสบดี", "ศุกร์", "เสาร์", "อาทิตย์");
    $strMonthCut = Array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    $strWeekThai = $strWeekCut[$strWeekday];
    $strMonthThai = $strMonthCut[$strMonth];
    return $strDay . "&nbsp;&nbsp;" . $strMonthThai . "&nbsp;&nbsp;พ.ศ.&nbsp;" . $strYear;
}

function getRoomNameM4($number) {
    switch ($number) {
        case 1:
            return "ห้อง 2 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)";
        case 2:
            return "ห้อง 3 : วิทยาศาสตร์พลังสิบ";
        case 3:
            return "ห้อง 4 : วิทยาศาสตร์ คณิตศาสตร์";
        case 4:
            return "ห้อง 5 : สังคมศาสตร์และภาษาไทย";
        case 5:
            return "ห้อง 6 : ภาษาศาสตร์ (ภาษาอังกฤษ, ภาษาจีน)";
        case 6:
            return "ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอาหาร";
        case 7:
            return "ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการเกษตร";
        case 8:
            return "ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอุตสาหกรรม";
        default:
            return "";
    }
}

$mpdf = new \Mpdf\Mpdf([
	'default_font_size' => 14,
	'default_font' => 'sarabun'
]);

$mpdf->SetTitle('บัตรประจำตัวผู้เข้าสอบ ของ'.$student["stu_prefix"].$student["stu_name"].' '.$student["stu_lastname"]);

$level = $student['level'];
$type = $student['typeregis'];

function typeText($type){
    switch ($type) {
        case "โควต้า":
            $results = "ห้องเรียนปกติ 
สำหรับนักเรียนชั้นมัธยมศึกษาปีที่ 3 โรงเรียนพิชัย (เดิม)";
            break;
        case "รอบทั่วไป":
            $results = "";
            break;
        default:
            $results = "";
            break;
    }
    return $results;
}
// logo && header
$html = '<div style="position:absolute;top:30px;left:50px;"><img src="dist/img/logo-phicha.png" alt="" style="width:65px;height:65px;"></div>';
$html .= '<div style="position:absolute;top:30px;left:230px;font-weight: bold;">ใบสมัครเข้าศึกษาต่อระดับชั้น มัธยมศึกษาปีที่ '.$student['level'].' ปีการศึกษา '.$year.'</div>';
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
            '. $space_bar . typeText($type) .'
            </div>
            </div>
            ';
//312 425
if($student['typeregis'] == 'ในเขต'){
$html .= '<div style="position:absolute;top:116px;left:312px;font-weight:">
            /
            </div>
            ';
}else if ($student['typeregis'] == 'นอกเขต') {
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

$str = $student["citizenid"];
$part1 = substr($str, 0, 1);
$part2 = substr($str, 1, 4);
$part3 = substr($str, 5, 5);
$part4 = substr($str, 10, 2);
$part5 = substr($str, 12, 1);
$formattedStr = $part1 . "-" . $part2 . "-" . $part3 . "-" . $part4 . "-" . $part5;

$html .= '<div style="position:absolute;top:185px;left:50px;width: 570px; height: 90px; border: 0px solid black;text-align: left;">
            <div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;">
            ชื่อผู้สมัคร'. $space_bar . $student['stu_prefix'] . $student['stu_name'] . $space_bar .'นามสกุล' . $space_bar . $student['stu_lastname'] 
            .'<br>เดิมเป็นนักเรียนโรงเรียน'. $space_bar . $student['old_school'] . $space_bar . 'อำเภอ' . $space_bar . $student['old_school_district'] 
            . $space_bar .'จังหวัด' . $space_bar . $student['old_school_province'] 
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
            เพศ'. $space_bar . $student['stu_sex'] . $space_bar .'เกิดวันที่' . $space_bar . $student['date_birth'] . ' / ' . $month[$student['month_birth']] . ' / พ.ศ. ' . $student['year_birth'] . $space_bar
            .'กรุ๊ปเลือด'. $space_bar . $student['stu_blood_group'] . $space_bar . 'ศาสนา' . $space_bar . $student['stu_religion'] . $space_bar 
            . 'เชื้อชาติ' . $space_bar . $student['stu_ethnicity'] . 'สัญชาติ' . $space_bar . $student['stu_nationality']
            . '<br>ที่อยู่ปัจจุบัน' . $space_bar . $student['now_addr'] . $space_bar . 'หมู่ที่' . $space_bar . $student['now_moo'] . $space_bar . 'ซอย' . $space_bar . $student['now_soy']
            . $space_bar . 'ถนน' . $space_bar . $student['now_street'] . $space_bar . 'ตำบล/แขวง' . $space_bar . $student['now_subdistrict'] 
            . '<br>อำเภอ/เขต' . $space_bar . $student['now_district'] . $space_bar . 'จังหวัด' . $student['now_province'] . $space_bar . 'รหัสไปรษณีย์' . $space_bar . $student['now_post'] 
            . $space_bar . 'โทรศัพท์' . $space_bar . $student['now_tel'] 
            . '</div></div>';

$html .= '<div style="position:absolute;top:335px;left:50px;width: 660px; height: 60px; border: 0px solid black;text-align: left;">
            <div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;">
            ที่อยู่ตามทะเบียนบ้าน บ้านเลขที่' . $space_bar . $student['old_addr'] . $space_bar . 'หมู่ที่' . $space_bar . $student['old_moo'] . $space_bar . 'ซอย' . $space_bar . $student['old_soy']
            . $space_bar . 'ถนน' . $space_bar . $student['old_street'] . $space_bar . 'ตำบล/แขวง' . $space_bar . $student['old_subdistrict'] 
            . '<br>อำเภอ/เขต' . $space_bar . $student['old_district'] . $space_bar . 'จังหวัด' . $student['old_province'] . $space_bar . 'รหัสไปรษณีย์' . $space_bar . $student['old_post'] 
            . $space_bar . 'โทรศัพท์' . $space_bar . $student['old_tel'] 
            . '</div></div>';

$html .= '<div style="position:absolute;top:385px;left:50px;width: 660px; height: 90px; border: 0px solid black;text-align: left;">
            <div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;">
            ชื่อบิดา' . $space_bar . $student['dad_prefix'] . $student['dad_name'] . $space_bar . 'นามสกุล' . $space_bar . $student['dad_lastname'] . $space_bar 
            . 'อาชีพ' . $space_bar . $student['dad_job'] . $space_bar . 'โทรศัพท์' . $space_bar . $student['dad_tel']            
            .'<br>ชื่อมารดา' . $space_bar . $student['mom_prefix'] . $student['mom_name'] . $space_bar . 'นามสกุล' . $space_bar . $student['mom_lastname'] . $space_bar 
            . 'อาชีพ' . $space_bar . $student['mom_job'] . $space_bar . 'โทรศัพท์' . $space_bar . $student['mom_tel']
            .'<br>ผู้ปกครองที่สามารถติดต่อได้ในกรณีฉุกเฉิน  ชื่อ' . $space_bar . $student['parent_prefix'] . $student['parent_name'] . $space_bar . 'นามสกุล' . $space_bar . $student['parent_lastname'] . $space_bar 
            . 'โทรศัพท์' . $space_bar . $student['parent_tel']
            . '</div></div>';

$html .= '<div style="position:absolute;top:470px;left:50px;width: 660px; height: 40px; border: 0px solid black;text-align: left;">
            <div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 16px;font-weight: bold;">
            นักเรียนมีความประสงค์จะเข้าเรียนในแผนการเรียน (เรียงลำดับตามความต้องการ 1- 5)' 
            . '</div></div>';

$html .= '<div style="position:absolute;top:495px;left:50px;width: 660px; height: 280px; border: 0px solid black;text-align: left;">
<div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;">'; 

// ลำดับแผนการเรียน
for ($i = 1; $i < 6; $i++) {
    $html .= 'ลำดับที่ ' . $i . $space_bar . getRoomNameM4($student["number" . $i]) . "<br>"; 
}

$html .= '</div></div>';
          

$html .= '<div style="position:absolute;top:650px;left:50px;width: 700px; height: 90px; border: 0px solid black;text-align: left;">
            <div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;font-weight: bold;">
            ลงชื่อผู้สมัคร......................................................(นักเรียน)' . str_repeat($space_bar, 7)
            . 'ลงชื่อ......................................................(ผู้ปกครอง)'
            . '<br>วันที่ยื่นใบสมัคร......................................................'
            . '</div></div>';

$html .= '<div style="position:absolute;top:720px;left:65px;width: 660px; height: 140px; border: 1px solid black;text-align: left;">
            <div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;font-weight: bold;">
            (เฉพาะเจ้าหน้าที่)'
            . '<br>ตรวจสอบการสมัคร' . $space_bar . '☐' . $space_bar . 'ครบถ้วน'
            . '<br> ' . str_repeat($space_bar, 7) . '☐' . $space_bar . 'ไม่ครบครบถ้วน' . $space_bar . 'ขาด' . str_repeat(".", 115)
            . '<br><br>' . str_repeat($space_bar, 20) . 'ลงชื่อผู้รับสมัคร' . str_repeat(".", 50) . '(ครู)'
            . '</div></div>';


$html .= '<div style="position:absolute;top:775px;left:530px;"><img src="../dist/img/signal.png" alt="" style="width:60%;height:60%"></div>';

    
$mpdf->WriteHTML($html);

$mpdf->Output();

?>

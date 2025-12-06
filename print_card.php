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

function ck_level2($level){
    switch ($level) {
        case "m1":
            $results = "1";
            break;
        case "m4":
            $results = "4";
            break;
        default:
            $results = "";
            break;
    }
    return $results;
}

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
        case "ในเขต":
            $results = "ห้องเรียนปกติ (ในเขตพื้นที่บริการ)";
            break;
        case "นอกเขต":
            $results = "ห้องเรียนปกติ (นอกเขตพื้นที่บริการ)";
            break;
        case "รอบทั่วไป":
            $results = "ห้องเรียนปกติ (รอบทั่วไป)";
            break;
        default:
            $results = "";
            break;
    }
    return $results;
}
// logo && header
$html = '<div style="position:absolute;top:30px;left:50px;"><img src="dist/img/logo-phicha.png" alt="" style="width:65px;height:65px;"></div>';
$html .= '<div style="position:absolute;top:30px;left:150px;width: 440px; height: 35px; border: 0px solid black;font-weight: bold;text-align: center;">
            <div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;">
            บัตรประจำตัวผู้เข้าสอบ
            </div>
            </div>
            ';
$html .= '<div style="position:absolute;top:50px;left:150px;width: 440px; height: 35px; border: 0px solid black;font-weight: bold;text-align: center;">
            <div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;">
            เพื่อเข้าศึกษาต่อระดับชั้นมัธยมศึกษาปีที่ ' . $level . ' ปีการศึกษา '.$year.'
            </div>
            </div>
            ';
$html .= '<div style="position:absolute;top:70px;left:150px;width: 440px; height: 35px; border: 0px solid black;font-weight: bold;text-align: center;">
            <div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;">
            ประเภท' . typeText($type) .'
            </div>
            </div>
            ';

$html .= '<div style="position:absolute;top:100px;left:150px;width: 440px; height: 35px; border: 0px solid black;font-weight: bold;text-align: center;">
            <div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;">
            '. str_repeat("_", 50) .'
            </div>
            </div>
            ';


// ขวาบน เฉพาะเจ้าหน้าที่
$html .= '<div style="position:absolute;top:10px;left:600px;width: 180px; height: 50px; border: 1px solid black;"></div>';
$html .= '<div style="position:absolute;top:15px;left:700px;font-size: 14px;font-weight: bold;">(เฉพาะเจ้าหน้าที่)</div>';
$html .= '<div style="position:absolute;top:40px;left:650px;font-size: 18px;font-weight: bold;">เลขที่ผู้สมัคร '. $student['numreg'] . '</div>';


$photoPath = "";
$stmt = $db->prepare("SELECT path FROM tbl_uploads WHERE citizenid = :citizenid AND name = 'document8'");
$stmt->bindParam(':citizenid', $uid);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result && isset($result['path'])) {
    $photoPath = "uploads/" . $uid . "/" . $result['path'];
} else {
    $photoPath = ""; // Default no-photo image
}


// กรอบติดรูป
$html .= '<div style="position:absolute;top:110px;left:630px;width: 110px; height: 150px; border: 1.5px solid black;text-align: center;">
            <div style="width: 100%; height: 100%; overflow: hidden;">';

if (!empty($photoPath)) {
    $html .= '<img src="' . $photoPath . '" alt="Student Photo" 
                     style="width: 100%; min-height: 100%; object-fit: cover; object-position: center;">';
} else {
    $html .= '<div style="padding-top: 50px; font-size: 14px;">ติดรูปถ่าย<br>1.5 นิ้ว</div>';
}

$html .= '    </div>
        </div>';


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
            . $space_bar .'จังหวัด' . $space_bar .  $student['old_school_province'] 
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


$html .= '<div style="position:absolute;top:280px;left:50px;width: 700px; height: 90px; border: 0px solid black;text-align: left;">
            <div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;font-weight: bold;">
            ลงชื่อผู้สมัคร' . str_repeat(".", 50) . '(นักเรียน)' . str_repeat($space_bar, 7)
            . 'ลงชื่อ' . str_repeat(".", 70) . '(ครู)'
            . '<br>วันที่ยื่นใบสมัคร ' . DateThai1($student['create_at'])
            . '</div></div>';

$html .= '<div style="position:absolute;top:230px;left:560px;"><img src="dist/img/signal.png" alt="" style="width:60%;height:60%"></div>';


$html .= '<div style="position:absolute;top:350px;left:50px;width: 570px; height: 35px; border: 0px solid black;font-weight: bold;text-align: left;">
<div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;text-decoration: underline;">
หมายเหตุ 
</div>

</div>
';
$html .= '<div style="position:absolute;top:350px;left:110px;width: 570px; height: 35px; border: 0px solid black;text-align: left;">
<div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;">
    ให้นักเรียนนำบัตรประจำตัวนี้และดินสอ 2B มาใช้ในวันสอบด้วย
</div>
</div>
';

$html .= '<div style="position:absolute;top:390px;left:0px;width: 800px; height: 35px; border: 0px solid black;text-align: left;">
<div style="display: flex; justify-content: left; align-items: left;margin-top: 8px;margin-left: 15px;">
    '. str_repeat(".", 350) .'
</div>
</div>
';
            
$mpdf->WriteHTML($html);

$mpdf->Output();

?>

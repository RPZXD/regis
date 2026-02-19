<?php
/**
 * Exam Card PDF Generator
 * บัตรประจำตัวผู้เข้าสอบ
 */
require_once __DIR__ . '/vendor/autoload.php';
require_once "config/Database.php";
require_once "function.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$dbRegis = new Database_Regis();
$db = $dbRegis->getConnection();
$db->exec("set names utf8");

// Get student ID (priority) or citizen ID
$regId = $_GET['reg_id'] ?? $_GET['id'] ?? null;
$citizenid = $_GET['citizenid'] ?? null;

if ($regId) {
    $select_stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
    $select_stmt->execute([':id' => $regId]);
} elseif ($citizenid) {
    $cleanCitizen = preg_replace('/[^0-9]/', '', $citizenid);
    $select_stmt = $db->prepare("SELECT * FROM users WHERE citizenid = :citizenid ORDER BY id DESC LIMIT 1");
    $select_stmt->execute([':citizenid' => $cleanCitizen]);
} else {
    die('กรุณาระบุรหัสผู้สมัครหรือเลขบัตรประชาชน');
}
$row = $select_stmt->fetch(PDO::FETCH_ASSOC);

if (!$row)
    die('ไม่พบข้อมูลนักเรียน');

// Settings - Handle both key-value and column-based settings table
$settings = [];
try {
    // Try to get all settings as key-value pairs
    $settingStmt = $db->prepare("SELECT * FROM settings");
    $settingStmt->execute();
    $settingsRows = $settingStmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($settingsRows)) {
        // Check if it's key-value format (has 'key_name' column)
        if (isset($settingsRows[0]['key_name'])) {
            foreach ($settingsRows as $settingRow) {
                $settings[$settingRow['key_name']] = $settingRow['value'];
            }
        } else {
            // It's column-based, use first row directly
            $settings = $settingsRows[0];
        }
    }
} catch (Exception $e) {
    // Ignore and use defaults
}
$schoolLogo = $settings['logo_school'] ?? 'logo-phicha.png';
$schoolName = $settings['school_name'] ?? 'โรงเรียนพิชัย';
$academicYear = $settings['academic_year'] ?? ($settings['year'] ?? '2568');

// Student Info
$level = $row['level'];
$isM1 = ($level == '1' || $level == 'm1');
$levelText = $isM1 ? '1' : '4';
$typeregis = $row['typeregis'] ?? 'ทั่วไป';

// Format citizen ID
$formattedCitizen = substr($row["citizenid"], 0, 1) . "-" . substr($row["citizenid"], 1, 4) . "-" . substr($row["citizenid"], 5, 5) . "-" . substr($row["citizenid"], 10, 2) . "-" . substr($row["citizenid"], 12, 1);

// Seat Number (you can add this column to users table if needed)
$seatNumber = $row['seat_number'] ?? '-';
$examRoom = $row['exam_room'] ?? 'รอประกาศ';
$examDate = $row['exam_date'] ?? 'รอประกาศ';

// Initialize mPDF
$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf([
    'tempDir' => defined('MPDF_TEMP_DIR') ? MPDF_TEMP_DIR : __DIR__ . '/tmp',
    'default_font_size' => 14,
    'default_font' => 'sarabun',
    'format' => 'A4',
    'orientation' => 'P',
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 10,
    'margin_bottom' => 10,
    'fontdata' => $fontData + [
        'sarabun' => [
            'R' => 'THSarabunNew.ttf',
            'B' => 'THSarabunNew-Bold.ttf',
            'I' => 'THSarabunNew-Italic.ttf',
            'BI' => 'THSarabunNew-BoldItalic.ttf',
        ]
    ],
]);

$mpdf->SetTitle('บัตรประจำตัวสอบ - ' . $row["stu_name"]);

// Colors
$primaryColor = $isM1 ? '#0056b3' : '#6f42c1';
$accentBg = $isM1 ? '#e7f3ff' : '#f3e8ff';

// Logo path
$logoPath = __DIR__ . '/dist/img/' . $schoolLogo;
$logoBase64 = '';
if (file_exists($logoPath)) {
    $logoData = file_get_contents($logoPath);
    $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
}

// Photo path - Check tbl_uploads first (like print_card.php), then fallback to uploads folder
$photoPath = '';
$photoBase64 = '';


// Try to get photo from tbl_uploads (document8 is student photo)
try {
    $photoStmt = $db->prepare("SELECT path FROM tbl_uploads WHERE citizenid = :citizenid AND name = 'document8'");
    $photoStmt->execute([':citizenid' => $row['citizenid']]);
    $photoResult = $photoStmt->fetch(PDO::FETCH_ASSOC);

    if ($photoResult && !empty($photoResult['path'])) {
        $photoPath = __DIR__ . '/uploads/' . $row['citizenid'] . '/' . $photoResult['path'];
    }
} catch (Exception $e) {
}

// Fallback: try photo.jpg in uploads folder
if (empty($photoPath) || !file_exists($photoPath)) {
    $photoPath = __DIR__ . '/uploads/' . $row['citizenid'] . '/photo.jpg';
}

// Fallback: try any jpg/png in uploads folder
if (!file_exists($photoPath)) {
    $uploadDir = __DIR__ . '/uploads/' . $row['citizenid'] . '/';
    if (is_dir($uploadDir)) {
        $files = glob($uploadDir . '*.{jpg,jpeg,png}', GLOB_BRACE);
        if (!empty($files)) {
            $photoPath = $files[0];
        }
    }
}

// Convert to base64 if file exists
if (file_exists($photoPath)) {
    $photoData = file_get_contents($photoPath);
    $ext = strtolower(pathinfo($photoPath, PATHINFO_EXTENSION));
    $mimeType = ($ext === 'png') ? 'image/png' : 'image/jpeg';
    $photoBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($photoData);
}

// Build HTML
$html = '
<style>
    body { font-family: sarabun, sans-serif; background-color: #f0f0f0; }
    .card-container { width: 148mm; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1); border: 2px solid ' . $primaryColor . '; }
    .header { background-color: ' . $primaryColor . '; color: white; padding: 15px 10px; border-bottom: 3px solid ' . ($isM1 ? '#003d82' : '#5227a0') . '; }
    .header-table td { color: white; }
    .header h1 { font-size: 22pt; margin: 0; font-weight: bold; line-height: 1; text-shadow: 1px 1px 2px rgba(0,0,0,0.2); }
    .header h2 { font-size: 16pt; margin: 5px 0 0 0; font-weight: normal; opacity: 0.9; }
    
    .content-wrapper { padding: 15px; }
    
    .photo-frame { padding: 2px; border: 1px solid #ddd; background: #fff; display: inline-block; }
    
    .info-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .info-table td { padding: 4px 0; vertical-align: bottom; border-bottom: 1px dotted #eee; }
    .info-table tr:last-child td { border-bottom: none; }
    
    .label { color: #666; font-size: 12pt; width: 28%; font-weight: bold; }
    .value { font-weight: bold; color: #333; font-size: 14pt; padding-left: 5px; }
    
    .seat-box { 
        background-color: ' . $accentBg . '; 
        border: 2px solid ' . $primaryColor . '; 
        border-radius: 8px; 
        text-align: center; 
        padding: 10px 5px; 
        margin-left: 10px;
        box-shadow: 2px 2px 5px rgba(0,0,0,0.05);
    }
    .seat-label { font-size: 11pt; font-weight: bold; color: ' . $primaryColor . '; text-transform: uppercase; letter-spacing: 0.5px; }
    .seat-number { font-size: 20pt; font-weight: bold; color: ' . $primaryColor . '; line-height: 1; margin-top: 5px; text-shadow: 1px 1px 0px rgba(255,255,255,1); }
    
    .footer { 
        font-size: 10pt; 
        color: #777; 
        text-align: center; 
        margin-top: 15px; 
        padding-top: 10px; 
        border-top: 1px dashed #ddd; 
        font-style: italic;
    }
</style>

<div class="card-container">
    <div class="header">
        <table width="100%" cellpadding="0" cellspacing="0" class="header-table">
            <tr>
                <td width="70" valign="middle" align="center">' . ($logoBase64 ? '<img src="' . $logoBase64 . '" style="width:55px;">' : '') . '</td>
                <td valign="middle" align="left">
                    <h1>' . htmlspecialchars($schoolName) . '</h1>
                    <h2>บัตรประจำตัวผู้เข้าสอบ ปีการศึกษา ' . $academicYear . '</h2>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="content-wrapper">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td width="100" valign="top" align="center">
                <div class="photo-frame">';

if ($photoBase64) {
    $html .= '<img src="' . $photoBase64 . '" style="width:25mm; height:38mm; object-fit:cover;">';
} else {
    $html .= '<div style="width:25mm; height:38mm; background:#eee; text-align:center; display:table-cell; vertical-align:middle; font-size:9pt; color:#888;">
        <div style="padding-top:10mm; line-height:1.4;">ติดรูป<br>1.5 นิ้ว</div>
    </div>';
}

$html .= '      </div>
            </td>
            <td valign="top" style="padding-left:15px;">
                <table width="100%" class="info-table">
                    <tr>
                        <td width="25%" class="label">ชื่อ-สกุล</td>
                        <td class="value">' . htmlspecialchars($row['stu_prefix'] . $row['stu_name'] . ' ' . $row['stu_lastname']) . '</td>
                    </tr>
                    <tr>
                        <td class="label">เลขบัตร</td>
                        <td class="value">' . $formattedCitizen . '</td>
                    </tr>
                    <tr>
                        <td class="label">ระดับชั้น</td>
                        <td class="value">ม.' . $levelText . ' (' . htmlspecialchars($typeregis) . ')</td>
                    </tr>
                    <tr>
                        <td class="label">ห้องสอบ</td>
                        <td class="value">' . htmlspecialchars($examRoom) . '</td>
                    </tr>
                    <tr>
                        <td class="label">วันสอบ</td>
                        <td class="value">' . htmlspecialchars($examDate) . '</td>
                    </tr>
                </table>
            </td>
            <td width="90" valign="top">
                <div class="seat-box">
                    <div class="seat-label">เลขที่นั่งสอบ</div>
                    <div class="seat-number">' . htmlspecialchars($seatNumber) . '</div>
                </div>
            </td>
        </tr>
    </table>
    
    <div class="footer">
        กรุณานำบัตรนี้มาแสดงในวันสอบ พร้อมบัตรประจำตัวประชาชน
    </div>
    </div>
</div>
';

$mpdf->WriteHTML($html);
$mpdf->Output('ExamCard_' . $row['citizenid'] . '.pdf', 'I');

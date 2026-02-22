<?php
/**
 * Universal PDF Print for All Registration Types
 * Modern Design - 2025 Edition
 */
require_once __DIR__ . '/vendor/autoload.php';
require_once "config/Database.php";
require_once "function.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$dbRegis = new Database_Regis();
$db = $dbRegis->getConnection();
$db->exec("set names utf8");

// Get student ID
$uid = $_GET['uid'] ?? $_GET['stu_id'] ?? null;
$citizenid = $_GET['citizenid'] ?? null;

if ($uid) {
    // Priority: Fetch by specific registration ID
    $select_stmt = $db->prepare("SELECT * FROM users WHERE id = :uid");
    $select_stmt->execute([':uid' => $uid]);
} elseif ($citizenid) {
    // Fallback: Fetch latest registration for this citizen
    $cleanCitizen = preg_replace('/[^0-9]/', '', $citizenid);
    $select_stmt = $db->prepare("SELECT * FROM users WHERE citizenid = :citizenid ORDER BY id DESC LIMIT 1");
    $select_stmt->execute([':citizenid' => $cleanCitizen]);
} else {
    die('กรุณาระบุรหัสนักเรียนหรือเลขบัตรประชาชน');
}

$row = $select_stmt->fetch(PDO::FETCH_ASSOC);
if (!$row)
    die('ไม่พบข้อมูลนักเรียน');

// Settings - Handle both key-value and column-based settings table
$settings = [];
try {
    $select_stmt = $db->prepare("SELECT * FROM settings");
    $select_stmt->execute();
    $settingsRows = $select_stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($settingsRows)) {
        if (isset($settingsRows[0]['key_name'])) {
            foreach ($settingsRows as $sRow) {
                $settings[$sRow['key_name']] = $sRow['value'];
            }
        } else {
            $settings = $settingsRows[0];
        }
    }
} catch (Exception $e) {
}
if (!isset($settings['logo_school']))
    $settings['logo_school'] = 'logo-phicha.png';
if (!isset($settings['academic_year']))
    $settings['academic_year'] = '2568';

// Logic
$level = $row['level'];
$isM1 = ($level == '1' || $level == 'm1');
$levelText = $isM1 ? '1' : '4';
$typeregis = $row['typeregis'] ?? 'ทั่วไป';

// Modern Color Palette
$primaryColor = $isM1 ? '#0056b3' : '#6f42c1'; // Blue for M1, Purple for M4
$accentColor = $isM1 ? '#e7f1ff' : '#f3e8ff';
$textColor = '#333333';
$borderColor = '#dee2e6';

// Initialize mPDF
$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf([
    'tempDir' => defined('MPDF_TEMP_DIR') ? MPDF_TEMP_DIR : __DIR__ . '/tmp',
    'default_font_size' => 14, // Sarabun usually needs larger size
    'default_font' => 'sarabun',
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

$mpdf->SetTitle('ใบสมัครเรียน ม.' . $levelText . ' - ' . $row["stu_name"]);

// Helpers
$formattedCitizen = substr($row["citizenid"], 0, 1) . "-" . substr($row["citizenid"], 1, 4) . "-" . substr($row["citizenid"], 5, 5) . "-" . substr($row["citizenid"], 10, 2) . "-" . substr($row["citizenid"], 12, 1);
$months = ["", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
$birthDate = $row['date_birth'] . ' ' . $months[intval($row['month_birth'])] . ' ' . $row['year_birth'];

// Fetch Plans
// 1. Get Student's Selected Plans for this specific registration
$planStmt = $db->prepare("SELECT sp.plan_id, p.name as plan_name 
                          FROM student_study_plans sp 
                          LEFT JOIN study_plans p ON sp.plan_id = p.id 
                          WHERE sp.user_id = :userId 
                          ORDER BY sp.priority ASC");
$planStmt->execute([':userId' => $row['id']]);
$selectedPlans = $planStmt->fetchAll(PDO::FETCH_ASSOC);

// 2. Get All Available Plans for this Type
$gradeLevelId = $isM1 ? 1 : 2;
$typeCode = 'general';

// First, try to find exact match in registration_types table (include inactive for matching)
$stmt = $db->prepare("SELECT id, code, name FROM registration_types WHERE grade_level_id = :gid ORDER BY sort_order ASC, id ASC");
$stmt->execute([':gid' => $gradeLevelId]);
$allRegTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Also get only active types for display purposes
$stmt = $db->prepare("SELECT id, code, name FROM registration_types WHERE grade_level_id = :gid AND is_active = 1 ORDER BY sort_order ASC, id ASC");
$stmt->execute([':gid' => $gradeLevelId]);
$activeRegTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$regTypeId = 0;
$matchedType = null;

// Try to find matching registration type
foreach ($allRegTypes as $regType) {
    // Check for exact match first
    if (strcasecmp($typeregis, $regType['name']) === 0) {
        $regTypeId = $regType['id'];
        $matchedType = $regType;
        break;
    }
    // Check for partial match (typeregis contains type name)
    if (stripos($typeregis, $regType['name']) !== false) {
        $regTypeId = $regType['id'];
        $matchedType = $regType;
        break;
    }
    // Check for partial match (type name contains typeregis)
    if (stripos($regType['name'], $typeregis) !== false) {
        $regTypeId = $regType['id'];
        $matchedType = $regType;
        break;
    }
}

// If still no match, try the old logic as fallback
if ($regTypeId == 0) {
    // Determine type code (Check specific types first)
    if (strpos($typeregis, 'ความสามารถพิเศษ') !== false)
        $typeCode = 'talent';
    elseif (strpos($typeregis, 'พิเศษ') !== false)
        $typeCode = 'special';
    elseif (strpos($typeregis, 'โควต้า') !== false)
        $typeCode = 'quota';

    foreach ($allRegTypes as $regType) {
        if ($regType['code'] === $typeCode) {
            $regTypeId = $regType['id'];
            $matchedType = $regType;
            break;
        }
    }
}

// Last resort: if still no match, use the first active registration type for this grade level
if ($regTypeId == 0 && !empty($allRegTypes)) {
    $regTypeId = $allRegTypes[0]['id'];
    $matchedType = $allRegTypes[0];
}

$allPlans = [];
if ($regTypeId) {
    // Get all plans for this registration type (include inactive plans to show complete options)
    // Sort by sort_order first, then ID to ensure proper order
    $stmt = $db->prepare("SELECT * FROM study_plans WHERE registration_type_id = :rid ORDER BY sort_order ASC, id ASC");
    $stmt->execute([':rid' => $regTypeId]);
    $allPlans = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 3. Merge Data
$displayPlans = [];
$selectedMap = [];
foreach ($selectedPlans as $idx => $p) {
    $selectedMap[$p['plan_id']] = $idx + 1; // Priority 1-based
}

if (empty($allPlans)) {
    // Fallback: Use student's selected plans if no master plans found
    // This ensures we always show something meaningful
    foreach ($selectedPlans as $idx => $p) {
        $displayPlans[] = [
            'name' => $p['plan_name'] ?? 'แผนการเรียน ' . ($idx + 1),
            'rank' => $idx + 1
        ];
    }

    // If no selected plans either, create placeholder plans based on registration type
    if (empty($displayPlans) && $matchedType) {
        $planNames = [];
        switch ($matchedType['code']) {
            case 'special':
                $planNames = ['ห้องเรียนพิเศษ คณิตศาสตร์', 'ห้องเรียนพิเศษ วิทยาศาสตร์', 'ห้องเรียนพิเศษ ภาษาอังกฤษ'];
                break;
            case 'talent':
                $planNames = ['ความสามารถพิเศษ ด้านกีฬา', 'ความสามารถพิเศษ ด้านศิลปะ', 'ความสามารถพิเศษ ด้านดนตรี'];
                break;
            case 'quota':
                $planNames = ['โควต้า ม.3 เดิม'];
                break;
            default:
                $planNames = ['แผนการเรียนทั่วไป 1', 'แผนการเรียนทั่วไป 2', 'แผนการเรียนทั่วไป 3'];
        }

        foreach ($planNames as $idx => $name) {
            $displayPlans[] = [
                'name' => $name,
                'rank' => ''
            ];
        }
    }
} else {
    // Use master plans from database and mark selected ones
    foreach ($allPlans as $p) {
        $rank = $selectedMap[$p['id']] ?? '';
        $displayPlans[] = [
            'name' => $p['name'],
            'rank' => $rank
        ];
    }

    // Sort by rank: chosen plans first (1, 2, 3...), then unchosen plans
    usort($displayPlans, function ($a, $b) {
        $rankA = $a['rank'] === '' ? 999 : (int) $a['rank'];
        $rankB = $b['rank'] === '' ? 999 : (int) $b['rank'];
        // If ranks are equal, preserve original order vaguely by comparing names, or just keep it 0
        if ($rankA === $rankB)
            return 0;
        return $rankA < $rankB ? -1 : 1;
    });
}

// Checkbox Symbols
$chk = '<span style="font-family: dejavusans; color: ' . $primaryColor . ';">&#9745;</span>';
$unchk = '<span style="font-family: dejavusans; color: #ccc;">&#9744;</span>';

// CSS
$css = '
<style>
    body { font-family: sarabun; font-size: 12pt; color: ' . $textColor . '; line-height: 1.3; }
    
    /* Header */
    .header-table { width: 100%; border-bottom: 2px solid ' . $primaryColor . '; padding-bottom: 10px; margin-bottom: 15px; }
    .logo { width: 65px; }
    .school-name { font-size: 12pt; font-weight: bold; color: ' . $textColor . '; }
    .doc-title { font-size: 20pt; font-weight: bold; color: ' . $primaryColor . '; text-transform: uppercase; }
    .doc-subtitle { font-size: 11pt; color: #666; }
    
    /* Photo Box: 1 x 1.5 inch (approx 25.4mm x 38.1mm) */
    .photo-frame-table {
        width: 25.4mm;
        height: 38.1mm;
        border: 1px solid #000;
        border-collapse: collapse;
        margin-left: auto; 
        margin-right: 0;
        background-color: #fff;
    }
    .photo-frame-td {
        text-align: center;
        vertical-align: middle;
        font-size: 10pt;
        height: 2.5cm;
        width: 1.5cm;
        color: #ccc;
        padding: 0;
    }
    
    /* Sections */
    .section-head {
        font-size: 12pt;
        font-weight: bold;
        color: #fff;
        background-color: ' . $primaryColor . ';
        padding: 4px 10px;
        border-radius: 4px;
        margin-top: 10px;
        margin-bottom: 8px;
    }
    
    /* Form Grid */
    .form-table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
    .form-table td { padding: 3px 5px; vertical-align: bottom; }
    
    .label { font-weight: bold; color: #555; font-size: 10pt; white-space: nowrap; }
    .value { 
        border-bottom: 1px dotted #999; 
        color: #000;
        font-size: 10pt; 
        padding-left: 5px; 
        font-weight: normal;
    }
    
    /* Type Box */
    .type-container {
        background-color: ' . $accentColor . ';
        border: 1px solid ' . $primaryColor . ';
        border-radius: 5px;
        padding: 8px;
        text-align: center;
        margin-bottom: 15px;
    }
    .checkbox-item { margin: 0 15px; font-weight: bold; color: #444; }
    
    /* Plan Table */
    .plan-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
    .plan-table th { background-color: #f0f0f0; border: 1px solid #ddd; padding: 4px; font-size: 10pt; }
    .plan-table td { border: 1px solid #ddd; padding: 4px; font-size: 10pt; }
    .plan-index { text-align: center; width: 40px; background-color: #f9f9f9; font-weight: bold; }
    
    /* Footer */
    .footer-table { width: 100%; margin-top: 20px; }
    .sign-box { text-align: center; padding: 10px; }
    .sign-line { border-bottom: 1px dotted #333; width: 180px; display: inline-block; margin-bottom: 3px; }
    
    /* Staff Area */
    .staff-area {
        border: 2px dashed #ccc;
        background-color: #fafafa;
        padding: 10px;
        margin-top: 15px;
        border-radius: 8px;
    }
    .staff-header { font-weight: bold; text-decoration: underline; margin-bottom: 5px; font-size: 10pt; }
</style>
';

$html = $css;

// --- Header Section ---
$html .= '<table class="header-table"><tr>';
// Logo
$logo_file = __DIR__ . '/dist/img/' . $settings['logo_school'];
$html .= '<td width="80"><img src="' . $logo_file . '" class="logo"></td>';
// Title
$html .= '<td valign="middle">';
$html .= '<div class="school-name">โรงเรียนพิชัย อำเภอพิชัย จังหวัดอุตรดิตถ์</div>';
$html .= '<div class="doc-title">ใบสมัครเข้าศึกษาต่อมัธยมศึกษาปีที่ ' . $levelText . '</div>';
$html .= '<div class="doc-subtitle">ปีการศึกษา ' . $settings['academic_year'] . ' | ระบบรับสมัครนักเรียนออนไลน์</div>';
$html .= '</td>';
// Photo
$numreg = $row['numreg'] ?? '';
$html .= '<td width="100" align="right" valign="top">';
$html .= '<div style="font-size: 9pt; margin-bottom: 5px; text-align: center;">เลขประจำตัวผู้สมัคร<br><span style="font-weight:bold; font-size:12pt;">' . ($numreg ?: '...................') . '</span></div>';
$html .= '<table class="photo-frame-table"><tr><td class="photo-frame-td">รูปถ่าย<br>1 x 1.5 นิ้ว</td></tr></table>';
$html .= '</td>';
$html .= '</tr></table>';

// --- Registration Type (Dynamic from DB) ---
$html .= '<div class="type-container">';
$html .= '<span style="font-weight:bold; color:' . $primaryColor . '">ประเภทการสมัคร : </span>';

// Fetch registration types from database based on grade level
$regTypesStmt = $db->prepare("SELECT name FROM registration_types WHERE grade_level_id = ? AND is_active = 1 ORDER BY id ASC");
$regTypesStmt->execute([$gradeLevelId]);
$regTypes = $regTypesStmt->fetchAll(PDO::FETCH_COLUMN);

if (!empty($regTypes)) {
    foreach ($regTypes as $typeName) {
        // Check if this type matches the student's typeregis (case-insensitive, partial match)
        $isChecked = (strcasecmp($typeregis, $typeName) === 0) || (stripos($typeregis, $typeName) !== false) || (stripos($typeName, $typeregis) !== false);
        $html .= '<span class="checkbox-item">' . ($isChecked ? $chk : $unchk) . ' ' . $typeName . '</span>';
    }
} else {
    // Fallback if no types in database
    $html .= '<span class="checkbox-item">' . $chk . ' ' . $typeregis . '</span>';
}

// Show zone_type if typeregis is รอบทั่วไป
if (stripos($typeregis, 'รอบทั่วไป') !== false || stripos($typeregis, 'ทั่วไป') !== false) {
    $zoneType = $row['zone_type'] ?? '';
    $html .= '<br><span style="font-weight:bold; color:' . $primaryColor . '; margin-left: 10px;">พื้นที่บริการ : </span>';
    $html .= '<span class="checkbox-item">' . (stripos($zoneType, 'ในเขต') !== false ? $chk : $unchk) . ' ในเขตพื้นที่บริการ</span>';
    $html .= '<span class="checkbox-item">' . (stripos($zoneType, 'นอกเขต') !== false ? $chk : $unchk) . ' นอกเขตพื้นที่บริการ</span>';
}

$html .= '</div>';

// --- Student Info ---
$html .= '<div class="section-head">1. ข้อมูลผู้สมัคร</div>';
$html .= '<table class="form-table">';
$html .= '<tr>
    <td width="10%" class="label">ชื่อ-สกุล</td>
    <td width="10%" class="value">' . $row['stu_prefix'] . $row['stu_name'] . ' ' . $row['stu_lastname'] . '</td>
    <td width="10%" class="label">เลข ปชช.</td>
    <td width="10%" class="value">' . $formattedCitizen . '</td>
</tr>';
$html .= '<tr>
    <td class="label">วันเกิด</td>
    <td class="value">' . $birthDate . '</td>
    <td class="label">เพศ/เลือด</td>
    <td class="value">' . $row['stu_sex'] . ' / กรุ๊ป ' . $row['stu_blood_group'] . '</td>
</tr>';
$html .= '<tr>
    <td class="label">ศาสนา</td>
    <td class="value">' . $row['stu_religion'] . '</td>
    <td class="label">เชื้อชาติ</td>
    <td class="value">' . $row['stu_ethnicity'] . ' / สัญชาติ ' . $row['stu_nationality'] . '</td>
</tr>';
$html .= '<tr>
    <td class="label">โรงเรียนเดิม</td>
    <td class="value" colspan="3">' . $row['old_school'] . ' (อ.' . $row['old_school_district'] . ' จ.' . $row['old_school_province'] . ') ' . (!empty($row['gpa_total']) ? 'GPA: ' . $row['gpa_total'] : '') . '</td>
</tr>';

// Type-specific information
if ($matchedType && $matchedType['code'] === 'special') {
    // ห้องเรียนพิเศษ - แสดงเกรด 3 วิชา
    $html .= '<tr>
        <td class="label">เกรดรายวิชา</td>
        <td class="value" colspan="3">คณิตศาสตร์: ' . ($row['grade_math'] ?? '-') . ' | วิทยาศาสตร์/เทคโนโลยี: ' . ($row['grade_science'] ?? '-') . ' | ภาษาอังกฤษ: ' . ($row['grade_english'] ?? '-') . '</td>
    </tr>';
} elseif ($matchedType && $matchedType['code'] === 'talent') {
    // ความสามารถพิเศษ
    $html .= '<tr>
        <td class="label">ความสามารถพิเศษ</td>
        <td class="value" colspan="3">' . ($row['talent_skill'] ?? '-') . '</td>
    </tr>';
    if (!empty($row['talent_awards'])) {
        $html .= '<tr>
            <td class="label">ผลงาน/รางวัล</td>
            <td class="value" colspan="3">' . $row['talent_awards'] . '</td>
        </tr>';
    }
} elseif ($matchedType && $matchedType['code'] === 'quota') {
    // โควต้า ม.3 เดิม
    $html .= '<tr>
        <td class="label">เลขประจำตัว ม.3</td>
        <td class="value" colspan="3">' . ($row['old_student_id'] ?? '-') . '</td>
    </tr>';
}

$html .= '</table>';

// For special type, use dynamic section numbering since we skip some sections
$sectionNum = 2;

// --- Address (skip for special type) ---
if (!$matchedType || $matchedType['code'] !== 'special') {
    $html .= '<div class="section-head">' . $sectionNum++ . '. ข้อมูลการติดต่อ</div>';
    $html .= '<table class="form-table">';
    $html .= '<tr>
        <td width="15%" class="label">ที่อยู่ปัจจุบัน</td>
        <td class="value">บ้านเลขที่ ' . $row['now_addr'] . ' หมู่ ' . $row['now_moo'] . ' ' . ($row['now_soy'] ? 'ซ.' . $row['now_soy'] : '') . ' ' . ($row['now_street'] ? 'ถ.' . $row['now_street'] : '') . '</td>
    </tr>';
    $html .= '<tr>
        <td class="label"></td>
        <td class="value">ต.' . $row['now_subdistrict'] . ' อ.' . $row['now_district'] . ' จ.' . $row['now_province'] . ' ' . $row['now_post'] . '</td>
    </tr>';
    $html .= '<tr>
        <td class="label">โทรศัพท์</td>
        <td class="value">' . $row['now_tel'] . '</td>
    </tr>';
    $html .= '</table>';
}

// --- Parents ---
$html .= '<div class="section-head">' . $sectionNum++ . '. ข้อมูลผู้ปกครอง</div>';
$html .= '<table class="form-table">';

// For special type, show only guardian info (no father/mother)
if (!$matchedType || $matchedType['code'] !== 'special') {
    $html .= '<tr>
        <td width="10%" class="label">บิดา</td>
        <td width="10%" class="value">' . $row['dad_prefix'] . $row['dad_name'] . ' ' . $row['dad_lastname'] . '</td>
        <td width="15%" class="label">อาชีพ/โทร</td>
        <td width="35%" class="value">' . $row['dad_job'] . ' (' . $row['dad_tel'] . ')</td>
    </tr>';
    $html .= '<tr>
        <td class="label">มารดา</td>
        <td class="value">' . $row['mom_prefix'] . $row['mom_name'] . ' ' . $row['mom_lastname'] . '</td>
        <td class="label">อาชีพ/โทร</td>
        <td class="value">' . $row['mom_job'] . ' (' . $row['mom_tel'] . ')</td>
    </tr>';
}

$html .= '<tr>
    <td class="label">ผู้ปกครอง</td>
    <td class="value">' . $row['parent_prefix'] . $row['parent_name'] . ' ' . $row['parent_lastname'] . '</td>
    <td class="label">ความสัมพันธ์</td>
    <td class="value">' . $row['parent_relation'] . ' (โทร: ' . $row['parent_tel'] . ')</td>
</tr>';
$html .= '</table>';

// --- Study Plans ---
// Debug: Add comment with useful info for troubleshooting
$debugInfo = "Type: {$typeregis}, GradeLevel: {$gradeLevelId}, RegTypeID: {$regTypeId}, Plans: " . count($displayPlans);
$html .= '<!-- Study Plans Debug: ' . $debugInfo . ' -->';
$html .= '<div class="section-head">' . $sectionNum . '. แผนการเรียนที่ต้องการสมัคร</div>';
$html .= '<table class="plan-table">';
$html .= '<tr>
    <th width="8%">ลำดับ</th><th width="42%">แผนการเรียน</th>
    <th width="8%">ลำดับ</th><th width="42%">แผนการเรียน</th>
</tr>';

$totalPlans = count($displayPlans);

// Ensure we have at least some plans to display (minimum 3 for most types, 1 for quota)
$minPlans = 3;
if ($matchedType && $matchedType['code'] === 'quota') {
    $minPlans = 1;
}

if ($totalPlans < $minPlans) {
    // Add empty slots to reach minimum
    for ($i = $totalPlans; $i < $minPlans; $i++) {
        $displayPlans[] = [
            'name' => '',
            'rank' => ''
        ];
    }
    $totalPlans = $minPlans;
}

$displaySlots = $totalPlans;
if ($displaySlots % 2 != 0 && $displaySlots > 0)
    $displaySlots++; // Ensure even number for 2 columns if not empty
$totalRows = $displaySlots / 2;

for ($r = 0; $r < $totalRows; $r++) {
    // Left column gets first half, Right column gets second half
    $idx1 = $r;
    $idx2 = $r + $totalRows;

    $plan1 = $displayPlans[$idx1] ?? null;
    $plan2 = $displayPlans[$idx2] ?? null;

    $html .= '<tr>';
    // Column 1
    $html .= '<td class="plan-index">' . ($plan1 ? $plan1['rank'] : '') . '</td>';
    $html .= '<td>' . ($plan1 ? $plan1['name'] : '&nbsp;') . '</td>';

    // Column 2
    $html .= '<td class="plan-index">' . ($plan2 ? $plan2['rank'] : '') . '</td>';
    $html .= '<td>' . ($plan2 ? $plan2['name'] : '&nbsp;') . '</td>';
    $html .= '</tr>';
}
$html .= '</table>';

// --- Signatures ---
$html .= '<table class="footer-table"><tr>';
$html .= '<td width="100%" class="sign-box" align="center">';
$html .= 'ลงชื่อ <span class="sign-line"></span> ผู้สมัคร<br>';
$html .= '<br>';
$html .= '(' . $row['stu_prefix'] . $row['stu_name'] . ' ' . $row['stu_lastname'] . ')<br>';
$html .= 'วันที่ ........./........./.........';
$html .= '</td>';
$html .= '</tr></table>';

// --- Staff Section ---
$html .= '<div class="staff-area">';
$html .= '<div class="staff-header">ส่วนสำหรับเจ้าหน้าที่รับสมัคร</div>';
$html .= '<table width="100%"><tr>';
$html .= '<td width="50%">';
$numreg = $row['numreg'] ?? '';
$html .= '<div>เลขประจำตัวผู้สมัคร: <span style="border-bottom: 1px dotted #000; padding: 0 20px; font-weight: bold;">' . ($numreg ?: '..................') . '</span></div>';
$html .= '<div style="margin-top:5px;">เอกสารหลักฐาน: ' . $unchk . ' ครบถ้วน ' . $unchk . ' ไม่ครบ</div>';
$html .= '</td>';
$html .= '<td width="50%" align="center">';
$html .= 'ลงชื่อ ....................................................... เจ้าหน้าที่<br>';
$html .= '(.......................................................)';
$html .= '</td>';
$html .= '</tr></table>';
$html .= '</div>';

$mpdf->WriteHTML($html);
$mpdf->Output();


<?php
/**
 * Exam Card PDF Generator — บัตรประจำตัวผู้เข้าสอบ
 */
require_once __DIR__ . '/vendor/autoload.php';
require_once "config/Database.php";
require_once "function.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '256M');

$dbRegis = new Database_Regis();
$db = $dbRegis->getConnection();
$db->exec("set names utf8");

// ── Fetch student ──────────────────────────────────────────────────
$regId = $_GET['reg_id'] ?? $_GET['id'] ?? null;
$citizenid = $_GET['citizenid'] ?? null;
if ($regId) {
  $s = $db->prepare("SELECT * FROM users WHERE id = :id");
  $s->execute([':id' => $regId]);
} elseif ($citizenid) {
  $cc = preg_replace('/[^0-9]/', '', $citizenid);
  $s = $db->prepare("SELECT * FROM users WHERE citizenid = :c ORDER BY id DESC LIMIT 1");
  $s->execute([':c' => $cc]);
} else {
  die('กรุณาระบุรหัสผู้สมัครหรือเลขบัตรประชาชน');
}
$row = $s->fetch(PDO::FETCH_ASSOC);
if (!$row)
  die('ไม่พบข้อมูลนักเรียน');

// ── Settings ───────────────────────────────────────────────────────
$settings = [];
try {
  $q = $db->query("SELECT * FROM settings");
  $rows = $q->fetchAll(PDO::FETCH_ASSOC);
  if ($rows) {
    if (isset($rows[0]['key_name']))
      foreach ($rows as $r)
        $settings[$r['key_name']] = $r['value'];
    else
      $settings = $rows[0];
  }
} catch (Exception $e) {
}
$schoolLogo = $settings['logo_school'] ?? 'logo-phicha.png';
$schoolName = $settings['school_name'] ?? 'โรงเรียนพิชัย';
$academicYear = $settings['academic_year'] ?? ($settings['year'] ?? '2568');

// ── Student variables ──────────────────────────────────────────────
$level = $row['level'];
$isM1 = ($level == '1' || $level == 'm1');
$levelText = $isM1 ? '1' : '4';
$typeregis = htmlspecialchars($row['typeregis'] ?? 'ทั่วไป');
$cid = $row['citizenid'];
$fmtCid = substr($cid, 0, 1) . '-' . substr($cid, 1, 4) . '-' . substr($cid, 5, 5) . '-' . substr($cid, 10, 2) . '-' . substr($cid, 12, 1);
$appNum = htmlspecialchars($row['numreg'] ?? '-');
$examRoom = htmlspecialchars($row['exam_room'] ?? 'รอประกาศ');

// ── Exam Date Thai Formatting ──────────────────────────────────────
$examDateRaw = $row['exam_date'] ?? 'รอประกาศ';
$examDate = 'รอประกาศ';
if ($examDateRaw && $examDateRaw !== 'รอประกาศ') {
  $dateObj = null;
  // Handle d/m/Y format (common in Thai systems)
  if (strpos($examDateRaw, '/') !== false) {
    $parts = explode('/', $examDateRaw);
    if (count($parts) === 3) {
      // Ensure leading zeros for createFromFormat
      $d = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
      $m = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
      $y = $parts[2];
      $dateObj = DateTime::createFromFormat('d/m/Y', "$d/$m/$y");
    }
  } else {
    try {
      $dateObj = new DateTime($examDateRaw);
    } catch (Exception $e) {
      $dateObj = null;
    }
  }

  if ($dateObj) {
    $y = (int) $dateObj->format("Y") + 543;
    $m = (int) $dateObj->format("n");
    $d = (int) $dateObj->format("j");
    $months = ["", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค. "];
    $examDate = $d . " " . $months[$m] . " " . $y;
  } else {
    $examDate = htmlspecialchars($examDateRaw);
  }
}
$fullName = htmlspecialchars($row['stu_prefix'] . $row['stu_name'] . ' ' . $row['stu_lastname']);

// ── Theme colors by typeregis ──────────────────────────────────────
// palette: [header, darker, light-tint, label]
$palettes = [
  'ในเขต' => ['#b45309', '#92400e', '#fef3c7', '🟡 รอบทั่วไป (ห้องเรียนปกติ)'],
  'นอกเขต' => ['#b45309', '#92400e', '#fef3c7', '🟡 รอบทั่วไป (ห้องเรียนปกติ)'],
  'โควต้า' => ['#be123c', '#9f1239', '#ffe4e6', '🔴 โควต้า ม.3 เดิม'],
  'รอบทั่วไป' => ['#b45309', '#92400e', '#fef3c7', '🟡 รอบทั่วไป'],
  'ESC' => ['#0369a1', '#075985', '#e0f2fe', '🔷 ห้องเรียนพิเศษ (ESC)'],
  'EP' => ['#7c3aed', '#6d28d9', '#ede9fe', '🟣 English Program'],
  'default_m1' => ['#1d4ed8', '#1e40af', '#dbeafe', ''],
  'default_m4' => ['#6d28d9', '#5b21b6', '#ede9fe', ''],
];

// Match typeregis to palette
$themeKey = 'default_m' . $levelText;
foreach ($palettes as $key => $pal) {
  if (mb_strpos($row['typeregis'] ?? '', $key) !== false || ($row['typeregis'] ?? '') === $key) {
    $themeKey = $key;
    break;
  }
}
$palette = $palettes[$themeKey] ?? $palettes['default_m4'];
$col1 = $palette[0];
$col2 = $palette[1];
$col3 = $palette[2];
$typeLabel = $palette[3] ?: ($isM1 ? 'ม.1' : 'ม.4') . ' ' . htmlspecialchars($typeregis);

// ── mPDF setup ─────────────────────────────────────────────────────
$fontData = (new Mpdf\Config\FontVariables())->getDefaults()['fontdata'];
$mpdf = new \Mpdf\Mpdf([
  'tempDir' => defined('MPDF_TEMP_DIR') ? MPDF_TEMP_DIR : __DIR__ . '/tmp',
  'default_font_size' => 14,
  'default_font' => 'sarabun',
  'format' => 'A4',
  'orientation' => 'P',
  'margin_left' => 15,
  'margin_right' => 15,
  'margin_top' => 15,
  'margin_bottom' => 15,
  'fontdata' => $fontData + [
    'sarabun' => [
      'R' => 'THSarabunNew.ttf',
      'B' => 'THSarabunNew-Bold.ttf',
      'I' => 'THSarabunNew-Italic.ttf',
      'BI' => 'THSarabunNew-BoldItalic.ttf',
    ]
  ],
]);
$mpdf->SetTitle('บัตรประจำตัวผู้เข้าสอบ - ' . $row['stu_name']);

// ── Logo (base64, resized inline via attribute) ────────────────────
$logoPath = __DIR__ . '/dist/img/' . $schoolLogo;
$logoTag = '';
if (file_exists($logoPath)) {
  $b64 = base64_encode(file_get_contents($logoPath));
  $logoTag = '<img src="data:image/png;base64,' . $b64 . '" width="48" height="48">';
}

// ── Photo ──────────────────────────────────────────────────────────
$photoPath = '';
try {
  $ps = $db->prepare("SELECT path FROM tbl_uploads WHERE citizenid=:c AND name='document8'");
  $ps->execute([':c' => $row['citizenid']]);
  $pr = $ps->fetch(PDO::FETCH_ASSOC);
  if ($pr && !empty($pr['path']))
    $photoPath = __DIR__ . '/uploads/' . $row['citizenid'] . '/' . $pr['path'];
} catch (Exception $e) {
}
if (!$photoPath || !file_exists($photoPath))
  $photoPath = __DIR__ . '/uploads/' . $row['citizenid'] . '/photo.jpg';
if (!file_exists($photoPath)) {
  $ud = __DIR__ . '/uploads/' . $row['citizenid'] . '/';
  if (is_dir($ud)) {
    $fs = glob($ud . '*.{jpg,jpeg,png}', GLOB_BRACE);
    if ($fs)
      $photoPath = $fs[0];
  }
}
$photoTag = '';
if (file_exists($photoPath)) {
  $photoTag = '<img src="' . $photoPath . '" width="113" height="150"
                      style="border:2px solid ' . $col1 . ';border-radius:4px;object-fit:cover;">';
} else {
  $photoTag = '<table width="113" height="150" cellpadding="0" cellspacing="0"
                        style="border:2px dashed #c0cde4;border-radius:4px;background:#f1f5fb;">
                   <tr><td align="center" valign="middle"
                         style="font-size:11pt;color:#9daabf;line-height:1.7;">
                     ติดรูปถ่าย<br/>1.5 นิ้ว
                   </td></tr>
                 </table>';
}

/* ═══════════════════════════════════════════════════════════
   HTML / CSS  — premium redesign, mPDF table-based layout
═══════════════════════════════════════════════════════════ */
$html = '
<style>
  body { font-family: sarabun, sans-serif; color: #1e293b; margin:0; padding:0; }

  /* ── outer wrapper ── */
  table.card {
    width: 185mm;
    margin: 0 auto;
    border-collapse: separate;
    border-spacing: 0;
    border: 2.5px solid ' . $col1 . ';
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.18);
  }

  /* ── header: logo area | title area | type box ── */
  td.h-logo {
    width: 70px;
    background-color: ' . $col2 . ';
    text-align: center;
    vertical-align: middle;
    padding: 14px 8px 14px 14px;
    border-radius: 7px 0 0 0;
  }
  td.h-main {
    background-color: ' . $col1 . ';
    vertical-align: middle;
    padding: 14px 12px;
  }
  td.h-typebox {
    background-color: ' . $col2 . ';
    width: 70px;
    vertical-align: middle;
    text-align: center;
    padding: 10px 8px;
    border-left: 2px solid rgba(255,255,255,0.15);
    border-radius: 0 7px 0 0;
  }
  .school-name {
    font-size: 22pt; font-weight: bold; color: #fff;
    text-shadow: 1px 2px 4px rgba(0,0,0,0.3);
    line-height: 1; margin: 0;
  }
  .card-subtitle {
    font-size: 13pt; color: rgba(255,255,255,0.88);
    margin: 6px 0 0; line-height: 1;
  }
  .h-level-num {
    font-size: 26pt; font-weight: bold; color: #fff;
    line-height: 1; text-shadow: 1px 1px 4px rgba(0,0,0,0.25);
  }
  .h-level-lbl {
    font-size: 9.5pt; color: rgba(255,255,255,0.8);
    margin-top: 3px; letter-spacing: 0.5px;
  }

  /* ── accent bar ── */
  td.accent-bar {
    height: 5px;
    background-color: ' . $col2 . ';
    border-left: 2.5px solid ' . $col1 . ';
    border-right: 2.5px solid ' . $col1 . ';
  }

  /* ── body ── */
  td.body-wrap {
    background: #fff;
    padding: 0;
    border-left: 2.5px solid ' . $col1 . ';
    border-right: 2.5px solid ' . $col1 . ';
  }

  /* decorative left stripe inside body */
  td.stripe { width: 8px; background-color: ' . $col1 . '; }

  td.body-inner { padding: 15px 16px 13px 14px; }

  /* inner 3-col: photo | info | badge */
  table.inner { width: 100%; border-collapse: collapse; }
  table.inner td { padding: 0; vertical-align: top; }

  /* photo */
  td.photo-wrap { width: 118px; padding-right: 14px; text-align: center; }
  .photo-caption {
    font-size: 9.5pt; color: #94a3b8;
    margin-top: 5px; text-align: center;
  }

  /* info rows */
  td.info-wrap { vertical-align: top; }
  table.info { width: 100%; border-collapse: collapse; }
  table.info td {
    padding: 7.5px 8px;
    font-size: 14pt;
    vertical-align: middle;
  }
  table.info tr.row-shade td { background-color: ' . $col3 . '; }
  table.info tr.row-plain td { background-color: #ffffff; }
  table.info tr:first-child td:first-child { border-radius: 5px 0 0 0; }
  table.info tr:first-child td:last-child  { border-radius: 0 5px 0 0; }
  table.info tr:last-child  td:first-child { border-radius: 0 0 0 5px; }
  table.info tr:last-child  td:last-child  { border-radius: 0 0 5px 0; }
  .info-lbl {
    color: ' . $col2 . '; font-weight: bold;
    width: 40%; white-space: nowrap;
    font-size: 13pt;
  }
  .info-val {
    color: #111827; font-weight: bold; padding-left: 4px; font-size: 14pt;
  }

  /* badge */
  td.badge-wrap { width: 115px; padding-left: 14px; text-align: center; vertical-align: top; }
  table.badge {
    width: 110px;
    border-collapse: separate;
    border: 2.5px solid ' . $col1 . ';
    border-radius: 8px;
  }
  td.b-header {
    background: ' . $col1 . ';
    color: #fff;
    font-size: 11pt; font-weight: bold;
    padding: 9px 4px;
    text-align: center;
    border-radius: 5px 5px 0 0;
    letter-spacing: 0.5px;
  }
  td.b-number {
    background: ' . $col3 . ';
    color: ' . $col1 . ';
    font-size: 32pt; font-weight: bold;
    padding: 14px 4px 10px;
    text-align: center;
    line-height: 1;
  }
  td.b-divider {
    background: #e2eaf4;
    height: 2px;
    padding: 0;
    font-size: 1pt;
    color: #e2eaf4;
  }
  td.b-type {
    background: #f8fafc;
    color: #475569;
    font-size: 10pt;
    padding: 6px 4px;
    text-align: center;
    border-top: 1px solid #e2e8f0;
  }
  td.b-footer {
    background: ' . $col2 . ';
    color: rgba(255,255,255,0.92);
    font-size: 10pt; font-weight: bold;
    padding: 7px 4px;
    text-align: center;
    border-radius: 0 0 5px 5px;
    letter-spacing: 0.3px;
  }

  /* ── footer bar ── */
  td.footer-bar {
    background: ' . $col3 . ';
    border-top: 2px solid ' . $col1 . ';
    border-left: 2.5px solid ' . $col1 . ';
    border-right: 2.5px solid ' . $col1 . ';
    text-align: center;
    padding: 9px 18px;
    font-size: 12pt;
    color: ' . $col2 . ';
    font-weight: bold;
  }
  td.border-btm {
    height: 0;
    border-left: 2.5px solid ' . $col1 . ';
    border-right: 2.5px solid ' . $col1 . ';
    border-bottom: 2.5px solid ' . $col1 . ';
    border-radius: 0 0 8px 8px;
  }

  /* ── cut marks ── */
  .cut { text-align:center; font-size:10.5pt; color:#b0bec5; letter-spacing:1px; margin:5px 0; }
  .cut-icon { font-family: dejavusans, sans-serif; font-size:12pt; }
</style>

<div class="cut">
  <span class="cut-icon">&#x2702;</span>&nbsp;
  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
  &nbsp;<span class="cut-icon">&#x2702;</span>
</div>

<table class="card" cellpadding="0" cellspacing="0">

  <!-- ═══ HEADER ═══ -->
  <tr>
    <td class="h-logo">' . $logoTag . '</td>
    <td class="h-main">
      <div class="school-name">' . htmlspecialchars($schoolName) . '</div>
      <div class="card-subtitle">บัตรประจำตัวผู้เข้าสอบ &nbsp;&bull;&nbsp; ปีการศึกษา ' . $academicYear . '</div>
    </td>
    <td class="h-typebox">
      <div class="h-level-num">ม.' . $levelText . '</div>
      <div class="h-level-lbl">ระดับชั้น</div>
    </td>
  </tr>
  <tr><td colspan="3" class="accent-bar"></td></tr>

  <!-- ═══ BODY ═══ -->
  <tr>
    <td colspan="3" class="body-wrap">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <!-- left stripe -->
          <td class="stripe"></td>

          <!-- content area -->
          <td class="body-inner">
            <table class="inner" cellpadding="0" cellspacing="0">
              <tr>

                <!-- PHOTO -->
  

                <!-- INFO TABLE -->
                <td class="info-wrap">
                  <table class="info" cellpadding="0" cellspacing="0">
                    <tr class="row-shade">
                      <td class="info-lbl">ชื่อ - นามสกุล</td>
                      <td class="info-val">' . $fullName . '</td>
                    </tr>
                    <tr class="row-plain">
                      <td class="info-lbl">เลขบัตรประชาชน</td>
                      <td class="info-val" style="font-size:12.5pt;letter-spacing:0.5px;">' . $fmtCid . '</td>
                    </tr>
                    <tr class="row-shade">
                      <td class="info-lbl">ประเภทการสมัคร</td>
                      <td class="info-val" style="font-size:13pt;">' . $typeregis . '</td>
                    </tr>
                    <tr class="row-plain">
                      <td class="info-lbl">ห้องสอบ</td>
                      <td class="info-val">' . $examRoom . '</td>
                    </tr>
                    <tr class="row-shade">
                      <td class="info-lbl">วันที่สอบ</td>
                      <td class="info-val">' . $examDate . '</td>
                    </tr>
                  </table>
                </td>

                <!-- BADGE -->
                <td class="badge-wrap">
                  <table class="badge" cellpadding="0" cellspacing="0">
                    <tr><td class="b-header">เลขที่ผู้สมัคร</td></tr>
                    <tr><td class="b-number">' . $appNum . '</td></tr>
                    <tr><td class="b-type">' . $typeregis . '</td></tr>
                    <tr><td class="b-footer">ปีการศึกษา ' . $academicYear . '</td></tr>
                  </table>
                </td>

              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- ═══ FOOTER ═══ -->
  <tr>
    <td colspan="3" class="footer-bar">
      &#9733;&nbsp; กรุณานำบัตรนี้มาแสดงในวันสอบ พร้อมบัตรประจำตัวประชาชน &nbsp;&#9733;
    </td>
  </tr>
  <tr><td colspan="3" class="border-btm"></td></tr>

</table>

<div class="cut">
  <span class="cut-icon">&#x2702;</span>&nbsp;
  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
  &nbsp;<span class="cut-icon">&#x2702;</span>
</div>
';

$mpdf->WriteHTML($html);
$mpdf->Output('ExamCard_' . $row['citizenid'] . '.pdf', 'I');


<?php
/**
 * Confirm Report Controller
 */
session_start();
require_once 'config/Database.php';
require_once 'class/StudentRegis.php';
require_once 'class/AdminConfig.php';
require_once 'config/Setting.php';

$setting = new Setting();
$pageTitle = 'ยืนยันการรายงานตัว';

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();

$studentRegis = new StudentRegis($db);
$studentRegis->autoCallReservistsAllPlans();
$adminConfig = new AdminConfig($db);

$studentData = null;
$plan = null;
$fees = [];
$error = null;

// Handle Search (POST or GET)
$citizenid = trim($_POST['citizenid'] ?? $_GET['citizenid'] ?? '');
$isSearching = ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) || !empty($_GET['citizenid']);

if ($isSearching) {
    if (empty($citizenid)) {
        $error = "กรุณากรอกเลขประจำตัวประชาชน";
    } else {
        // Fetch ALL registrations for this citizen ID (handles multi-type registration)
        $allRegistrations = $studentRegis->getAllStudentsByCitizenId($citizenid);

        if (!empty($allRegistrations)) {
            // If a specific registration ID was selected via POST or GET
            $selectedRegId = $_POST['selected_reg_id'] ?? $_GET['selected_reg_id'] ?? $_GET['id'] ?? null;

            if ($selectedRegId) {
                // Find the selected registration
                foreach ($allRegistrations as $reg) {
                    if ($reg['id'] == $selectedRegId) {
                        $studentData = $reg;
                        break;
                    }
                }
                // Fallback if not found
                if (!$studentData) {
                    $studentData = $allRegistrations[0];
                }
            } else {
                // Auto-select the best actionable record
                // Priority: status=1 & is_called=1 > status=1 > status=2 > status=0 > status=3
                $bestRecord = null;
                foreach ($allRegistrations as $reg) {
                    if (intval($reg['status']) === 1 && intval($reg['is_called'] ?? 0) === 1) {
                        $bestRecord = $reg;
                        break; // Best possible: passed and called
                    }
                }
                if (!$bestRecord) {
                    foreach ($allRegistrations as $reg) {
                        if (intval($reg['status']) === 1) {
                            $bestRecord = $reg;
                            break;
                        }
                    }
                }
                if (!$bestRecord) {
                    foreach ($allRegistrations as $reg) {
                        if (intval($reg['status']) === 2) {
                            $bestRecord = $reg;
                            break;
                        }
                    }
                }
                if (!$bestRecord) {
                    foreach ($allRegistrations as $reg) {
                        if (intval($reg['status']) === 0) {
                            $bestRecord = $reg;
                            break;
                        }
                    }
                }
                // If all are cancelled (status=3), use the first one
                if (!$bestRecord) {
                    $bestRecord = $allRegistrations[0];
                }
                $studentData = $bestRecord;
            }

            // Store all registrations for display in view (multi-type selection)
            $allStudentRegistrations = $allRegistrations;

            // Check Final Plan
            if (!empty($studentData['final_plan_id'])) {
                $stmt = $db->prepare("SELECT sp.*, rt.name as type_name, gl.code as grade_code, gl.name as grade_name,
                                        rt.use_schedule, rt.report_start, rt.report_end
                                    FROM study_plans sp 
                                    JOIN registration_types rt ON sp.registration_type_id = rt.id 
                                    JOIN grade_levels gl ON rt.grade_level_id = gl.id
                                    WHERE sp.id = :id");
                $stmt->execute([':id' => $studentData['final_plan_id']]);
                $plan = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($plan) {
                    $fees = $adminConfig->getPlanFees($plan['id']);

                    $isReportPeriodValid = true;
                    $reportScheduleMsg = '';
                    if (!empty($plan['report_start']) && !empty($plan['report_end'])) {
                        $now = new DateTime();
                        $start = new DateTime($plan['report_start']);
                        $end = new DateTime($plan['report_end']);

                        // Thai year formatting helper
                        $startThai = $start->format('d/m/') . ($start->format('Y') + 543) . ' ' . $start->format('H:i');
                        $endThai = $end->format('d/m/') . ($end->format('Y') + 543) . ' ' . $end->format('H:i');

                        if ($now < $start) {
                            $isReportPeriodValid = false;
                            $reportScheduleMsg = "ยังไม่อยู่ในช่วงเวลารายงานตัว (ระบบเปิดให้รายงานตัว วันที่ {$startThai} น.)";
                        } elseif ($now > $end) {
                            $isReportPeriodValid = false;
                            $reportScheduleMsg = "หมดเวลารายงานตัวแล้ว (ปิดระบบเมื่อวันที่ {$endThai} น.)";
                        }
                    }
                }
            }
        } else {
            $error = "ไม่พบข้อมูลผู้สมัคร";
        }
    }
}

// Check session if logged in (optional, if system supports student login)
// If you want to auto-login from search
if ($studentData) {
    // $_SESSION['student_login'] = $studentData['id']; // Uncomment if we want to maintain session
}

ob_start();
require 'views/confirm/confirm-report.php';
$content = ob_get_clean();

require 'views/layouts/app.php';
?>
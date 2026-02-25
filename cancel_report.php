<?php
/**
 * Cancel Report Controller
 */
session_start();
require_once 'config/Database.php';
require_once 'class/StudentRegis.php';
require_once 'config/Setting.php';

$setting = new Setting();
$pageTitle = 'สละสิทธิ์การรายงานตัว';

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();

$studentRegis = new StudentRegis($db);

$studentData = null;
$error = null;
$success = false;

// Handle Search
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $citizenid = trim($_POST['citizenid']);

    if (empty($citizenid)) {
        $error = "กรุณากรอกเลขประจำตัวประชาชน";
    } else {
        $student = $studentRegis->getStudentByCitizenId($citizenid);

        if ($student) {
            $studentData = $student;

            // Check Final Plan Schedule
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

ob_start();
require 'views/confirm/cancel-report.php';
$content = ob_get_clean();

require 'views/layouts/app.php';
?>
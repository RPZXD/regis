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
$adminConfig = new AdminConfig($db);

$studentData = null;
$plan = null;
$fees = [];
$error = null;

// Handle Search
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $citizenid = trim($_POST['citizenid']);
    
    if (empty($citizenid)) {
        $error = "กรุณากรอกเลขประจำตัวประชาชน";
    } else {
        // Fetch student by citizen ID
        $student = $studentRegis->getStudentByCitizenId($citizenid);
        
        if ($student) {
            $studentData = $student;
            
            // Check Final Plan
            if (!empty($studentData['final_plan_id'])) {
                // Fetch Plan Details
                $plans = $adminConfig->getStudyPlans(); // We might need a getStudyPlanById
                // Filter manually or add method. Let's filter manually for now or use the type fetch.
                // Actually AdminConfig::getStudyPlans takes type_id.
                // Let's add getStudyPlanById to AdminConfig or just query directly here?
                // Querying directly is cleaner for specific logic, but let's stick to class if possible.
                // AdminConfig doesn't have getStudyPlanById. I'll just query DB here for simplicity or add method.
                // Query DB here is faster.
                
                $stmt = $db->prepare("SELECT sp.*, rt.name as type_name, gl.code as grade_code, gl.name as grade_name
                                    FROM study_plans sp 
                                    JOIN registration_types rt ON sp.registration_type_id = rt.id 
                                    JOIN grade_levels gl ON rt.grade_level_id = gl.id
                                    WHERE sp.id = :id");
                $stmt->execute([':id' => $studentData['final_plan_id']]);
                $plan = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($plan) {
                    $fees = $adminConfig->getPlanFees($plan['id']);
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

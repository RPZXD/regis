<?php
header('Content-Type: application/json');

require_once '../config/Database.php';

$id = $_POST['id'] ?? '';
$reason = $_POST['reason'] ?? '';

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูล ID', 'error' => 'ไม่พบข้อมูล ID']);
    exit;
}

try {
    $db = (new Database_Regis())->getConnection();

    // Verify report schedule window and user status
    $checkStmt = $db->prepare("SELECT u.status, rt.use_schedule, rt.report_start, rt.report_end
                               FROM users u
                               JOIN study_plans sp ON u.final_plan_id = sp.id
                               JOIN registration_types rt ON sp.registration_type_id = rt.id
                               WHERE u.id = ?");
    $checkStmt->execute([$id]);
    $scheduleCheck = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($scheduleCheck) {
        if ($scheduleCheck['status'] != 1) {
            echo json_encode(['success' => false, 'message' => 'ไม่สามารถดำเนินการได้ สถานะไม่ถูกต้อง', 'error' => 'ไม่สามารถดำเนินการได้ สถานะไม่ถูกต้อง']);
            exit;
        }
        if (!empty($scheduleCheck['report_start']) && !empty($scheduleCheck['report_end'])) {
            $now = new DateTime();
            $start = new DateTime($scheduleCheck['report_start']);
            $end = new DateTime($scheduleCheck['report_end']);
            if ($now < $start || $now > $end) {
                echo json_encode(['success' => false, 'message' => 'ไม่อยู่ในช่วงเวลารายงานตัว', 'error' => 'ไม่อยู่ในช่วงเวลารายงานตัว']);
                exit;
            }
        }
    }

    // Update student status to 3 (สละสิทธิ์) 
    $stmt = $db->prepare("UPDATE users SET status = 3 WHERE id = ?"); // Let's also save the reason if we want to... but the users table might not have a cancel_reason column. I'll pass for now or save if it has one. Let me check users schema.
    $result = $stmt->execute([$id]);

    if ($result) {
        // Also call the autoCallReservistsAllPlans right after a cancellation so the system fills the spot!
        require_once '../class/StudentRegis.php';
        $studentRegis = new StudentRegis($db);
        $studentRegis->autoCallReservistsAllPlans();

        echo json_encode(['success' => true, 'message' => 'ยืนยันการสละสิทธิ์สำเร็จ']);
    } else {
        echo json_encode(['success' => false, 'message' => 'ไม่สามารถปรับปรุงข้อมูลได้', 'error' => 'ไม่สามารถปรับปรุงข้อมูลได้']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage(), 'error' => $e->getMessage()]);
}

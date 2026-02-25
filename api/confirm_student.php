<?php
header('Content-Type: application/json');

require_once '../config/Database.php';

$id = $_POST['id'] ?? '';

if (empty($id)) {
    echo json_encode(['success' => false, 'error' => 'ไม่พบข้อมูล ID']);
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
            echo json_encode(['success' => false, 'error' => 'ไม่สามารถดำเนินการได้ สถานะไม่ถูกต้อง']);
            exit;
        }
        if (!empty($scheduleCheck['report_start']) && !empty($scheduleCheck['report_end'])) {
            $now = new DateTime();
            $start = new DateTime($scheduleCheck['report_start']);
            $end = new DateTime($scheduleCheck['report_end']);
            if ($now < $start || $now > $end) {
                echo json_encode(['success' => false, 'error' => 'ไม่อยู่ในช่วงเวลารายงานตัว']);
                exit;
            }
        }
    }

    // Update student status to 2 (ยืนยันสิทธิ์)
    $stmt = $db->prepare("UPDATE users SET status = 2 WHERE id = ?");
    $result = $stmt->execute([$id]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'ยืนยันการรายงานตัวสำเร็จ']);
    } else {
        echo json_encode(['success' => false, 'error' => 'ไม่สามารถปรับปรุงข้อมูลได้']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

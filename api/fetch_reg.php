<?php
/**
 * fetch_reg.php - API for fetching student registration data
 */
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json');

// Error handling helper
function sendError($message, $code = 500)
{
    http_response_code($code);
    echo json_encode([
        'error' => true,
        'message' => $message,
        'exists' => false
    ]);
    exit;
}

// Convert PHP errors to JSON
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno))
        return;
    sendError("PHP Error: $errstr in $errfile line $errline");
});

// Helper functions moved outside respond call
function getScheduleInfo($db, $key)
{
    try {
        $stmt = $db->prepare("SELECT start_datetime, end_datetime, use_schedule FROM menu_config WHERE menu_key = ?");
        $stmt->execute([$key]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && $row['use_schedule']) {
            return [
                'start' => date('d/m/Y H:i', strtotime($row['start_datetime'])),
                'end' => date('d/m/Y H:i', strtotime($row['end_datetime']))
            ];
        }
    } catch (Exception $e) {
    }
    return null;
}

function processStudentData($student, $db, $adminConfig, $context)
{
    $regId = $student['id'];
    $citizenid = $student['citizenid'];

    // 1. Fetch Study Plans
    $allPlans = $adminConfig->getStudyPlans();
    $plansMap = [];
    foreach ($allPlans as $p) {
        $plansMap[$p['id']] = $p['name'];
    }

    $planNames = [];
    try {
        // Try to get from student_study_plans table (modern way)
        $planStmt = $db->prepare("SELECT plan_id, priority FROM student_study_plans WHERE user_id = :uid OR (citizenid = :cid AND (user_id IS NULL OR user_id = 0)) ORDER BY priority ASC");
        $planStmt->execute([':uid' => $regId, ':cid' => $citizenid]);
        $regPlans = $planStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($regPlans as $rp) {
            if (isset($plansMap[$rp['plan_id']]))
                $planNames[] = $plansMap[$rp['plan_id']];
        }
    } catch (Exception $e) {
    }

    // Fallback to number1-10 columns
    if (empty($planNames)) {
        for ($i = 1; $i <= 10; $i++) {
            $key = 'number' . $i;
            if (!empty($student[$key]) && isset($plansMap[$student[$key]])) {
                $planNames[] = $plansMap[$student[$key]];
            }
        }
    }

    // 2. Determine Document/Confirmation Status
    $docStatus = 'pending';
    $docStatusText = 'รอดำเนินการ';
    if ($student['status'] == 1) {
        $docStatus = 'complete';
        $docStatusText = 'ตรวจสอบแล้ว';
    } else {
        try {
            $upStmt = $db->prepare("SELECT status FROM tbl_uploads WHERE citizenid = :cid");
            $upStmt->execute([':cid' => $citizenid]);
            $uploads = $upStmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($uploads) > 0) {
                $hasRejected = false;
                $allApproved = true;
                foreach ($uploads as $u) {
                    if ($u['status'] == 'rejected')
                        $hasRejected = true;
                    if ($u['status'] != 'approved')
                        $allApproved = false;
                }
                if ($hasRejected) {
                    $docStatus = 'rejected';
                    $docStatusText = 'เอกสารไม่ถูกต้อง กรุณาอัพโหลดใหม่';
                } elseif ($allApproved) {
                    $docStatus = 'complete';
                    $docStatusText = 'ตรวจสอบแล้ว';
                } else {
                    $docStatusText = 'กำลังตรวจสอบเอกสาร';
                }
            }
        } catch (Exception $e) {
        }
    }

    // 3. Schedule & Printing Rights
    $isExamCardAvailable = $adminConfig->isMenuAvailable('exam_card');
    $isPrintFormAvailable = $adminConfig->isMenuAvailable('print_form');

    $examSchedule = getScheduleInfo($db, 'exam_card');
    $formSchedule = getScheduleInfo($db, 'print_form');

    $canPrintCard = ($isExamCardAvailable && $student['status'] == 1);
    $canPrintForm = $isPrintFormAvailable;

    $examMsg = 'ยังไม่ถึงช่วงเวลาพิมพ์บัตรประจำตัวสอบ';
    if ($isExamCardAvailable) {
        $examMsg = ($student['status'] == 1) ? 'พร้อมพิมพ์บัตรประจำตัวสอบ' : 'รอการยืนยันสถานะการสมัครก่อนพิมพ์บัตร';
    }
    $formMsg = $isPrintFormAvailable ? 'พร้อมพิมพ์ใบสมัคร' : 'ยังไม่ถึงช่วงเวลาพิมพ์ใบสมัคร';

    return [
        'exists' => true,
        'id' => $student['id'],
        'numreg' => $student['numreg'],
        'citizenid' => $student['citizenid'],
        'fullname' => $student['stu_prefix'] . $student['stu_name'] . ' ' . $student['stu_lastname'],
        'typeregis' => $student['typeregis'],
        'level' => $student['level'],
        'birthday' => $student['birthday'] ?? '-',
        'now_tel' => $student['now_tel'] ?? '-',
        'docStatus' => $docStatus,
        'docStatusText' => $docStatusText,
        'plans' => $planNames,
        'seat_number' => $student['seat_number'] ?? null,
        'exam_room' => $student['exam_room'] ?? null,
        'exam_date' => $student['exam_date'] ?? null,
        'canPrint' => ($context === 'application' ? $canPrintForm : $canPrintCard),
        'canPrintCard' => $canPrintCard,
        'printMessage' => ($context === 'application' ? $formMsg : $examMsg),
        'printCardMessage' => $examMsg,
        'printSchedule' => ($context === 'application' ? $formSchedule : $examSchedule)
    ];
}

try {
    require_once '../config/Database.php';
    require_once '../class/StudentRegis.php';
    require_once '../class/AdminConfig.php';

    // Parse JSON Input
    $input = json_decode(file_get_contents('php://input'), true);
    $searchInput = trim($input['search_input'] ?? '');
    $regId = $input['reg_id'] ?? null;
    $context = $input['context'] ?? '';

    if (!$searchInput && !$regId) {
        echo json_encode(['exists' => false, 'message' => 'No search input provided']);
        exit;
    }

    $db = (new Database_Regis())->getConnection();
    $studentRegis = new StudentRegis($db);
    $adminConfig = new AdminConfig($db);

    // Case 1: Direct Fetch by ID (from modal selection)
    if ($regId) {
        $student = $studentRegis->getStudentById($regId);
        if ($student) {
            echo json_encode(processStudentData($student, $db, $adminConfig, $context));
        } else {
            echo json_encode(['exists' => false]);
        }
        exit;
    }

    // Case 2: Search by input
    $cleanSearch = preg_replace('/[^0-9]/', '', $searchInput);
    if (strlen($cleanSearch) === 13) {
        $query = "SELECT id, numreg, citizenid, stu_prefix, stu_name, stu_lastname, typeregis, level, status 
                  FROM users WHERE citizenid = :search";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':search', $cleanSearch);
    } else {
        $query = "SELECT id, numreg, citizenid, stu_prefix, stu_name, stu_lastname, typeregis, level, status 
                  FROM users 
                  WHERE CONCAT(stu_prefix, stu_name, ' ', stu_lastname) LIKE :search_like 
                     OR numreg = :search";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':search_like', '%' . $searchInput . '%');
        $stmt->bindValue(':search', $searchInput);
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($results)) {
        echo json_encode(['exists' => false]);
    } elseif (count($results) > 1) {
        // Multiple registrations found
        echo json_encode([
            'exists' => true,
            'multiple' => true,
            'fullname' => $results[0]['stu_prefix'] . $results[0]['stu_name'] . ' ' . $results[0]['stu_lastname'],
            'registrations' => array_map(function ($r) {
                return [
                    'id' => $r['id'],
                    'typeregis' => $r['typeregis'],
                    'level' => $r['level'],
                    'numreg' => $r['numreg']
                ];
            }, $results)
        ]);
    } else {
        // Single registration found
        $student = $studentRegis->getStudentById($results[0]['id']);
        echo json_encode(processStudentData($student, $db, $adminConfig, $context));
    }

} catch (Exception $e) {
    sendError($e->getMessage());
} catch (Error $e) {
    sendError($e->getMessage());
}

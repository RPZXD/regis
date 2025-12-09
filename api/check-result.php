<?php
/**
 * API to check student exam results
 */
header('Content-Type: application/json; charset=utf-8');
require_once '../config/Database.php';

$data = json_decode(file_get_contents('php://input'), true);
$search = trim($data['search'] ?? '');

if (empty($search)) {
    echo json_encode(['found' => false, 'message' => 'กรุณากรอกข้อมูลที่ต้องการค้นหา']);
    exit;
}

try {
    $db = (new Database_Regis())->getConnection();
    $db->exec("SET NAMES utf8");
    
    // Clean search input (remove dashes from citizen ID)
    $cleanSearch = preg_replace('/[^0-9ก-๙a-zA-Z\s]/', '', $search);
    $searchParam = "%{$cleanSearch}%";
    
    // Search by citizen ID or name
    $sql = "SELECT 
                citizenid,
                CONCAT(stu_prefix, stu_name, ' ', stu_lastname) as fullname,
                level,
                typeregis,
                status,
                seat_number,
                exam_room,
                exam_date,
                final_plan_id
            FROM users 
            WHERE citizenid = :search 
            OR CONCAT(stu_name, ' ', stu_lastname) LIKE :searchLike
            OR CONCAT(stu_name, stu_lastname) LIKE :searchLike2
            ORDER BY create_at DESC
            LIMIT 1";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':search' => preg_replace('/\D/', '', $search), // Citizen ID only digits
        ':searchLike' => $searchParam,
        ':searchLike2' => str_replace(' ', '', $searchParam)
    ]);
    
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($student) {
        // Determine level display
        $levelText = (in_array($student['level'], ['1', 'm1'])) ? 'ม.1' : 'ม.4';
        
        // Determine result status
        // status: 0 = pending, 1 = confirmed/passed, 2 = failed, 3 = cancelled
        $resultStatus = 'pending';
        if ($student['status'] == 1) {
            $resultStatus = 'passed';
        } elseif ($student['status'] == 2) {
            $resultStatus = 'failed';
        }
        
        echo json_encode([
            'found' => true,
            'fullname' => $student['fullname'],
            'level' => $levelText,
            'typeregis' => $student['typeregis'],
            'status' => $resultStatus,
            'seat_number' => $student['seat_number'],
            'exam_room' => $student['exam_room'],
            'exam_date' => $student['exam_date']
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode([
            'found' => false,
            'message' => 'ไม่พบข้อมูล กรุณาตรวจสอบเลขบัตรประชาชน หรือ ชื่อ-นามสกุล อีกครั้ง'
        ], JSON_UNESCAPED_UNICODE);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'found' => false,
        'message' => 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล'
    ], JSON_UNESCAPED_UNICODE);
}

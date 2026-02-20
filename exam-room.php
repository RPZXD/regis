<?php
/**
 * Exam Room Preview Page
 */
session_start();

require_once 'config/Database.php';
require_once 'config/Setting.php';
require_once 'class/AdminConfig.php';
require_once 'class/StudentRegis.php';

$setting = new Setting();
$databaseRegis = new Database_Regis();
$db = $databaseRegis->getConnection();

$adminConfig = new AdminConfig($db);
$studentRegis = new StudentRegis($db);

// Ensure exam_rooms table exists
$createTableSQL = "CREATE TABLE IF NOT EXISTS exam_rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    building VARCHAR(100) NOT NULL,
    seats INT DEFAULT NULL,
    capacity INT DEFAULT NULL,
    details TEXT DEFAULT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
$db->exec($createTableSQL);

$pageTitle = 'ผังห้องสอบ';

// Fetch all study plans for mapping
$allPlans = $adminConfig->getStudyPlans();
$plansMap = [];
foreach ($allPlans as $plan) {
    $plansMap[$plan['id']] = $plan['name'];
}

// Fetch all active exam rooms and de-duplicate by name (taking highest capacity)
$stmt = $db->prepare("SELECT * FROM exam_rooms WHERE is_active = 1 ORDER BY capacity DESC, id DESC");
$stmt->execute();
$allRooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

$rooms = [];
$seenNames = [];
foreach ($allRooms as $r) {
    if (!isset($seenNames[$r['name']])) {
        $rooms[] = $r;
        $seenNames[$r['name']] = true;
    }
}
// Sort back by building and name
usort($rooms, function ($a, $b) {
    if ($a['building'] === $b['building']) {
        return strcmp($a['name'], $b['name']);
    }
    return strcmp($a['building'], $b['building']);
});

// Fetch all unique exam dates from the users table
$stmtDates = $db->prepare("SELECT DISTINCT exam_date FROM users WHERE exam_date IS NOT NULL AND exam_date != '' ORDER BY exam_date ASC");
$stmtDates->execute();
$availableDates = $stmtDates->fetchAll(PDO::FETCH_COLUMN);

// Get selected date from URL or default to the first one
$selectedDate = $_GET['date'] ?? ($availableDates[0] ?? '');

// 1. Fetch all students who have ANY exam room assigned for the selected date
$sqlAll = "SELECT u.id, u.citizenid, CONCAT_WS(' ', u.stu_prefix, u.stu_name, u.stu_lastname) as fullname, 
               u.seat_number, u.exam_room, u.exam_date, u.level, u.typeregis,
               GROUP_CONCAT(DISTINCT CONCAT(ssp.priority, ':', ssp.plan_id) ORDER BY ssp.priority ASC SEPARATOR ',') as plan_string
        FROM users u
        LEFT JOIN student_study_plans ssp ON u.id = ssp.user_id 
        WHERE u.exam_room IS NOT NULL AND u.exam_room != ''
        AND u.exam_date = ?
        GROUP BY u.id
        ORDER BY u.seat_number ASC";

$stmtAll = $db->prepare($sqlAll);
$stmtAll->execute([$selectedDate]);
$allAssignedStudents = $stmtAll->fetchAll(PDO::FETCH_ASSOC);

// 2. Distribute students to rooms
$roomData = [];
foreach ($rooms as $room) {
    $matchedStudents = [];
    $roomNameTrim = trim($room['name']);

    foreach ($allAssignedStudents as $student) {
        // Match if student's exam_room contains our room name (e.g. "ห้อง 233" in "ห้อง 233 (อาคาร 2 ชั้น 3)")
        if (strpos($student['exam_room'], $roomNameTrim) !== false) {
            $matchedStudents[] = $student;
        }
    }

    // Only include room if it has students assigned for this specific date
    if (!empty($matchedStudents)) {
        $room['students'] = $matchedStudents;
        $roomData[] = $room;
    }
}

// Debug: Count total students found across all rooms
$debugTotal = array_sum(array_map(function ($r) {
    return count($r['students']);
}, $roomData));
// echo "<!-- Debug: Found " . count($roomData) . " rooms and $debugTotal students total -->";


// Fetch students with no room assigned but have exam info (optional, for debug)
$stmt = $db->prepare("SELECT id, citizenid, CONCAT(stu_prefix, stu_name, ' ', stu_lastname) as fullname, seat_number, exam_room, level, typeregis 
                      FROM users 
                      WHERE (exam_room IS NULL OR exam_room = '') AND seat_number IS NOT NULL AND seat_number != ''
                      ORDER BY seat_number ASC");
$stmt->execute();
$unassignedStudents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Global plansMap for JSON encoding in the script
$plansMapJson = json_encode($plansMap, JSON_UNESCAPED_UNICODE);

ob_start();
include 'views/admin/exam-room-layout.php';
$content = ob_get_clean();

require 'views/layouts/app.php';

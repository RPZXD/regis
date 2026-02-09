<?php
/**
 * API: Get all students for document checking by registration type
 * Shows all students including those who haven't uploaded documents
 */
header('Content-Type: application/json');
require_once('../../../config/Database.php');
require_once('../../../class/StudentRegis.php');
require_once('../../../class/AdminConfig.php');

session_start();
if (!isset($_SESSION['Admin_login'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();

$adminConfig = new AdminConfig($db);
$studentRegis = new StudentRegis($db);

$typeId = isset($_GET['type_id']) ? intval($_GET['type_id']) : 0;

if (!$typeId) {
    echo json_encode(['students' => [], 'stats' => [], 'requirements' => []]);
    exit;
}

try {
    // Get registration type info
    $regType = $adminConfig->getRegistrationTypeById($typeId);
    if (!$regType) {
        echo json_encode(['error' => 'Registration type not found']);
        exit;
    }

    // Determine level for query
    $level = ($regType['grade_code'] == 'm1') ? '1' : '4';

    // Get all students of this type
    $students = $studentRegis->getStudentsByCriteria($level, $regType['name']);

    // Get document requirements for this type
    $reqSql = "SELECT id, name, is_required FROM document_requirements WHERE registration_type_id = ? AND is_active = 1";
    $reqStmt = $db->prepare($reqSql);
    $reqStmt->execute([$typeId]);
    $requirements = $reqStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get all uploaded documents for these students
    $docSql = "SELECT sd.*, dr.name as doc_name 
               FROM student_documents sd 
               JOIN document_requirements dr ON sd.requirement_id = dr.id 
               WHERE dr.registration_type_id = ?";
    $docStmt = $db->prepare($docSql);
    $docStmt->execute([$typeId]);
    $allDocs = $docStmt->fetchAll(PDO::FETCH_ASSOC);

    // Index documents by citizenid
    $docsByCitizen = [];
    foreach ($allDocs as $doc) {
        if (!isset($docsByCitizen[$doc['citizenid']])) {
            $docsByCitizen[$doc['citizenid']] = [];
        }
        $docsByCitizen[$doc['citizenid']][] = $doc;
    }

    // Build result with document status for each student
    $result = [];
    $statsTotal = 0;
    $statsComplete = 0;
    $statsPending = 0;
    $statsNoUpload = 0;
    $statsApproved = 0;  // Student approval status count

    foreach ($students as $student) {
        $statsTotal++;

        // Count approved students
        if (($student['status'] ?? 0) == 1) {
            $statsApproved++;
        }

        $citizenid = $student['citizenid'];
        $studentDocs = $docsByCitizen[$citizenid] ?? [];

        // Calculate document status
        $totalRequired = count($requirements);
        $uploadedCount = count($studentDocs);
        $approvedCount = 0;
        $rejectedCount = 0;
        $pendingCount = 0;

        foreach ($studentDocs as $doc) {
            if ($doc['status'] === 'approved')
                $approvedCount++;
            elseif ($doc['status'] === 'rejected')
                $rejectedCount++;
            else
                $pendingCount++;
        }

        // Determine overall status
        $docStatus = 'no_upload';
        if ($uploadedCount === 0) {
            $docStatus = 'no_upload';
            $statsNoUpload++;
        } elseif ($approvedCount === $totalRequired && $totalRequired > 0) {
            $docStatus = 'complete';
            $statsComplete++;
        } elseif ($rejectedCount > 0) {
            $docStatus = 'rejected';
            $statsPending++;
        } else {
            $docStatus = 'pending';
            $statsPending++;
        }

        $result[] = [
            'id' => $student['id'],
            'user_id' => $student['id'],
            'citizenid' => $citizenid,
            'numreg' => $student['numreg'] ?? null,
            'fullname' => trim(($student['stu_prefix'] ?? '') . ($student['stu_name'] ?? '') . ' ' . ($student['stu_lastname'] ?? '')),
            'type_name' => $regType['grade_name'] . ' - ' . $regType['name'],
            'status' => $student['status'] ?? 0,  // Student approval status (0=pending, 1=approved)
            'documents' => array_map(function ($d) {
                return [
                    'id' => $d['id'],
                    'doc_name' => $d['doc_name'],
                    'file_path' => $d['file_path'],
                    'status' => $d['status'] ?? 'pending',
                    'reject_reason' => $d['reject_reason'] ?? ''
                ];
            }, $studentDocs),
            'doc_status' => $docStatus,
            'uploaded_count' => $uploadedCount,
            'total_required' => $totalRequired,
            'approved_count' => $approvedCount,
            'rejected_count' => $rejectedCount,
            'pending_count' => $pendingCount
        ];
    }

    echo json_encode([
        'students' => $result,
        'stats' => [
            'total' => $statsTotal,
            'complete' => $statsComplete,
            'pending' => $statsPending,
            'no_upload' => $statsNoUpload,
            'approved' => $statsApproved
        ],
        'requirements' => $requirements,
        'type_info' => $regType
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

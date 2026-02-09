<?php
header('Content-Type: application/json');
require_once('../../../config/Database.php');

session_start();
if (!isset($_SESSION['Admin_login'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();

// Get filters
$status = $_GET['status'] ?? '';
$typeId = $_GET['type_id'] ?? '';

try {
    // Build query
    $sql = "SELECT sd.id, sd.citizenid, sd.file_path, sd.original_name, sd.status, 
                   sd.uploaded_at, sd.reject_reason,
                   dr.name as doc_name, dr.registration_type_id,
                   u.id as user_id, u.numreg, u.stu_prefix, u.stu_name, u.stu_lastname,
                   rt.name as type_name, gl.name as grade_name
            FROM student_documents sd
            JOIN document_requirements dr ON sd.requirement_id = dr.id
            LEFT JOIN users u ON sd.citizenid = u.citizenid
            LEFT JOIN registration_types rt ON dr.registration_type_id = rt.id
            LEFT JOIN grade_levels gl ON rt.grade_level_id = gl.id
            WHERE 1=1";

    $params = [];

    if ($status) {
        $sql .= " AND sd.status = ?";
        $params[] = $status;
    }

    if ($typeId) {
        $sql .= " AND dr.registration_type_id = ?";
        $params[] = $typeId;
    }

    $sql .= " ORDER BY sd.uploaded_at DESC";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Process results
    $result = [];
    foreach ($documents as $doc) {
        $result[] = [
            'id' => $doc['id'],
            'user_id' => $doc['user_id'],
            'citizenid' => $doc['citizenid'],
            'numreg' => $doc['numreg'],
            'fullname' => trim(($doc['stu_prefix'] ?? '') . ($doc['stu_name'] ?? '') . ' ' . ($doc['stu_lastname'] ?? '')),
            'file_path' => $doc['file_path'],
            'original_name' => $doc['original_name'],
            'doc_name' => $doc['doc_name'],
            'type_name' => ($doc['grade_name'] ?? '') . ' - ' . ($doc['type_name'] ?? ''),
            'status' => $doc['status'],
            'uploaded_at' => date('d/m/Y H:i', strtotime($doc['uploaded_at'])),
            'reject_reason' => $doc['reject_reason']
        ];
    }

    // Get stats
    $statsSql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
                 FROM student_documents";
    $statsStmt = $db->query($statsSql);
    $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);

    // Get types for filter
    $typesSql = "SELECT DISTINCT rt.id, rt.name, gl.name as grade_name 
                 FROM registration_types rt
                 JOIN grade_levels gl ON rt.grade_level_id = gl.id
                 JOIN document_requirements dr ON dr.registration_type_id = rt.id
                 ORDER BY gl.id, rt.id";
    $typesStmt = $db->query($typesSql);
    $types = $typesStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'documents' => $result,
        'stats' => $stats,
        'types' => $types
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

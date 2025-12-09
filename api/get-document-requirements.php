<?php
header('Content-Type: application/json');
require_once '../config/Database.php';

// Get citizenid from query
$citizenid = $_GET['citizenid'] ?? '';

if (empty($citizenid)) {
    echo json_encode(['error' => 'Missing citizenid']);
    exit;
}

try {
    $db = new Database_Regis();
    $conn = $db->getConnection();

    // Get student info (level and typeregis only)
    $studentSql = "SELECT level, typeregis FROM users WHERE citizenid = ?";
    $studentStmt = $conn->prepare($studentSql);
    $studentStmt->execute([$citizenid]);
    $student = $studentStmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        echo json_encode(['requirements' => [], 'uploaded' => [], 'error' => 'Student not found']);
        exit;
    }

    $typeId = null;
    
    // If registration_type_id is not set, try to find it from level and typeregis
    if (!$typeId && $student['level'] && $student['typeregis']) {
        $level = strtolower($student['level']); // m1, m4
        $typeregis = $student['typeregis']; // ห้องเรียนพิเศษ, ทั่วไป, etc.
        
        // Find matching registration type by joining with grade_levels
        $typeSql = "SELECT rt.id FROM registration_types rt 
                    JOIN grade_levels gl ON rt.grade_level_id = gl.id
                    WHERE gl.code = ? AND rt.name LIKE ?
                    LIMIT 1";
        $typeStmt = $conn->prepare($typeSql);
        $typeStmt->execute([$level, '%' . $typeregis . '%']);
        $typeResult = $typeStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($typeResult) {
            $typeId = $typeResult['id'];
        }
    }

    if (!$typeId) {
        echo json_encode(['requirements' => [], 'uploaded' => [], 'message' => 'No registration type found']);
        exit;
    }

    // Get document requirements for this type
    $reqSql = "SELECT id, name, description, is_required, file_types, max_size_mb 
               FROM document_requirements 
               WHERE registration_type_id = ? AND is_active = 1 
               ORDER BY sort_order";
    $reqStmt = $conn->prepare($reqSql);
    $reqStmt->execute([$typeId]);
    $requirements = $reqStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get already uploaded documents
    $uploadedSql = "SELECT requirement_id, original_name, file_path, status 
                    FROM student_documents 
                    WHERE citizenid = ?";
    $uploadedStmt = $conn->prepare($uploadedSql);
    $uploadedStmt->execute([$citizenid]);
    $uploadedList = $uploadedStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Index by requirement_id
    $uploaded = [];
    foreach ($uploadedList as $doc) {
        $uploaded[$doc['requirement_id']] = $doc;
    }

    echo json_encode([
        'requirements' => $requirements,
        'uploaded' => $uploaded,
        'typeId' => $typeId
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}


<?php
/**
 * api/get-document-requirements.php
 * Fetches document requirements for a student based on their citizenid
 * Returns requirements list and any already-uploaded documents
 */
header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../config/Database.php';
    require_once __DIR__ . '/../class/AdminConfig.php';

    $citizenid = $_GET['citizenid'] ?? '';
    $regId = $_GET['reg_id'] ?? '';

    if (empty($citizenid) && empty($regId)) {
        echo json_encode(['requirements' => [], 'uploaded' => []]);
        exit;
    }

    $db = (new Database_Regis())->getConnection();
    $adminConfig = new AdminConfig($db);

    // 1. Look up the student record
    if (!empty($regId)) {
        $stmt = $db->prepare("SELECT id, citizenid, level, typeregis FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$regId]);
    } else {
        $stmt = $db->prepare("SELECT id, citizenid, level, typeregis FROM users WHERE citizenid = ? LIMIT 1");
        $stmt->execute([$citizenid]);
    }
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        echo json_encode(['requirements' => [], 'uploaded' => []]);
        exit;
    }

    $citizenid = $student['citizenid']; // Safety if only regId was provided

    $level = $student['level'];
    $typeregis = $student['typeregis'];

    // 2. Find the matching registration_type_id
    //    Map level to grade_code: '1' => 'm1', '4' => 'm4'
    $gradeCode = ($level == '1') ? 'm1' : 'm4';

    // For M.1 General with zone: typeregis is 'ในเขต' or 'นอกเขต' or 'รอบทั่วไป'
    // These all map to registration_types with code='general' and grade='m1'
    $zoneTypes = ['ในเขต', 'นอกเขต', 'รอบทั่วไป'];

    if (in_array($typeregis, $zoneTypes)) {
        // Find the 'general' registration type for this grade
        $rtStmt = $db->prepare("
            SELECT rt.id FROM registration_types rt 
            JOIN grade_levels gl ON rt.grade_level_id = gl.id
            WHERE gl.code = ? AND (rt.code = 'general' OR rt.name = 'รอบทั่วไป')
            LIMIT 1
        ");
        $rtStmt->execute([$gradeCode]);
    } else {
        // Try to match by typeregis name
        $rtStmt = $db->prepare("
            SELECT rt.id FROM registration_types rt 
            JOIN grade_levels gl ON rt.grade_level_id = gl.id
            WHERE gl.code = ? AND rt.name = ?
            LIMIT 1
        ");
        $rtStmt->execute([$gradeCode, $typeregis]);
    }

    $rtRow = $rtStmt->fetch(PDO::FETCH_ASSOC);
    if (!$rtRow) {
        // Fallback: try searching by name across all grade levels
        $rtStmt2 = $db->prepare("SELECT id FROM registration_types WHERE name = ? LIMIT 1");
        $rtStmt2->execute([$typeregis]);
        $rtRow = $rtStmt2->fetch(PDO::FETCH_ASSOC);
    }

    if (!$rtRow) {
        echo json_encode(['requirements' => [], 'uploaded' => []]);
        exit;
    }

    $typeId = $rtRow['id'];

    // 3. Fetch document requirements for this registration type
    $reqStmt = $db->prepare("
        SELECT id, name, description, is_required, file_types, max_size_mb 
        FROM document_requirements 
        WHERE registration_type_id = ? AND is_active = 1
        ORDER BY sort_order
    ");
    $reqStmt->execute([$typeId]);
    $requirements = $reqStmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. Fetch already-uploaded documents
    $uploaded = [];
    $upStmt = $db->prepare("
        SELECT sd.requirement_id, sd.file_path, sd.original_name, sd.status, sd.reject_reason, sd.uploaded_at
        FROM student_documents sd
        WHERE sd.citizenid = ?
    ");
    $upStmt->execute([$citizenid]);
    $uploadedRows = $upStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($uploadedRows as $row) {
        $uploaded[$row['requirement_id']] = $row;
    }

    echo json_encode([
        'requirements' => $requirements,
        'uploaded' => $uploaded
    ]);

} catch (Throwable $t) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal Server Error',
        'message' => $t->getMessage()
    ]);
}
?>

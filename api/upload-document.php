<?php
/**
 * api/upload-document.php
 * Handles file upload for student documents
 * Saves to student_documents table with reference to document_requirements
 */
header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../config/Database.php';

    $citizenid = $_POST['citizenid'] ?? '';
    $requirementId = intval($_POST['requirement_id'] ?? 0);

    if (empty($citizenid) || empty($requirementId) || !isset($_FILES['file'])) {
        echo json_encode(['success' => false, 'error' => 'ข้อมูลไม่ครบถ้วน']);
        exit;
    }

    $file = $_FILES['file'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'error' => 'เกิดข้อผิดพลาดในการอัพโหลด (code: ' . $file['error'] . ')']);
        exit;
    }

    $db = (new Database_Regis())->getConnection();

    // Validate requirement exists
    $reqStmt = $db->prepare("SELECT id, file_types, max_size_mb FROM document_requirements WHERE id = ? AND is_active = 1");
    $reqStmt->execute([$requirementId]);
    $requirement = $reqStmt->fetch(PDO::FETCH_ASSOC);

    if (!$requirement) {
        echo json_encode(['success' => false, 'error' => 'ไม่พบเอกสารที่ต้องการ']);
        exit;
    }

    // Validate file type
    $allowedTypes = array_map('trim', explode(',', $requirement['file_types']));
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedTypes)) {
        echo json_encode(['success' => false, 'error' => 'ประเภทไฟล์ไม่อนุญาต (รองรับ: ' . $requirement['file_types'] . ')']);
        exit;
    }

    // Validate file size
    $maxBytes = ($requirement['max_size_mb'] ?? 5) * 1024 * 1024;
    if ($file['size'] > $maxBytes) {
        echo json_encode(['success' => false, 'error' => 'ไฟล์ขนาดใหญ่เกินไป (สูงสุด: ' . $requirement['max_size_mb'] . ' MB)']);
        exit;
    }

    // Create upload directory
    $uploadDir = __DIR__ . '/../uploads/' . $citizenid . '/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generate unique filename
    $newFileName = 'doc_' . $requirementId . '_' . date('YmdHis') . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
    $targetPath = $uploadDir . $newFileName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        echo json_encode(['success' => false, 'error' => 'ไม่สามารถบันทึกไฟล์ได้']);
        exit;
    }

    // Relative path for DB storage
    $relativePath = 'uploads/' . $citizenid . '/' . $newFileName;

    // Check if document already exists for this student and requirement
    $existStmt = $db->prepare("SELECT id, status FROM student_documents WHERE citizenid = ? AND requirement_id = ?");
    $existStmt->execute([$citizenid, $requirementId]);
    $existing = $existStmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        // Update existing document (reset status to pending)
        $updateStmt = $db->prepare("
            UPDATE student_documents 
            SET file_path = ?, original_name = ?, status = 'pending', reject_reason = NULL, uploaded_at = NOW()
            WHERE id = ?
        ");
        $updateStmt->execute([$relativePath, $file['name'], $existing['id']]);
    } else {
        // Insert new document
        $insertStmt = $db->prepare("
            INSERT INTO student_documents (citizenid, requirement_id, file_path, original_name, status, uploaded_at)
            VALUES (?, ?, ?, ?, 'pending', NOW())
        ");
        $insertStmt->execute([$citizenid, $requirementId, $relativePath, $file['name']]);
    }

    // Also save to legacy tbl_uploads for backward compatibility
    try {
        $reqNameStmt = $db->prepare("SELECT name FROM document_requirements WHERE id = ?");
        $reqNameStmt->execute([$requirementId]);
        $reqName = $reqNameStmt->fetchColumn();
        $legacyName = 'document' . $requirementId;

        $legacyCheck = $db->prepare("SELECT id FROM tbl_uploads WHERE citizenid = ? AND name = ?");
        $legacyCheck->execute([$citizenid, $legacyName]);

        if ($legacyCheck->fetch()) {
            $legacyUpdate = $db->prepare("UPDATE tbl_uploads SET path = ?, create_at = NOW(), status = 'pending' WHERE citizenid = ? AND name = ?");
            $legacyUpdate->execute([$relativePath, $citizenid, $legacyName]);
        } else {
            // Get student level
            $lvlStmt = $db->prepare("SELECT level FROM users WHERE citizenid = ? LIMIT 1");
            $lvlStmt->execute([$citizenid]);
            $level = $lvlStmt->fetchColumn() ?: '1';

            $legacyInsert = $db->prepare("INSERT INTO tbl_uploads (citizenid, name, path, level, create_at) VALUES (?, ?, ?, ?, NOW())");
            $legacyInsert->execute([$citizenid, $legacyName, $relativePath, $level]);
        }
    } catch (Exception $e) {
        // Legacy save is not critical, continue
    }

    echo json_encode([
        'success' => true,
        'message' => 'อัพโหลดสำเร็จ',
        'file_path' => $relativePath
    ]);

} catch (Throwable $t) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'เกิดข้อผิดพลาด: ' . $t->getMessage()
    ]);
}
?>

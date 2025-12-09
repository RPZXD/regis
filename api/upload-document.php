<?php
header('Content-Type: application/json');
require_once '../config/Database.php';

// Check for POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get form data
$citizenid = $_POST['citizenid'] ?? '';
$requirementId = intval($_POST['requirement_id'] ?? 0);

if (empty($citizenid) || !$requirementId) {
    echo json_encode(['success' => false, 'error' => 'ข้อมูลไม่ครบถ้วน']);
    exit;
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'ไม่พบไฟล์หรือเกิดข้อผิดพลาดในการอัพโหลด']);
    exit;
}

try {
    $db = new Database_Regis();
    $conn = $db->getConnection();

    // Get requirement details for validation
    $reqSql = "SELECT * FROM document_requirements WHERE id = ?";
    $reqStmt = $conn->prepare($reqSql);
    $reqStmt->execute([$requirementId]);
    $requirement = $reqStmt->fetch(PDO::FETCH_ASSOC);

    if (!$requirement) {
        echo json_encode(['success' => false, 'error' => 'ไม่พบข้อกำหนดเอกสาร']);
        exit;
    }

    // Validate file type
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileSize = $file['size'];
    $fileTmpPath = $file['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    $allowedTypes = array_map('trim', explode(',', strtolower($requirement['file_types'])));
    if (!in_array($fileExt, $allowedTypes)) {
        echo json_encode(['success' => false, 'error' => 'ประเภทไฟล์ไม่ถูกต้อง (รองรับ: ' . $requirement['file_types'] . ')']);
        exit;
    }

    // Validate file size
    $maxSizeBytes = $requirement['max_size_mb'] * 1024 * 1024;
    if ($fileSize > $maxSizeBytes) {
        echo json_encode(['success' => false, 'error' => 'ไฟล์มีขนาดเกิน ' . $requirement['max_size_mb'] . ' MB']);
        exit;
    }

    // Create upload directory
    $uploadDir = __DIR__ . '/../uploads/' . $citizenid . '/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Generate unique filename
    $newFileName = $requirementId . '_' . time() . '.' . $fileExt;
    $filePath = $uploadDir . $newFileName;
    $relativePath = 'uploads/' . $citizenid . '/' . $newFileName;

    // Move uploaded file
    if (!move_uploaded_file($fileTmpPath, $filePath)) {
        echo json_encode(['success' => false, 'error' => 'ไม่สามารถบันทึกไฟล์ได้']);
        exit;
    }

    // Delete existing record if any
    $deleteSql = "DELETE FROM student_documents WHERE citizenid = ? AND requirement_id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->execute([$citizenid, $requirementId]);

    // Insert new record
    $insertSql = "INSERT INTO student_documents (citizenid, requirement_id, file_path, original_name, file_size) VALUES (?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $result = $insertStmt->execute([$citizenid, $requirementId, $relativePath, $fileName, $fileSize]);

    if ($result) {
        // Get student name for notification
        $stmtUser = $conn->prepare("SELECT CONCAT(stu_prefix, stu_name, ' ', stu_lastname) AS fullname FROM users WHERE citizenid = ?");
        $stmtUser->execute([$citizenid]);
        $userInfo = $stmtUser->fetch(PDO::FETCH_ASSOC);
        
        // Send notification
        require_once '../class/NotificationHelper.php';
        $notifier = new NotificationHelper($conn);
        $notifier->notifyDocumentUpload($userInfo['fullname'] ?? $citizenid, $requirement['name'] ?? 'เอกสาร', $citizenid);
        
        echo json_encode(['success' => true, 'message' => 'อัพโหลดสำเร็จ']);
    } else {
        echo json_encode(['success' => false, 'error' => 'ไม่สามารถบันทึกข้อมูลได้']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

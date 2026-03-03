<?php
header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../../../config/Database.php';

    session_start();
    if (!isset($_SESSION['Admin_login'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Unauthorized']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['csv_file'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'No file uploaded or invalid request.']);
        exit;
    }

    $file = $_FILES['csv_file'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("File upload error code: " . $file['error']);
    }

    $filename = $file['tmp_name'];
    $handle = fopen($filename, "r");

    if ($handle === FALSE) {
        throw new Exception("Cannot open uploaded file.");
    }

    $connectDB = new Database_Regis();
    $db = $connectDB->getConnection();

    // Read header row
    $header = fgetcsv($handle, 1000, ",");
    if (!$header) {
        throw new Exception("Invalid CSV format or empty file.");
    }

    // Handle potential BOM in the first column header
    $header[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header[0]);

    // Expected headers
    $expectedHeaders = [
        'เลขประจำตัวผู้สมัคร',
        'เลขบัตรประชาชน',
        'ชื่อนามสกุล',
        'แผนการเรียน',
        'เลขที่นั่งสอบ',
        'ห้องสอบ',
        'วันสอบ',
        'สถานะ (0=รอตรวจสอบ, 1=ยืนยันแล้ว, 2=สละสิทธิ์)'
    ];

    // Map column names to indexes dynamically to handle reordered columns
    $colMap = [];
    foreach ($header as $index => $colName) {
        $cleanColName = trim($colName);
        $colMap[$cleanColName] = $index;
    }

    // Required columns for update
    if (!isset($colMap['เลขบัตรประชาชน'])) {
        throw new Exception("ไม่พบคอลัมน์ 'เลขบัตรประชาชน' ในไฟล์ CSV");
    }

    $seatCol = $colMap['เลขที่นั่งสอบ'] ?? -1;
    $roomCol = $colMap['ห้องสอบ'] ?? -1;
    $dateCol = $colMap['วันสอบ'] ?? -1;

    $updatedCount = 0;
    $errors = [];

    $stmt = $db->prepare("UPDATE users SET seat_number = ?, exam_room = ?, exam_date = ?, update_at = NOW() WHERE citizenid = ?");

    $rowNum = 1;
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $rowNum++;

        // Skip empty rows
        if (empty(array_filter($data)))
            continue;

        $citizenIdRaw = isset($data[$colMap['เลขบัตรประชาชน']]) ? trim($data[$colMap['เลขบัตรประชาชน']]) : '';

        // Clean Excel formatting: ="1234567890123" -> 1234567890123
        $citizenId = preg_replace('/^="([^"]*)"$/', '$1', $citizenIdRaw);
        $citizenId = preg_replace('/[^0-9]/', '', $citizenId);

        if (strlen($citizenId) !== 13) {
            $errors[] = "แถวที่ {$rowNum}: เลขบัตรประชาชนไม่ถูกต้อง ({$citizenIdRaw})";
            continue;
        }

        $seatNumber = ($seatCol >= 0 && isset($data[$seatCol])) ? trim($data[$seatCol]) : null;
        $examRoom = ($roomCol >= 0 && isset($data[$roomCol])) ? trim($data[$roomCol]) : null;
        $examDate = ($dateCol >= 0 && isset($data[$dateCol])) ? trim($data[$dateCol]) : null;

        // Skip if all arrangement fields are empty (nothing to update)
        if ($seatNumber === '' && $examRoom === '' && $examDate === '') {
            continue;
        }

        // Execute update
        $result = $stmt->execute([
            $seatNumber !== '' ? $seatNumber : null,
            $examRoom !== '' ? $examRoom : null,
            $examDate !== '' ? $examDate : null,
            $citizenId
        ]);

        if ($result && $stmt->rowCount() > 0) {
            $updatedCount++;
        }
    }

    fclose($handle);

    echo json_encode([
        'success' => true,
        'updated_count' => $updatedCount,
        'errors' => $errors
    ]);

} catch (Throwable $t) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $t->getMessage()
    ]);
}
?>
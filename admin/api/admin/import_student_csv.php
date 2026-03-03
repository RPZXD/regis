<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../config/Database.php';
require_once __DIR__ . '/../../../class/StudentRegis.php';

try {
    session_start();
    if (!isset($_SESSION['Admin_login'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }

    if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("ไม่พบไฟล์ที่อัปโหลด หรือเกิดข้อผิดพลาดในการโหลดไฟล์");
    }

    $fileTmpPath = $_FILES['csv_file']['tmp_name'];

    // Open the CSV file
    if (($handle = fopen($fileTmpPath, "r")) === FALSE) {
        throw new Exception("ไม่สามารถเปิดไฟล์อัปโหลดได้");
    }

    // Skip BOM if present
    $bom = fread($handle, 3);
    if ($bom != "\xEF\xBB\xBF") {
        rewind($handle); // Not BOM, go back to start
    }

    // Attempt to read headers
    $headers = fgetcsv($handle, 10000, ",");
    if (!$headers) {
        throw new Exception("ไม่พบส่วนหัว (Header) ในไฟล์ CSV");
    }

    // Clean headers (trim whitespace, quotes, etc.)
    $headers = array_map(function ($h) {
        return trim($h);
    }, $headers);

    // Provide a mapping from CSV Thai columns to DB columns
    $headersMap = [
        "เลขที่ผู้สมัคร" => "numreg",
        "เลขบัตรประชาชน" => "citizenid",
        "คำนำหน้า" => "stu_prefix",
        "ชื่อ" => "stu_name",
        "นามสกุล" => "stu_lastname",
        "เพศ" => "stu_sex",
        // "วันเกิด" => "birthday", // Custom handling if we split it
        "เบอร์โทรนักเรียน" => "now_tel",
        "ประเภทการสมัคร" => "typeregis",
        "ศาสนา" => "stu_religion",
        "เชื้อชาติ" => "stu_ethnicity",
        "สัญชาติ" => "stu_nationality",
        "กรุ๊ปเลือด" => "stu_blood_group",
        "ที่อยู่ปัจจุบัน" => "now_addr",
        "หมู่" => "now_moo",
        "ซอย" => "now_soy",
        "ถนน" => "now_street",
        "ตำบล" => "now_subdistrict",
        "อำเภอ" => "now_district",
        "จังหวัด" => "now_province",
        "รหัสไปรษณีย์" => "now_post",
        "โรงเรียนเดิม" => "old_school",
        "จังหวัด(รร.เดิม)" => "old_school_province",
        "อำเภอ(รร.เดิม)" => "old_school_district",
        "เลขประจำตัวเดิม" => "old_school_stuid",
        "GPA รวม" => "gpa_total",
        "GPA วิทย์" => "grade_science",
        "GPA คณิต" => "grade_math",
        "GPA อังกฤษ" => "grade_english",
        // "เบอร์โทรบิดา" => "dad_tel",
        // "อาชีพบิดา" => "dad_job",
        // "เบอร์โทรมารดา" => "mom_tel",
        // "อาชีพมารดา" => "mom_job",
        "ความสัมพันธ์" => "parent_relation",
        "เบอร์โทรผู้ปกครอง" => "parent_tel"
    ];

    $dbRegis = new Database_Regis();
    $db = $dbRegis->getConnection();
    $studentRegis = new StudentRegis($db);

    $updatedCount = 0;

    // Process each row
    while (($row = fgetcsv($handle, 10000, ",")) !== FALSE) {
        // Skip empty rows
        if (count(array_filter($row)) == 0)
            continue;

        $rowData = [];
        $citizenid = '';

        // Build data format
        foreach ($headers as $index => $headerName) {
            if (!isset($row[$index]))
                continue;

            $val = trim($row[$index]);

            // Handle ="1234" Excel trick by stripping `="` and `"`
            if (preg_match('/^="([^"]*)"$/', $val, $matches)) {
                $val = $matches[1];
            } elseif (preg_match('/^\'?(.*?)\'?$/', $val, $matches)) {
                // Remove leading single quote if present
                $val = ltrim($val, "'");
            }

            // Extreme fallback if preg_match failed for some reason
            $val = str_replace(['="', '"'], '', $val);

            if ($headerName === 'เลขบัตรประชาชน') {
                // Ensure numeric only, even if it had =" or spaces inside it
                $citizenid = preg_replace('/[^0-9]/', '', $val);
                $val = $citizenid; // Keep strict clean id
            }

            if (isset($headersMap[$headerName])) {
                $dbCol = $headersMap[$headerName];
                // Excel empty fields parsing safety
                if ($val === '')
                    $val = null;
                $rowData[$dbCol] = $val;
            }

            // Parse birthday manually if it was edited 
            if ($headerName === 'วันเกิด' && !empty($val)) {
                $parts = explode('/', $val);
                if (count($parts) == 3) { // usually exported as DD/MM/YYYY
                    $rowData['date_birth'] = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                    $rowData['month_birth'] = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
                    $rowData['year_birth'] = $parts[2];
                } elseif (strpos($val, '-') !== false) {
                    $parts = explode('-', $val);
                    if (count($parts) == 3) {
                        $rowData['date_birth'] = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                        $rowData['month_birth'] = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
                        $rowData['year_birth'] = $parts[2];
                    }
                }
            }
        }

        // We must have a citizen ID to identify the record
        if (empty($citizenid) || strlen($citizenid) < 5) {
            continue;
        }

        // Fetch user from DB
        $stmt_check = $db->prepare("SELECT id FROM users WHERE citizenid = :cid");
        $stmt_check->execute(['cid' => $citizenid]);
        $u = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($u) {
            // Ensure citizenid is provided to update method
            if (isset($rowData['citizenid'])) {
                $rowData['citizenid'] = $citizenid;
            }
            // Keep numeric values empty as null handled correctly by the parser assignment above

            // Call the existing logic to update
            $success = $studentRegis->updateStudent($u['id'], $rowData, []);
            if ($success === true) {
                $updatedCount++;
            }
        }
    }
    fclose($handle);

    echo json_encode([
        'success' => true,
        'message' => 'นำเข้าข้อมูลเรียบร้อย',
        'updated' => $updatedCount
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

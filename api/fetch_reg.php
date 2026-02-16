<?php
header('Content-Type: application/json');
require_once '../config/Database.php';

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);
$search = $data['search_input'] ?? '';

if (empty($search)) {
    echo json_encode(['exists' => false, 'message' => 'No search input provided']);
    exit;
}

try {
    $db = new Database_Regis();
    $conn = $db->getConnection();

    // Prepare search params
    $searchParam = "%{$search}%";
    $regId = $data['reg_id'] ?? null;

    // If a specific registration ID is provided, fetch that one directly
    if ($regId) {
        $sql = "SELECT * FROM users WHERE id = :regId LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':regId' => $regId]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        // Normalize search input if it looks like a citizen ID (has dashes or is 13 digits)
        $cleanSearch = $search;
        if (preg_match('/[0-9-]{10,}/', $search)) {
            $cleanSearch = preg_replace('/[^0-9]/', '', $search);
        }

        // Search by Citizen ID OR Name — check for multiple registrations
        $sql = "SELECT * FROM users 
                WHERE citizenid = :search 
                OR citizenid = :cleanSearch
                OR CONCAT(stu_name, ' ', stu_lastname) LIKE :searchLike 
                ORDER BY id DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':search' => $search,
            ':cleanSearch' => $cleanSearch,
            ':searchLike' => $searchParam
        ]);

        $allResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($allResults) > 1) {
            // Multiple registrations found — let user choose
            $options = [];
            foreach ($allResults as $row) {
                $level = str_replace('m', '', strtolower($row['level']));
                $options[] = [
                    'id' => $row['id'],
                    'typeregis' => $row['typeregis'],
                    'level' => $level,
                    'numreg' => $row['numreg'],
                    'fullname' => $row['stu_prefix'] . $row['stu_name'] . ' ' . $row['stu_lastname']
                ];
            }
            echo json_encode([
                'exists' => true,
                'multiple' => true,
                'citizenid' => $allResults[0]['citizenid'],
                'fullname' => $allResults[0]['stu_prefix'] . $allResults[0]['stu_name'] . ' ' . $allResults[0]['stu_lastname'],
                'registrations' => $options
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $student = $allResults[0] ?? null;
    }

    if ($student) {
        // Fetch Plans
        $plans = [];
        $planSql = "SELECT sp.name 
                   FROM student_study_plans ssp
                   JOIN study_plans sp ON ssp.plan_id = sp.id
                   WHERE ssp.user_id = :userId
                   ORDER BY ssp.priority ASC";
        $planStmt = $conn->prepare($planSql);
        $planStmt->execute([':userId' => $student['id']]);
        $plans = $planStmt->fetchAll(PDO::FETCH_COLUMN);

        // Map typeregis if needed
        $typeName = $student['typeregis'];

        // Get registration type to check all schedules
        $registrationTypeId = $student['registration_type_id'] ?? null;

        // Initialize all schedule variables
        $canPrint = false;
        $printMessage = 'ยังไม่ได้กำหนดช่วงเวลาพิมพ์ใบสมัคร';
        $printScheduleInfo = null;

        $canUpload = false;
        $uploadMessage = 'ยังไม่ได้กำหนดช่วงเวลาอัพโหลด';
        $uploadScheduleInfo = null;

        $canPrintCard = false;
        $printCardMessage = 'ยังไม่ได้กำหนดช่วงเวลาพิมพ์บัตรสอบ';
        $printCardScheduleInfo = null;

        if ($registrationTypeId) {
            $typeSql = "SELECT rt.*, gl.name as grade_name 
                       FROM registration_types rt 
                       LEFT JOIN grade_levels gl ON rt.grade_level_id = gl.id
                       WHERE rt.id = ?";
            $typeStmt = $conn->prepare($typeSql);
            $typeStmt->execute([$registrationTypeId]);
            $regType = $typeStmt->fetch(PDO::FETCH_ASSOC);

            if ($regType) {
                $typeName = $regType['name']; // Use registration type name

                $now = new DateTime();

                // === 1. Print Form Schedule (พิมพ์ใบสมัคร) ===
                $printStart = !empty($regType['print_form_start']) ? new DateTime($regType['print_form_start']) : null;
                $printEnd = !empty($regType['print_form_end']) ? new DateTime($regType['print_form_end']) : null;

                if ($printStart && $printEnd) {
                    $printScheduleInfo = [
                        'start' => $printStart->format('d/m/Y H:i'),
                        'end' => $printEnd->format('d/m/Y H:i')
                    ];

                    if ($now < $printStart) {
                        $canPrint = false;
                        $printMessage = 'ยังไม่ถึงช่วงเวลาพิมพ์ใบสมัคร (เริ่ม ' . $printStart->format('d/m/Y H:i') . ')';
                    } elseif ($now > $printEnd) {
                        $canPrint = false;
                        $printMessage = 'หมดช่วงเวลาพิมพ์ใบสมัครแล้ว (สิ้นสุด ' . $printEnd->format('d/m/Y H:i') . ')';
                    } else {
                        $canPrint = true;
                        $printMessage = 'สามารถพิมพ์ใบสมัครได้ (ถึง ' . $printEnd->format('d/m/Y H:i') . ')';
                    }
                } else {
                    // If no schedule set, allow by default
                    $canPrint = true;
                    $printMessage = 'สามารถพิมพ์ใบสมัครได้';
                }

                // === 2. Upload Documents Schedule (อัพโหลดหลักฐาน) ===
                $uploadStart = !empty($regType['upload_start']) ? new DateTime($regType['upload_start']) : null;
                $uploadEnd = !empty($regType['upload_end']) ? new DateTime($regType['upload_end']) : null;

                if ($uploadStart && $uploadEnd) {
                    $uploadScheduleInfo = [
                        'start' => $uploadStart->format('d/m/Y H:i'),
                        'end' => $uploadEnd->format('d/m/Y H:i')
                    ];

                    if ($now < $uploadStart) {
                        $canUpload = false;
                        $uploadMessage = 'ยังไม่ถึงช่วงเวลาอัพโหลดเอกสาร (เริ่ม ' . $uploadStart->format('d/m/Y H:i') . ')';
                    } elseif ($now > $uploadEnd) {
                        $canUpload = false;
                        $uploadMessage = 'หมดช่วงเวลาอัพโหลดเอกสารแล้ว (สิ้นสุด ' . $uploadEnd->format('d/m/Y H:i') . ')';
                    } else {
                        $canUpload = true;
                        $uploadMessage = 'สามารถอัพโหลดเอกสารได้ (ถึง ' . $uploadEnd->format('d/m/Y H:i') . ')';
                    }
                } else {
                    // If no schedule set, allow by default
                    $canUpload = true;
                    $uploadMessage = 'สามารถอัพโหลดเอกสารได้';
                }

                // === 3. Print Exam Card Schedule (พิมพ์บัตรสอบ) ===
                $cardStart = !empty($regType['exam_card_start']) ? new DateTime($regType['exam_card_start']) : null;
                $cardEnd = !empty($regType['exam_card_end']) ? new DateTime($regType['exam_card_end']) : null;

                if ($cardStart && $cardEnd) {
                    $printCardScheduleInfo = [
                        'start' => $cardStart->format('d/m/Y H:i'),
                        'end' => $cardEnd->format('d/m/Y H:i')
                    ];

                    if ($now < $cardStart) {
                        $canPrintCard = false;
                        $printCardMessage = 'ยังไม่ถึงช่วงเวลาพิมพ์บัตรสอบ (เริ่ม ' . $cardStart->format('d/m/Y H:i') . ')';
                    } elseif ($now > $cardEnd) {
                        $canPrintCard = false;
                        $printCardMessage = 'หมดช่วงเวลาพิมพ์บัตรสอบแล้ว (สิ้นสุด ' . $cardEnd->format('d/m/Y H:i') . ')';
                    } else {
                        $canPrintCard = true;
                        $printCardMessage = 'สามารถพิมพ์บัตรสอบได้ (ถึง ' . $cardEnd->format('d/m/Y H:i') . ')';
                    }
                } else {
                    // If no schedule set, allow by default
                    $canPrintCard = true;
                    $printCardMessage = 'สามารถพิมพ์บัตรสอบได้';
                }
            }
        } else {
            // No registration type - allow all by default
            $canPrint = true;
            $canUpload = true;
            $canPrintCard = true;
            $printMessage = 'สามารถพิมพ์ใบสมัครได้';
            $uploadMessage = 'สามารถอัพโหลดเอกสารได้';
            $printCardMessage = 'สามารถพิมพ์บัตรสอบได้';
        }

        // Format Thai Date
        $thai_months = [
            "01" => "มกราคม",
            "02" => "กุมภาพันธ์",
            "03" => "มีนาคม",
            "04" => "เมษายน",
            "05" => "พฤษภาคม",
            "06" => "มิถุนายน",
            "07" => "กรกฎาคม",
            "08" => "สิงหาคม",
            "09" => "กันยายน",
            "10" => "ตุลาคม",
            "11" => "พฤศจิกายน",
            "12" => "ธันวาคม",
            "1" => "มกราคม",
            "2" => "กุมภาพันธ์",
            "3" => "มีนาคม",
            "4" => "เมษายน",
            "5" => "พฤษภาคม",
            "6" => "มิถุนายน",
            "7" => "กรกฎาคม",
            "8" => "สิงหาคม",
            "9" => "กันยายน"
        ];
        $monthKey = $student['month_birth'];
        $thaiMonth = $thai_months[$monthKey] ?? $monthKey;
        $formattedDate = $student['date_birth'] . ' ' . $thaiMonth . ' ' . $student['year_birth'];

        // Clean Level (m1 -> 1, m4 -> 4)
        $cleanLevel = str_replace('m', '', strtolower($student['level']));

        // === Document Completion Status ===
        $docStatus = 'none'; // none, pending, incomplete, complete
        $docStatusText = 'ยังไม่มีเอกสาร';
        $requiredDocsTotal = 0;
        $uploadedDocsCount = 0;
        $approvedDocsCount = 0;
        $rejectedDocsCount = 0;

        // Find registration type ID from level and typeregis if not set
        $regTypeId = $student['registration_type_id'] ?? null;
        if (!$regTypeId && $student['level'] && $student['typeregis']) {
            $typeFindSql = "SELECT rt.id FROM registration_types rt 
                            JOIN grade_levels gl ON rt.grade_level_id = gl.id
                            WHERE gl.code = ? AND rt.name LIKE ? LIMIT 1";
            $typeFindStmt = $conn->prepare($typeFindSql);
            $typeFindStmt->execute([strtolower($student['level']), '%' . $student['typeregis'] . '%']);
            $typeFindResult = $typeFindStmt->fetch(PDO::FETCH_ASSOC);
            if ($typeFindResult)
                $regTypeId = $typeFindResult['id'];
        }

        if ($regTypeId) {
            // Get required documents count
            $reqDocsSql = "SELECT COUNT(*) FROM document_requirements WHERE registration_type_id = ? AND is_required = 1 AND is_active = 1";
            $reqDocsStmt = $conn->prepare($reqDocsSql);
            $reqDocsStmt->execute([$regTypeId]);
            $requiredDocsTotal = (int) $reqDocsStmt->fetchColumn();

            // Get uploaded documents status
            $uploadedSql = "SELECT sd.status FROM student_documents sd 
                            JOIN document_requirements dr ON sd.requirement_id = dr.id
                            WHERE sd.citizenid = ? AND dr.is_required = 1 AND dr.is_active = 1";
            $uploadedStmt = $conn->prepare($uploadedSql);
            $uploadedStmt->execute([$student['citizenid']]);
            $uploadedDocs = $uploadedStmt->fetchAll(PDO::FETCH_ASSOC);

            $uploadedDocsCount = count($uploadedDocs);
            foreach ($uploadedDocs as $doc) {
                if ($doc['status'] === 'approved')
                    $approvedDocsCount++;
                if ($doc['status'] === 'rejected')
                    $rejectedDocsCount++;
            }

            // Determine status
            if ($requiredDocsTotal == 0) {
                $docStatus = 'none';
                $docStatusText = 'ไม่มีเอกสารที่ต้องอัพโหลด';
            } elseif ($uploadedDocsCount == 0) {
                $docStatus = 'none';
                $docStatusText = 'ยังไม่ได้อัพโหลดเอกสาร';
            } elseif ($rejectedDocsCount > 0) {
                $docStatus = 'rejected';
                $docStatusText = 'มีเอกสารไม่ผ่าน กรุณาแก้ไข';
            } elseif ($uploadedDocsCount < $requiredDocsTotal) {
                $docStatus = 'incomplete';
                $docStatusText = "อัพโหลดเอกสารแล้ว {$uploadedDocsCount}/{$requiredDocsTotal}";
            } elseif ($approvedDocsCount == $requiredDocsTotal) {
                $docStatus = 'complete';
                $docStatusText = 'เอกสารครบถ้วน ✓';
            } else {
                $docStatus = 'pending';
                $docStatusText = 'รอตรวจสอบเอกสาร';
            }
        }

        echo json_encode([
            'exists' => true,
            'citizenid' => $student['citizenid'],
            'fullname' => $student['stu_prefix'] . $student['stu_name'] . ' ' . $student['stu_lastname'],
            'typeregis' => $student['typeregis'],
            'level' => $cleanLevel,
            'birthday' => $formattedDate,
            'now_tel' => $student['now_tel'],
            'parent_tel' => $student['parent_tel'],
            'plans' => $plans,
            // Print Form Schedule
            'canPrint' => $canPrint,
            'printMessage' => $printMessage,
            'printSchedule' => $printScheduleInfo,
            // Upload Schedule
            'canUpload' => $canUpload,
            'uploadMessage' => $uploadMessage,
            'uploadSchedule' => $uploadScheduleInfo,
            // Print Exam Card Schedule
            'canPrintCard' => $canPrintCard,
            'printCardMessage' => $printCardMessage,
            'printCardSchedule' => $printCardScheduleInfo,
            // Status
            'status' => $student['status'] ?? 0,
            'docStatus' => $docStatus,
            'docStatusText' => $docStatusText,
            'docStats' => [
                'required' => $requiredDocsTotal,
                'uploaded' => $uploadedDocsCount,
                'approved' => $approvedDocsCount,
                'rejected' => $rejectedDocsCount
            ],
            'seat_number' => $student['seat_number'] ?? null,
            'exam_room' => $student['exam_room'] ?? null,
            'exam_date' => $student['exam_date'] ?? null
        ]);
    } else {
        echo json_encode(['exists' => false]);
    }

} catch (PDOException $e) {
    echo json_encode(['exists' => false, 'error' => $e->getMessage()]);
}



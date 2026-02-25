<?php
header('Content-Type: application/json');

session_start();
if (!isset($_SESSION['Admin_login'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../../config/Database.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Please upload a valid CSV file']);
    exit;
}

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();

$csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

if (!empty($_FILES['csv_file']['name']) && in_array($_FILES['csv_file']['type'], $csvMimes)) {
    if (is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
        $csvFile = fopen($_FILES['csv_file']['tmp_name'], 'r');

        // Skip first line assuming it's header: citizenid, final_plan_id, pass_rank, is_called
        fgetcsv($csvFile);

        $updated = 0;
        try {
            $db->beginTransaction();
            $stmt = $db->prepare("UPDATE users SET final_plan_id = :final_plan_id, pass_rank = :pass_rank, is_called = :is_called WHERE citizenid = :citizenid");

            while (($line = fgetcsv($csvFile)) !== FALSE) {
                // Ensure array has enough elements
                // Strip out = and " if they exist from the citizenid
                $citizenid = isset($line[0]) ? str_replace(['=', '"', "'", "\t", " "], '', trim($line[0])) : '';
                $finalPlanId = isset($line[1]) && is_numeric(trim($line[1])) ? intval(trim($line[1])) : null;
                // Treat '-' or empty string as null for passRank
                $passRankStr = isset($line[2]) ? trim($line[2]) : '';
                if ($passRankStr === '-' || $passRankStr === '') {
                    $passRank = null;
                } else {
                    $passRank = is_numeric($passRankStr) ? intval($passRankStr) : null;
                }

                $isCalled = isset($line[3]) && is_numeric(trim($line[3])) ? intval(trim($line[3])) : 0;

                if (!empty($citizenid)) {
                    $stmt->execute([
                        ':final_plan_id' => $finalPlanId,
                        ':pass_rank' => $passRank,
                        ':is_called' => $isCalled,
                        ':citizenid' => $citizenid
                    ]);
                    $updated++;
                }
            }
            $db->commit();
            fclose($csvFile);
            echo json_encode(['success' => true, 'updated' => $updated]);
        } catch (PDOException $e) {
            $db->rollBack();
            fclose($csvFile);
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error uploading file']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid file format. Please upload a CSV file.']);
}
?>
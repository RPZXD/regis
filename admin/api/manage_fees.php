<?php
header('Content-Type: application/json');
require_once('../../config/Database.php');
require_once('../../class/AdminConfig.php');

session_start();
if (!isset($_SESSION['Admin_login'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();
$config = new AdminConfig($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $action = $_GET['action'] ?? '';

        if ($action === 'export') {
            // Export CSV for a plan
            $planId = $_GET['plan_id'] ?? 0;
            $fees = $config->getPlanFees($planId);
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="fees_plan_' . $planId . '.csv"');
            echo "\xEF\xBB\xBF"; // BOM for Thai encoding
            echo "หมวดหมู่,รายการ,ภาคเรียนที่1,ภาคเรียนที่2,ลำดับ\n";
            foreach ($fees as $fee) {
                echo '"' . str_replace('"', '""', $fee['category']) . '",';
                echo '"' . str_replace('"', '""', $fee['item_name']) . '",';
                echo $fee['term1_amount'] . ',';
                echo $fee['term2_amount'] . ',';
                echo $fee['sort_order'] . "\n";
            }
            exit;
        }

        if ($action === 'template') {
            // Download CSV template
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="fee_template.csv"');
            echo "\xEF\xBB\xBF";
            echo "หมวดหมู่,รายการ,ภาคเรียนที่1,ภาคเรียนที่2,ลำดับ\n";
            echo "maintenance,ค่าประกันอุบัติเหตุ,200,0,1\n";
            echo "maintenance,ค่าสาธารณูปโภค,500,500,2\n";
            echo "support,ค่ากิจกรรมพัฒนาผู้เรียน,300,300,1\n";
            exit;
        }

        // Default: Get fees for a plan
        $planId = $_GET['plan_id'] ?? null;
        if ($planId) {
            echo json_encode($config->getPlanFees($planId));
        } else {
            echo json_encode([]);
        }
        break;

    case 'POST':
        $action = $_POST['action'] ?? '';

        if ($action === 'copy') {
            $source = intval($_POST['source_plan_id'] ?? 0);
            $target = intval($_POST['target_plan_id'] ?? 0);
            $mode = $_POST['mode'] ?? 'replace';
            if ($source && $target) {
                $config->copyPlanFees($source, $target, $mode);
                echo json_encode(['success' => true]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Missing source or target plan']);
            }
            break;
        }

        if ($action === 'import') {
            $planId = intval($_POST['plan_id'] ?? 0);
            $mode = $_POST['mode'] ?? 'replace';

            if (!$planId || !isset($_FILES['csv_file'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Missing plan_id or csv_file']);
                break;
            }

            $file = $_FILES['csv_file']['tmp_name'];
            $fees = [];
            if (($handle = fopen($file, 'r')) !== false) {
                // Skip BOM if present
                $bom = fread($handle, 3);
                if ($bom !== "\xEF\xBB\xBF") {
                    rewind($handle);
                }
                // Skip header row
                fgetcsv($handle);
                while (($row = fgetcsv($handle)) !== false) {
                    if (count($row) >= 4 && !empty(trim($row[1]))) {
                        $fees[] = [
                            'category' => trim($row[0]),
                            'item_name' => trim($row[1]),
                            'term1_amount' => floatval($row[2] ?? 0),
                            'term2_amount' => floatval($row[3] ?? 0),
                            'sort_order' => intval($row[4] ?? 0)
                        ];
                    }
                }
                fclose($handle);
            }

            if (empty($fees)) {
                http_response_code(400);
                echo json_encode(['error' => 'No valid data found in CSV']);
                break;
            }

            $config->importPlanFees($planId, $fees, $mode);
            echo json_encode(['success' => true, 'imported' => count($fees)]);
            break;
        }

        // Default: Add single fee
        $data = [
            'plan_id' => $_POST['plan_id'],
            'category' => $_POST['category'],
            'item_name' => $_POST['item_name'],
            'term1_amount' => $_POST['term1_amount'] ?? 0,
            'term2_amount' => $_POST['term2_amount'] ?? 0,
            'sort_order' => $_POST['sort_order'] ?? 0
        ];
        if ($config->addPlanFee($data)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to add fee']);
        }
        break;

    case 'PUT':
        parse_str(file_get_contents('php://input'), $data);
        $id = $data['id'] ?? 0;
        if ($id && $config->updatePlanFee($id, $data)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to update fee']);
        }
        break;

    case 'DELETE':
        $id = $_GET['id'] ?? 0;
        if ($id && $config->deletePlanFee($id)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to delete fee']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
?>
<?php
header('Content-Type: application/json');
require_once('../../config/Database.php');

session_start();
if (!isset($_SESSION['Admin_login'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();

$typeId = isset($_GET['type_id']) ? intval($_GET['type_id']) : 0;

try {
    $baseSelect = "SELECT 
                    u.id,
                    u.citizenid,
                    CONCAT(u.stu_prefix, u.stu_name, ' ', u.stu_lastname) AS fullname,
                    u.typeregis,
                    u.level,
                    u.status,
                    u.update_at,
                    u.final_plan_id,
                    sp.name AS plan_name
                FROM users u
                LEFT JOIN study_plans sp ON u.final_plan_id = sp.id
                WHERE u.status IN (1, 2, 3)";
    
    if ($typeId) {
        // Get type info first
        $typeStmt = $db->prepare("SELECT rt.name, gl.code FROM registration_types rt JOIN grade_levels gl ON rt.grade_level_id = gl.id WHERE rt.id = ?");
        $typeStmt->execute([$typeId]);
        $typeInfo = $typeStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($typeInfo) {
            // Filter by partial match on typeregis and by level
            $baseSelect .= " AND (u.typeregis LIKE '%" . substr($typeInfo['name'], 0, 10) . "%' OR u.typeregis LIKE '%พิเศษ%' AND '" . $typeInfo['name'] . "' LIKE '%พิเศษ%')";
            $baseSelect .= " AND u.level = '" . $typeInfo['code'] . "'";
        }
    }
    
    // Confirmed (status = 2)
    $confirmedSql = $baseSelect . " AND u.status = 2 ORDER BY u.update_at DESC";
    $stmt = $db->query($confirmedSql);
    $confirmed = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Cancelled (status = 3)
    $cancelledSql = $baseSelect . " AND u.status = 3 ORDER BY u.update_at DESC";
    $stmt = $db->query($cancelledSql);
    $cancelled = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Pending (status = 1, passed but not confirmed yet)
    $pendingSql = $baseSelect . " AND u.status = 1 ORDER BY u.update_at DESC";
    $stmt = $db->query($pendingSql);
    $pending = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get types
    $typesSql = "SELECT rt.id, rt.name, gl.name as grade_name 
                 FROM registration_types rt
                 JOIN grade_levels gl ON rt.grade_level_id = gl.id
                 ORDER BY gl.id, rt.id";
    $typesStmt = $db->query($typesSql);
    $types = $typesStmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'confirmed' => $confirmed,
        'cancelled' => $cancelled,
        'pending' => $pending,
        'types' => $types
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

<?php
header("Content-Type: application/json; charset=UTF-8");
include_once("../config/Database.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(array("success" => false, "message" => "Only POST method is allowed."));
    exit();
}

$id = $_POST['id'] ?? null;
$reason = $_POST['reason'] ?? '';

if (!$id) {
    http_response_code(400);
    echo json_encode(array("success" => false, "message" => "Missing ID."));
    exit();
}

$database = new Database_Regis();
$db = $database->getConnection();

try {
    // Status 3 = สละสิทธิ์ (Cancelled/Given up rights)
    $sql = "UPDATE users SET status = 3, update_at = NOW() WHERE id = :id";
    
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        // Get student info for notification
        $stmtInfo = $db->prepare("SELECT CONCAT(stu_prefix, stu_name, ' ', stu_lastname) AS fullname, citizenid FROM users WHERE id = ?");
        $stmtInfo->execute([$id]);
        $studentInfo = $stmtInfo->fetch(PDO::FETCH_ASSOC);
        
        // Send notification
        require_once("../class/NotificationHelper.php");
        $notifier = new NotificationHelper($db);
        $notifier->notifyReportCancel($studentInfo['fullname'] ?? '', $studentInfo['citizenid'] ?? '');
        
        echo json_encode(array("success" => true, "message" => "Cancelled successfully."));
    } else {
        echo json_encode(array("success" => false, "message" => "Database update failed."));
    }

} catch (PDOException $e) {
    // If cancel_reason column doesn't exist, try without it
    try {
        $sql = "UPDATE users SET status = 3, update_at = NOW() WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            echo json_encode(array("success" => true, "message" => "Cancelled successfully."));
        } else {
            echo json_encode(array("success" => false, "message" => "Database update failed."));
        }
    } catch (PDOException $e2) {
        echo json_encode(array("success" => false, "message" => "Error: " . $e2->getMessage()));
    }
}
?>

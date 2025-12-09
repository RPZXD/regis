<?php
require_once '../../config/Database.php';

$citizenid = $_GET['citizenid'];
$upload_name = $_GET['upload_name'];

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();

$query = "SELECT status FROM tbl_uploads WHERE citizenid = :citizenid AND name = :upload_name";
$stmt = $db->prepare($query);
$stmt->bindParam(':citizenid', $citizenid);
$stmt->bindParam(':upload_name', $upload_name);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    echo json_encode(['success' => true, 'status' => $result['status']]);
} else {
    echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูลสถานะ']);
}
?>

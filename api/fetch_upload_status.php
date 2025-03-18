<?php
require_once('../config/Database.php');

$database = new Database_Regis();
$db = $database->getConnection();

$citizenid = json_decode(file_get_contents("php://input"))->citizenid;

$query = "SELECT id, citizenid, name, path, create_at, status, error_detail FROM tbl_uploads WHERE citizenid = :citizenid";
$stmt = $db->prepare($query);
$stmt->bindParam(':citizenid', $citizenid);
$stmt->execute();

$uploads = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($uploads);
?>

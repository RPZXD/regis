<?php
require_once('../config/Database.php');

$database = new Database_Regis();
$db = $database->getConnection();

$citizenid = json_decode(file_get_contents("php://input"))->citizenid;

$query = "SELECT 
    up.id, 
    up.citizenid, 
    up.name, 
    up.path, 
    up.create_at, 
    up.status, 
    up.error_detail, 
    con.label
FROM 
    tbl_uploads AS up
INNER JOIN 
    config_uploads AS con
ON 
    up.name = con.document_id AND up.level = con.level
WHERE 
    citizenid = :citizenid";
$stmt = $db->prepare($query);
$stmt->bindParam(':citizenid', $citizenid);
$stmt->execute();

$uploads = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($uploads);
?>

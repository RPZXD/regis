<?php
require_once('../config/Database.php');
require_once('../class/Upload.php');

$database = new Database_Regis();
$db = $database->getConnection();

$uploads = new Uploads($db);

$citizenid = $_POST['citizenid'];
$files = $_FILES;

if (!isset($files['document9'])) {
    $files['document9'] = null;
}

$result = $uploads->uploadDocument($citizenid, $files);

echo json_encode($result);
?>

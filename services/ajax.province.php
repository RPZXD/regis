<?php
// Include the Database_Regis class
include_once '../config/Database.php';

// Instantiate the Database_Regis object
$dbRegis = new Database_Regis();
$conn = $dbRegis->getConnection();

// Prepare SQL query using the PDO connection
$sql = "SELECT
            t1.`code`,
            t1.name_th
        FROM
            province AS t1
        ORDER BY CONVERT (t1.name_th USING tis620) ASC";

$stmt = $conn->prepare($sql);

// Execute the query
$stmt->execute();

// Fetch the results
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Process the results and prepare the response
$cnt = 0;
$arr = [];
foreach ($result as $index => $value) {
    $arr[$cnt]['code'] = $value['code'];
    $arr[$cnt]['name'] = $value['name_th'];
    $cnt++;
}

// Output the results as JSON
echo json_encode($arr);
?>

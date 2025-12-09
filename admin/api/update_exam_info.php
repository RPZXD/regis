<?php
header("Content-Type: application/json; charset=UTF-8");
include_once("../../config/Database.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(array("success" => false, "message" => "Only POST method is allowed."));
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['id'])) {
    http_response_code(400);
    echo json_encode(array("success" => false, "message" => "Invalid JSON or missing ID."));
    exit();
}

$database = new Database_Regis();
$db = $database->getConnection();

try {
    $parts = [];
    $params = [':id' => $data['id']];

    if (isset($data['seat_number'])) {
        $parts[] = "seat_number = :seat_number";
        $params[':seat_number'] = $data['seat_number'];
    }
    if (isset($data['exam_room'])) {
        $parts[] = "exam_room = :exam_room";
        $params[':exam_room'] = $data['exam_room'];
    }
    if (isset($data['exam_date'])) {
        $parts[] = "exam_date = :exam_date";
        $params[':exam_date'] = $data['exam_date'];
    }

    if (empty($parts)) {
        echo json_encode(array("success" => true, "message" => "No changes to update."));
        exit();
    }

    $sql = "UPDATE users SET " . implode(", ", $parts) . " WHERE id = :id";
    $stmt = $db->prepare($sql);
    
    if ($stmt->execute($params)) {
        echo json_encode(array("success" => true, "message" => "Updated successfully."));
    } else {
        echo json_encode(array("success" => false, "message" => "Database update failed."));
    }

} catch (PDOException $e) {
    echo json_encode(array("success" => false, "message" => "Error: " . $e->getMessage()));
}
?>

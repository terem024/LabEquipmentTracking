<?php
require "dbConnection.php";

if (!isset($_GET['rfid'])) {
    echo json_encode(["status" => "error", "message" => "No RFID provided"]);
    exit;
}

$rfid = $_GET['rfid'];

$stmt = $conn->prepare("
    SELECT equipment_id, rfid_tag, item_name, category, quantity, status 
    FROM lab_equipments 
    WHERE rfid_tag = ? AND is_deleted = 0
");
$stmt->bind_param("s", $rfid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "not_found"]);
    exit;
}

$eq = $result->fetch_assoc();

echo json_encode([
    "status" => "ok",
    "equipment_id" => $eq["equipment_id"],
    "rfid" => $eq["rfid_tag"],
    "item_name" => $eq["item_name"],
    "category" => $eq["category"],
    "quantity" => $eq["quantity"],
    "status_flag" => $eq["status"]
]);

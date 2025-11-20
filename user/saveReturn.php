<?php
session_start();
require "../config/db.php";

if (!isset($_POST['rfid'])) {
    echo "RFID missing.";
    exit;
}

$rfid = $_POST['rfid'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    UPDATE borrow_records 
    SET return_time = NOW() 
    WHERE equipment_rfid = ? 
      AND user_id = ?
      AND return_time IS NULL
");
$stmt->bind_param("si", $rfid, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Equipment returned successfully!";
} else {
    echo "No borrow record found or already returned.";
}

<?php
session_start();
require "../config/dbConnection.php";

if (!isset($_POST['rfid'])) {
    echo "RFID not received.";
    exit;
}

$rfid = $_POST['rfid'];
$user_id = $_SESSION['user_id'];

// Insert INTO transaction history
$stmt = $conn->prepare("
    INSERT INTO borrow_records (user_id, equipment_rfid, borrow_time) 
    VALUES (?, ?, NOW())
");
$stmt->bind_param("is", $user_id, $rfid);

if ($stmt->execute()) {
    echo "Equipment borrowed successfully!";
} else {
    echo "Error saving borrow record.";
}

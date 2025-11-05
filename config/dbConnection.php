<?php

$host = "localhost";
$dbname = "lab_borrowing_system";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    date_default_timezone_set('Asia/Manila');
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
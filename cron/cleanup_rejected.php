<?php
require_once __DIR__ . '/../config/dbConnection.php';
$conn = dbConnection();

// Auto-delete users rejected for 30+ days
$stmt = $conn->prepare("
    DELETE FROM users
    WHERE account_status='For Approval' 
    AND created_at < (NOW() - INTERVAL 30 DAY)
");
$stmt->execute();

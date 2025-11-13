<?php
require_once __DIR__ . '/../../config/dbConnection.php';
require_once __DIR__ . '/../../config/helpers.php';

require_valid_csrf_and_post($_POST['csrf_token'] ?? null);

$user_id = intval($_POST['user_id'] ?? 0);
// The $conn variable should be available from the included dbConnection.php
if (!isset($conn) || !$conn) {
    die("Database connection failed");
}

$stmt = $conn->prepare("UPDATE users SET account_status='Approved' WHERE user_id=?");
$stmt->execute([$user_id]);

setFlash('success', 'User approved successfully!');
header('Location: ../userManagement.php');
exit;
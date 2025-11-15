<?php
require_once __DIR__ . '/../../config/dbConnection.php';
require_once __DIR__ . '/../../config/helpers.php';

require_valid_csrf_and_post($_POST['csrf_token'] ?? null);

$user_id = intval($_POST['user_id'] ?? 0);


// Soft delete or mark for auto-delete (cleanup_rejected.php)
$stmt = $conn->prepare("DELETE FROM users WHERE user_id=? AND account_status='For Approval'");
$stmt->execute([$user_id]);

setFlash('danger', 'User rejected and will be removed.');
header('Location: ../userManagement.php');
exit;

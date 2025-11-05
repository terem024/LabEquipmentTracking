<?php
require_once __DIR__ . '/../config/DbConnection.php';
require_once __DIR__ . '/../config/helpers.php';

$pageContent = '';
$conn = dbConnect();

// Fetch all users
$stmt = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Build table HTML
$pageContent .= '<h3>User Management</h3>';
$pageContent .= '<table class="table table-striped">';
$pageContent .= '<thead><tr><th>ID</th><th>SR Code</th><th>Full Name</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead>';
$pageContent .= '<tbody>';

foreach ($users as $user) {
    $pageContent .= '<tr>';
    $pageContent .= '<td>' . $user['user_id'] . '</td>';
    $pageContent .= '<td>' . htmlspecialchars($user['sr_code']) . '</td>';
    $pageContent .= '<td>' . htmlspecialchars($user['full_name']) . '</td>';
    $pageContent .= '<td>' . htmlspecialchars($user['role']) . '</td>';
    $pageContent .= '<td>' . htmlspecialchars($user['account_status']) . '</td>';
    $pageContent .= '<td>';
    if ($user['account_status'] === 'For Approval') {
        $pageContent .= '<form method="post" class="d-inline" action="actions/user_approve.php">';
        $pageContent .= csrf_input_field();
        $pageContent .= '<input type="hidden" name="user_id" value="' . $user['user_id'] . '">';
        $pageContent .= '<button class="btn btn-success btn-sm" type="submit">Approve</button>';
        $pageContent .= '</form> ';

        $pageContent .= '<form method="post" class="d-inline" action="actions/user_reject.php">';
        $pageContent .= csrf_input_field();
        $pageContent .= '<input type="hidden" name="user_id" value="' . $user['user_id'] . '">';
        $pageContent .= '<button class="btn btn-danger btn-sm" type="submit">Reject</button>';
        $pageContent .= '</form>';
    }
    $pageContent .= '</td>';
    $pageContent .= '</tr>';
}
$pageContent .= '</tbody></table>';

include 'layout.php';

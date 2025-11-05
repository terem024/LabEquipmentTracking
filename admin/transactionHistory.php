<?php
require_once __DIR__ . '/../config/DbConnection.php';
require_once __DIR__ . '/../config/helpers.php';

$pageContent = '';
$conn = dbConnect();

$stmt = $conn->query("
    SELECT t.*, u.full_name, u.sr_code, e.item_name
    FROM transactions t
    JOIN users u ON t.user_id = u.user_id
    JOIN lab_equipments e ON t.equipment_id = e.equipment_id
    ORDER BY t.borrow_time DESC
");
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageContent .= '<h3>Transaction History</h3>';
$pageContent .= '<table class="table table-striped">';
$pageContent .= '<thead><tr><th>ID</th><th>User</th><th>SR Code</th><th>Equipment</th><th>Borrow Time</th><th>Return Time</th><th>Status</th></tr></thead><tbody>';

foreach ($transactions as $tx) {
    $pageContent .= '<tr>';
    $pageContent .= '<td>' . $tx['transaction_id'] . '</td>';
    $pageContent .= '<td>' . htmlspecialchars($tx['full_name']) . '</td>';
    $pageContent .= '<td>' . htmlspecialchars($tx['sr_code']) . '</td>';
    $pageContent .= '<td>' . htmlspecialchars($tx['item_name']) . '</td>';
    $pageContent .= '<td>' . $tx['borrow_time'] . '</td>';
    $pageContent .= '<td>' . ($tx['return_time'] ?? '-') . '</td>';
    $pageContent .= '<td>' . htmlspecialchars($tx['status']) . '</td>';
    $pageContent .= '</tr>';
}

$pageContent .= '</tbody></table>';
include 'layout.php';

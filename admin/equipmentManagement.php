<?php
require_once __DIR__ . '/../config/dbConnection.php';
require_once __DIR__ . '/../config/helpers.php';

$pageContent = '';
$conn = dbConnection();
// Fetch all equipments
$stmt = $conn->query("SELECT * FROM lab_equipments ORDER BY created_at DESC");
$equipments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageContent .= '<h3>Equipment Management</h3>';
$pageContent .= '<a href="actions/equipment_add.php" class="btn btn-primary mb-2">Add Equipment</a>';
$pageContent .= '<table class="table table-striped">';
$pageContent .= '<thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Quantity</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
foreach ($equipments as $eq) {
    $pageContent .= '<tr>';
    $pageContent .= '<td>' . $eq['equipment_id'] . '</td>';
    $pageContent .= '<td>' . htmlspecialchars($eq['item_name']) . '</td>';
    $pageContent .= '<td>' . htmlspecialchars($eq['category']) . '</td>';
    $pageContent .= '<td>' . $eq['quantity'] . '</td>';
    $pageContent .= '<td>' . htmlspecialchars($eq['status']) . '</td>';
    $pageContent .= '<td>';
    $pageContent .= '<a class="btn btn-sm btn-warning" href="actions/equipment_edit.php?id=' . $eq['equipment_id'] . '">Edit</a> ';
    $pageContent .= '<form method="post" action="actions/equipment_delete.php" class="d-inline">';
    $pageContent .= csrf_input_field();
    $pageContent .= '<input type="hidden" name="equipment_id" value="' . $eq['equipment_id'] . '">';
    $pageContent .= '<button class="btn btn-sm btn-danger" type="submit">Delete</button>';
    $pageContent .= '</form>';
    $pageContent .= '</td>';
    $pageContent .= '</tr>';
}
$pageContent .= '</tbody></table>';

include 'layout.php';

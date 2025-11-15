<?php
require_once __DIR__ . '/../config/session.php';         // starts session & provides csrf_token
require_once __DIR__ . '/../config/dbConnection.php';

$conn = db();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Equipment Management</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; }
        .container { display: flex; min-height: 100vh; }
        .sidebar {
            width: 230px; background: #222; padding: 20px; color: #fff;
        }
        .sidebar ul { list-style: none; padding: 0; margin: 0; }
        .sidebar li { margin: 18px 0; }
        .sidebar a { color: #fff; text-decoration: none; font-size: 16px; }
        .main { flex: 1; padding: 25px; }
        .add_btn {
            background: #2196F3; border: none; padding: 10px 16px;
            color: white; cursor: pointer; margin-bottom: 15px; border-radius: 4px;
        }
        table.display { width: 100%; }
        .action-btn { margin-right: 6px; }
    </style>

    <script>
        $(document).ready(function () {
            $('#equipmentTable').DataTable({
                // optional: adjust DataTables options here
            });
        });
    </script>
</head>
<body>
<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>ADMIN DASHBOARD</h2>
        <ul>
            <li><a href="equipmentManagement.php">Equipment Management</a></li>
            <li><a href="userManagement.php">User Management</a></li>
            <li><a href="transactionHistory.php">Transaction History</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main">
        <h2>Equipment Management</h2>

        <!-- Add page now lives under admin/ (update file accordingly) -->
        <a href="equipment_add.php"><button class="add_btn">Add Equipment</button></a>

        <table id="equipmentTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th style="min-width:160px">Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($equipments)): ?>
                    <?php foreach ($equipments as $eq): ?>
                        <tr>
                            <td><?= (int)$eq['equipment_id'] ?></td>
                            <td><?= htmlspecialchars($eq['item_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($eq['category'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= (int)$eq['quantity'] ?></td>
                            <td><?= htmlspecialchars($eq['status'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <!-- Edit: go to admin/equipment_edit.php?id=... -->
                                <button class="edit-btn action-btn"
                                    data-id="<?= (int)$eq['equipment_id'] ?>"
                                    data-name="<?= htmlspecialchars($eq['item_name'], ENT_QUOTES, 'UTF-8') ?>">
                                    Edit
                                </button>

                                <!-- Delete: handled by controllers/crud.php -->
                                <form method="POST" action="../controllers/crud.php" style="display:inline;">
                                    <!-- CSRF: session.php generates $_SESSION['csrf_token'] -->
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="action" value="delete_equipment">
                                    <input type="hidden" name="equipment_id" value="<?= (int)$eq['equipment_id'] ?>">
                                    <button type="button" class="delete-btn action-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>

        </table>

        <script>
            // Delete confirmation -> submit form to controllers/crud.php
            $(document).on('click', '.delete-btn', function () {
                const form = $(this).closest('form');

                Swal.fire({
                    title: "Confirm Delete",
                    text: "This action cannot be undone.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Delete",
                    cancelButtonText: "Cancel"
                }).then(result => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Edit: navigate to admin/equipment_edit.php?id={id}
            $(document).on('click', '.edit-btn', function () {
                let id = $(this).data("id");
                // Optionally confirm before navigating
                Swal.fire({
                    title: "Edit Equipment?",
                    text: "Proceed to the edit page for this equipment.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Proceed",
                    cancelButtonText: "Cancel"
                }).then(result => {
                    if (result.isConfirmed) {
                        window.location.href = `equipment_edit.php?id=${encodeURIComponent(id)}`;
                    }
                });
            });
        </script>
    </div>
</div>
</body>
</html>

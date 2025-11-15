<?php
require_once '../config/dbConnection.php';
require_once '../controllers/EquipmentController.php';
require_once '../config/helpers.php'; 
session_start();

$database = new Database();
$db = $database->getConnect();

$controller = new EquipmentController($db);
$equipments = $controller->listAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Management</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; }
        .container { display: flex; height: 100vh; }
        .sidebar {
            width: 230px; background: #222; padding: 20px; color: #fff;
        }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar li { margin: 18px 0; }
        .sidebar a {
            color: #fff; text-decoration: none; font-size: 16px;
        }
        .main {
            flex: 1; padding: 25px;
        }
        .add_btn {
            background: #2196F3; border: none; padding: 10px 16px;
            color: white; cursor: pointer; margin-bottom: 15px;
        }
    </style>

    <script>
        $(document).ready(function () {
            $('#equipmentTable').DataTable();
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

        <a href="actions/equipment_add.php">
            <button class="add_btn">Add Equipment</button>
        </a>

        <table id="equipmentTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($equipments as $eq): ?>
                    <tr>
                        <td><?= $eq['equipment_id'] ?></td>
                        <td><?= htmlspecialchars($eq['item_name']) ?></td>
                        <td><?= htmlspecialchars($eq['category']) ?></td>
                        <td><?= $eq['quantity'] ?></td>
                        <td><?= htmlspecialchars($eq['status']) ?></td>
                        <td>
                            <button class="edit-btn"
                                data-id="<?= $eq['equipment_id'] ?>"
                                data-name="<?= htmlspecialchars($eq['item_name']) ?>">
                                Edit
                            </button>

                            <form method="POST" action="actions/equipment_delete.php" style="display:inline;">
                                <?= csrf_input_field(); ?>
                                <input type="hidden" name="equipment_id" value="<?= $eq['equipment_id'] ?>">
                                <button type="button" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>

        <script>
            $(document).on('click', '.delete-btn', function () {
                const form = $(this).closest('form');

                Swal.fire({
                    title: "Confirm Delete",
                    text: "This action cannot be undone.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Delete"
                }).then(result => {
                    if (result.isConfirmed) form.submit();
                });
            });

            $(document).on('click', '.edit-btn', function () {
                let id = $(this).data("id");
                Swal.fire({
                    title: "Edit Equipment?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Proceed"
                }).then(result => {
                    if (result.isConfirmed)
                        window.location.href = `actions/equipment_edit.php?id=${id}`;
                });
            });
        </script>
    </div>
</div>

</body>
</html>

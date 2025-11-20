<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/dbConnection.php';


if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: ../public/login.php");
    exit;
}

if ($_SESSION['user_role'] === 'users') {
    header("Location: ../user/Home.php");
    exit;
}

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
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Sidebar CSS -->
    <link rel="stylesheet" href="../assets/adminlogout.css">
    
    <!-- Equipment Page CSS -->
    <link rel="stylesheet" href="../assets/AdminAsset/equipmentM.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            $('#equipmentTable').DataTable({
                // optional: adjust DataTables options here
            });
        });
    </script>
</head>
<body>

    <!-- Sidebar -->
    <?php include '../admin/logout.php' ?>

    <!-- Main Content -->
    <div class="main">
        <h2>Equipment Management</h2>

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
                                <button class="edit-btn action-btn"
                                    data-id="<?= (int)$eq['equipment_id'] ?>"
                                    data-name="<?= htmlspecialchars($eq['item_name'], ENT_QUOTES, 'UTF-8') ?>">
                                    Edit
                                </button>

                                <form method="POST" action="../controllers/crud.php" style="display:inline;">
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
            // Delete confirmation
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

            // Edit navigation
            $(document).on('click', '.edit-btn', function () {
                let id = $(this).data("id");
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
</body>
</html>

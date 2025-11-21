<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/dbConnection.php';

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: ../public/login.php");
    exit;
}

if ($_SESSION['user_role'] === 'user' || $_SESSION['user_role'] === '') {
    header("Location: ../user/Home.php");
    exit;
}

$conn = db();

// FETCH ALL EQUIPMENT DATA
$stmt = $conn->prepare("SELECT * FROM lab_equipments ORDER BY equipment_id DESC");
$stmt->execute();
$equipments = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Equipment Management</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

    <!-- Sidebar CSS -->
    <link rel="stylesheet" href="../assets/adminlogout.css">

    <!-- Equipment Page CSS -->
    <link rel="stylesheet" href="../assets/AdminAsset/equipmentM.css">

    <!-- Bootstrap CSS (required for modal) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            $('#equipmentTable').DataTable();
        });
    </script>



</head>

<body>
        
    <!-- Sidebar -->
    <?php include '../admin/logout.php' ?>

    <div class="main">
        <h2>Equipment Management</h2>

        <button class="add_btn btn btn-primary" data-toggle="modal" data-target="#addEquipmentModal">
            Add Equipment
        </button>

        <table id="equipmentTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>RFID Code</th>
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
                            <td><?= htmlspecialchars($eq['item_name']) ?></td>
                            <td><?= htmlspecialchars($eq['rfid_tag'] ?? '') ?></td>
                            <td><?= htmlspecialchars($eq['category'] ?? '') ?></td>
                            <td><?= (int)$eq['quantity'] ?></td>
                            <td><?= htmlspecialchars($eq['status'] ?? '') ?></td>

                            <td>
                                <button class="edit-btn action-btn"
                                    data-id="<?= (int)$eq['equipment_id'] ?>">
                                    Edit
                                </button>

                                <form method="POST" action="../controllers/crud.php" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
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
                    if (result.isConfirmed) form.submit();
                });
            });

            $(document).on('click', '.edit-btn', function () {
                let id = $(this).data("id");

                Swal.fire({
                    title: "Edit Equipment?",
                    text: "Proceed to the edit page.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Proceed"
                }).then(result => {
                    if (result.isConfirmed) {
                        window.location.href = `equipment_edit.php?id=${encodeURIComponent(id)}`;
                    }
                });
            });
        </script>
    </div>

    <!-- ADD EQUIPMENT MODAL -->
    <div class="modal fade" id="addEquipmentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form method="POST" action="../controllers/crud.php">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="action" value="add_equipment">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Equipment</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" class="form-control" name="item_name" required>
                        </div>

                        <div class="form-group">
                            <label>RFID Code:</label>
                            <input type="text" class="form-control" name="rfid_tag" required>
                        </div>

                        <div class="form-group">
                            <label>Category:</label>
                            <input type="text" class="form-control" name="category" required>
                        </div>

                        <div class="form-group">
                            <label>Quantity:</label>
                            <input type="number" class="form-control" name="quantity" min="1" required>
                        </div>

                        <div class="form-group">
                            <label>Status:</label>
                            <input type="text" class="form-control" name="status" required>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Equipment</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

</body>
</html>

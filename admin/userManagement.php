<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/dbConnection.php';


if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: ../public/login.php");
    exit;
}

if ($_SESSION['user_role'] === 'user' ||  $_SESSION['user_role'] === '' ) {
    header("Location: ../user/Home.php");
    exit;
}

$conn = db();

// Fetch all users
$stmt = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Sidebar CSS -->
    <link rel="stylesheet" href="../assets/adminlogout.css">

    <!-- User Management CSS -->
    <link rel="stylesheet" href="../assets//AdminAsset//userM.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <!-- Sidebar -->
    <?php include '../admin/logout.php' ?>

    <!-- Main Content -->
    <div class="main">
        <h2>User Management</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>SR Code</th>
                    <th>Full Name</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= (int)$user['user_id'] ?></td>
                        <td><?= htmlspecialchars($user['sr_code'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($user['account_status'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <?php if ($user['account_status'] === 'For Approval'): ?>
                                <!-- Approve -->
                                <form method="POST" action="../controllers/crud.php" class="d-inline">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="action" value="approve_user">
                                    <input type="hidden" name="user_id" value="<?= (int)$user['user_id'] ?>">
                                    <button type="button" class="btn btn-success btn-sm approve-btn">Approve</button>
                                </form>

                                <!-- Reject -->
                                <form method="POST" action="../controllers/crud.php" class="d-inline">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="action" value="reject_user">
                                    <input type="hidden" name="user_id" value="<?= (int)$user['user_id'] ?>">
                                    <button type="button" class="btn btn-danger btn-sm reject-btn">Reject</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <script>
            // Approve confirmation
            $(document).on('click', '.approve-btn', function () {
                const form = $(this).closest('form');
                Swal.fire({
                    title: "Approve User?",
                    text: "This user will be approved.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Approve",
                    cancelButtonText: "Cancel"
                }).then(result => {
                    if (result.isConfirmed) form.submit();
                });
            });

            // Reject confirmation
            $(document).on('click', '.reject-btn', function () {
                const form = $(this).closest('form');
                Swal.fire({
                    title: "Reject User?",
                    text: "This user will be removed.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Reject",
                    cancelButtonText: "Cancel"
                }).then(result => {
                    if (result.isConfirmed) form.submit();
                });
            });
        </script>

    </div>

</body>
</html>

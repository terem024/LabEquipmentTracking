<?php
require_once __DIR__ . '/../config/session.php';    // session + CSRF token
require_once __DIR__ . '/../config/dbConnection.php';

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

    <!-- Bootstrap (optional styling) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">

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
    </style>
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
</div>

</body>
</html>

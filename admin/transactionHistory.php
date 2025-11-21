<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/dbConnection.php';

$conn = db();


if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: ../public/login.php");
    exit;
}

if ($_SESSION['user_role'] === 'user' ||  $_SESSION['user_role'] === '' ) {
    header("Location: ../user/Home.php");
    exit;
}

// Fetch transactions with JOIN
$stmt = $conn->query("
    SELECT t.*, u.full_name, u.sr_code, e.item_name
    FROM transactions t
    JOIN users u ON t.user_id = u.user_id
    JOIN equipment e ON t.equipment_id = e.equipment_id
    ORDER BY t.borrow_time DESC
");
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>

    <!-- Bootstrap (optional styling) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">

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
            <?php include '../admin/logout.php' ?>

    <!-- Main Content -->
    <div class="main">
        <h2>Transaction History</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>SR Code</th>
                    <th>Equipment</th>
                    <th>Borrow Time</th>
                    <th>Return Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $tx): ?>
                    <tr>
                        <td><?= (int)$tx['transaction_id'] ?></td>
                        <td><?= htmlspecialchars($tx['full_name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($tx['sr_code'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($tx['item_name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $tx['borrow_time'] ?></td>
                        <td><?= $tx['return_time'] ?? '-' ?></td>
                        <td><?= htmlspecialchars($tx['status'], ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>

</body>
</html>

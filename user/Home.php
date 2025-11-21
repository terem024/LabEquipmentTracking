<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/dbConnection.php';


if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: ../public/login.php");
    exit;
}

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header("Location: ../admin/userManagement.php");
    exit;
}

$conn = db();


$userId = $_SESSION['user_id'] ?? null;
$fullName = 'User';

if ($userId) {
    $stmt = $conn->prepare("SELECT full_name FROM users WHERE id = :id");
    $stmt->execute([':id' => $userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {$fullName = $row['full_name'];
    }
}

$displayName = $_SESSION['user_name'] ?? $fullName;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body class="dashboard-body">
    <?php include __DIR__ . '/user_header.php'; ?>
<main class="dashboard-content">
    <div class="container mt-4">
        <div class="row">
            <?php
            // Fetch equipment from the database
            try {
                $stmt = $conn->prepare("SELECT item_name, quantity FROM lab_equipments");
                $stmt->execute();
                $equipmentList = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($equipmentList) {
                    foreach ($equipmentList as $equipment) {
                        ?>
                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <span class="fw-bold"><?= htmlspecialchars($equipment['item_name']); ?></span>
                                    <span class="badge bg-primary rounded-pill"><?= (int)$equipment['quantity']; ?></span>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p class="text-muted">No equipment available.</p>';
                }
            } catch (PDOException $e) {
                echo '<p class="text-danger">Error fetching equipment: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
            ?>
        </div>
    </div>
</main>


<script src="../config/cookies.js"></script>

</body>
</html>

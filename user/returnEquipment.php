<?php
include '../config/session.php';

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header("Location: ../admin/userManagement.php");
    exit;
}


$userId = $_SESSION['user_id'] ?? null;
$fullName = 'User';

if ($userId) {
    $stmt = $conn->prepare("SELECT full_name FROM users WHERE id = :id");
    $stmt->execute([':id' => $userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $fullName = $row['full_name'];
    }
}

$displayName = $_SESSION['user_name'] ?? $fullName;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Equipment</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="dashboard-body">

<header class="topbar">
    <h1>Lab Equipment Tracking</h1>

    <div class="admin-area">
        <span>Welcome, <?= htmlspecialchars($displayName); ?></span>
        <form method="post" class="logout-form" action="../public/logout.php">
            <input type="hidden" name="logout" value="1">
            <button type="submit" class="btn danger">Logout</button>
        </form>
    </div>
</header>

    <nav class="navbar">
        <a href="home.php" class="nav-link active">Dashboard</a>
        <a href="borrowEquipment.php" class="nav-link">Borrow</a>
        <a href="returnEquipment.php" class="nav-link">Return</a>
        <a href="userProfile.php" class="nav-link">User Profile</a>
    </nav>

    <main class="borrow-return-body">
        <div class="rfid-box">
            <h2>Return Equipment</h2>
            <p>Please scan the RFID of the equipment that you wish to return.</p>
        </div>
    </main>

</body>
</html>

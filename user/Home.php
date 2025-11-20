<?php
include '../config/session.php';

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: ../public/login.php");
    exit;
}

include '../config/dbConnection.php'; 

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
    <title>User Dashboard</title>
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

<main class="dashboard-content">
    <section class="welcome-box">
        <h2>Welcome to the Lab Equipment Tracking System</h2>
        <p>This platform helps you easily borrow and return laboratory equipment with RFID-based tracking.</p>
    </section>

    <section class="intro-grid">
        <div class="intro-card"><h3>📋 Borrow Equipment</h3><p>View and borrow available lab equipment.</p></div>
        <div class="intro-card"><h3>🔁 Return Equipment</h3><p>Quickly process returns and maintain records.</p></div>
        <div class="intro-card"><h3>👤 User Profile</h3><p>Manage your account and borrowing history.</p></div>
        <div class="intro-card"><h3>📊 System Overview</h3><p>Track activities and summaries in real time.</p></div>
    </section>
</main>

<script src="../config/cookies.js"></script>

</body>
</html>

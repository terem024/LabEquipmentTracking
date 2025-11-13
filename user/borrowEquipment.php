<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: UserLogin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Equipment</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="dashboard-body">

    <header class="topbar">
        <h1>Lab Equipment Tracking</h1>
        <div class="admin-area">
            <span>Welcome, User</span>
            <form method="post" class="logout-form" action="../public/logout.php">
                <input type="hidden" name="logout" value="1">
                <button type="submit" class="btn danger">Logout</button>
            </form>
        </div>
    </header>

    <nav class="navbar">
        <a href="Home.php" class="nav-link">Dashboard</a>
        <a href="borrowEquipment.php" class="nav-link active">Borrow</a>
        <a href="returnEquipment.php" class="nav-link">Return</a>
        <a href="userInfo.php" class="nav-link">User</a>
    </nav>

    <main class="borrow-return-body">
        <div class="rfid-box">
            <h2>Borrow Equipment</h2>
            <p>Please scan the RFID of the equipment that you wish to borrow.</p>
        </div>
    </main>

</body>
</html>

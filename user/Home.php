<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: UserLogin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
    header('Location: UserLogin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-body">

    <header class="topbar">
        <h1>Lab Equipment Tracking</h1>
        <div class="admin-area">
            <span>Welcome, User</span>
            <form method="post" class="logout-form">
                <input type="hidden" name="logout" value="1">
                <button type="submit" class="btn danger">Logout</button>
            </form>
        </div>
    </header>

    <nav class="navbar">
        <a href="Home.php" class="nav-link active">Dashboard</a>
        <a href="BorrowEquipment.php" class="nav-link">Borrow</a>
        <a href="ReturnEquipment.php" class="nav-link">Return</a>
        <a href="UserProfile.php" class="nav-link">User</a>
    </nav>

    <main class="dashboard-content">
        <section class="welcome-box">
            <h2>Welcome to the Lab Equipment Tracking System</h2>
            <p>This platform helps you easily borrow and return laboratory equipment with RFID-based tracking.</p>
        </section>

        <section class="intro-grid">
            <div class="intro-card">
                <h3>📋 Borrow Equipment</h3>
                <p>View and borrow available lab equipment with just a few clicks. Keep track of borrowed items easily.</p>
            </div>

            <div class="intro-card">
                <h3>🔁 Return Equipment</h3>
                <p>Quickly process returns to update the inventory and maintain accurate records.</p>
            </div>

            <div class="intro-card">
                <h3>👤 User Profile</h3>
                <p>Manage your account details and check your borrowing history anytime.</p>
            </div>

            <div class="intro-card">
                <h3>📊 System Overview</h3>
                <p>Stay informed with real-time tracking and summaries of your recent activities.</p>
            </div>
        </section>
    </main>

</body>
</html>

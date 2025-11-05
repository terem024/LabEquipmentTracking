<?php
session_start();

// Redirect if not logged in
if (empty($_SESSION['admin_logged_in'])) {
    header('Location: AdminLogin.php');
    exit;
}

// Handle logout
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
    header('Location: AdminLogin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-body">
    <header class="topbar">
        <h1>Lab Equipment Tracking</h1>
        <div class="admin-area">
            <span>Welcome, Admin</span>
            <form method="post" class="logout-form">
                <input type="hidden" name="logout" value="1">
                <button type="submit" class="btn danger">Logout</button>
            </form>
        </div>
    </header>

    <nav class="navbar">
        <a href="UserManagement.php" class="nav-link">User Management</a>
        <a href="EquipmentManagement.php" class="nav-link">Equipment Management</a>
        <a href="TransactionHistory.php" class="nav-link">Transaction History</a>
    </nav>

    <main class="dashboard-content">
        <div class="welcome-box">
            <h2>Dashboard Overview</h2>
            <p>Manage users, monitor equipment, and track transaction records all in one place.</p>
        </div>
    </main>
</body>
</html>

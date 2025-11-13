<?php
session_start();

include '../config/dbConnection.php';

if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: userManagement.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validUser = 'admin';
    $validPass = 'admin123';

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === $validUser && $password === $validPass) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header('Location: userManagement.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="login-body">
    <div class="login-box">
        <h2>Admin Login</h2>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="AdminLogin.php" method="post" id="loginForm">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required autocomplete="username">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">

            <div class="show-pass-row">
                <input type="checkbox" id="showPassword" onchange="togglePassword()">
                <label for="showPassword">Show Password</label>
            </div>

            <div class="actions">
                <input type="submit" value="Login" class="btn primary">
                <button type="button" class="btn secondary" onclick="exitApp()">Exit</button>
            </div>
        </form>
    </div>

    <script>
        function togglePassword() {
            const pass = document.getElementById('password');
            pass.type = pass.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>

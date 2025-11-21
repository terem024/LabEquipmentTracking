<?php
include '../config/session.php';
include '../config/dbConnection.php';
$conn = db();

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    // Redirect based on role
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: ../admin/Equiment/equipmentManagement.php");
    } else {
        header("Location: ../user/home.php");
    }
    exit;
}


$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $password = trim($_POST['password']);

    // Query database for user (works for both admin and regular users)
    $stmt = $conn->prepare("SELECT * FROM users WHERE full_name = :name LIMIT 1");
    $stmt->execute([':name' => $name]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $input_hash = hash("sha256", $password);

        if ($input_hash === $user['password_hash']) {
            // Set session variables
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'];

            // Store sr_code if available
            if (!empty($user['sr_code'])) {
                $_SESSION['sr_code'] = $user['sr_code'];
            }

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: ../admin/equipmentManagement.php");
            } else {
                header("Location: ../user/home.php");
            }
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/login.css">
</head>
<body class="login-body">

    <div class="split-wrapper">

        <!-- LEFT: Your existing login form -->
        <section class="left-panel">
        <div class="form-container">
            <form method="POST" action="">
            <h1>Login</h1>

            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <input type="text" name="name" placeholder="Name" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit">Login</button>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
            </form>
        </div>
        </section>

        <!-- RIGHT: White clean info panel -->
        <section class="right-panel">
        <div class="intro-box">
            <h1>Welcome!</h1>
            <h2>Laboratory Equipment Tracking System</h2>

            <p class="sdg-text">
            A centralized platform for monitoring, reserving, and managing laboratory equipment efficiently.
            Designed to help students and faculty ensure accessibility, accountability, and proper usage of lab tools.
            </p>

            <h2>Purpose</h2>
            <p class="sdg-text">
            Enhancing productivity, reducing equipment conflicts, and ensuring responsible use of university resources.
            </p>
        </div>
        </section>

    </div>

</body>
</html>

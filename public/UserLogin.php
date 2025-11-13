<?php
session_start();
include '../config/dbConnection.php';

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: ../user/Home.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $password = trim($_POST['password']);

    // 🔒 Check for admin credentials first
    if ($name === 'admin' && $password === 'admin123') {
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_name'] = 'Administrator';
        $_SESSION['user_role'] = 'admin';
        header("Location: ../admin/userManagement.php");
        exit;
    }

    // 🧠 Otherwise, check in database for normal users
    $stmt = $conn->prepare("SELECT * FROM users WHERE full_name = :name");
    $stmt->execute([':name' => $name]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'];

            header("Location: ../user/Home.php");
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
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="login-body">

  <main class="form-page">
    <div class="form-container">
      <form method="POST" action="">
        <h2>Login</h2>

        <?php if ($error): ?>
          <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <input type="text" name="name" placeholder="Name" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>
        <p>Don't have an account? <a href="UserRegister.php">Register here</a></p>
      </form>
    </div>
  </main>

</body>
</html>

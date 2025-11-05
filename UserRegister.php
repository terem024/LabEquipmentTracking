<?php
session_start();

// If already logged in, redirect to home
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: Home.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['Confirmpassword']);

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (empty($name) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Simulate account creation
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_name'] = $name;
        $success = "Registration successful! Redirecting...";
        header("refresh:2;url=Home.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Register</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">

  <div class="nav-menu">
    <a href="Home.php" class="nav-link">Home</a>
    <a href="BorrowEquipment.php" class="nav-link">Borrow</a>
    <a href="ReturnEquipment.php" class="nav-link">Return</a>
    <a href="UserInfo.php" class="nav-link">User</a>
  </div>

  <main class="form-page">
    <div class="form-container">
      <form method="POST" action="">
        <h2>Register</h2>

        <?php if ($error): ?>
          <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
          <div class="success" style="background:#d4edda;color:#155724;padding:10px;border-radius:6px;text-align:center;margin-bottom:10px;">
            <?php echo htmlspecialchars($success); ?>
          </div>
        <?php endif; ?>

        <input type="text" name="name" placeholder="Name" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="Confirmpassword" placeholder="Confirm Password" required>

        <button type="submit">Register</button>
        <p>Already have an account? <a href="UserLogin.php">Login here</a></p>
      </form>
    </div>
  </main>

</body>
</html>

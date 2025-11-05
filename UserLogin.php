<?php
session_start();

// If already logged in, redirect to home
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: Home.php");
    exit;
}

// Temporary hardcoded login (replace with DB query later)
$valid_user = "student";
$valid_pass = "12345";
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $password = trim($_POST['password']);

  $found = false;
  if (isset($_SESSION['users'])) {
    foreach ($_SESSION['users'] as $user) {
      if ($user['name'] === $name && $user['password'] === $password) {
        $found = true;
        break;
      }
    }
  }
  // Fallback to hardcoded account if no users or not found
  if ($found || ($name === $valid_user && $password === $valid_pass)) {
    $_SESSION['user_logged_in'] = true;
    $_SESSION['user_name'] = $name;
    header("Location: Home.php");
    exit;
  } else {
    $error = "Invalid name or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Login</title>
  <link rel="stylesheet" href="style.css">
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

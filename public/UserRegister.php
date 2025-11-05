<?php
  session_start();
include 'db_connect.php';

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

    if (empty($name) || empty($password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $check = $conn->prepare("SELECT * FROM users WHERE full_name = ?");
        $check->bind_param("s", $name);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $error = "Username already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $school_id = "S" . rand(1000, 9999);

            $stmt = $conn->prepare("INSERT INTO users (school_id, full_name, role, password_hash) VALUES (?, ?, 'student', ?)");
            $stmt->bind_param("sss", $school_id, $name, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_name'] = $name;
                $success = "Registration successful! Redirecting...";
                header("refresh:2;url=Home.php");
            } else {
                $error = "Error registering user: " . $conn->error;
            }
        }
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

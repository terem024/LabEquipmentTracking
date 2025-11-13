<?php
session_start();
include '../config/dbConnection.php';

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: ../user/Home.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['Confirmpassword']);

    $sr_code = trim($_POST['sr_code']);
    
    // Validate SR Code format (2 digits, hyphen, 5 digits)
    if (!preg_match('/^\d{2}-\d{5}$/', $sr_code)) {
        $error = "SR Code must be in format: XX-XXXXX (e.g., 21-12345)";
    } elseif (empty($name) || empty($password) || empty($sr_code)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if username or SR code already exists
        $check = $conn->prepare("SELECT * FROM users WHERE full_name = :name OR sr_code = :sr_code");
        $check->execute([':name' => $name, ':sr_code' => $sr_code]);
        $result = $check->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            if ($result['full_name'] === $name) {
                $error = "Username already exists.";
            } else {
                $error = "SR code already exists.";
            }
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (sr_code, full_name, role, password_hash) 
                                    VALUES (:sr_code, :full_name, 'student', :password_hash)");

            $inserted = $stmt->execute([
                ':sr_code' => $sr_code,
                ':full_name' => $name,
                ':password_hash' => $hashed_password
            ]);

            if ($inserted) {
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_name'] = $name;
                $success = "Registration successful! Redirecting...";
                header("refresh:2;url=../user/Home.php");
            } else {
                $error = "Error registering user.";
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
  <link rel="stylesheet" href="../assets/style.css">
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
        <input type="text" name="sr_code" placeholder="SR Code" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="Confirmpassword" placeholder="Confirm Password" required>

        <button type="submit">Register</button>
        <p>Already have an account? <a href="UserLogin.php">Login here</a></p>
      </form>
    </div>
  </main>

</body>
</html>

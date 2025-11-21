<?php
include '../config/session.php';
include '../config/dbConnection.php';
$conn = db();

  if ($_SESSION['user_role'] === 'user' ) {
      header("Location: ../user/Home.php");
      exit;
  }else{
    header("Location: ../admin/equipmentManagement.php");
  }

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['Confirmpassword']);
    $sr_code = trim($_POST['sr_code']);

    // Validate SR Code
    if (!preg_match('/^\d{2}-\d{5}$/', $sr_code)) {
        $error = "SR Code must be in format: XX-XXXXX (e.g., 21-12345)";
    } elseif (empty($name) || empty($password) || empty($sr_code)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check duplicates
        $check = $conn->prepare("SELECT * FROM users 
                                WHERE full_name = :name OR sr_code = :sr_code");
        $check->execute([':name' => $name, ':sr_code' => $sr_code]);
        $result = $check->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            if ($result['full_name'] === $name) {
                $error = "Username already exists.";
            } else {
                $error = "SR code already exists.";
            }
        } else {
            // SHA256 hash
            $password_hash = hash("sha256", $password);

            // Insert user
            $stmt = $conn->prepare("INSERT INTO users 
                (sr_code, full_name,password_hash) 
                VALUES (:sr_code, :full_name, :password_hash)");

            $inserted = $stmt->execute([
                ':sr_code' => $sr_code,
                ':full_name' => $name,
                ':password_hash' => $password_hash
            ]);

            if ($inserted) {
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_name'] = $name;
                // store sr_code in session for easy access
                $_SESSION['sr_code'] = $sr_code;
                $success = "Registration successful! Redirecting...";
                header("refresh:2;url=../user/home.php");
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
  <link rel="stylesheet" href="../assets/login.css">
</head>
<body class="login-body">

    <div class="split-wrapper">

        <!-- LEFT: Your existing register form -->
        <section class="left-panel">
        <div class="form-container">
            <form method="POST" action="">
            <h2>Register</h2>

            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success">
                <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="sr_code" placeholder="SR Code (e.g., 21-12345)" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="Confirmpassword" placeholder="Confirm Password" required>

            <button type="submit">Register</button>
            <p>Already have an account? <a href="login.php">Login here</a></p>
            </form>
        </div>
        </section>

        <!-- RIGHT: White intro panel -->
        <section class="right-panel">
        <div class="intro-box">
            <h1 class="sdg-title">Create Your Account</h1>
            <p class="sdg-text">
            Register now and gain access to the laboratory equipment tracking system.
            Borrow tools responsibly, manage returns, and keep track of your activity with ease.
            </p>

            <p class="sdg-title">Why Register?</p>
            <p class="sdg-text">
            Improve workflow, reduce equipment misplacement, and support efficient lab operations.
            </p>
        </div>
        </section>

    </div>

</body>
</html>


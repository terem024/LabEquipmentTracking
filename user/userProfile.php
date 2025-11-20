<?php
include '../config/session.php';

// If not logged in, redirect to login
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: ../public/login.php");
    exit;
}

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header("Location: ../admin/userManagement.php");
    exit;
}


include '../config/dbConnection.php';
$conn = db();

// Pull from session (preferred)
$fullName = $_SESSION['user_name'] ?? '';
$srCode   = $_SESSION['sr_code'] ?? '';

// If missing, fetch from DB
if (empty($fullName) || empty($srCode)) {

    $userId = $_SESSION['user_id'] ?? null;

    if ($userId) {
        $stmt = $conn->prepare("SELECT full_name, sr_code FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $fullName = $row['full_name'];
            $srCode   = $row['sr_code'];

            // Save updated values back to session
            $_SESSION['user_name'] = $fullName;
            $_SESSION['sr_code']   = $srCode;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>User Profile</title>
  <link rel="stylesheet" href="../assets/style.css" />
</head>

<body class="dashboard-body">

<header class="topbar">
  <h1>Lab Equipment Tracking</h1>

  <div class="admin-area">
    <span>Welcome, <?= htmlspecialchars($fullName); ?></span>
    <form method="post" class="logout-form" action="../public/logout.php">
      <input type="hidden" name="logout" value="1" />
      <button type="submit" class="btn danger">Logout</button>
    </form>
  </div>
</header>

<nav class="navbar">
    <a href="home.php" class="nav-link">Dashboard</a>
    <a href="borrowEquipment.php" class="nav-link">Borrow</a>
    <a href="returnEquipment.php" class="nav-link">Return</a>
    <a href="userProfile.php" class="nav-link active">User Profile</a>
</nav>

<main>
  <div class="profile-container">
    <h2>Your Profile</h2>

    <div class="profile-row">
      <label class="profile-label">Full Name</label>
      <div class="profile-value"><?= htmlspecialchars($fullName); ?></div>
    </div>

    <div class="profile-row">
      <label class="profile-label">SR Code</label>
      <div class="profile-value"><?= htmlspecialchars($srCode); ?></div>
    </div>

  </div>
</main>

</body>
</html>

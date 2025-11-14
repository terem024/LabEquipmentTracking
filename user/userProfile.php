<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    echo "Debug: user_logged_in is not set or false.";
    header("Location: ../public/Login.php");
    exit;
}

include '../config/dbConnection.php';

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    echo "Debug: user_id is not set.";
    header("Location: ../public/Login.php");
    exit;
}

$stmt = $conn->prepare("SELECT id, sr_code, full_name, role FROM users WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $userId]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    echo "Debug: User not found in the database.";
    exit;
}

$dbUserId = $row['id'];
// Prefer session values when available (session set at login/register)
$dbSrCode = $_SESSION['sr_code'] ?? ($row['sr_code'] ?? '');
$dbFullName = $_SESSION['user_name'] ?? ($row['full_name'] ?? '');
$dbUserType = $_SESSION['user_role'] ?? ($row['role'] ?? '');
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
    <span>Welcome, <?= htmlspecialchars($dbFullName); ?></span>
    <form method="post" class="logout-form" action="../public/logout.php">
      <input type="hidden" name="logout" value="1" />
      <button type="submit" class="btn danger">Logout</button>
    </form>
  </div>
</header>

<nav class="navbar">
  <a href="/LabEquipmentTracking/user/Home.php" class="nav-link">Dashboard</a>
  <a href="/LabEquipmentTracking/user/borrowEquipment.php" class="nav-link">Borrow</a>
  <a href="/LabEquipmentTracking/user/returnEquipment.php" class="nav-link">Return</a>
  <a href="/LabEquipmentTracking/user/userProfile.php" class="nav-link">User Profile</a>
</nav>

<main>
  <div class="profile-container">
    <h2>Your Profile</h2>

    <div class="profile-row">
      <label class="profile-label">User ID</label>
      <div class="profile-value"><?= htmlspecialchars($dbUserId); ?></div>
    </div>

    <div class="profile-row">
      <label class="profile-label">Full Name</label>
      <div class="profile-value"><?= htmlspecialchars($dbFullName); ?></div>
    </div>

    <div class="profile-row">
      <label class="profile-label">SR Code</label>
      <div class="profile-value"><?= htmlspecialchars($dbSrCode); ?></div>
    </div>

    <div class="profile-row">
      <label class="profile-label">User Type</label>
      <div class="profile-value"><?= htmlspecialchars(ucfirst($dbUserType)); ?></div>
    </div>
  </div>
</main>

</body>
</html>

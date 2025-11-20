<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/AdminAsset/adminlogout.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<?php
// Handle logout if clicked
if (isset($_GET['logout'])) {
    session_start();
    $_SESSION = array();
    
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    session_destroy();
    header("Location: ../public/login.php");
    exit;
}
?>

    <div class="sidebar" id="sidebar">
    <button class="toggle-btn" id="toggleBtn">
        <i class="bi bi-list"></i>
    </button>
    
    <h2 class="sidebar-title">ADMIN DASHBOARD</h2>
    
    <ul class="sidebar-menu">
        <li>
            <a href="equipmentManagement.php">
                <span class="icon"><i class="bi bi-box-seam"></i></span>
                <span class="text">Equipment Management</span>
            </a>
        </li>
        <li>
            <a href="userManagement.php">
                <span class="icon"><i class="bi bi-people"></i></span>
                <span class="text">User Management</span>
            </a>
        </li>
        <li>
            <a href="transactionHistory.php">
                <span class="icon"><i class="bi bi-graph-up"></i></span>
                <span class="text">Transaction History</span>
            </a>
        </li>
    </ul>
    
    <div class="sidebar-footer">
        <a href="?logout=true" class="logout-btn">
            <span class="icon"><i class="bi bi-box-arrow-left"></i></span>
            <span class="text">Logout</span>
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleBtn');
    
    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
    });
});
</script>

</body>
</html>

<nav class="navbar navbar-expand navbar-light bg-white border-bottom">
    <div class="container-fluid">
        <span class="navbar-text">
            Admin: <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Unknown') ?>
        </span>
        <a href="../config/session.php?logout=1" class="btn btn-outline-danger btn-sm ms-auto">Logout</a>
    </div>
</nav>

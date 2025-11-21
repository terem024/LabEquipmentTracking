<header class="bg-white bg-opacity-75 shadow-sm py-2 sticky-top" style="backdrop-filter: blur(6px);">
    <div class="container-fluid px-3">
        <div class="d-flex justify-content-between align-items-center">

        <!-- Title -->
        <span class="h5 fw-bold text-danger text-uppercase mb-0">Lab Equipment Tracking</span>

        <!-- Hamburger for Mobile -->
        <button class="navbar-toggler d-md-none btn btn-outline-danger ms-auto" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Desktop Navbar -->
        <nav class="d-none d-md-flex gap-3 ms-3">
            <a href="http://localhost/LabEquipmentTracking/user/home.php" class="nav-link text-danger fw-semibold">Dashboard</a>
            <a href="http://localhost/LabEquipmentTracking/user/borrowEquipment.php" class="nav-link text-danger fw-semibold">Borrow</a>
            <a href="http://localhost/LabEquipmentTracking/user/returnEquipment.php" class="nav-link text-danger fw-semibold">Return</a>
            <a href="http://localhost/LabEquipmentTracking/user/userProfile.php" class="nav-link text-danger fw-semibold">Profile</a>
        </nav>

        <!-- User Info + Logout (desktop only) -->
        <div class="d-none d-md-flex align-items-center gap-2 ms-3">
            <div class="p-1 px-3 rounded-pill bg-light shadow-sm">
            <span class="fw-bold text-danger"><?= htmlspecialchars($displayName); ?></span>
            </div>
            <form method="post" action="../public/logout.php">
            <input type="hidden" name="logout" value="1">
            <button type="submit" class="btn btn-danger btn-sm rounded-pill ms-2">Logout</button>
            </form>
        </div>
        </div>
    </div>

    <!-- Offcanvas Mobile Menu -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
        <div class="offcanvas-header flex-column align-items-start pt-3">
        <!-- User Name at Top -->
        <span class="fw-bold text-danger mb-1" style="font-size:1.1rem;">Welcome <?= htmlspecialchars($displayName); ?></span>
        <h5 class="offcanvas-title text-danger mt-2" id="offcanvasMenuLabel">Menu</h5>
        <button type="button" class="btn-close ms-auto mt-2" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
        <nav class="nav flex-column gap-2">
            <a href="http://localhost/LabEquipmentTracking/user/home.php" class="nav-link text-danger fw-semibold">Dashboard</a>
            <a href="http://localhost/LabEquipmentTracking/user/borrowEquipment.php" class="nav-link text-danger fw-semibold">Borrow</a>
            <a href="http://localhost/LabEquipmentTracking/user/returnEquipment.php" class="nav-link text-danger fw-semibold">Return</a>
            <a href="http://localhost/LabEquipmentTracking/user/userProfile.php" class="nav-link text-danger fw-semibold">Profile</a>
        </nav>
        <form method="post" action="../public/logout.php" class="mt-3">
            <input type="hidden" name="logout" value="1">
            <button type="submit" class="btn btn-danger btn-sm rounded-pill">Logout</button>
        </form>
        </div>
    </div>
</header>

<!-- Bootstrap JS required for offcanvas and collapse -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

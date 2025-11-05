<?php
require_once __DIR__ . '/../config/helpers.php';

if (!hasFlash()) return;

$flash = getFlash();
$type = $flash['type'];      // success, danger, warning, info
$message = $flash['message'];
?>

<div style="
    position: fixed;
    top: 56px; /* BELOW NAVBAR */
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    width: 100%;
    max-width: 600px;
">
    <div class="alert alert-<?php echo $type; ?> alert-dismissible fade show shadow" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>

<?php
if (!function_exists('getFlash')) {
    require_once __DIR__ . '/../config/helpers.php';
}

$flash = getFlash();
?>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if ($flash): ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const type = "<?= $flash['type']; ?>";
    const message = "<?= $flash['message']; ?>";

    // Map flash types to Bootstrap-style colors & icons
    const iconMap = {
        success: { icon: "success", bg: "#198754" }, // Bootstrap success green
        danger: { icon: "error", bg: "#dc3545" },    // Bootstrap danger red
        warning: { icon: "warning", bg: "#ffc107" }, // Bootstrap warning yellow
        info: { icon: "info", bg: "#0dcaf0" }        // Bootstrap info blue
    };

    const toastConfig = iconMap[type] || iconMap.info;

    Swal.fire({
        toast: true,
        position: "top",
        icon: toastConfig.icon,
        title: message,
        showConfirmButton: false,
        timer: 3000,
        background: toastConfig.bg,
        color: "#fff",
        timerProgressBar: true
    });
});
</script>
<?php endif; ?>

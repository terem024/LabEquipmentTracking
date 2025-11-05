<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- SESSION SECURITY SETTINGS ---
$SESSION_TIMEOUT = 600; // 10 minutes

if (!function_exists('setFlash')) {
    function setFlash($type, $message) {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }
}

if (isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > $SESSION_TIMEOUT) {
        session_unset();
        session_destroy();
        session_start();
        setFlash('warning', 'You were logged out due to inactivity.');
        header("Location: ../login.php");
        exit();
    }
}
$_SESSION['last_activity'] = time();

if (isset($_SESSION['client_fingerprint'])) {
    $currentFingerprint = $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'];

    if ($_SESSION['client_fingerprint'] !== $currentFingerprint) {
        session_unset();
        session_destroy();
        session_start();
        setFlash('warning', 'You have been logged out for security reasons. Please sign in again.');
        header("Location: ../login.php");
        exit();
    }
} else {
    $_SESSION['client_fingerprint'] = $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'];
}
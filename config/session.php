<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



// Redirect to login if no session role
$currentPage = basename($_SERVER['PHP_SELF']);

if ($currentPage !== 'Login.php' && !isset($_SESSION['role'])) {
    header("Location: ../public/Login.php");
    exit();
}

// Role-based access control functions
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isUser() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'user';
}

function checkAccess($allowedRoles = []) {
    if (!in_array($_SESSION['role'], $allowedRoles)) {
        header("HTTP/1.1 403 Forbidden");
        echo "Access denied.";
    }
}





$SESSION_TIMEOUT = 600; // 10 minutes inactivity logout

// Flash message function
if (!function_exists('setFlash')) {
    function setFlash($type, $message) {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }
}


/* ------------------------------
   AUTO-LOGOUT ON INACTIVITY
------------------------------ */
if (isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > $SESSION_TIMEOUT) {
        session_unset();
        session_destroy();
        session_start();
        setFlash('warning', 'You were logged out due to inactivity.');
        header("Location: ../public/login.php");
        exit();
    }
}
$_SESSION['last_activity'] = time();

/* ------------------------------
   CLIENT FINGERPRINT VALIDATION
------------------------------ */
if (isset($_SESSION['client_fingerprint'])) {
    $currentFingerprint = $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'];

    if ($_SESSION['client_fingerprint'] !== $currentFingerprint) {
        session_unset();
        session_destroy();
        session_start();
        setFlash('warning', 'You have been logged out for security reasons. Please sign in again.');
        header("Location: ../public/login.php");
        exit();
    }

} else {
    $_SESSION['client_fingerprint'] = $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'];
}

/* ------------------------------
   CSRF TOKEN (SECURE & GLOBAL)
------------------------------ */
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

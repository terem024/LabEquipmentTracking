<?php
// Central logout endpoint to reliably destroy session and remove cookie across paths
session_start();

// Unset all session variables
$_SESSION = array();

// If there's a session cookie, remove it for the root path to be safe
if (ini_get('session.use_cookies')) {
    // remove cookie for root path so it clears regardless of folder
    setcookie(session_name(), '', time() - 42000, '/');
}

// Destroy the session
session_destroy();

// Redirect to the public login page
header('Location: UserLogin.php');
exit;

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Set a flash message to show on next page load
 * @param string $type  success|danger|warning|info
 * @param string $message
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Check if a flash message exists
 * @return bool
 */
function hasFlash() {
    return isset($_SESSION['flash']);
}

/**
 * Get and remove flash message
 * @return array|null
 */
function getFlash() {
    if (!hasFlash()) return null;

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']); // remove after one display
    return $flash;
}

<?php
// config/helpers.php
// Session must be started for flash & CSRF to work
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ---------------------------
   FLASH MESSAGE HELPERS
   --------------------------- */

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

/* ---------------------------
   CSRF TOKEN HELPERS (T-Session, L32)
   --------------------------- */

/**
 * Generate a CSRF token (32 bytes => 64 hex chars) and store it in session.
 * Returns the token string.
 * Idempotent: if a token already exists for the session it will return it.
 *
 * @return string
 * @throws Exception if random_bytes fails
 */
function generate_csrf_token(): string {
    // if token already exists for session, reuse (T-Session lifetime)
    if (!isset($_SESSION['csrf_token'])) {
        $bytes = random_bytes(32); // L32
        $_SESSION['csrf_token'] = bin2hex($bytes); // 64-hex chars
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify a given CSRF token against the session-stored token.
 * Returns true if valid, false otherwise.
 *
 * @param string|null $token
 * @return bool
 */
function verify_csrf_token(?string $token): bool {
    if (empty($token) || !isset($_SESSION['csrf_token'])) {
        return false;
    }
    // timing-safe comparison
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Echo a hidden input field for CSRF token to include in forms.
 * Usage: <?php echo csrf_input_field(); ?>
 *
 * @return string
 */
function csrf_input_field(): string {
    $token = generate_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Require CSRF to be valid for POST requests. If invalid, set flash and redirect back (F2).
 * Call this at the top of action scripts that process forms.
 *
 * @param string|null $token The token to verify (usually $_POST['csrf_token'])
 * @return void (exits on failure)
 */
function require_valid_csrf_and_post(?string $token): void {
    // Only protect POST requests by default
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        setFlash('danger', 'Invalid request method.');
        safe_redirect_back();
    }

    if (!verify_csrf_token($token)) {
        setFlash('danger', 'Invalid request. Please try again.');
        safe_redirect_back();
    }
}

/* ---------------------------
   HELPER: safe_redirect_back() used by CSRF failure handler (F2)
   --------------------------- */

/**
 * Redirect back to HTTP_REFERER if present, else redirect to login page.
 * Uses a header redirect and exits. Callers should not output before calling.
 */
function safe_redirect_back(): void {
    // Prefer referer if it is same-origin-ish; basic check to avoid open-redirects
    $fallback = '/login.php';
    $referer = $_SERVER['HTTP_REFERER'] ?? null;

    if ($referer) {
        // Basic same-host check to reduce open-redirect risk
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $referer_host = parse_url($referer, PHP_URL_HOST);
        if ($referer_host === $host) {
            header('Location: ' . $referer);
            exit;
        }
    }

    header('Location: ' . $fallback);
    exit;
}

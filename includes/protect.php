<?php
/**
 * Authentication middleware for protected pages.
 * Include this file at the top of any page that requires login.
 */

require_once dirname(__DIR__) . '/includes/functions.php';

// Sessions are required for auth checks.
start_session();

// Extra safety: enforce basic inactivity timeout.
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
    // Clear session if expired so stale sessions cannot be reused.
    $_SESSION = [];
    session_destroy();
    redirect('/pages/login.php?error=session_expired');
}

if (!is_logged_in()) {
    // Redirect unauthenticated users to login instead of exposing protected pages.
    redirect('/pages/login.php?error=login_required');
}

// Refresh activity timestamp after successful validation.
$_SESSION['last_activity'] = time();


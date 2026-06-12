<?php
/**
 * Authentication middleware for protected pages.
 *
 * SECURITY WHY:
 * Protected pages should not trust that a browser is logged in.
 * We verify session data on every request, then redirect if missing.
 */

require_once dirname(__DIR__) . '/config.php';
require_once __DIR__ . '/functions.php';

start_session();

if (!is_logged_in()) {
    redirect('/pages/login.php?message=' . urlencode('Please log in to continue.'));
}
?>

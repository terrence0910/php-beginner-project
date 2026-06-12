<?php
require_once dirname(__DIR__) . '/includes/protect.php';

// Clear session values and destroy current session.
logout_user();

// Explicitly clear session cookie in browser.
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

redirect('/index.php?message=' . urlencode('You have been logged out successfully.'));


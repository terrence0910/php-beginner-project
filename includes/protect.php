<?php
/**
 * Authentication guard for protected pages.
 */

require_once dirname(__DIR__) . '/config.php';
require_once INCLUDES_PATH . '/functions.php';

start_session();

if (!is_logged_in()) {
    // Security: stop unauthenticated access to task and account pages.
    redirect('/pages/login.php?error=' . urlencode('Please log in to continue.'));
}

<?php
require_once dirname(__DIR__) . '/includes/protect.php';

// SECURITY WHY:
// Clearing session data and destroying session prevents session reuse after logout.
$_SESSION = [];
session_destroy();

redirect('/index.php?message=' . urlencode('You have been logged out successfully.'));
?>

<?php
require_once dirname(__DIR__) . '/includes/protect.php';

logout_user();
redirect('/pages/login.php?success=' . urlencode('You have been logged out.'));

<?php
if (!defined('BASE_PATH')) {
    require_once dirname(__DIR__) . '/config.php';
}

start_session();
$current_user = is_logged_in() ? get_logged_in_user() : null;
$title = isset($page_title) ? $page_title . ' | ' . APP_NAME : APP_NAME;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo escape_output($title); ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f8f9fb; }
        nav { background: #1f2937; color: #fff; padding: 12px 16px; display: flex; gap: 16px; flex-wrap: wrap; align-items: center; }
        nav a { color: #fff; text-decoration: none; }
        nav .grow { flex: 1; }
        main { max-width: 900px; margin: 20px auto; padding: 0 16px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; margin-bottom: 16px; }
        .success { color: #065f46; background: #d1fae5; border: 1px solid #6ee7b7; padding: 8px; border-radius: 6px; }
        .error { color: #7f1d1d; background: #fee2e2; border: 1px solid #fca5a5; padding: 8px; border-radius: 6px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 8px; text-align: left; vertical-align: top; }
        .status-pending { color: #92400e; }
        .status-completed { color: #065f46; }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; }
        input[type="text"], input[type="email"], input[type="password"], textarea, select { width: 100%; padding: 8px; margin: 4px 0 10px; box-sizing: border-box; }
        button { padding: 8px 12px; cursor: pointer; }
        .muted { color: #6b7280; }
    </style>
</head>
<body>
<nav>
    <strong><?php echo escape_output(APP_NAME); ?></strong>
    <a href="/index.php">Home</a>
    <?php if ($current_user): ?>
        <a href="/pages/dashboard.php">Dashboard</a>
    <?php endif; ?>
    <span class="grow"></span>
    <?php if ($current_user): ?>
        <span>Signed in as <?php echo escape_output($current_user['username']); ?></span>
        <a href="/pages/logout.php">Logout</a>
    <?php else: ?>
        <a href="/pages/login.php">Login</a>
        <a href="/pages/register.php">Register</a>
    <?php endif; ?>
</nav>
<main>

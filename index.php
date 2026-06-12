<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

start_session();

if (is_logged_in()) {
    redirect('/pages/dashboard.php');
}

$message = isset($_GET['message']) ? sanitize_input($_GET['message']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo escape_output(APP_NAME); ?> - Welcome</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f7f7fb; color: #222; }
        .container { max-width: 720px; margin: 40px auto; background: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        nav { margin-bottom: 20px; }
        nav a { margin-right: 12px; text-decoration: none; color: #0056b3; }
        .message { background: #e6f7ff; border: 1px solid #91d5ff; padding: 10px; border-radius: 6px; margin-bottom: 16px; }
        .actions { display: flex; gap: 12px; flex-wrap: wrap; }
        .btn { display: inline-block; padding: 10px 16px; border-radius: 6px; text-decoration: none; color: #fff; }
        .btn-login { background: #0056b3; }
        .btn-register { background: #198754; }
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <a href="/index.php">Home</a>
            <a href="/pages/login.php">Login</a>
            <a href="/pages/register.php">Register</a>
        </nav>

        <?php if ($message !== ''): ?>
            <div class="message" role="status"><?php echo escape_output($message); ?></div>
        <?php endif; ?>

        <h1>Welcome to <?php echo escape_output(APP_NAME); ?></h1>
        <p>This is Step 2 of your beginner project. Please log in or create an account to continue.</p>

        <div class="actions">
            <a class="btn btn-login" href="/pages/login.php">Log In</a>
            <a class="btn btn-register" href="/pages/register.php">Create Account</a>
        </div>
    </div>
</body>
</html>

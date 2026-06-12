<?php
require_once __DIR__ . '/includes/functions.php';

start_session();

if (is_logged_in()) {
    // Step 3 target: dashboard page placeholder path.
    redirect('/pages/dashboard.php');
}

$message = '';
if (isset($_GET['message'])) {
    $message = sanitize_input($_GET['message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo escape_output(APP_NAME); ?> - Welcome</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f5f7fb; color: #1f2937; }
        .container { max-width: 900px; margin: 0 auto; padding: 1rem; }
        .nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .btn { display: inline-block; padding: .7rem 1rem; border-radius: 8px; text-decoration: none; margin-right: .5rem; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-secondary { background: #fff; border: 1px solid #d1d5db; color: #111827; }
        .card { background: #fff; border-radius: 10px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        .message { background: #dcfce7; border: 1px solid #86efac; padding: .75rem; border-radius: 8px; margin-bottom: 1rem; }
        @media (max-width: 640px) { .nav { flex-direction: column; align-items: flex-start; gap: .75rem; } }
    </style>
</head>
<body>
    <div class="container">
        <nav class="nav" aria-label="Main navigation">
            <strong><?php echo escape_output(APP_NAME); ?></strong>
            <div>
                <a class="btn btn-secondary" href="/pages/login.php">Login</a>
                <a class="btn btn-primary" href="/pages/register.php">Register</a>
            </div>
        </nav>

        <?php if (!empty($message)): ?>
            <p class="message"><?php echo escape_output($message); ?></p>
        <?php endif; ?>

        <section class="card">
            <h1>Welcome to the PHP Beginner Project</h1>
            <p>This is the public home page. Create an account or sign in to continue.</p>
            <p>
                <a class="btn btn-primary" href="/pages/register.php">Get Started</a>
                <a class="btn btn-secondary" href="/pages/login.php">I Already Have an Account</a>
            </p>
        </section>
    </div>
</body>
</html>


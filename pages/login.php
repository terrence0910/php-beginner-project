<?php
require_once dirname(__DIR__) . '/includes/functions.php';

start_session();

if (is_logged_in()) {
    redirect('/pages/dashboard.php');
}

$error_message = '';
$success_message = '';

if (isset($_GET['registered']) && $_GET['registered'] === '1') {
    $success_message = 'Registration successful! You can now login.';
}

if (isset($_GET['error'])) {
    $error_map = [
        'login_required' => 'Please login to continue.',
        'session_expired' => 'Your session expired. Please login again.',
    ];
    if (isset($error_map[$_GET['error']])) {
        $error_message = $error_map[$_GET['error']];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_or_email = sanitize_input($_POST['username_or_email'] ?? '');
    $password = isset($_POST['password']) ? (string) $_POST['password'] : '';

    if ($username_or_email === '' || $password === '') {
        $error_message = 'Please enter both username/email and password.';
    } else {
        $user = authenticate_user($username_or_email, $password);
        if ($user) {
            // Regenerate session ID to prevent session fixation after login.
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['last_activity'] = time();
            redirect('/index.php');
        } else {
            $error_message = 'Invalid credentials. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo escape_output(APP_NAME); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f5f7fb; color: #1f2937; }
        .container { max-width: 500px; margin: 3rem auto; padding: 1rem; }
        .card { background: #fff; border-radius: 10px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        label { display: block; margin-top: .75rem; font-weight: 600; }
        input { width: 100%; padding: .65rem; margin-top: .25rem; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; }
        button { margin-top: 1rem; width: 100%; padding: .75rem; border: 0; border-radius: 8px; background: #2563eb; color: #fff; font-weight: 600; cursor: pointer; }
        .error { background: #fee2e2; border: 1px solid #fca5a5; padding: .75rem; border-radius: 8px; margin-bottom: 1rem; }
        .success { background: #dcfce7; border: 1px solid #86efac; padding: .75rem; border-radius: 8px; margin-bottom: 1rem; }
        .links { margin-top: 1rem; text-align: center; }
    </style>
</head>
<body>
<main class="container">
    <section class="card" aria-labelledby="login-heading">
        <h1 id="login-heading">Login</h1>

        <?php if ($error_message !== ''): ?>
            <p class="error"><?php echo escape_output($error_message); ?></p>
        <?php endif; ?>
        <?php if ($success_message !== ''): ?>
            <p class="success"><?php echo escape_output($success_message); ?></p>
        <?php endif; ?>

        <form method="POST" action="/pages/login.php" novalidate>
            <label for="username_or_email">Username or Email</label>
            <input id="username_or_email" name="username_or_email" type="text" required>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>

            <button type="submit">Login</button>
        </form>

        <p class="links">
            Don't have an account?
            <a href="/pages/register.php">Register here</a>
        </p>
        <p class="links"><a href="/index.php">Back to Home</a></p>
    </section>
</main>
</body>
</html>


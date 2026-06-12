<?php
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

start_session();

if (is_logged_in()) {
    redirect('/pages/dashboard.php');
}

$errors = [];
$message = isset($_GET['message']) ? sanitize_input($_GET['message']) : '';
$login_input = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_input = isset($_POST['username']) ? sanitize_input($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($login_input === '' || trim($login_input) === '') {
        $errors[] = 'Username or email is required.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if (empty($errors)) {
        $user = authenticate_user($login_input, $password);

        if ($user) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            redirect('/pages/dashboard.php');
        } else {
            $errors[] = 'Invalid username/email or password.';
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
        body { font-family: Arial, sans-serif; margin: 0; background: #f7f7fb; color: #222; }
        .container { max-width: 420px; margin: 40px auto; background: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input { width: 100%; padding: 10px; margin-top: 6px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; }
        button { margin-top: 16px; width: 100%; background: #0056b3; color: #fff; border: 0; padding: 10px; border-radius: 6px; cursor: pointer; }
        .alert { padding: 10px; border-radius: 6px; margin-bottom: 12px; }
        .alert-error { background: #fdecea; border: 1px solid #f5c2c7; color: #842029; }
        .alert-success { background: #e6f7ff; border: 1px solid #91d5ff; }
        a { color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <p><a href="/index.php">← Back to home</a></p>

        <?php if ($message !== ''): ?>
            <div class="alert alert-success" role="status"><?php echo escape_output($message); ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error" role="alert">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo escape_output($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="/pages/login.php" novalidate>
            <label for="username">Username or Email</label>
            <input type="text" id="username" name="username" value="<?php echo escape_output($login_input); ?>" required autocomplete="username">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">

            <button type="submit">Log In</button>
        </form>

        <p>Don't have an account? <a href="/pages/register.php">Register here</a>.</p>
    </div>
</body>
</html>

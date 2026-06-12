<?php
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

start_session();

if (is_logged_in()) {
    redirect('/pages/dashboard.php');
}

$errors = [];

$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? sanitize_input($_POST['username']) : '';
    $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if ($username === '' || trim($username) === '') {
        $errors[] = 'Username is required.';
    } elseif (!validate_username($username)) {
        $errors[] = 'Username must be 3-20 characters and use only letters, numbers, and underscores.';
    }

    if ($email === '' || trim($email) === '') {
        $errors[] = 'Email is required.';
    } elseif (!validate_email($email)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (!validate_password_strength($password)) {
        $errors[] = 'Password must be at least 8 characters long.';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Password confirmation does not match.';
    }

    if (user_exists($username)) {
        $errors[] = 'That username is already taken.';
    }

    if (user_exists($email)) {
        $errors[] = 'That email is already registered.';
    }

    if (empty($errors)) {
        $result = register_user($username, $email, $password);

        if ($result['success']) {
            redirect('/pages/login.php?message=' . urlencode('Registration successful! Please log in.'));
        } else {
            $errors = array_merge($errors, $result['errors']);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - <?php echo escape_output(APP_NAME); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f7f7fb; color: #222; }
        .container { max-width: 460px; margin: 40px auto; background: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input { width: 100%; padding: 10px; margin-top: 6px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; }
        button { margin-top: 16px; width: 100%; background: #198754; color: #fff; border: 0; padding: 10px; border-radius: 6px; cursor: pointer; }
        .alert { padding: 10px; border-radius: 6px; margin-bottom: 12px; }
        .alert-error { background: #fdecea; border: 1px solid #f5c2c7; color: #842029; }
        a { color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create Account</h1>
        <p><a href="/index.php">← Back to home</a></p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error" role="alert">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo escape_output($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="/pages/register.php" novalidate>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo escape_output($username); ?>" required autocomplete="username" minlength="3" maxlength="20">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo escape_output($email); ?>" required autocomplete="email">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="new-password" minlength="8">

            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required autocomplete="new-password" minlength="8">

            <button type="submit">Register</button>
        </form>

        <p>Already have an account? <a href="/pages/login.php">Log in here</a>.</p>
    </div>
</body>
</html>

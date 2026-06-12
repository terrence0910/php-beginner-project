<?php
require_once dirname(__DIR__) . '/includes/functions.php';

start_session();

if (is_logged_in()) {
    redirect('/pages/dashboard.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_input($_POST['username'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $password = isset($_POST['password']) ? (string) $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? (string) $_POST['confirm_password'] : '';

    if ($username === '') {
        $errors[] = 'Username is required.';
    } elseif (!validate_username($username)) {
        $errors[] = 'Username must be 3-20 characters and contain only letters, numbers, and underscores.';
    } elseif (user_exists($username)) {
        $errors[] = 'Username already exists.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!validate_email($email)) {
        $errors[] = 'Please enter a valid email address.';
    } elseif (user_exists($email)) {
        $errors[] = 'Email already exists.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    } elseif (!validate_password_strength($password)) {
        $errors[] = 'Password must be at least 8 characters long.';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Password confirmation does not match.';
    }

    if (empty($errors)) {
        $result = register_user($username, $email, $password);
        if ($result['success']) {
            redirect('/pages/login.php?registered=1');
        } else {
            $errors[] = $result['error'];
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
        body { font-family: Arial, sans-serif; margin: 0; background: #f5f7fb; color: #1f2937; }
        .container { max-width: 560px; margin: 3rem auto; padding: 1rem; }
        .card { background: #fff; border-radius: 10px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        label { display: block; margin-top: .75rem; font-weight: 600; }
        input { width: 100%; padding: .65rem; margin-top: .25rem; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; }
        button { margin-top: 1rem; width: 100%; padding: .75rem; border: 0; border-radius: 8px; background: #2563eb; color: #fff; font-weight: 600; cursor: pointer; }
        .error { background: #fee2e2; border: 1px solid #fca5a5; padding: .75rem; border-radius: 8px; margin-bottom: 1rem; }
        .links { margin-top: 1rem; text-align: center; }
        ul { margin: 0; padding-left: 1.25rem; }
    </style>
</head>
<body>
<main class="container">
    <section class="card" aria-labelledby="register-heading">
        <h1 id="register-heading">Create Account</h1>

        <?php if (!empty($errors)): ?>
            <div class="error" role="alert">
                <strong>Please fix the following:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo escape_output($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="/pages/register.php" novalidate>
            <label for="username">Username</label>
            <input id="username" name="username" type="text" required maxlength="20" pattern="[A-Za-z0-9_]{3,20}" value="<?php echo isset($username) ? escape_output($username) : ''; ?>">

            <label for="email">Email</label>
            <input id="email" name="email" type="email" required value="<?php echo isset($email) ? escape_output($email) : ''; ?>">

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required minlength="8">

            <label for="confirm_password">Confirm Password</label>
            <input id="confirm_password" name="confirm_password" type="password" required minlength="8">

            <button type="submit">Register</button>
        </form>

        <p class="links">
            Already have an account?
            <a href="/pages/login.php">Login here</a>
        </p>
        <p class="links"><a href="/index.php">Back to Home</a></p>
    </section>
</main>
</body>
</html>


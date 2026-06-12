<?php
require_once dirname(__DIR__) . '/config.php';
require_once INCLUDES_PATH . '/functions.php';

start_session();

if (is_logged_in()) {
    redirect('/pages/dashboard.php');
}

$errors = [];
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_input($_POST['username'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!validate_username($username)) {
        $errors[] = 'Username must be 3-20 characters and use letters, numbers, or underscore.';
    }

    if (!validate_email($email)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (!validate_password_strength($password)) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if (user_exists($username, $email)) {
        $errors[] = 'Username or email already exists.';
    }

    if (empty($errors)) {
        $user = register_user($username, $email, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            redirect('/pages/dashboard.php?success=' . urlencode('Account created successfully.'));
        }
        $errors[] = 'Unable to create account right now. Please try again.';
    }
}

$page_title = 'Register';
require_once INCLUDES_PATH . '/header.php';
?>
<div class="card">
    <h1>Register</h1>
    <?php foreach ($errors as $error): ?>
        <p class="error"><?php echo escape_output($error); ?></p>
    <?php endforeach; ?>

    <form method="POST" action="/pages/register.php">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?php echo escape_output($username); ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo escape_output($email); ?>" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Create Account</button>
    </form>

    <p class="muted">Already have an account? <a href="/pages/login.php">Login</a>.</p>
</div>
<?php require_once INCLUDES_PATH . '/footer.php'; ?>

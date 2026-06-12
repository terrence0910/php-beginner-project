<?php
require_once dirname(__DIR__) . '/config.php';
require_once INCLUDES_PATH . '/functions.php';

start_session();

if (is_logged_in()) {
    redirect('/pages/dashboard.php');
}

$error = $_GET['error'] ?? '';
$identifier = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = sanitize_input($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($identifier === '' || $password === '') {
        $error = 'Please enter username/email and password.';
    } else {
        $user = authenticate_user($identifier, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            redirect('/pages/dashboard.php?success=' . urlencode('Welcome back, ' . $user['username'] . '!'));
        }
        $error = 'Invalid login credentials.';
    }
}

$page_title = 'Login';
require_once INCLUDES_PATH . '/header.php';
?>
<div class="card">
    <h1>Login</h1>
    <?php if ($error !== ''): ?><p class="error"><?php echo escape_output($error); ?></p><?php endif; ?>
    <form method="POST" action="/pages/login.php">
        <label for="identifier">Username or Email</label>
        <input type="text" id="identifier" name="identifier" value="<?php echo escape_output($identifier); ?>" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>
    <p class="muted">Need an account? <a href="/pages/register.php">Register here</a>.</p>
</div>
<?php require_once INCLUDES_PATH . '/footer.php'; ?>

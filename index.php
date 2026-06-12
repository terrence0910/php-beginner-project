<?php
require_once __DIR__ . '/config.php';
require_once INCLUDES_PATH . '/functions.php';

start_session();

if (is_logged_in()) {
    redirect('/pages/dashboard.php');
}

$page_title = 'Welcome';
require_once INCLUDES_PATH . '/header.php';
?>
<div class="card">
    <h1>Welcome to <?php echo escape_output(APP_NAME); ?></h1>
    <p>Log in to manage your tasks with create, edit, complete, and delete actions.</p>
    <div class="actions">
        <a href="/pages/login.php"><button type="button">Login</button></a>
        <a href="/pages/register.php"><button type="button">Register</button></a>
    </div>
</div>
<?php require_once INCLUDES_PATH . '/footer.php'; ?>

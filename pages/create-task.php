<?php
require_once dirname(__DIR__) . '/includes/protect.php';

$current_user = get_logged_in_user();
$errors = [];
$title = '';
$description = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize_input($_POST['title'] ?? '');
    $description = sanitize_input($_POST['description'] ?? '');

    if ($title === '' || trim($title) === '') {
        $errors[] = 'Task title is required.';
    }

    if (strlen($title) < 1 || strlen($title) > 100) {
        $errors[] = 'Task title must be between 1 and 100 characters.';
    }

    if (strlen($description) > 500) {
        $errors[] = 'Task description cannot exceed 500 characters.';
    }

    if (empty($errors)) {
        $tasks = read_json_file(TASKS_FILE);

        $tasks[] = [
            'id' => generate_id(),
            'user_id' => $current_user['id'],
            'title' => $title,
            'description' => $description,
            'status' => 'pending',
            'created_at' => get_timestamp(),
            'updated_at' => get_timestamp(),
        ];

        write_json_file(TASKS_FILE, $tasks);
        redirect('/pages/dashboard.php?success=' . urlencode('Task created successfully.'));
    }
}

$page_title = 'Create Task';
require_once INCLUDES_PATH . '/header.php';
?>
<div class="card">
    <h1>Create Task</h1>

    <?php foreach ($errors as $error): ?>
        <p class="error"><?php echo escape_output($error); ?></p>
    <?php endforeach; ?>

    <form method="POST" action="/pages/create-task.php">
        <label for="title">Task Title (required, max 100)</label>
        <input type="text" id="title" name="title" maxlength="100" value="<?php echo escape_output($title); ?>" required>

        <label for="description">Description (optional, max 500)</label>
        <textarea id="description" name="description" maxlength="500" rows="5"><?php echo escape_output($description); ?></textarea>

        <button type="submit">Save Task</button>
    </form>

    <p><a href="/pages/dashboard.php">← Back to Dashboard</a></p>
</div>
<?php require_once INCLUDES_PATH . '/footer.php'; ?>

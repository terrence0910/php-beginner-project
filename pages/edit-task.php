<?php
require_once dirname(__DIR__) . '/includes/protect.php';

$current_user = get_logged_in_user();
$task_id = sanitize_input($_GET['id'] ?? '');
$errors = [];
$not_found_or_denied = '';

$tasks = read_json_file(TASKS_FILE);
$task_index = null;
$task = null;

foreach ($tasks as $index => $item) {
    if (($item['id'] ?? '') === $task_id) {
        $task_index = $index;
        $task = $item;
        break;
    }
}

if (!$task) {
    $not_found_or_denied = 'Task not found.';
} elseif (($task['user_id'] ?? '') !== $current_user['id']) {
    // Security: ownership verification prevents ID-guessing attacks.
    $not_found_or_denied = 'Access denied. You can only edit your own tasks.';
}

$title = $task['title'] ?? '';
$description = $task['description'] ?? '';
$status = $task['status'] ?? 'pending';
if (!in_array($status, ['pending', 'completed'], true)) {
    $status = 'pending';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $not_found_or_denied === '') {
    $title = sanitize_input($_POST['title'] ?? '');
    $description = sanitize_input($_POST['description'] ?? '');
    $status = sanitize_input($_POST['status'] ?? 'pending');

    if ($title === '' || trim($title) === '') {
        $errors[] = 'Task title is required.';
    }

    if (strlen($title) < 1 || strlen($title) > 100) {
        $errors[] = 'Task title must be between 1 and 100 characters.';
    }

    if (strlen($description) > 500) {
        $errors[] = 'Task description cannot exceed 500 characters.';
    }

    if (!in_array($status, ['pending', 'completed'], true)) {
        $errors[] = 'Task status must be pending or completed.';
    }

    if (empty($errors)) {
        $tasks[$task_index]['title'] = $title;
        $tasks[$task_index]['description'] = $description;
        $tasks[$task_index]['status'] = $status;
        $tasks[$task_index]['updated_at'] = get_timestamp();

        write_json_file(TASKS_FILE, $tasks);
        redirect('/pages/dashboard.php?success=' . urlencode('Task updated successfully.'));
    }
}

$page_title = 'Edit Task';
require_once INCLUDES_PATH . '/header.php';
?>
<div class="card">
    <h1>Edit Task</h1>

    <?php if ($not_found_or_denied !== ''): ?>
        <p class="error"><?php echo escape_output($not_found_or_denied); ?></p>
        <p><a href="/pages/dashboard.php">← Back to Dashboard</a></p>
    <?php else: ?>
        <?php foreach ($errors as $error): ?>
            <p class="error"><?php echo escape_output($error); ?></p>
        <?php endforeach; ?>

        <form method="POST" action="/pages/edit-task.php?id=<?php echo urlencode($task_id); ?>">
            <label for="title">Task Title (required, max 100)</label>
            <input type="text" id="title" name="title" maxlength="100" value="<?php echo escape_output($title); ?>" required>

            <label for="description">Description (optional, max 500)</label>
            <textarea id="description" name="description" maxlength="500" rows="5"><?php echo escape_output($description); ?></textarea>

            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="completed" <?php echo $status === 'completed' ? 'selected' : ''; ?>>Completed</option>
            </select>

            <button type="submit">Update Task</button>
        </form>

        <p><a href="/pages/dashboard.php">← Back to Dashboard</a></p>
    <?php endif; ?>
</div>
<?php require_once INCLUDES_PATH . '/footer.php'; ?>

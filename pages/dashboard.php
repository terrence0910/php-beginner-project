<?php
require_once dirname(__DIR__) . '/includes/protect.php';

$current_user = get_logged_in_user();
$tasks = read_json_file(TASKS_FILE);
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'toggle') {
    $task_id = sanitize_input($_POST['id'] ?? '');

    foreach ($tasks as $index => $task) {
        if (($task['id'] ?? '') === $task_id) {
            // Security: ownership check blocks editing another user's task.
            if (($task['user_id'] ?? '') !== $current_user['id']) {
                redirect('/pages/dashboard.php?error=' . urlencode('Access denied for this task.'));
            }

            $current_status = $task['status'] ?? 'pending';
            $tasks[$index]['status'] = ($current_status === 'completed') ? 'pending' : 'completed';
            $tasks[$index]['updated_at'] = get_timestamp();
            write_json_file(TASKS_FILE, $tasks);
            redirect('/pages/dashboard.php?success=' . urlencode('Task status updated.'));
        }
    }

    redirect('/pages/dashboard.php?error=' . urlencode('Task not found.'));
}

$user_tasks = array_values(filter_by_user($tasks, $current_user['id']));

$page_title = 'Dashboard';
require_once INCLUDES_PATH . '/header.php';
?>
<div class="card">
    <h1>Dashboard</h1>
    <p>Hello, <strong><?php echo escape_output($current_user['username']); ?></strong>!</p>

    <?php if ($success !== ''): ?><p class="success"><?php echo escape_output($success); ?></p><?php endif; ?>
    <?php if ($error !== ''): ?><p class="error"><?php echo escape_output($error); ?></p><?php endif; ?>

    <div class="actions" style="margin:10px 0;">
        <a href="/pages/create-task.php"><button type="button">+ Create New Task</button></a>
    </div>

    <?php if (empty($user_tasks)): ?>
        <p class="muted">No tasks yet. Create your first task to get started.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($user_tasks as $task): ?>
                <?php $is_completed = (($task['status'] ?? '') === 'completed'); ?>
                <tr>
                    <td><?php echo escape_output($task['title'] ?? ''); ?></td>
                    <td><?php echo escape_output($task['description'] ?? ''); ?></td>
                    <td class="<?php echo $is_completed ? 'status-completed' : 'status-pending'; ?>">
                        <?php echo $is_completed ? '✓ Completed' : '○ Pending'; ?>
                    </td>
                    <td><?php echo escape_output($task['created_at'] ?? ''); ?></td>
                    <td>
                        <div class="actions">
                            <a href="/pages/edit-task.php?id=<?php echo urlencode($task['id']); ?>">Edit</a>

                            <form method="POST" action="/pages/dashboard.php" style="display:inline;">
                                <input type="hidden" name="action" value="toggle">
                                <input type="hidden" name="id" value="<?php echo escape_output($task['id']); ?>">
                                <button type="submit"><?php echo $is_completed ? 'Mark Pending' : 'Mark Complete'; ?></button>
                            </form>

                            <form method="POST" action="/pages/delete-task.php?id=<?php echo urlencode($task['id']); ?>" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                <button type="submit">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require_once INCLUDES_PATH . '/footer.php'; ?>

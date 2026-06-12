<?php
require_once dirname(__DIR__) . '/includes/protect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Security: deletion should be an intentional POST action, not a direct URL visit.
    redirect('/pages/dashboard.php?error=' . urlencode('Invalid request method for deleting tasks.'));
}

$current_user = get_logged_in_user();
$task_id = sanitize_input($_GET['id'] ?? '');

if ($task_id === '') {
    redirect('/pages/dashboard.php?error=' . urlencode('Task ID is required.'));
}

$tasks = read_json_file(TASKS_FILE);
$target_index = null;

foreach ($tasks as $index => $task) {
    if (($task['id'] ?? '') === $task_id) {
        // Security: ownership check ensures users can only delete their own tasks.
        if (($task['user_id'] ?? '') !== $current_user['id']) {
            redirect('/pages/dashboard.php?error=' . urlencode('Access denied for this task.'));
        }

        $target_index = $index;
        break;
    }
}

if ($target_index === null) {
    redirect('/pages/dashboard.php?error=' . urlencode('Task not found.'));
}

unset($tasks[$target_index]);
$tasks = array_values($tasks);
write_json_file(TASKS_FILE, $tasks);

redirect('/pages/dashboard.php?success=' . urlencode('Task deleted successfully.'));

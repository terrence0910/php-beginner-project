<?php
/**
 * Authentication helper functions for Step 2.
 *
 * WHY this file exists:
 * - Keep auth logic in one reusable place
 * - Avoid repeating validation/security code across pages
 */

require_once dirname(__DIR__) . '/config.php';

/**
 * Hash a plain-text password using PHP's modern default algorithm.
 *
 * WHY: We never store plain-text passwords. If data leaks, hashed passwords
 * are much harder to abuse.
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify a plain-text password against a stored hash.
 */
function verify_password($input, $hash) {
    return password_verify($input, $hash);
}

/**
 * Validate username format.
 * Rules: 3-20 chars, letters/numbers/underscore only.
 */
function validate_username($username) {
    return preg_match('/^[A-Za-z0-9_]{3,20}$/', $username) === 1;
}

/**
 * Validate password strength.
 * Rule: minimum 8 characters for beginner project Step 2.
 */
function validate_password_strength($password) {
    return strlen($password) >= 8;
}

/**
 * Check if a user exists by username OR email.
 * Returns the user array if found, otherwise null.
 */
function user_exists($username_or_email) {
    $search = strtolower(trim($username_or_email));
    $users = read_json_file(USERS_FILE);

    foreach ($users as $user) {
        $username = strtolower($user['username'] ?? '');
        $email = strtolower($user['email'] ?? '');

        if ($search === $username || $search === $email) {
            return $user;
        }
    }

    return null;
}

/**
 * Register a new user account and save it to users.json.
 *
 * Returns:
 * - ['success' => true, 'user' => <new user array>]
 * - ['success' => false, 'errors' => [..]]
 */
function register_user($username, $email, $password) {
    $errors = [];

    $username = trim(sanitize_input($username));
    $email = trim(sanitize_input($email));

    if ($username === '') {
        $errors[] = 'Username is required.';
    } elseif (!validate_username($username)) {
        $errors[] = 'Username must be 3-20 characters and use only letters, numbers, and underscores.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!validate_email($email)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (!validate_password_strength($password)) {
        $errors[] = 'Password must be at least 8 characters long.';
    }

    if (user_exists($username)) {
        $errors[] = 'That username is already taken.';
    }

    if (user_exists($email)) {
        $errors[] = 'That email is already registered.';
    }

    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors];
    }

    $users = read_json_file(USERS_FILE);

    $new_user = [
        'id' => generate_id(),
        'username' => $username,
        'email' => strtolower($email),
        'password' => hash_password($password),
        'created_at' => get_timestamp(),
        'updated_at' => get_timestamp()
    ];

    $users[] = $new_user;

    if (!write_json_file(USERS_FILE, $users)) {
        return [
            'success' => false,
            'errors' => ['Unable to save user data. Please try again.']
        ];
    }

    return ['success' => true, 'user' => $new_user];
}

/**
 * Authenticate user by username/email and password.
 * Returns user array on success, null on failure.
 */
function authenticate_user($username, $password) {
    $username = trim(sanitize_input($username));

    if ($username === '' || $password === '') {
        return null;
    }

    $user = user_exists($username);
    if (!$user) {
        return null;
    }

    return verify_password($password, $user['password'] ?? '') ? $user : null;
}

/**
 * Safe redirect helper.
 *
 * WHY security note:
 * We reject newline characters to block header injection and only allow
 * local relative paths used by this beginner project.
 */
function redirect($location) {
    $location = trim($location);

    if ($location === '' || strpos($location, "\n") !== false || strpos($location, "\r") !== false) {
        $location = '/index.php';
    }

    if ($location[0] !== '/') {
        $location = '/' . ltrim($location, '/');
    }

    header('Location: ' . $location);
    exit;
}
?>

<?php
/**
 * Authentication helper functions for Step 2.
 * We keep auth logic here so login/register pages stay clean (DRY principle).
 */

require_once dirname(__DIR__) . '/config.php';

/**
 * Hash password securely using PHP's built-in adaptive algorithm.
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify plain-text password against stored hash.
 */
function verify_password($input, $hash) {
    return password_verify($input, $hash);
}

/**
 * Validate username: 3-20 chars, alphanumeric + underscore only.
 */
function validate_username($username) {
    return preg_match('/^[A-Za-z0-9_]{3,20}$/', $username) === 1;
}

/**
 * Validate minimum password strength requirement for this step.
 */
function validate_password_strength($password) {
    return strlen($password) >= 8;
}

/**
 * Check whether username OR email already exists.
 */
function user_exists($username_or_email) {
    $needle = strtolower(trim($username_or_email));
    $users = read_json_file(USERS_FILE);

    foreach ($users as $user) {
        $username = isset($user['username']) ? strtolower($user['username']) : '';
        $email = isset($user['email']) ? strtolower($user['email']) : '';
        if ($needle === $username || $needle === $email) {
            return true;
        }
    }

    return false;
}

/**
 * Register a new user account and store it in users.json.
 * Returns structured response for clear UI messaging.
 */
function register_user($username, $email, $password) {
    $users = read_json_file(USERS_FILE);

    if (user_exists($username)) {
        return ['success' => false, 'error' => 'Username already exists.'];
    }
    if (user_exists($email)) {
        return ['success' => false, 'error' => 'Email already exists.'];
    }

    $new_user = [
        'id' => generate_id(),
        'username' => $username,
        'email' => $email,
        'password' => hash_password($password),
        'created_at' => get_timestamp(),
        'updated_at' => get_timestamp(),
    ];

    $users[] = $new_user;
    $saved = write_json_file(USERS_FILE, $users);

    if (!$saved) {
        return ['success' => false, 'error' => 'Failed to save user data.'];
    }

    return ['success' => true, 'user' => $new_user];
}

/**
 * Authenticate by username or email + password.
 * Returns user array when valid, null when invalid.
 */
function authenticate_user($username, $password) {
    $needle = strtolower(trim($username));
    $users = read_json_file(USERS_FILE);

    foreach ($users as $user) {
        $stored_username = isset($user['username']) ? strtolower($user['username']) : '';
        $stored_email = isset($user['email']) ? strtolower($user['email']) : '';

        if ($needle !== $stored_username && $needle !== $stored_email) {
            continue;
        }

        if (isset($user['password']) && verify_password($password, $user['password'])) {
            return $user;
        }
    }

    return null;
}

/**
 * Safe redirect helper.
 * Prevents header injection by removing CR/LF from the location.
 */
function redirect($location) {
    $safe_location = str_replace(["\r", "\n"], '', (string) $location);
    if ($safe_location === '') {
        $safe_location = '/';
    }

    header('Location: ' . $safe_location);
    exit;
}


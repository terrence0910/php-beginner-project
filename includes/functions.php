<?php
/**
 * Additional helper functions used by authentication/task pages.
 */

if (!defined('BASE_PATH')) {
    require_once dirname(__DIR__) . '/config.php';
}

/**
 * Redirect helper keeps redirect+exit consistent.
 */
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * Hash plain-text password before storing.
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify a login password against a stored hash.
 */
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Validate username format for beginner-friendly auth.
 */
function validate_username($username) {
    return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username) === 1;
}

/**
 * Keep password rules simple and explicit.
 */
function validate_password_strength($password) {
    return strlen($password) >= 8;
}

/**
 * Prevent duplicate username/email accounts.
 */
function user_exists($username, $email) {
    $users = read_json_file(USERS_FILE);
    foreach ($users as $user) {
        if (
            (isset($user['username']) && strtolower($user['username']) === strtolower($username)) ||
            (isset($user['email']) && strtolower($user['email']) === strtolower($email))
        ) {
            return true;
        }
    }
    return false;
}

/**
 * Authenticate by username OR email.
 */
function authenticate_user($identifier, $password) {
    $users = read_json_file(USERS_FILE);

    foreach ($users as $user) {
        $username_match = isset($user['username']) && strtolower($user['username']) === strtolower($identifier);
        $email_match = isset($user['email']) && strtolower($user['email']) === strtolower($identifier);

        if (($username_match || $email_match) && isset($user['password']) && verify_password($password, $user['password'])) {
            return $user;
        }
    }

    return null;
}

/**
 * Register a user and persist to users.json.
 */
function register_user($username, $email, $password) {
    $users = read_json_file(USERS_FILE);

    $new_user = [
        'id' => generate_id(),
        'username' => $username,
        'email' => $email,
        'password' => hash_password($password),
        'created_at' => get_timestamp(),
        'updated_at' => get_timestamp(),
    ];

    $users[] = $new_user;

    if (!write_json_file(USERS_FILE, $users)) {
        return null;
    }

    return $new_user;
}

<?php
/**
 * ========================================
 * PHP BEGINNER PROJECT - Configuration File
 * ========================================
 * 
 * This is the main configuration file for the Task Management application.
 * It contains:
 * 1. PHP configuration settings
 * 2. Path constants for directories
 * 3. Helper functions for common operations
 * 4. Data management functions (JSON file operations)
 * 5. Security helper functions
 * 
 * LEARNING NOTE: This file is loaded by all pages, so we define shared
 * functions and constants here to avoid duplication (DRY principle).
 */

// ========================================
// 1. ERROR REPORTING & PHP SETTINGS
// ========================================

// Display all errors during development (LEARNING HELPER)
// In production, you'd set this to E_ALL & ~E_DEPRECATED & ~E_STRICT
error_reporting(E_ALL);
// Show errors in the browser (helpful for learning)
ini_set('display_errors', 1);

// Set default timezone
date_default_timezone_set('UTC');

// ========================================
// 2. DEFINE PATH CONSTANTS
// ========================================

/**
 * __DIR__ is a PHP magic constant that gives us the current directory
 * Using constants prevents typos and makes code more maintainable
 * 
 * WHY: If we change folder structure, we only change it in one place
 */
define('BASE_PATH', __DIR__);
define('DATA_PATH', BASE_PATH . '/data');
define('INCLUDES_PATH', BASE_PATH . '/includes');
define('PAGES_PATH', BASE_PATH . '/pages');
define('CSS_PATH', BASE_PATH . '/css');

// File paths for data storage
define('USERS_FILE', DATA_PATH . '/users.json');
define('TASKS_FILE', DATA_PATH . '/tasks.json');

// ========================================
// 3. APPLICATION CONSTANTS
// ========================================

/**
 * These are configuration values used throughout the application
 * Changing them here affects the entire app
 */

// Session configuration
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds
define('SESSION_NAME', 'task_app_session');

// Application name and version
define('APP_NAME', 'Task Manager');
define('APP_VERSION', '1.0.0');

// ========================================
// 4. HELPER FUNCTIONS
// ========================================

/**
 * Function: sanitize_input()
 * 
 * Purpose: Remove potentially dangerous characters from user input
 * WHY: Prevents XSS (Cross-Site Scripting) attacks
 * 
 * Parameters:
 *   $input (string) - The user input to sanitize
 * 
 * Returns:
 *   (string) - The cleaned/safe input
 * 
 * Example:
 *   $username = sanitize_input($_POST['username']);
 */
function sanitize_input($input) {
    // trim() removes whitespace from beginning and end
    $input = trim($input);
    
    // stripslashes() removes backslashes (if they exist)
    if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
        $input = stripslashes($input);
    }
    
    // htmlspecialchars() converts special characters to HTML entities
    // This prevents the browser from interpreting them as code
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    
    return $input;
}

/**
 * Function: escape_output()
 * 
 * Purpose: Safely display user data in HTML (prevents XSS)
 * WHY: When displaying user data, we must escape it to prevent attacks
 * 
 * Parameters:
 *   $data (string) - The data to escape
 * 
 * Returns:
 *   (string) - The escaped data safe to display
 * 
 * Example:
 *   echo escape_output($user_comment);
 */
function escape_output($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Function: validate_email()
 * 
 * Purpose: Check if an email address is valid
 * WHY: Email validation prevents storing invalid email addresses
 * 
 * Parameters:
 *   $email (string) - The email to validate
 * 
 * Returns:
 *   (boolean) - true if valid, false otherwise
 * 
 * Example:
 *   if (validate_email($email)) {
 *       // Email is valid
 *   }
 */
function validate_email($email) {
    // filter_var() is a built-in PHP function for validation
    // FILTER_VALIDATE_EMAIL checks if format is valid
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Function: generate_id()
 * 
 * Purpose: Create a unique ID for new records
 * WHY: We need unique identifiers for users and tasks
 * 
 * Parameters:
 *   None
 * 
 * Returns:
 *   (string) - A unique ID based on current timestamp and random number
 * 
 * Example:
 *   $new_id = generate_id();
 */
function generate_id() {
    // uniqid() generates unique ID based on microtime
    // mt_rand() adds random number for extra uniqueness
    return uniqid() . '-' . mt_rand(1000, 9999);
}

/**
 * Function: get_timestamp()
 * 
 * Purpose: Get current date/time in ISO 8601 format
 * WHY: Consistent timestamp format throughout the application
 * 
 * Parameters:
 *   None
 * 
 * Returns:
 *   (string) - Current timestamp in format: 2024-01-15T10:30:45Z
 * 
 * Example:
 *   $created = get_timestamp();
 */
function get_timestamp() {
    // date() formats the current time
    // 'c' is the ISO 8601 format
    return date('c');
}

// ========================================
// 5. JSON FILE OPERATIONS
// ========================================

/**
 * Function: read_json_file()
 * 
 * Purpose: Read and parse a JSON file
 * WHY: Convert JSON data from file into PHP arrays we can work with
 * 
 * Parameters:
 *   $file_path (string) - Full path to the JSON file
 * 
 * Returns:
 *   (array) - Parsed JSON as PHP array, empty array if file doesn't exist
 * 
 * Example:
 *   $users = read_json_file(USERS_FILE);
 */
function read_json_file($file_path) {
    // Check if file exists before trying to read it
    if (!file_exists($file_path)) {
        // Return empty array if file doesn't exist
        return [];
    }
    
    // file_get_contents() reads entire file into a string
    $json_content = file_get_contents($file_path);
    
    // json_decode() converts JSON string to PHP array
    // Second parameter 'true' means return as associative array
    // Third parameter is depth limit (prevents deep nesting attacks)
    $data = json_decode($json_content, true, 512);
    
    // If json_decode fails, return empty array
    // This prevents errors if file is corrupted
    return is_array($data) ? $data : [];
}

/**
 * Function: write_json_file()
 * 
 * Purpose: Write PHP array to JSON file
 * WHY: Save our data so it persists between page loads
 * 
 * Parameters:
 *   $file_path (string) - Full path to the JSON file
 *   $data (array) - The data to save
 * 
 * Returns:
 *   (boolean) - true if successful, false if failed
 * 
 * Example:
 *   $success = write_json_file(USERS_FILE, $users);
 *   if (!$success) {
 *       echo "Error saving file";
 *   }
 */
function write_json_file($file_path, $data) {
    // json_encode() converts PHP array to JSON string
    // JSON_PRETTY_PRINT makes it readable (useful for debugging)
    // JSON_UNESCAPED_SLASHES keeps forward slashes normal
    $json_content = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    
    // Check if json_encode succeeded
    if ($json_content === false) {
        return false;
    }
    
    // file_put_contents() writes string to file
    // Creates file if it doesn't exist, overwrites if it does
    $bytes_written = file_put_contents($file_path, $json_content);
    
    // Return true if bytes were written, false otherwise
    return $bytes_written !== false;
}

/**
 * Function: find_by_id()
 * 
 * Purpose: Find a record in array by its ID
 * WHY: Common operation - search for user by ID, task by ID, etc.
 * 
 * Parameters:
 *   $array (array) - Array of items to search
 *   $id (string) - The ID to find
 *   $id_key (string) - Which key contains the ID (default 'id')
 * 
 * Returns:
 *   (array) - The found item, or null if not found
 * 
 * Example:
 *   $user = find_by_id($users, '12345');
 *   if ($user) {
 *       echo "Found: " . $user['username'];
 *   }
 */
function find_by_id($array, $id, $id_key = 'id') {
    // Loop through each item in the array
    foreach ($array as $item) {
        // Check if this item has matching ID
        if (isset($item[$id_key]) && $item[$id_key] === $id) {
            // Found it! Return this item
            return $item;
        }
    }
    
    // If we get here, ID was not found
    return null;
}

/**
 * Function: find_by_field()
 * 
 * Purpose: Find a record by any field value
 * WHY: Search by username, email, or any other field
 * 
 * Parameters:
 *   $array (array) - Array to search
 *   $field (string) - Which field to search in
 *   $value (string) - Value to find
 * 
 * Returns:
 *   (array) - First item matching the value, or null
 * 
 * Example:
 *   $user = find_by_field($users, 'username', 'john_doe');
 */
function find_by_field($array, $field, $value) {
    // Loop through array items
    foreach ($array as $item) {
        // Check if this item's field matches our search value
        if (isset($item[$field]) && $item[$field] === $value) {
            return $item;
        }
    }
    
    // Not found
    return null;
}

/**
 * Function: update_record()
 * 
 * Purpose: Update a record in an array by ID and save to file
 * WHY: Common operation for editing users, tasks, etc.
 * 
 * Parameters:
 *   $array (array) - The array containing records
 *   $file_path (string) - Path to JSON file to save
 *   $id (string) - ID of record to update
 *   $new_data (array) - New values to update
 *   $id_key (string) - Which key contains the ID (default 'id')
 * 
 * Returns:
 *   (boolean) - true if successful, false if ID not found
 * 
 * Example:
 *   $updated = update_record($tasks, TASKS_FILE, '123', ['status' => 'completed']);
 */
function update_record(&$array, $file_path, $id, $new_data, $id_key = 'id') {
    // Loop through array to find the record
    foreach ($array as $key => $item) {
        // Check if this is the record we're looking for
        if (isset($item[$id_key]) && $item[$id_key] === $id) {
            // Merge new data with existing data
            // array_merge keeps existing values and adds new ones
            $array[$key] = array_merge($item, $new_data);
            
            // Update the timestamp
            if (isset($array[$key]['updated_at'])) {
                $array[$key]['updated_at'] = get_timestamp();
            }
            
            // Save the updated array to file
            write_json_file($file_path, $array);
            
            // Successfully updated
            return true;
        }
    }
    
    // Record not found
    return false;
}

/**
 * Function: delete_record()
 * 
 * Purpose: Delete a record from array and save to file
 * WHY: Remove users or tasks
 * 
 * Parameters:
 *   $array (array) - Array containing records
 *   $file_path (string) - Path to JSON file
 *   $id (string) - ID of record to delete
 *   $id_key (string) - Which key contains ID (default 'id')
 * 
 * Returns:
 *   (boolean) - true if deleted, false if not found
 * 
 * Example:
 *   $deleted = delete_record($tasks, TASKS_FILE, '123');
 */
function delete_record(&$array, $file_path, $id, $id_key = 'id') {
    // Loop through array with index
    foreach ($array as $key => $item) {
        // Check if this is the record to delete
        if (isset($item[$id_key]) && $item[$id_key] === $id) {
            // unset() removes this element from array
            unset($array[$key]);
            
            // Re-index the array (remove gaps in indices)
            // array_values() creates new array with new indices
            $array = array_values($array);
            
            // Save updated array to file
            write_json_file($file_path, $array);
            
            // Successfully deleted
            return true;
        }
    }
    
    // Record not found
    return false;
}

/**
 * Function: filter_by_user()
 * 
 * Purpose: Get all items belonging to a specific user
 * WHY: Each user should only see their own tasks
 * 
 * Parameters:
 *   $array (array) - Array to filter
 *   $user_id (string) - User ID to filter by
 * 
 * Returns:
 *   (array) - All items where user_id matches
 * 
 * Example:
 *   $user_tasks = filter_by_user($tasks, $current_user_id);
 */
function filter_by_user($array, $user_id) {
    // array_filter() keeps items where callback returns true
    return array_filter($array, function($item) use ($user_id) {
        return isset($item['user_id']) && $item['user_id'] === $user_id;
    });
}

// ========================================
// 6. SESSION MANAGEMENT
// ========================================

/**
 * Function: start_session()
 * 
 * Purpose: Initialize user session
 * WHY: Keep users logged in across pages
 * 
 * Parameters:
 *   None
 * 
 * Returns:
 *   (void) - No return value
 * 
 * Example:
 *   start_session();
 *   $_SESSION['user_id'] = $user['id'];
 */
function start_session() {
    // Check if session is not already started
    // session_status() returns PHP_SESSION_ACTIVE if already started
    if (session_status() === PHP_SESSION_NONE) {
        // Start the session
        session_start();
    }
}

/**
 * Function: is_logged_in()
 * 
 * Purpose: Check if user is currently logged in
 * WHY: Determine if we should show dashboard or login page
 * 
 * Parameters:
 *   None
 * 
 * Returns:
 *   (boolean) - true if user is logged in, false otherwise
 * 
 * Example:
 *   if (is_logged_in()) {
 *       // Show dashboard
 *   } else {
 *       // Redirect to login
 *   }
 */
function is_logged_in() {
    // Check if session user_id exists
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Function: get_logged_in_user()
 * 
 * Purpose: Get the currently logged-in user's data
 * WHY: Need user info on every page (name, email, preferences, etc.)
 * 
 * Parameters:
 *   None
 * 
 * Returns:
 *   (array) - Current user data, or null if not logged in
 * 
 * Example:
 *   $user = get_logged_in_user();
 *   if ($user) {
 *       echo "Welcome, " . escape_output($user['username']);
 *   }
 */
function get_logged_in_user() {
    // Check if user is logged in
    if (!is_logged_in()) {
        return null;
    }
    
    // Get user ID from session
    $user_id = $_SESSION['user_id'];
    
    // Read all users from file
    $users = read_json_file(USERS_FILE);
    
    // Find and return current user
    return find_by_id($users, $user_id);
}

/**
 * Function: logout_user()
 * 
 * Purpose: Log the user out
 * WHY: Destroy session and clear login state
 * 
 * Parameters:
 *   None
 * 
 * Returns:
 *   (void) - No return value
 * 
 * Example:
 *   if ($logout_requested) {
 *       logout_user();
 *   }
 */
function logout_user() {
    // Unset all session variables
    $_SESSION = [];
    
    // Destroy the session
    session_destroy();
}

// ========================================
// 7. END OF CONFIG FILE
// ========================================

/**
 * HOW THIS FILE WORKS:
 * 
 * When a page needs to use these functions, it includes this file:
 *   require_once 'config.php';
 * 
 * Then it can use any function or constant defined here:
 *   $users = read_json_file(USERS_FILE);
 *   $clean_input = sanitize_input($_POST['username']);
 * 
 * WHY organize this way?
 * - DRY Principle: Define functions once, use everywhere
 * - Easy maintenance: Change function in one place
 * - Clean code: Each file has clear responsibility
 * - Security: Security functions in one place
 * 
 * NEXT STEPS:
 * - Look at data/users.json to understand data structure
 * - Look at data/tasks.json to understand task structure
 * - In Step 2, we'll create login/register pages that use these functions
 */
?>
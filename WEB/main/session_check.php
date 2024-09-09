<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set the inactivity timeout (30 minutes)
$timeout = 1800; // 30 minutes in seconds

// Check if the last activity cookie is set
if (isset($_COOKIE['last_activity'])) {
    // Calculate the time since last activity
    $time_since_last_activity = time() - $_COOKIE['last_activity'];
    
    // If the inactivity exceeds the timeout, destroy the session and redirect to login
    if ($time_since_last_activity > $timeout) {
        session_unset();   // Clear session variables
        session_destroy(); // Destroy the session
        setcookie('last_activity', '', time() - 3600, '/'); // Expire the activity cookie
        
        // Redirect to the login page
        header("Location: ../main/login.php");
        exit();
    }
}

// Update the last activity cookie if the user is active
setcookie('last_activity', time(), time() + $timeout, '/'); // Reset the cookie timer



if (!isset($_SESSION['user'])) {
    header("Location: ../main/login.php");
    exit();
} else {
    $session_valid = true;
    
    $user_profile = $_SESSION['user_profile'] ?? 'unknown';
    
    // Define the base directory for profile folders
    $base_dir = __DIR__ . '/..';

    // Function to get all PHP files in a directory
    function get_php_files($dir) {
        $php_files = glob($dir . '/*.php');
        return array_map('basename', $php_files);
    }

    // Define allowed pages for each profile
    $allowed_pages = [
        'civilian' => get_php_files($base_dir . '/civilian'),
        'administrator' => get_php_files($base_dir . '/admin'),
        'volunteer' => get_php_files($base_dir . '/volunteer')
    ];
    
    $current_page = basename($_SERVER['PHP_SELF']);
    
    if (!isset($allowed_pages[$user_profile]) || !in_array($current_page, $allowed_pages[$user_profile])) {
        header("Location: ../main/unauthorized.php");
        exit();
    }
}

// Add this function at the end of the file
function checkSessionAndRedirect() {
    if (!isset($_SESSION['user'])) {
        header("Location: ../main/login.php");
        exit();
    }
}
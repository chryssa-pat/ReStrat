<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);


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
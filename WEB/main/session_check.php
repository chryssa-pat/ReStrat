<?php
session_start();

// Define login-related pages that should be accessible without a session
$public_pages = ['login.php', 'register.php', 'forgot_password.php'];

$current_page = basename($_SERVER['PHP_SELF']);

// Allow access to public pages without a session
if (in_array($current_page, $public_pages)) {
    return;
}

// Check if user is logged in
if (!isset($_SESSION['user']) || !isset($_SESSION['role'])) {
    header("Location: ../main/login.php");
    exit();
}

$user_role = $_SESSION['role'];

$allowed_pages = [
    'civilian' => ['civilian_main.php', 'history_main.php', 'announcements_main.php'],
    'administrator' => ['createuser_admin_main.php', 'warehouse_main.php', 'announcement.php'],
    'volunteer' => ['volunteer.php', 'load.php']
];

$default_pages = [
    'civilian' => 'civilian_main.php',
    'administrator' => 'createuser_admin_main.php',
    'volunteer' => 'volunteer.php'
];

// Check if the current page is allowed for the user's role
if (!in_array($current_page, $allowed_pages[$user_role])) {
    header("Location: ../" . $user_role . "/" . $default_pages[$user_role]);
    exit();
}
?>

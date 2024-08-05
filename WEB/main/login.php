<?php
session_start();

// Establish a database connection 
$conn = new mysqli("localhost", "root", "", "web");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate login credentials against the database
$username = $_POST['login-username'];  // Update field name here
$password = $_POST['login-password'];  // Update field name here

$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user'] = $user['username'];
    $_SESSION['profile'] = $user['profile'];

    // Redirect based on user type
    switch ($user['profile']) {
        case 'civilian':
            header('Location: ../civilian/civilian_main.php');
            break;
        case 'volunteer':
            header('Location: ../volunteer/volunteer.html');
            break;
        case 'administrator':
            header('Location: ../admin/announcement.php');
            break;
        default:
            // Redirect to a default page if user type is not recognized
            header('Location: default_dashboard.php');
            break;
    }

    exit;
} else {
    $_SESSION['login_message'] = 'Invalid username or password.';
    header('Location: login.html');
    exit;
}

// Close the database connection
$conn->close();
?>

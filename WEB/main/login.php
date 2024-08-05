<?php
session_start();

// Establish a database connection
$conn = new mysqli("localhost", "root", "", "web");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate login credentials against the database
$username = $_POST['login-username'];
$password = $_POST['login-password'];

// Use prepared statements to prevent SQL injection
$sql = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user'] = $user['username'];
    $_SESSION['role'] = $user['profile'];

    // Redirect based on user type
    switch ($user['profile']) {
        case 'civilian':
            header('Location: ../civilian/civilian_main.php');
            break;
        case 'volunteer':
            header('Location: ../volunteer/volunteer.html');
            break;
        case 'administrator':
            header('Location: ../administrator/announcement.php');
            break;
        default:
            header('Location: default_dashboard.php');
            break;
    }
    exit;
} else {
    $_SESSION['login_message'] = 'Invalid username or password.';
    header('Location: login.php');
    exit;
}

// Close the database connection
$stmt->close();
$conn->close();
?>

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


// Use prepared statement to prevent SQL injection
$sql = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user'] = $user['username'];
    $_SESSION['user_profile'] = $user['profile'];
    
    // Clear any previous login messages
    unset($_SESSION['login_message']);

    // If the user is a volunteer, store additional information
    if ($user['profile'] == 'volunteer') {
        // Fetch volunteer details
        $volunteerSql = "SELECT * FROM volunteer WHERE volunteer_user = ?";
        $volunteerStmt = $conn->prepare($volunteerSql);
        $volunteerStmt->bind_param("s", $username);
        $volunteerStmt->execute();
        $volunteerResult = $volunteerStmt->get_result();
        
        if ($volunteerResult->num_rows > 0) {
            $volunteerData = $volunteerResult->fetch_assoc();
            $_SESSION['vehicle_id'] = $volunteerData['vehicle'];

        }
        $volunteerStmt->close();
    }

    // Redirect based on user type
    switch ($user['profile']) {
        case 'civilian':
            header('Location: ../civilian/civilian_main.php');
            break;
        case 'volunteer':
            header('Location: ../volunteer/volunteer.php');
            break;
        case 'administrator':
            header('Location: ../admin/announcement.php');
            break;
        default:
            // Redirect to a default page if user type is not recognized
            header('Location: ../main/login.php');
            break;
    }

    exit;
} else {
    $_SESSION['login_message'] = 'Invalid username or password.';
    header('Location: login.html');
    exit;
}

// Close the database connection
$stmt->close();
$conn->close();
?>
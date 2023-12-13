<?php
session_start();

// Establish a database connection
$conn = new mysqli("localhost", "root", "canmp168", "web");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the signup form
$username = $_POST['signup-username'];
$fullname = $_POST['signup-fullname'];
$password = $_POST['signup-password'];
$phone = $_POST['signup-phone'];
$latitude = $_POST['latitude'];  // Corrected from $Location
$longitude = $_POST['longitude']; // Corrected from $Location

// Insert into USERS table
$sql_users = "INSERT INTO users (username, password, profile) VALUES ('$username', '$password', 'civilian')";

if ($conn->query($sql_users) === TRUE) {
    // If the insertion into USERS table is successful, proceed with CIVILIAN table
    $sql_civilian = "INSERT INTO civilian (civilian_user, full_name, phone, latitude, longitude) VALUES ('$username', '$fullname', '$phone', '$latitude', '$longitude')";

    if ($conn->query($sql_civilian) === TRUE) {
        header('Location: ../civilian/civilian.html');
        exit;
    } else {
        $_SESSION['signup_message'] = 'Error creating account in CIVILIAN table: ' . $conn->error;
        header('Location: signup.html');
        exit;
    }
} else {
    $_SESSION['signup_message'] = 'Error creating account in USERS table: ' . $conn->error;
    header('Location: signup.html');
    exit;
}

// Close the database connection
$conn->close();
?>

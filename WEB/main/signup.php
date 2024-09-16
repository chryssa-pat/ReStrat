<?php
session_start();

// Set content type to JSON
header('Content-Type: application/json');

// Establish a database connection
$conn = new mysqli("localhost", "root", "", "web");

// Check the connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Retrieve data from the signup form
$username = $_POST['signup-username'];
$fullname = $_POST['signup-fullname'];
$password = $_POST['signup-password'];
$phone = $_POST['signup-phone'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

// Check if the username already exists
$sql_check_user = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql_check_user);

if ($result->num_rows > 0) {
    // Username exists, return JSON response
    echo json_encode(['success' => false, 'message' => 'Username already exists. Please choose another.']);
    exit;
} else {
    // Insert into USERS table
    $sql_users = "INSERT INTO users (username, password, profile) VALUES ('$username', '$password', 'civilian')";

    if ($conn->query($sql_users) === TRUE) {
        // If the insertion into USERS table is successful, proceed with CIVILIAN table
        $sql_civilian = "INSERT INTO civilian (civilian_user, full_name, phone, latitude, longitude) VALUES ('$username', '$fullname', '$phone', '$latitude', '$longitude')";

        if ($conn->query($sql_civilian) === TRUE) {
            // Success: Return success message
            echo json_encode(['success' => true, 'message' => 'User created successfully! Redirecting...']);
        } else {
            // Error in inserting into CIVILIAN table
            echo json_encode(['success' => false, 'message' => 'Error creating account in CIVILIAN table: ' . $conn->error]);
        }
    } else {
        // Error in inserting into USERS table
        echo json_encode(['success' => false, 'message' => 'Error creating account in USERS table: ' . $conn->error]);
    }
}

// Close the database connection
$conn->close();
?>

<?php
// Establish a database connection (replace these with your actual database credentials)

$conn = mysqli_connect('localhost', 'root', 'canmp168', 'WEB');

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from AJAX request
$username = $_POST['username'];
$fullname = $_POST['fullname'];
$password = $_POST['password'];
$phone = $_POST['phone'];

// Hash the password (you should use a stronger hashing algorithm in production)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert data into the database - USERS table
$sql = "INSERT INTO USERS (username, password, profile) VALUES ('$username', '$hashed_password', 'civilian')";

if ($conn->query($sql) === TRUE) {
    // If the insertion into USERS table is successful, proceed with CIVILIAN table
    $sql_civilian = "INSERT INTO CIVILIAN (civilian_user, full_name, phone) VALUES ('$username', '$fullname', '$phone')";

    if ($conn->query($sql_civilian) === TRUE) {
        echo "Sign up successful!";
    } else {
        echo "Error inserting into CIVILIAN table: " . $conn->error;
    }
} else {
    echo "Error inserting into USERS table: " . $conn->error;
}

// Close the database connection
$conn->close();
?>

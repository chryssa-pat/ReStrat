<?php
session_start();

$conn = new mysqli("localhost", "root", "", "web");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$username = $_POST['username'];
$password = $_POST['password'];
$profile = $_POST['profile'];

// Perform the insertion
$stmt = $conn->prepare("INSERT INTO USERS (username, password, profile) VALUES (?, ?, ?)");
$stmt->execute([$username, $password, $profile]);

echo "User added successfully!";

$conn->close();

?>

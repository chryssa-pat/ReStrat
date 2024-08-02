<?php
session_start();

header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "web");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Get form data
$username = $_POST['username'];
$password = $_POST['password'];
$profile = $_POST['profile'];

// Perform the insertion
$stmt = $conn->prepare("INSERT INTO USERS (username, password, profile) VALUES (?, ?, ?)");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}
$stmt->bind_param("sss", $username, $password, $profile);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User added successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>

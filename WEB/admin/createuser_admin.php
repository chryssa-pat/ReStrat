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
$carId = $_POST['carId'];
$profile = 'volunteer';

// Check if username already exists
$stmt = $conn->prepare("SELECT username FROM USERS WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'User with this username already exists. Please select another username.']);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

// Start transaction
$conn->begin_transaction();

try {
    // Insert into USERS table
    $stmt = $conn->prepare("INSERT INTO USERS (username, password, profile) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $profile);
    $stmt->execute();
    $stmt->close();

    // Insert into VOLUNTEER table
    $stmt = $conn->prepare("INSERT INTO VOLUNTEER (volunteer_user, vehicle) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $carId);
    $stmt->execute();
    $stmt->close();

    // Commit transaction
    $conn->commit();

    echo json_encode(['success' => true, 'message' => 'User added successfully!']);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Failed to add user: ' . $e->getMessage()]);
}

$conn->close();
?>
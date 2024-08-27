<?php
session_start();
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Get the username of the logged-in user from the session
$username = $_SESSION['user'] ?? null;

if ($username === null) {
    echo json_encode(['success' => false, 'message' => 'Username not found in session']);
    exit();
}

// Database connection details
$host = "localhost";
$username_db = "root";
$password_db = "";
$database = "web";

// Establish a database connection
$conn = new mysqli($host, $username_db, $password_db, $database);

// Check the connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Fetch addresses with latitude and longitude for the logged-in user
$sql = "SELECT latitude, longitude FROM CIVILIAN WHERE civilian_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username); // Bind the username to the query

$stmt->execute();
$result = $stmt->get_result();

$addresses = [];
while ($row = $result->fetch_assoc()) {
    $addresses[] = [
        'latitude' => $row['latitude'],
        'longitude' => $row['longitude']
    ];
}

echo json_encode($addresses);

$stmt->close();
$conn->close();
?>

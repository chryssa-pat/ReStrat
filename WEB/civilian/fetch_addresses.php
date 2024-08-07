<?php
session_start();
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$database = "web";

// Establish a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Fetch addresses with latitude and longitude
$sql = "SELECT latitude, longitude FROM CIVILIAN";
$result = $conn->query($sql);

$addresses = [];
while ($row = $result->fetch_assoc()) {
    $addresses[] = [
        'latitude' => $row['latitude'],
        'longitude' => $row['longitude']
    ];
}

echo json_encode($addresses);

$conn->close();
?>

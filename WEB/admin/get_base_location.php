<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

$conn = new mysqli("localhost", "root", "", "web");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

$sql = "SELECT latitude_vehicle, longitude_vehicle FROM ADMIN LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        'success' => true,
        'latitude' => $row['latitude_vehicle'],
        'longitude' => $row['longitude_vehicle']
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Base location not found']);
}

$conn->close();
?>
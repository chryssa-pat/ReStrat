<?php
session_start();
header('Content-Type: application/json');

// Ελέγχουμε αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

// Συνδεόμαστε στη βάση δεδομένων
$conn = new mysqli("localhost", "root", "", "web");

// Ελέγχουμε τη σύνδεση
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

// Ανακτούμε τις συντεταγμένες της βάσης από τον πίνακα admin
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
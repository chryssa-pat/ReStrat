<?php
session_start();
header('Content-Type: application/json');

// Ελέγχουμε αν ο χρήστης είναι συνδεδεμένος και είναι εθελοντής
if (!isset($_SESSION['user']) || $_SESSION['user_profile'] !== 'volunteer') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

// Λαμβάνουμε τις συντεταγμένες από το POST request
$latitude = $_POST['latitude'] ?? null;
$longitude = $_POST['longitude'] ?? null;

// Ελέγχουμε αν οι συντεταγμένες είναι έγκυρες
if ($latitude === null || $longitude === null || !is_numeric($latitude) || !is_numeric($longitude)) {
    echo json_encode(['success' => false, 'error' => 'Invalid coordinates']);
    exit;
}

// Συνδεόμαστε στη βάση δεδομένων
$conn = new mysqli("localhost", "root", "", "web");

// Ελέγχουμε τη σύνδεση
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

// Ενημερώνουμε τις συντεταγμένες στον πίνακα vehicle
$sql = "UPDATE vehicle SET latitude_vehicle = ?, longitude_vehicle = ? WHERE vehicle_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ddi", $latitude, $longitude, $_SESSION['vehicle_id']);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update location']);
}

$stmt->close();
$conn->close();
?>
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

// Ενημερώνουμε τις συντεταγμένες στον πίνακα VOLUNTEER
$sql = "UPDATE VOLUNTEER SET latitude_vehicle = ?, longtitude_vehicle = ? WHERE volunteer_user = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

// Assuming $_SESSION['user'] holds the volunteer_user
$stmt->bind_param("dds", $latitude, $longitude, $_SESSION['user']);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update location']);
}

$stmt->close();
$conn->close();
?>
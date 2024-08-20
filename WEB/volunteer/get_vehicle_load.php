<?php
session_start();
header('Content-Type: application/json');

// Ελέγχουμε αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

$conn = new mysqli("localhost", "root", "", "web");

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Get the vehicle_id associated with the logged-in user
$userId = $_SESSION['user']; // Adjust this based on your session structure
$stmt = $conn->prepare("SELECT vehicle FROM VOLUNTEER WHERE volunteer_user = ?");
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'No vehicle found for the user']);
    exit;
}

$vehicleId = $result->fetch_assoc()['vehicle'];

// Retrieve the load for the user's vehicle
$sql = "SELECT vl.item, vl.quantity, p.description 
        FROM VEHICLE_LOAD vl
        JOIN PRODUCTS p ON vl.item = p.item
        WHERE vl.vehicle_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $vehicleId);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    $products = [];
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode(['success' => true, 'products' => $products]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

$stmt->close();
$conn->close();
?>
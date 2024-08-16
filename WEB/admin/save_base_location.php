<?php
session_start();
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

// Ensure only POST requests are processed
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
    exit;
}

// Get the raw POST data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Validate the data
if (!isset($data['latitude']) || !isset($data['longitude'])) {
    echo json_encode(["success" => false, "error" => "Invalid data"]);
    exit;
}

$latitude = floatval($data['latitude']);
$longitude = floatval($data['longitude']);

// Database connection
$conn = new mysqli("localhost", "root", "", "web");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Update the base location in the database
$sql = "UPDATE ADMIN SET latitude_vehicle = ?, longitude_vehicle = ? WHERE admin_user = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Prepare statement failed: ' . $conn->error]);
    exit;
}

$admin_user = $_SESSION['user']; // Assuming the session variable holds the admin username
$stmt->bind_param("dds", $latitude, $longitude, $admin_user);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "No rows updated. Make sure the admin user exists in the ADMIN table."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Execute failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
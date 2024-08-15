<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['user']['profile'] !== 'volunteer') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

$conn = new mysqli("localhost", "root", "", "web");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

$username = $_SESSION['user']['username'];

$sql = "SELECT vehicle FROM volunteer WHERE volunteer_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$vehicle_id = $row['vehicle'];

$sql = "SELECT product_id, quantity FROM vehicle_load WHERE vehicle_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vehicle_id);
$stmt->execute();
$result = $stmt->get_result();
$vehicle_load = [];
while ($row = $result->fetch_assoc()) {
    $vehicle_load[] = $row;
}

echo json_encode(['success' => true, 'vehicle_load' => $vehicle_load]);

$conn->close();
?>
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

$sql = "SELECT vl.item, vl.quantity, p.description 
        FROM VEHICLE_LOAD vl
        JOIN PRODUCTS p ON vl.item = p.item
        WHERE vl.vehicle_id = (SELECT vehicle FROM VOLUNTEER LIMIT 1)";

$result = $conn->query($sql);

if ($result) {
    $products = [];
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode(['success' => true, 'products' => $products]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

$conn->close();
?>
<?php
header('Content-Type: application/json');

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "web";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Use prepared statement to prevent SQL injection
$query = "UPDATE PRODUCTS SET available = ? WHERE product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $quantity, $product_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => "Quantity updated successfully"]);
} else {
    echo json_encode(['success' => false, 'message' => "Error updating quantity: " . $conn->error]);
}

$stmt->close();
$conn->close();
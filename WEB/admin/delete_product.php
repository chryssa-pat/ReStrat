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

// Use prepared statement to prevent SQL injection
$query = "DELETE FROM PRODUCTS WHERE product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => "Product deleted successfully"]);
} else {
    echo json_encode(['success' => false, 'message' => "Error deleting product: " . $conn->error]);
}

$stmt->close();
$conn->close();
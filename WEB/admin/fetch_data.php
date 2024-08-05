<?php
session_start();

// Check if the user is logged in and has administrator privileges
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'administrator') {
    header('HTTP/1.0 403 Forbidden');
    echo json_encode(['error' => 'Access denied']);
    exit;
}

// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$database = "web";

// Establish a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    header('HTTP/1.0 500 Internal Server Error');
    echo json_encode(['error' => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Fetch categories and products
$query = "SELECT c.category_name, p.item, p.product_id, p.available 
          FROM CATEGORIES c
          JOIN PRODUCTS p ON c.category_id = p.category_id";
$result = $conn->query($query);

if (!$result) {
    header('HTTP/1.0 500 Internal Server Error');
    echo json_encode(['error' => "Query failed: " . $conn->error]);
    $conn->close();
    exit;
}

// Prepare data for JSON response
$data = [
    'categories' => [],
    'items' => [],
];

while ($row = $result->fetch_assoc()) {
    $category = $row['category_name'];
    $item = $row['item'];
    $productId = $row['product_id'];
    $available = $row['available'];

    // Add category if it's not already in the list
    if (!in_array($category, $data['categories'])) {
        $data['categories'][] = $category;
    }

    // Add item to the category
    if (!isset($data['items'][$category])) {
        $data['items'][$category] = [];
    }
    $data['items'][$category][] = [
        'id' => $productId,
        'name' => $item,
        'available' => $available
    ];
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($data);

// Close the database connection
$conn->close();
?>
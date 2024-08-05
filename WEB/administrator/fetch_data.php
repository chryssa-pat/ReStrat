<?php
session_start();

// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$database = "web";

// Establish a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories and products
$query = "SELECT c.category_name, p.item, p.product_id, p.description, p.available 
          FROM CATEGORIES c
          JOIN PRODUCTS p ON c.category_id = p.category_id";
$result = mysqli_query($conn, $query);

// Prepare data for JSON response
$data = [
    'categories' => [],
    'items' => [],
];

while ($row = mysqli_fetch_assoc($result)) {
    $category = $row['category_name'];
    $item = $row['item'];
    $productId = $row['product_id'];
    $description = $row['description'];
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
        'description' => $description,
        'available' => $available
    ];
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($data);

// Close the database connection
$conn->close();
?>
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
    header('HTTP/1.0 500 Internal Server Error');
    echo json_encode(['error' => "Connection failed: " . $conn->connect_error]);
    exit;
}


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

$data = [
    'categories' => [],
    'items' => [],
];

$rowCount = 0;
while ($row = $result->fetch_assoc()) {
    $rowCount++;
    $category = $row['category_name'];
    $item = $row['item'];
    $productId = $row['product_id'];
    $available = $row['available'];

    if (!in_array($category, $data['categories'])) {
        $data['categories'][] = $category;
    }

    if (!isset($data['items'][$category])) {
        $data['items'][$category] = [];
    }
    $data['items'][$category][] = [
        'id' => $productId,
        'name' => $item,
        'available' => $available
    ];
}

$data['debug'] = [
    'rowCount' => $rowCount,
    'categoriesCount' => count($data['categories']),
    'itemsCount' => count($data['items']),
];

header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>
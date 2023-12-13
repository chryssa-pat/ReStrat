<?php
session_start();

// Establish a database connection
$conn = new mysqli("localhost", "root", "canmp168", "web");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch distinct categories
$query_categories = "SELECT DISTINCT category FROM PRODUCTS";
$result_categories = mysqli_query($conn, $query_categories);

// Fetch items for each category
$query_items = "SELECT category, item FROM PRODUCTS";
$result_items = mysqli_query($conn, $query_items);

// Prepare data for JSON response
$data = [
    'categories' => [],
    'items' => [],
];

// Keep track of unique categories
$uniqueCategories = [];

while ($row = mysqli_fetch_assoc($result_categories)) {
    $category = $row['category'];

    // Add the category to the list if it hasn't been added before
    if (!in_array($category, $uniqueCategories)) {
        $uniqueCategories[] = $category;
        $data['categories'][] = $category;
    }

    // Initialize the items array for the category
    $data['items'][$category] = [];
}

// Populate items for each category
while ($row = mysqli_fetch_assoc($result_items)) {
    $category = $row['category'];
    $item = $row['item'];

    // Add the item to the list for the corresponding category
    $data['items'][$category][] = $item;
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($data);

// Close the database connection
$conn->close();
?>

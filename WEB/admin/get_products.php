<?php
header('Content-Type: application/json');

include('../main/session_check.php'); // Include your database connection file

$data = json_decode(file_get_contents('php://input'), true);

$category_ids = $data['category_ids']; // Get array of category IDs

$host = "localhost";
$username = "root";
$password = "";
$database = "web";

// Establish connection
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(array('error' => 'Database connection failed: ' . $conn->connect_error)));
}

// Convert category IDs array to comma-separated string for SQL IN clause
$category_ids_str = implode(',', array_map('intval', $category_ids));

$sql = "SELECT p.product_id, p.item, p.available, 
        GROUP_CONCAT(DISTINCT CONCAT(pd.detail_name, ': ', pd.detail_value) SEPARATOR ', ') AS details,
        vl.vehicle_id, vl.quantity
        FROM PRODUCTS p
        LEFT JOIN PRODUCT_DETAILS pd ON p.product_id = pd.product_id
        LEFT JOIN VEHICLE_LOAD vl ON p.item = vl.item
        WHERE p.category_id IN ($category_ids_str)
        GROUP BY p.product_id, vl.vehicle_id, vl.quantity";

$result = $conn->query($sql);

$products = array();
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$conn->close();

echo json_encode($products);
?>
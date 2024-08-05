<?php
header('Content-Type: application/json');

$host = "localhost";
$username = "root";
$password = "";
$database = "web";

// Establish connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(array('error' => 'Database connection failed: ' . $conn->connect_error)));
}

$sql = "SELECT category_id, category_name FROM CATEGORIES";
$result = $conn->query($sql);

$categories = array();
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

$conn->close();

echo json_encode($categories);
?>

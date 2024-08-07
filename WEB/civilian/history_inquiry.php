<?php
session_start();
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
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
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Get the logged-in user
$loggedInUser = $_SESSION['user'];

// Fetch inquiries for the logged-in user
$sql = "SELECT inquiry_status, inquiry_user, p.item, inquiry_quantity, inquiry_date
        FROM INQUIRY i
        JOIN PRODUCTS p ON i.inquiry_product = p.product_id
        WHERE i.inquiry_user = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $loggedInUser);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $inquiries = [];
    while ($row = $result->fetch_assoc()) {
        $inquiries[] = $row;
    }
    
    echo json_encode($inquiries);
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Database query failed']);
}

$conn->close();
?>
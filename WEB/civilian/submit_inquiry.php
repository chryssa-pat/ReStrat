<?php
session_start();
header('Content-Type: application/json');

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $category = $_POST['category']; // This may not be used directly, as your form submission seems to include item ID directly
    $item = $_POST['item'];
    $quantity = $_POST['quantity'];

    // Check if user is logged in
    if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit;
    }
    $user = $_SESSION['user'];

    // Validate inputs
    if (empty($item) || empty($quantity)) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO INQUIRY (inquiry_status, inquiry_user, inquiry_product, inquiry_quantity) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $status = 'approved'; // Or set this based on your business logic
        $stmt->bind_param("sssi", $status, $user, $item, $quantity);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>

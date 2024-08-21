<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

include('../main/session_check.php');

// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'web';

// Create a new connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("POST data received: " . print_r($_POST, true));

    // Validate input
    if (!isset($_POST['productId'], $_POST['categoryId'], $_POST['item'], $_POST['description'], $_POST['available'])) {
        die(json_encode(['success' => false, 'message' => "Missing required fields"]));
    }

    $productId = intval($_POST['productId']);
    $categoryId = intval($_POST['categoryId']);
    $item = trim($_POST['item']);
    $description = trim($_POST['description']);
    $available = intval($_POST['available']);

    // Insert product into PRODUCTS table
    $sql = "INSERT INTO PRODUCTS (product_id, category_id, item, description, available) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        die(json_encode(['success' => false, 'message' => "Prepare failed: " . $conn->error]));
    }

    $stmt->bind_param("iissi", $productId, $categoryId, $item, $description, $available);

    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        die(json_encode(['success' => false, 'message' => "Execute failed: " . $stmt->error]));
    }

    error_log("Product added successfully. Product ID: " . $productId);

    // Handle product details
    if (isset($_POST['detailName']) && isset($_POST['detailValue'])) {
        $detailNames = $_POST['detailName'];
        $detailValues = $_POST['detailValue'];

        $detailSql = "INSERT INTO PRODUCT_DETAILS (product_id, detail_name, detail_value) VALUES (?, ?, ?)";
        $detailStmt = $conn->prepare($detailSql);

        if (!$detailStmt) {
            error_log("Prepare failed for details: " . $conn->error);
            die(json_encode(['success' => false, 'message' => "Prepare failed for details: " . $conn->error]));
        }

        foreach ($detailNames as $key => $detailName) {
            if (empty($detailName)) continue; // Skip empty detail names
            $detailValue = $detailValues[$key] ?? '';
            $detailStmt->bind_param("iss", $productId, $detailName, $detailValue);
            if (!$detailStmt->execute()) {
                error_log("Execute failed for details: " . $detailStmt->error);
                die(json_encode(['success' => false, 'message' => "Execute failed for details: " . $detailStmt->error]));
            }
        }

        $detailStmt->close();
    }

    $stmt->close();
    echo json_encode(['success' => true, 'message' => "Product added successfully. Product ID: " . $productId]);
} else {
    echo json_encode(['success' => false, 'message' => "Invalid request method"]);
}

$conn->close();
?>
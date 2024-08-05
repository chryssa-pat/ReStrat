<?php
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

// Get the form data
$categoryId = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
$itemName = isset($_POST['item_name']) ? $_POST['item_name'] : '';
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

// Log received data for debugging
error_log("Received data: category_id=$categoryId, item_name='$itemName', quantity=$quantity");

// Check if itemName is empty
if (empty($itemName)) {
    echo json_encode(['success' => false, 'error' => 'Item name is empty']);
    exit();
}

// Function to get product_id from item name
function getProductIdByName($conn, $itemName) {
    $itemName = trim($itemName);
    $itemName = strtolower($itemName);



    $stmt = $conn->prepare("SELECT product_id FROM PRODUCTS WHERE product_id = ?");
    if ($stmt) {
        $stmt->bind_param("s", $itemName);
        $stmt->execute();
        $stmt->bind_result($productId);

        if ($stmt->fetch()) {
            $stmt->close();
            return $productId;
        }
        $stmt->close();
    }
    return null;
}


// Get the product_id from item name
$productId = getProductIdByName($conn, $itemName);

if ($productId === null) {
    echo json_encode(['success' => false, 'error' => 'Product not found for item: ' . $itemName]);
    exit();
}

if ($quantity > 0) {
    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO ANNOUNCEMENTS (announce_product, announce_quantity) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param("ii", $productId, $quantity);

        // Execute the statement
        if ($stmt->execute()) {
            error_log("Announcement created successfully with Product ID: $productId and Quantity: $quantity");
            echo json_encode(['success' => true, 'message' => 'Announcement created successfully!']);
        } else {
            error_log("SQL Error: " . $stmt->error);
            echo json_encode(['success' => false, 'error' => 'SQL Error: ' . $stmt->error]);
        }

        // Close the statement
        $stmt->close();
    } else {
        error_log("Failed to prepare the SQL statement");
        echo json_encode(['success' => false, 'error' => 'Failed to prepare the SQL statement']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid quantity']);
}

// Close the connection
$conn->close();
?>
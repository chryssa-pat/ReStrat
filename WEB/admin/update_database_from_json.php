<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_FILES['jsonFile']) || $_FILES['jsonFile']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
    exit;
}

$jsonContent = file_get_contents($_FILES['jsonFile']['tmp_name']);
$data = json_decode($jsonContent, true);

if ($data === null) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON file']);
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Start transaction
$conn->begin_transaction();

try {
    // Update categories
    $stmt = $conn->prepare("INSERT INTO CATEGORIES (category_id, category_name) VALUES (?, ?) ON DUPLICATE KEY UPDATE category_name = VALUES(category_name)");
    foreach ($data['categories'] as $category) {
        $stmt->bind_param("is", $category['id'], $category['category_name']);
        $stmt->execute();
    }
    $stmt->close();

    // Update products and product details
    $stmt_product = $conn->prepare("INSERT INTO PRODUCTS (product_id, category_id, item, description, available) VALUES (?, ?, ?, ?, 0) ON DUPLICATE KEY UPDATE category_id = VALUES(category_id), item = VALUES(item), description = VALUES(description)");
    $stmt_details = $conn->prepare("INSERT INTO PRODUCT_DETAILS (product_id, detail_name, detail_value) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE detail_value = VALUES(detail_value)");

    foreach ($data['items'] as $item) {
        $stmt_product->bind_param("iiss", $item['id'], $item['category'], $item['name'], $item['name']);
        $stmt_product->execute();

        foreach ($item['details'] as $detail) {
            $stmt_details->bind_param("iss", $item['id'], $detail['detail_name'], $detail['detail_value']);
            $stmt_details->execute();
        }
    }

    $stmt_product->close();
    $stmt_details->close();

    // Commit transaction
    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Database updated successfully']);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Error updating database: ' . $e->getMessage()]);
}

$conn->close();
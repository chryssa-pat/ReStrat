<?php
include('../main/session_check.php');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to download and decode JSON
function getJSONData($url) {
    $json = file_get_contents($url);
    return json_decode($json, true);
}

// Download and process JSON data
$data = getJSONData('http://usidas.ceid.upatras.gr/web/2023/export.php');

if ($data['code'] == 1) {
    // Start a transaction
    $conn->begin_transaction();

    try {
        // Update existing categories and insert new ones
        $stmt = $conn->prepare("INSERT INTO CATEGORIES (category_id, category_name) VALUES (?, ?) ON DUPLICATE KEY UPDATE category_name = VALUES(category_name)");
        foreach ($data['categories'] as $category) {
            $stmt->bind_param("is", $category['id'], $category['category_name']);
            $stmt->execute();
        }
        $stmt->close();

        // Update existing products and insert new ones
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

        // Commit the transaction
        $conn->commit();
        echo "Database updated successfully!";
    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        $conn->rollback();
        echo "Error updating database: " . $e->getMessage();
    }
} else {
    echo "Error retrieving data from the API.";
}

$conn->close();
?>
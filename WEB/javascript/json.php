<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "WEB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected to the database<br>";

// Read JSON file
$json_file_path = 'C:/xampp/htdocs/WEB/javascript/products.json';
$json_data = file_get_contents($json_file_path);

if ($json_data === false) {
    die("Error reading JSON file at $json_file_path");
}

// Decode JSON data
$data = json_decode($json_data, true);

if ($data === null) {
    die("Error decoding JSON data: " . json_last_error_msg());
}

// Check if the JSON data is an array and contains the required keys
if (!isset($data['items']) || !isset($data['categories'])) {
    die("Error: Required keys ('items' or 'categories') not found in JSON data");
}

// Insert categories into CATEGORIES table
$existing_category_ids = [];
foreach ($data['categories'] as $category) {
    $category_id = (int)$category['id'];
    $category_name = $category['category_name'];

    // Skip if category_name is empty
    if (empty($category_name)) {
        echo "Skipping empty category_name for ID $category_id<br>";
        continue;
    }

    // Insert category into CATEGORIES table
    $category_query = "INSERT INTO CATEGORIES (category_id, category_name) VALUES (?, ?) ON DUPLICATE KEY UPDATE category_name = VALUES(category_name)";
    $stmt = $conn->prepare($category_query);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("is", $category_id, $category_name);
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }
    echo "Category inserted: $category_name<br>";
    $existing_category_ids[$category_id] = true; // Track inserted categories
}

// Insert data into PRODUCTS and PRODUCT_DETAILS tables
foreach ($data['items'] as $item) {
    $product_id = (int)$item['id'];
    $item_name = $item['name'];
    $category_id = (int)$item['category'];
    $details = $item['details'];

    // Check if category_id exists
    if (!isset($existing_category_ids[$category_id])) {
        echo "Skipping product insertion with non-existent category_id $category_id for product $item_name<br>";
        continue;
    }

    // Insert or update product in PRODUCTS table
    $product_query = "INSERT INTO PRODUCTS (product_id, category_id, item, description, available) 
                      VALUES (?, ?, ?, '', 1) 
                      ON DUPLICATE KEY UPDATE 
                          category_id = VALUES(category_id), 
                          item = VALUES(item), 
                          description = VALUES(description), 
                          available = VALUES(available)";
    $stmt = $conn->prepare($product_query);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("iis", $product_id, $category_id, $item_name);
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }
    echo "Product inserted: $item_name<br>";

    // Insert product details into PRODUCT_DETAILS table
    foreach ($details as $detail) {
        $detail_name = $detail['detail_name'];
        $detail_value = $detail['detail_value'];

        $detail_query = "INSERT INTO PRODUCT_DETAILS (product_id, detail_name, detail_value) 
                         VALUES (?, ?, ?)";
        $stmt = $conn->prepare($detail_query);
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("iss", $product_id, $detail_name, $detail_value);
        if (!$stmt->execute()) {
            die("Error executing statement: " . $stmt->error);
        }
        echo "Detail inserted for product $product_id: $detail_name - $detail_value<br>";
    }
}

// Close  connection
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();

echo "Data inserted successfully.";
?>

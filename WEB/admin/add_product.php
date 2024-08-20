<?php
include('../main/session_check.php');
include('../main/db_connection.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categoryId = $_POST['categoryId'];
    $item = $_POST['item'];
    $description = $_POST['description'];
    $available = $_POST['available'];
    $detailNames = $_POST['detailName'];
    $detailValues = $_POST['detailValue'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert into PRODUCTS table
        $stmt = $conn->prepare("INSERT INTO PRODUCTS (category_id, item, description, available) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $categoryId, $item, $description, $available);
        $stmt->execute();
        $productId = $conn->insert_id;
        $stmt->close();

        // Insert into PRODUCT_DETAILS table
        if (!empty($detailNames)) {
            $stmt = $conn->prepare("INSERT INTO PRODUCT_DETAILS (product_id, detail_name, detail_value) VALUES (?, ?, ?)");
            foreach ($detailNames as $index => $detailName) {
                if (!empty($detailName)) {
                    $detailValue = $detailValues[$index];
                    $stmt->bind_param("iss", $productId, $detailName, $detailValue);
                    $stmt->execute();
                }
            }
            $stmt->close();
        }

        // Commit transaction
        $conn->commit();
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
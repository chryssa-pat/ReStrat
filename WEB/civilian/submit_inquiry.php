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

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert into INQUIRY table
        $sql1 = "INSERT INTO INQUIRY (inquiry_user, inquiry_product, inquiry_quantity) VALUES (?, ?, ?)";
        $stmt1 = $conn->prepare($sql1);
        if (!$stmt1) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }
        $stmt1->bind_param("sii", $user, $item, $quantity);
        if (!$stmt1->execute()) {
            throw new Exception('Execute failed: ' . $stmt1->error);
        }
        $inquiry_id = $conn->insert_id;
        $stmt1->close();

        // Insert into INQUIRY_DETAILS table
        $sql2 = "INSERT INTO INQUIRY_DETAILS (details_id, inquiry_status) VALUES (?, ?)";
        $stmt2 = $conn->prepare($sql2);
        if (!$stmt2) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }
        $status = 'pending'; // Or set this based on your business logic
        $stmt2->bind_param("is", $inquiry_id, $status);
        if (!$stmt2->execute()) {
            throw new Exception('Execute failed: ' . $stmt2->error);
        }
        $stmt2->close();

        // If we got here, it means both queries were successful
        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
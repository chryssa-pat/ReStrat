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

    // Check if the user exists in the CIVILIAN table
    $check_user_sql = "SELECT civilian_user FROM CIVILIAN WHERE civilian_user = ?";
    $check_user_stmt = $conn->prepare($check_user_sql);
    $check_user_stmt->bind_param("s", $user);
    $check_user_stmt->execute();
    $check_user_result = $check_user_stmt->get_result();

    if ($check_user_result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'User not found in the CIVILIAN table.']);
        exit;
    }
    $check_user_stmt->close();

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
        $status = 'pending';
        $stmt2->bind_param("is", $inquiry_id, $status);
        if (!$stmt2->execute()) {
            throw new Exception('Execute failed: ' . $stmt2->error);
        }
        $stmt2->close();

        // Insert into INQUIRY_HISTORY table
        $sql3 = "INSERT INTO INQUIRY_HISTORY (inquiry_history_id, history_status) VALUES (?, ?)";
        $stmt3 = $conn->prepare($sql3);
        if (!$stmt3) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }
        $stmt3->bind_param("is", $inquiry_id, $status);
        if (!$stmt3->execute()) {
            throw new Exception('Execute failed: ' . $stmt3->error);
        }
        $stmt3->close();

        // If we got here, it means all queries were successful
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
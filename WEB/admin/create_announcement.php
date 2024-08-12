<?php
// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$database = "web";

// Establish a database connection
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die(json_encode(['success' => false, 'error' => "Connection failed: " . $conn->connect_error]));
}

// Decode the JSON input
$input = json_decode(file_get_contents('php://input'), true);
error_log("Decoded input: " . print_r($input, true));

if (!isset($input['items']) || !is_array($input['items'])) {
    error_log("Invalid input structure");
    die(json_encode(['success' => false, 'error' => "Invalid input structure"]));
}

$items = $input['items'];

// Start transaction
$conn->begin_transaction();

try {
    // Insert into ANNOUNCEMENTS table
    $stmt = $conn->prepare("INSERT INTO ANNOUNCEMENTS (created_at) VALUES (NOW())");
    if (!$stmt->execute()) {
        throw new Exception("Failed to create announcement: " . $stmt->error);
    }
    $announceId = $conn->insert_id;
    $stmt->close();

    // Insert items into ANNOUNCEMENT_ITEMS table
    foreach ($items as $item) {
        $stmt = $conn->prepare("INSERT INTO ANNOUNCEMENT_ITEMS (announce_id, announce_product, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $announceId, $item['id'], $item['quantity']);
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert item: " . $stmt->error);
        }
        $stmt->close();
    }

    // Commit transaction
    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Announcement created successfully']);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    error_log("Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
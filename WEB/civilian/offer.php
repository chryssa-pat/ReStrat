<?php
include('../main/session_check.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log all POST data
error_log("POST data: " . print_r($_POST, true));

$host = "localhost";
$username = "root";
$password = "";
$database = "web";

// Establish connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => "Connection failed: " . $conn->connect_error]));
}

// Retrieve input data
$announce_id = $_POST['announce_id'] ?? null;
$product_id = $_POST['product_id'] ?? null;
$offer_quantity = $_POST['offer_quantity'] ?? null;
$offer_user = $_SESSION['user'] ?? null;
$offer_status = 'pending';

// Validate input
if (!$announce_id || !$product_id || !$offer_quantity || !$offer_user) {
    die(json_encode(['success' => false, 'error' => 'Missing required data']));
}

// Debugging output
error_log("Offer User: " . $offer_user);
error_log("Announcement ID: " . $announce_id);
error_log("Product ID: " . $product_id);
error_log("Offer Quantity: " . $offer_quantity);

// Check if announcement item exists
$sql = "SELECT quantity FROM ANNOUNCEMENT_ITEMS WHERE announce_id = ? AND announce_product = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die(json_encode(['success' => false, 'error' => 'Prepare failed: ' . $conn->error]));
}
$stmt->bind_param("ii", $announce_id, $product_id);
if (!$stmt->execute()) {
    die(json_encode(['success' => false, 'error' => 'Execute failed: ' . $stmt->error]));
}
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die(json_encode(['success' => false, 'error' => 'Announcement item not found']));
}

$stmt->bind_result($announce_quantity);
$stmt->fetch();
$stmt->close();

// Check if offer quantity is valid
if ($announce_quantity >= $offer_quantity) {
    $new_quantity = $announce_quantity - $offer_quantity;

    // Update announcement item quantity
    $sql = "UPDATE ANNOUNCEMENT_ITEMS SET quantity = ? WHERE announce_id = ? AND announce_product = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die(json_encode(['success' => false, 'error' => 'Prepare failed: ' . $conn->error]));
    }
    $stmt->bind_param("iii", $new_quantity, $announce_id, $product_id);
    if (!$stmt->execute()) {
        die(json_encode(['success' => false, 'error' => 'Failed to update announcement item: ' . $stmt->error]));
    }
    $stmt->close();

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert offer into the OFFERS table
        $sql = "INSERT INTO OFFERS (offer_user, offer_product, offer_quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }
        $stmt->bind_param("sii", $offer_user, $product_id, $offer_quantity);
        if (!$stmt->execute()) {
            throw new Exception('Failed to insert offer: ' . $stmt->error);
        }
        $offer_id = $stmt->insert_id;
        $stmt->close();

        // Insert offer details into the OFFERS_DETAILS table
        $sql = "INSERT INTO OFFERS_DETAILS (details_id, offer_status, offer_date) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }
        $stmt->bind_param("is", $offer_id, $offer_status);
        if (!$stmt->execute()) {
            throw new Exception('Failed to insert offer details: ' . $stmt->error);
        }
        $stmt->close();

        // Commit transaction
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Offer submitted successfully']);
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        die(json_encode(['success' => false, 'error' => $e->getMessage()]));
    }
} else {
    echo json_encode(['success' => false, 'error' => 'There is need only for ' . $announce_quantity]);
}

$conn->close();
?>
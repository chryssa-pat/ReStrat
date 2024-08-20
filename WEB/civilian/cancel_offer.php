<?php
include('../main/session_check.php');
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

$offer_id = $_POST['offer_id'] ?? null;
$offer_user = $_SESSION['user'];

if (!$offer_id) {
    die(json_encode(['success' => false, 'error' => 'Missing offer ID']));
}

// Start transaction
$conn->begin_transaction();

try {
    // Check if the offer belongs to the current user and is in 'pending' or 'approved' status
    $sql = "SELECT o.offer_id, o.offer_quantity, a.quantity, o.announce_id, o.offer_product 
            FROM OFFERS o
            JOIN OFFER_HISTORY oh ON o.offer_id = oh.offer_history_id
            JOIN ANNOUNCEMENT_ITEMS a ON o.announce_id = a.announce_id AND o.offer_product = a.announce_product
            WHERE o.offer_id = ? AND o.offer_user = ? AND (oh.history_status = 'pending' OR oh.history_status = 'approved')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $offer_id, $offer_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('Offer not found or not eligible for cancellation');
    }

    $row = $result->fetch_assoc();
    $offer_quantity = $row['offer_quantity'];
    $announcement_quantity = $row['quantity'];
    $announce_id = $row['announce_id'];
    $offer_product = $row['offer_product']; // Get the product ID

    // Debugging output
    error_log("Offer Quantity: " . $offer_quantity);
    error_log("Announcement Quantity: " . $announcement_quantity);
    
    // Update the announcement item quantity for the specific product
    $new_quantity = $announcement_quantity + $offer_quantity;
    error_log("New Quantity: " . $new_quantity);

    $sql = "UPDATE ANNOUNCEMENT_ITEMS SET quantity = ? WHERE announce_id = ? AND announce_product = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $new_quantity, $announce_id, $offer_product); // Update only for the specific product
    if (!$stmt->execute()) {
        throw new Exception('Failed to update announcement item quantity: ' . $stmt->error);
    }

    // Insert a new 'cancelled' status entry into OFFER_HISTORY
    $sql = "INSERT INTO OFFER_HISTORY (offer_history_id, history_status) VALUES (?, 'cancelled')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $offer_id);
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert cancelled status: ' . $stmt->error);
    }

    // Commit transaction
    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
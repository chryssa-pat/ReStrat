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
    // Check if the offer belongs to the current user and is in 'pending' status
    $sql = "SELECT o.offer_id FROM OFFERS o
            JOIN OFFERS_DETAILS od ON o.offer_id = od.details_id
            WHERE o.offer_id = ? AND o.offer_user = ? AND od.offer_status = 'pending'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $offer_id, $offer_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('Offer not found or not eligible for cancellation');
    }

    // Insert a new 'cancelled' status entry
    $sql = "INSERT INTO OFFERS_DETAILS (details_id, offer_status, offer_date) VALUES (?, 'cancelled', NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $offer_id);
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert cancelled status');
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
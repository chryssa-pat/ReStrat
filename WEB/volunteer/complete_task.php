<?php
session_start();
header('Content-Type: application/json');

// Ελέγχουμε αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

// Ελέγχουμε αν έχουμε λάβει τα απαραίτητα δεδομένα
if (!isset($_POST['detailsId']) || !isset($_POST['type'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required data']);
    exit;
}

$detailsId = $_POST['detailsId'];
$type = $_POST['type'];

$conn = new mysqli("localhost", "root", "", "web");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Ξεκινάμε μια συναλλαγή
$conn->begin_transaction();

try {
    // Επιλέγουμε τον κατάλληλο πίνακα και τα ονόματα των στηλών με βάση τον τύπο της εργασίας
    if ($type === 'inquiry') {
        $table = 'INQUIRY_DETAILS';
        $statusColumn = 'inquiry_status';
        $dateColumn = 'inquiry_date';
        $quantityColumn = 'inquiry_quantity';
        $mainTable = 'INQUIRY';
        $idColumn = 'inquiry_id';
        $productColumn = 'inquiry_product';
    } else if ($type === 'offer') {
        $table = 'OFFERS_DETAILS';
        $statusColumn = 'offer_status';
        $dateColumn = 'offer_date';
        $quantityColumn = 'offer_quantity';
        $mainTable = 'OFFERS';
        $idColumn = 'offer_id';
        $productColumn = 'offer_product';
    } else {
        throw new Exception('Invalid task type');
    }

    // Παίρνουμε τα τρέχοντα δεδομένα της εργασίας
    $sql = "SELECT d.*, m.$quantityColumn, m.$productColumn, d.approved_vehicle_id 
            FROM $table d
            JOIN $mainTable m ON d.details_id = m.$idColumn
            WHERE d.details_id = ? AND d.$statusColumn = 'approved'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $detailsId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('Approved task not found');
    }

    $currentTask = $result->fetch_assoc();

    // Ενημερώνουμε το status σε 'finished'
    $sql = "UPDATE $table SET $statusColumn = 'finished' WHERE details_id = ? AND $statusColumn = 'approved'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $detailsId);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception('Failed to update task status');
    }

    // Ενημερώνουμε το vehicle_load
    $quantity = $currentTask[$quantityColumn];
    $productId = $currentTask[$productColumn];
    $vehicleId = $currentTask['approved_vehicle_id'];

    // Παίρνουμε το item από τον πίνακα PRODUCTS
    $sql = "SELECT item FROM PRODUCTS WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $item = $product['item'];

    // Ελέγχουμε αν υπάρχει ήδη εγγραφή στο VEHICLE_LOAD
    $sql = "SELECT * FROM VEHICLE_LOAD WHERE vehicle_id = ? AND item = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $vehicleId, $item);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Υπάρχει ήδη εγγραφή, κάνουμε UPDATE
        if ($type === 'inquiry') {
            $sql = "UPDATE VEHICLE_LOAD SET quantity = quantity - ? WHERE vehicle_id = ? AND item = ?";
        } else {
            $sql = "UPDATE VEHICLE_LOAD SET quantity = quantity + ? WHERE vehicle_id = ? AND item = ?";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $quantity, $vehicleId, $item);
    } else {
        // Δεν υπάρχει εγγραφή, κάνουμε INSERT
        $sql = "INSERT INTO VEHICLE_LOAD (vehicle_id, item, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $finalQuantity = ($type === 'inquiry') ? -$quantity : $quantity;
        $stmt->bind_param("ssi", $vehicleId, $item, $finalQuantity);
    }
    $stmt->execute();

    // Προσθέτουμε εγγραφή στον κατάλληλο πίνακα ιστορικού
    if ($type === 'offer') {
        $sql = "INSERT INTO OFFER_HISTORY (offer_history_id, history_status) VALUES (?, 'finished')";
    } else { // inquiry
        $sql = "INSERT INTO INQUIRY_HISTORY (inquiry_history_id, history_status) VALUES (?, 'finished')";
    }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $detailsId);
    $stmt->execute();

    // Ελέγχουμε αν η εισαγωγή στο ιστορικό ήταν επιτυχής
    if ($stmt->affected_rows === 0) {
        throw new Exception('Failed to update history');
    }

    // Αν όλα πήγαν καλά, επικυρώνουμε τη συναλλαγή
    $conn->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    // Αν κάτι πήγε στραβά, αναιρούμε τη συναλλαγή
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
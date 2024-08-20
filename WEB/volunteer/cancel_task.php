<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

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
        $historyTable = 'INQUIRY_HISTORY';
        $historyIdColumn = 'inquiry_history_id';
    } else if ($type === 'offer') {
        $table = 'OFFERS_DETAILS';
        $statusColumn = 'offer_status';
        $historyTable = 'OFFER_HISTORY';
        $historyIdColumn = 'offer_history_id';
    } else {
        throw new Exception('Invalid task type');
    }

    // Ενημερώνουμε το status από 'approved' σε 'pending'
    $sql = "UPDATE $table SET $statusColumn = 'pending', approved_vehicle_id = NULL, approved_timestamp = NULL WHERE details_id = ? AND $statusColumn = 'approved'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $detailsId);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception('Task not found or not in approved status');
    }

    // Προσθέτουμε εγγραφή στον κατάλληλο πίνακα ιστορικού με status 'pending'
    $sql = "INSERT INTO $historyTable ($historyIdColumn, history_status) VALUES (?, 'pending')";
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
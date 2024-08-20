<?php
session_start();
header('Content-Type: application/json');

if (!isset($_POST['item']) || !isset($_POST['quantity'])) {
    echo json_encode(['success' => false, 'error' => 'Λείπουν απαραίτητα δεδομένα']);
    exit;
}

$item = $_POST['item'];
$quantity = intval($_POST['quantity']);

$conn = new mysqli("localhost", "root", "", "web");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Αποτυχία σύνδεσης στη βάση δεδομένων']);
    exit;
}

$conn->begin_transaction();

try {
    // Παίρνουμε το vehicle_id
    $stmt = $conn->prepare("SELECT vehicle FROM VOLUNTEER LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception("Δεν βρέθηκε όχημα");
    }
    $vehicleId = $result->fetch_assoc()['vehicle'];

    // Ελέγχουμε αν υπάρχει αρκετή ποσότητα στο όχημα
    $stmt = $conn->prepare("SELECT quantity FROM VEHICLE_LOAD WHERE vehicle_id = ? AND item = ? FOR UPDATE");
    $stmt->bind_param("ss", $vehicleId, $item);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception("Item  not found in vehicle!");
    }
    $row = $result->fetch_assoc();
    $loadedQuantity = $row['quantity'];
    if ($quantity > $loadedQuantity) {
        throw new Exception("There is not enough quantity in the vehicle!");
    }

    // Ενημερώνουμε την ποσότητα στο VEHICLE_LOAD
    $newLoadedQuantity = $loadedQuantity - $quantity;
    if ($newLoadedQuantity == 0) {
        $stmt = $conn->prepare("DELETE FROM VEHICLE_LOAD WHERE vehicle_id = ? AND item = ?");
        $stmt->bind_param("ss", $vehicleId, $item);
    } else {
        $stmt = $conn->prepare("UPDATE VEHICLE_LOAD SET quantity = ? WHERE vehicle_id = ? AND item = ?");
        $stmt->bind_param("iss", $newLoadedQuantity, $vehicleId, $item);
    }
    $stmt->execute();

    // Ενημερώνουμε την ποσότητα στο PRODUCTS
    $stmt = $conn->prepare("UPDATE PRODUCTS SET available = available + ? WHERE item = ?");
    $stmt->bind_param("is", $quantity, $item);
    $stmt->execute();

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Load updated successfully!']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
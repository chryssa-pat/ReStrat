<?php
session_start();
header('Content-Type: application/json');

if (!isset($_POST['type']) || !isset($_POST['id']) || !isset($_POST['timestamp'])) {
    echo json_encode(['success' => false, 'error' => 'Λείπουν απαραίτητα δεδομένα']);
    exit;
}

$type = $_POST['type'];
$id = $_POST['id'];
$timestamp = $_POST['timestamp'];

$conn = new mysqli("localhost", "root", "", "web");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Αποτυχία σύνδεσης στη βάση δεδομένων']);
    exit;
}

$conn->begin_transaction();

try {
    // Παίρνουμε το vehicle_id
    $stmt = $conn->prepare("SELECT vehicle FROM VOLUNTEER WHERE volunteer_user = ?"); // Get vehicle for logged-in user
    $stmt->bind_param("s", $_SESSION['user']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception("Δεν βρέθηκε όχημα");
    }
    $vehicleId = $result->fetch_assoc()['vehicle'];

    // Ελέγχουμε αν το vehicle_id είναι έγκυρο
    if ($vehicleId === '0' || $vehicleId === 0 || $vehicleId === null) {
        throw new Exception("Μη έγκυρο vehicle_id");
    }

    // Ελέγχουμε τον αριθμό των εγκεκριμένων αιτημάτων για αυτό το όχημα
    $stmt = $conn->prepare("
        SELECT COUNT(*) as approved_count
        FROM (
            SELECT approved_vehicle_id FROM INQUIRY_DETAILS
            WHERE inquiry_status = 'approved' AND approved_vehicle_id = ?
            UNION ALL
            SELECT approved_vehicle_id FROM OFFERS_DETAILS
            WHERE offer_status = 'approved' AND approved_vehicle_id = ?
        ) as combined
    ");
    $stmt->bind_param("ss", $vehicleId, $vehicleId);
    $stmt->execute();
    $result = $stmt->get_result();
    $approved_count = $result->fetch_assoc()['approved_count'];

    if ($approved_count >= 4) {
        echo json_encode(['success' => false, 'error' => 'You have reached the maximum amount of tasks you can approve!']);
        exit;
    }

    // Επιλέγουμε τους κατάλληλους πίνακες και στήλες
    if ($type === 'inquiry') {
        $details_table = 'INQUIRY_DETAILS';
        $status_column = 'inquiry_status';
        $history_table = 'INQUIRY_HISTORY';
        $history_id_column = 'inquiry_history_id';
        $main_table = 'INQUIRY';
        $product_column = 'inquiry_product';
        $quantity_column = 'inquiry_quantity';
    } else {
        $details_table = 'OFFERS_DETAILS';
        $status_column = 'offer_status';
        $history_table = 'OFFER_HISTORY';
        $history_id_column = 'offer_history_id';
        $main_table = 'OFFERS';
        $product_column = 'offer_product';
        $quantity_column = 'offer_quantity';
    }

    // Αν είναι inquiry, ελέγχουμε το vehicle_load
    if ($type === 'inquiry') {
        // Παίρνουμε το προϊόν και την ποσότητα του inquiry
        $stmt = $conn->prepare("SELECT $product_column, $quantity_column FROM $main_table WHERE inquiry_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            throw new Exception("Δεν βρέθηκε το inquiry");
        }
        $inquiry_data = $result->fetch_assoc();
        $product_id = $inquiry_data[$product_column];
        $inquiry_quantity = $inquiry_data[$quantity_column];

        // Ελέγχουμε αν υπάρχει το προϊόν στο vehicle_load και αν η ποσότητα επαρκεί
        $stmt = $conn->prepare("SELECT quantity FROM VEHICLE_LOAD WHERE vehicle_id = ? AND item = (SELECT item FROM PRODUCTS WHERE product_id = ?)");
        $stmt->bind_param("si", $vehicleId, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            echo json_encode(['success' => false, 'error' => 'The load of your vehicle does not contain this item!']);
            exit;
        }
        $vehicle_quantity = $result->fetch_assoc()['quantity'];
        if ($inquiry_quantity > $vehicle_quantity) {
            echo json_encode(['success' => false, 'error' => 'There is not enough load in your vehicle to approve this inquiry!']);
            exit;
        }
    }

    // Ενημερώνουμε τον πίνακα details
    $stmt = $conn->prepare("UPDATE $details_table SET $status_column = 'approved' WHERE details_id = ?");
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        throw new Exception("Αποτυχία ενημέρωσης πίνακα details");
    }

    // Ενημερώνουμε approved_vehicle_id και approved_timestamp
    $stmt = $conn->prepare("UPDATE $details_table SET approved_vehicle_id = ?, approved_timestamp = ? WHERE details_id = ?");
    $stmt->bind_param("ssi", $vehicleId, $timestamp, $id);
    if (!$stmt->execute()) {
        throw new Exception("Αποτυχία ενημέρωσης approved_vehicle_id και approved_timestamp");
    }

    // Προσθέτουμε εγγραφή στον κατάλληλο πίνακα ιστορικού με status 'approved'
    $stmt = $conn->prepare("INSERT INTO $history_table ($history_id_column, history_status) VALUES (?, 'approved')");
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        throw new Exception("Αποτυχία προσθήκης εγγραφής στο ιστορικό");
    }

    // Fetch the approved_vehicle_id and approved_timestamp
    $stmt = $conn->prepare("SELECT approved_vehicle_id, approved_timestamp FROM $details_table WHERE details_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception("Δεν βρέθηκε το approved_vehicle_id και approved_timestamp");
    }
    $approved_data = $result->fetch_assoc();

    $conn->commit();
    echo json_encode([
        'success' => true, 
        'message' => 'Το αίτημα εγκρίθηκε επιτυχώς',
        'vehicle_id' => $approved_data['approved_vehicle_id'], // Fetch from the SELECT query
        'approved_timestamp' => $approved_data['approved_timestamp'] // Fetch from the SELECT query
    ]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
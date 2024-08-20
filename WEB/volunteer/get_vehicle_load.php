<?php
session_start();
header('Content-Type: application/json');

// Ελέγχουμε αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

$conn = new mysqli("localhost", "root", "", "web");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Αποτυχία σύνδεσης στη βάση δεδομένων']);
    exit;
}

try {
    // Παίρνουμε το vehicle_id
    $stmt = $conn->prepare("SELECT vehicle FROM VOLUNTEER WHERE volunteer_user = ?");
    $stmt->bind_param("s", $_SESSION['user']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception("Δεν βρέθηκε όχημα για τον χρήστη");
    }
    $vehicleId = $result->fetch_assoc()['vehicle'];

    // Ελέγχουμε αν το vehicle_id είναι έγκυρο
    if ($vehicleId === '0' || $vehicleId === 0 || $vehicleId === null) {
        throw new Exception("Μη έγκυρο vehicle_id");
    }

    // Retrieve the load for the user's vehicle
    $sql = "SELECT vl.item, vl.quantity, p.description 
            FROM VEHICLE_LOAD vl
            JOIN PRODUCTS p ON vl.item = p.item
            WHERE vl.vehicle_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $vehicleId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $products = [];
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        echo json_encode(['success' => true, 'products' => $products]);
    } else {
        throw new Exception("Αποτυχία ανάκτησης δεδομένων φορτίου οχήματος");
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
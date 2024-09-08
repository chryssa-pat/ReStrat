<?php
session_start();
header('Content-Type: application/json');

// Ενεργοποίηση καταγραφής σφαλμάτων
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Ελέγχουμε αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Μη εξουσιοδοτημένη πρόσβαση']);
    exit;
}

// Ελέγχουμε αν έχουμε λάβει τα απαραίτητα δεδομένα
if (!isset($_POST['item']) || !isset($_POST['quantity'])) {
    echo json_encode(['success' => false, 'error' => 'Λείπουν απαραίτητα δεδομένα']);
    exit;
}

$item = $_POST['item'];
$quantity = intval($_POST['quantity']);

error_log("Processed unload data - item: $item, quantity: $quantity");

// Συνδεόμαστε στη βάση δεδομένων
$conn = new mysqli("localhost", "root", "", "web");

// Ελέγχουμε τη σύνδεση
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    echo json_encode(['success' => false, 'error' => 'Αποτυχία σύνδεσης στη βάση δεδομένων']);
    exit;
}

// Ξεκινάμε μια συναλλαγή
$conn->begin_transaction();

try {
    // Παίρνουμε το vehicle από τον πίνακα VOLUNTEER για τον συγκεκριμένο χρήστη
    $stmt = $conn->prepare("SELECT vehicle FROM VOLUNTEER WHERE volunteer_user = ?");
    $stmt->bind_param("s", $_SESSION['user']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception("No vehicle found for this user!");
    }
    $vehicleId = $result->fetch_assoc()['vehicle'];
    
    // Ελέγχουμε αν το vehicle_id είναι έγκυρο
    if ($vehicleId === '0' || $vehicleId === 0 || $vehicleId === null) {
        throw new Exception("Μη έγκυρο vehicle_id");
    }
    
    error_log("Retrieved vehicle: $vehicleId");

    // Ελέγχουμε αν υπάρχει το προϊόν στο VEHICLE_LOAD και αν υπάρχει αρκετή ποσότητα
    $stmt = $conn->prepare("SELECT quantity FROM VEHICLE_LOAD WHERE vehicle_id = ? AND item = ? FOR UPDATE");
    $stmt->bind_param("ss", $vehicleId, $item);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        error_log("Product not found in vehicle load - item: $item");
        throw new Exception("Product not found in vehicle load!");
    }
    
    $row = $result->fetch_assoc();
    $loadedQuantity = $row['quantity'];
    error_log("Loaded quantity: $loadedQuantity");
    
    if ($quantity > $loadedQuantity) {
        error_log("Not enough quantity in vehicle - requested: $quantity, available: $loadedQuantity");
        throw new Exception("The quantity you requested exceeds the vehicle load!");
    }
    
    // Ενημερώνουμε την ποσότητα στον πίνακα VEHICLE_LOAD
    $newLoadedQuantity = $loadedQuantity - $quantity;
    if ($newLoadedQuantity > 0) {
        $stmt = $conn->prepare("UPDATE VEHICLE_LOAD SET quantity = ? WHERE vehicle_id = ? AND item = ?");
        $stmt->bind_param("iss", $newLoadedQuantity, $vehicleId, $item);
    } else {
        $stmt = $conn->prepare("DELETE FROM VEHICLE_LOAD WHERE vehicle_id = ? AND item = ?");
        $stmt->bind_param("ss", $vehicleId, $item);
    }
    $stmt->execute();
    
    error_log("Updated vehicle load - new quantity: $newLoadedQuantity");
    
    // Ενημερώνουμε την ποσότητα στον πίνακα PRODUCTS
    $stmt = $conn->prepare("UPDATE PRODUCTS SET available = available + ? WHERE item = ?");
    $stmt->bind_param("is", $quantity, $item);
    $stmt->execute();
    
    error_log("Updated PRODUCTS - added quantity: $quantity");

    // Ολοκληρώνουμε τη συναλλαγή
    $conn->commit();
    
    echo json_encode(['success' => true, 'message' => 'Product unloaded successfully!']);
} catch (Exception $e) {
    // Σε περίπτωση σφάλματος, ακυρώνουμε τη συναλλαγή
    $conn->rollback();
    error_log("Error in transaction: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
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

error_log("Processed data - item: $item, quantity: $quantity");

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
        throw new Exception("Δεν βρέθηκε όχημα για τον χρήστη");
    }
    $vehicleId = $result->fetch_assoc()['vehicle'];
    
    // Ελέγχουμε αν το vehicle_id είναι έγκυρο
    if ($vehicleId === '0' || $vehicleId === 0 || $vehicleId === null) {
        throw new Exception("Μη έγκυρο vehicle_id");
    }
    
    error_log("Retrieved vehicle: $vehicleId");

    // Ελέγχουμε αν υπάρχει το προϊόν και αν υπάρχει αρκετή διαθέσιμη ποσότητα
    $stmt = $conn->prepare("SELECT available FROM PRODUCTS WHERE item = ? FOR UPDATE");
    $stmt->bind_param("s", $item);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        error_log("Product not found - item: $item");
        throw new Exception("Το προϊόν δεν βρέθηκε!");
    }
    
    $row = $result->fetch_assoc();
    $availableQuantity = $row['available'];
    error_log("Available quantity: $availableQuantity");
    
    if ($quantity > $availableQuantity) {
        error_log("Not enough quantity - requested: $quantity, available: $availableQuantity");
        throw new Exception("The quantity you exceeds the!");
    }
    
    // Ενημερώνουμε την ποσότητα στον πίνακα PRODUCTS
    $newQuantity = $availableQuantity - $quantity;
    $stmt = $conn->prepare("UPDATE PRODUCTS SET available = ? WHERE item = ?");
    $stmt->bind_param("is", $newQuantity, $item);
    $stmt->execute();
    
    error_log("Updated quantity - new available: $newQuantity");
    
    // Ελέγχουμε αν υπάρχει ήδη εγγραφή στον πίνακα VEHICLE_LOAD
    $stmt = $conn->prepare("SELECT quantity FROM VEHICLE_LOAD WHERE vehicle_id = ? AND item = ?");
    $stmt->bind_param("ss", $vehicleId, $item);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Υπάρχει ήδη εγγραφή, την ενημερώνουμε
        $currentQuantity = $result->fetch_assoc()['quantity'];
        $newQuantity = $currentQuantity + $quantity;
        $stmt = $conn->prepare("UPDATE VEHICLE_LOAD SET quantity = ? WHERE vehicle_id = ? AND item = ?");
        $stmt->bind_param("iss", $newQuantity, $vehicleId, $item);
        $stmt->execute();
        error_log("Updated VEHICLE_LOAD - vehicle_id: $vehicleId, item: $item, new quantity: $newQuantity");
    } else {
        // Δεν υπάρχει εγγραφή, προσθέτουμε νέα
        $stmt = $conn->prepare("INSERT INTO VEHICLE_LOAD (vehicle_id, item, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $vehicleId, $item, $quantity);
        $stmt->execute();
        error_log("Inserted into VEHICLE_LOAD - vehicle_id: $vehicleId, item: $item, quantity: $quantity");
    }

    // Ολοκληρώνουμε τη συναλλαγή
    $conn->commit();
    
    echo json_encode(['success' => true, 'message' => 'Product loaded successfully!']);
} catch (Exception $e) {
    // Σε περίπτωση σφάλματος, ακυρώνουμε τη συναλλαγή
    $conn->rollback();
    error_log("Error in transaction: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
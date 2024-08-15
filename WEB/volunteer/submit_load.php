<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['user']['profile'] !== 'volunteer') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

$conn = new mysqli("localhost", "root", "", "web");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

$username = $_SESSION['user']['username'];

// Ανάκτηση του vehicle_id του εθελοντή
$sql = "SELECT vehicle FROM VOLUNTEER WHERE volunteer_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$volunteer = $result->fetch_assoc();

if (!$volunteer) {
    echo json_encode(['success' => false, 'error' => 'Volunteer not found']);
    exit;
}

$vehicle_id = $volunteer['vehicle'];

// Επεξεργασία των υποβληθέντων δεδομένων
foreach ($_POST as $key => $value) {
    if (strpos($key, 'product_') === 0 && $value > 0) {
        $product_id = substr($key, 8);
        $quantity = intval($value);

        // Έλεγχος διαθεσιμότητας
        $sql = "SELECT available FROM PRODUCTS WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($quantity > $product['available']) {
            echo json_encode(['success' => false, 'error' => 'Not enough quantity available for product ' . $product_id]);
            exit;
        }

        // Ενημέρωση του VEHICLE_LOAD
        $sql = "INSERT INTO VEHICLE_LOAD (vehicle_id, product_id, quantity) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $vehicle_id, $product_id, $quantity);
        $stmt->execute();

        // Μείωση της διαθέσιμης ποσότητας στον πίνακα PRODUCTS
        $sql = "UPDATE PRODUCTS SET available = available - ? WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();
    }
}

echo json_encode(['success' => true]);

$conn->close();
?>
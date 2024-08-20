<?php
session_start();
header('Content-Type: application/json');

// Ελέγχουμε αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

// Συνδεόμαστε στη βάση δεδομένων
$conn = new mysqli("localhost", "root", "", "web");

// Ελέγχουμε τη σύνδεση
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

// Ανακτούμε τα προϊόντα από τον πίνακα PRODUCTS
$sql = "SELECT item, available FROM PRODUCTS";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $products = array();
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode([
        'success' => true,
        'products' => $products
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'No products found']);
}

$conn->close();
?>
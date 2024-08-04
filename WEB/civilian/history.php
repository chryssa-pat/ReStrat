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
    die("Connection failed: " . $conn->connect_error);
}

$offer_user = $_SESSION['user']; 

$sql = "SELECT offer_status, offer_user, PRODUCTS.item AS item, offer_quantity, offer_date 
        FROM OFFERS 
        JOIN PRODUCTS ON OFFERS.offer_product = PRODUCTS.product_id
        WHERE offer_user = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare statement failed: " . $conn->error);
}
$stmt->bind_param("s", $offer_user);
$stmt->execute();
$result = $stmt->get_result();

$offers = [];
while ($row = $result->fetch_assoc()) {
    $offers[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($offers);
?>

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

$sql = "SELECT od.offer_status, o.offer_user, p.item, o.offer_quantity, od.offer_date 
        FROM OFFERS o
        JOIN OFFERS_DETAILS od ON o.offer_id = od.details_id
        JOIN PRODUCTS p ON o.offer_product = p.product_id
        WHERE o.offer_user = ?
        ORDER BY od.offer_date DESC";

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
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

$sql = "SELECT o.offer_id, od.offer_status, o.offer_user, p.item, o.offer_quantity, od.offer_date 
        FROM OFFERS o
        JOIN OFFERS_DETAILS od ON o.offer_id = od.details_id
        JOIN PRODUCTS p ON o.offer_product = p.product_id
        WHERE o.offer_user = ?
        ORDER BY o.offer_id DESC, od.offer_date DESC";

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

// Group offers by ID
$groupedOffers = [];
foreach ($offers as $offer) {
    if (!isset($groupedOffers[$offer['offer_id']])) {
        $groupedOffers[$offer['offer_id']] = [
            'id' => $offer['offer_id'],
            'item' => $offer['item'],
            'quantity' => $offer['offer_quantity'],
            'statuses' => []
        ];
    }
    $groupedOffers[$offer['offer_id']]['statuses'][] = [
        'status' => $offer['offer_status'],
        'date' => $offer['offer_date']
    ];
}

// Debug: Log the grouped offers
error_log("Grouped Offers: " . print_r($groupedOffers, true));

// Send the grouped offers as JSON
echo json_encode(array_values($groupedOffers));
?>
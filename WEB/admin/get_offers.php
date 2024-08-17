<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

$conn = new mysqli("localhost", "root", "", "web");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

$sql_offer = "SELECT c.latitude, c.longitude, o.offer_id,
              c.full_name, c.phone, p.item as product, o.offer_quantity as quantity,
              od.offer_date as registration_date, od.offer_status as status
              FROM CIVILIAN c
              JOIN OFFERS o ON c.civilian_user = o.offer_user
              JOIN PRODUCTS p ON o.offer_product = p.product_id
              JOIN OFFERS_DETAILS od ON o.offer_id = od.details_id
              WHERE c.latitude IS NOT NULL AND c.longitude IS NOT NULL
              AND od.offer_status IN ('approved', 'pending')";

$result_offer = $conn->query($sql_offer);

$offers = [];

if ($result_offer) {
    while ($row = $result_offer->fetch_assoc()) {
        $offers[] = $row;
    }
}

echo json_encode(['success' => true, 'offers' => $offers]);

$conn->close();
?>
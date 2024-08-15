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

$sql_inquiry = "SELECT c.latitude, c.longitude, 'inquiry' as type, i.inquiry_id as id,
                c.full_name, c.phone, p.item as product, i.inquiry_quantity as quantity,
                id.inquiry_date as registration_date, id.inquiry_status as status
                FROM CIVILIAN c
                JOIN INQUIRY i ON c.civilian_user = i.inquiry_user
                JOIN PRODUCTS p ON i.inquiry_product = p.product_id
                JOIN INQUIRY_DETAILS id ON i.inquiry_id = id.details_id
                WHERE c.latitude IS NOT NULL AND c.longitude IS NOT NULL
                AND id.inquiry_status IN ('approved', 'pending')";

$sql_offer = "SELECT c.latitude, c.longitude, 'offer' as type, o.offer_id as id,
              c.full_name, c.phone, p.item as product, o.offer_quantity as quantity,
              od.offer_date as registration_date, od.offer_status as status
              FROM CIVILIAN c
              JOIN OFFERS o ON c.civilian_user = o.offer_user
              JOIN PRODUCTS p ON o.offer_product = p.product_id
              JOIN OFFERS_DETAILS od ON o.offer_id = od.details_id
              WHERE c.latitude IS NOT NULL AND c.longitude IS NOT NULL
              AND od.offer_status IN ('approved', 'pending')";

$result_inquiry = $conn->query($sql_inquiry);
$result_offer = $conn->query($sql_offer);

$coordinates = [];

if ($result_inquiry) {
    while ($row = $result_inquiry->fetch_assoc()) {
        $coordinates[] = $row;
    }
}

if ($result_offer) {
    while ($row = $result_offer->fetch_assoc()) {
        $coordinates[] = $row;
    }
}

echo json_encode(['success' => true, 'coordinates' => $coordinates]);

$conn->close();
?>
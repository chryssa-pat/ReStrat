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

$sql_inquiry = "SELECT c.latitude, c.longitude, i.inquiry_id,
                c.full_name, c.phone, p.item as product, i.inquiry_quantity as quantity,
                id.inquiry_date as registration_date, id.inquiry_status as status,
                id.approved_vehicle_id
                FROM CIVILIAN c
                JOIN INQUIRY i ON c.civilian_user = i.inquiry_user
                JOIN PRODUCTS p ON i.inquiry_product = p.product_id
                JOIN INQUIRY_DETAILS id ON i.inquiry_id = id.details_id
                WHERE c.latitude IS NOT NULL AND c.longitude IS NOT NULL
                AND id.inquiry_status IN ('approved', 'pending')";

$result_inquiry = $conn->query($sql_inquiry);

$inquiries = [];

if ($result_inquiry) {
    while ($row = $result_inquiry->fetch_assoc()) {
        $inquiries[] = $row;
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Query failed']);
    exit;
}

echo json_encode(['success' => true, 'inquiries' => $inquiries]);

$conn->close();
?>

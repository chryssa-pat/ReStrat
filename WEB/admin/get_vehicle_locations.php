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

// Fetch vehicle locations along with their load and task count
$sql_vehicles = "SELECT v.vehicle AS vehicle_id, v.latitude_vehicle, v.longtitude_vehicle,
                 COUNT(DISTINCT o.offer_id) + COUNT(DISTINCT i.inquiry_id) AS task_count,
                 GROUP_CONCAT(CONCAT(p.item, ' (', vl.quantity, ')') SEPARATOR ', ') AS load_details
                 FROM VOLUNTEER v
                 LEFT JOIN VEHICLE_LOAD vl ON v.vehicle = vl.vehicle_id
                 LEFT JOIN PRODUCTS p ON vl.item = p.item
                 LEFT JOIN OFFERS_DETAILS od ON od.approved_vehicle_id = v.vehicle
                 LEFT JOIN OFFERS o ON o.offer_id = od.details_id
                 LEFT JOIN INQUIRY_DETAILS id ON id.approved_vehicle_id = v.vehicle
                 LEFT JOIN INQUIRY i ON i.inquiry_id = id.details_id
                 GROUP BY v.vehicle, v.latitude_vehicle, v.longtitude_vehicle";

$result_vehicles = $conn->query($sql_vehicles);

$vehicles = [];

if ($result_vehicles) {
    while ($row = $result_vehicles->fetch_assoc()) {
        $vehicles[] = $row;
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Query failed']);
    exit;
}

echo json_encode(['success' => true, 'vehicles' => $vehicles]);

$conn->close();
?>

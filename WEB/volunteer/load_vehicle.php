<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['user']['profile'] !== 'volunteer') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

$conn = new mysqli("localhost", "root", "", "web");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

$username = $_SESSION['user']['username'];

$sql_volunteer = "SELECT v.latitude_vehicle, v.longitude_vehicle, vol.vehicle 
                  FROM VOLUNTEER vol
                  JOIN VEHICLE v ON vol.vehicle = v.vehicle_id 
                  WHERE vol.volunteer_user = '$username'";

$sql_base = "SELECT latitude, longitude FROM ADMIN LIMIT 1";

$sql_products = "SELECT product_id, item, available as quantity FROM PRODUCTS WHERE available > 0";

$result_volunteer = $conn->query($sql_volunteer);
$result_base = $conn->query($sql_base);
$result_products = $conn->query($sql_products);

if (!$result_volunteer || !$result_base || !$result_products) {
    echo json_encode(['success' => false, 'error' => 'Query failed: ' . $conn->error]);
    exit;
}

$volunteer = $result_volunteer->fetch_assoc();
$base = $result_base->fetch_assoc();

if (!$volunteer || !$base) {
    echo json_encode(['success' => false, 'error' => 'Volunteer or base location not found']);
    exit;
}

function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371; // in kilometers
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return $earthRadius * $c;
}

$distance = calculateDistance($base['latitude'], $base['longitude'], $volunteer['latitude_vehicle'], $volunteer['longitude_vehicle']);

if ($distance > 0.1) { // 0.1 km = 100 meters
    echo json_encode(['success' => false, 'error' => 'You are too far from the base']);
    exit;
}

$products = [];
while ($row = $result_products->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode(['success' => true, 'products' => $products]);

$conn->close();
?>
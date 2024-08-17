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

$start_date = isset($_GET['start']) ? $_GET['start'] : date('Y-m-d', strtotime('-7 days'));
$end_date = isset($_GET['end']) ? $_GET['end'] : date('Y-m-d');

$sql_inquiries = "SELECT 
    SUM(CASE WHEN inquiry_status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN inquiry_status = 'approved' THEN 1 ELSE 0 END) as approved,
    SUM(CASE WHEN inquiry_status = 'finished' THEN 1 ELSE 0 END) as finished
FROM INQUIRY_DETAILS
WHERE inquiry_date BETWEEN ? AND ?";

$sql_offers = "SELECT 
    SUM(CASE WHEN offer_status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN offer_status = 'approved' THEN 1 ELSE 0 END) as approved,
    SUM(CASE WHEN offer_status = 'finished' THEN 1 ELSE 0 END) as finished
FROM OFFERS_DETAILS
WHERE offer_date BETWEEN ? AND ?";

$stmt_inquiries = $conn->prepare($sql_inquiries);
$stmt_offers = $conn->prepare($sql_offers);

$stmt_inquiries->bind_param("ss", $start_date, $end_date);
$stmt_offers->bind_param("ss", $start_date, $end_date);

$stmt_inquiries->execute();
$result_inquiries = $stmt_inquiries->get_result();

$stmt_offers->execute();
$result_offers = $stmt_offers->get_result();

if ($result_inquiries && $result_offers) {
    $data_inquiries = $result_inquiries->fetch_assoc();
    $data_offers = $result_offers->fetch_assoc();
    
    echo json_encode([
        'inquiries' => $data_inquiries,
        'offers' => $data_offers
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Query failed']);
}

$stmt_inquiries->close();
$stmt_offers->close();
$conn->close();
?>
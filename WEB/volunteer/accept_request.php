<?php
header('Content-Type: application/json');

if (!isset($_POST['type']) || !isset($_POST['id'])) {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    exit;
}

$conn = new mysqli("localhost", "root", "", "web");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

$type = $_POST['type'];
$id = $_POST['id'];

if ($type === 'inquiry') {
    $sql = "UPDATE INQUIRY_DETAILS SET inquiry_status = 'approved' WHERE details_id = ?";
} elseif ($type === 'offer') {
    $sql = "UPDATE OFFERS_DETAILS SET offer_status = 'approved' WHERE details_id = ?";
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid type']);
    exit;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update status']);
}

$conn->close();
?>
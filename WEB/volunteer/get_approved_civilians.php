<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Μη εξουσιοδοτημένη πρόσβαση']);
    exit;
}

$conn = new mysqli("localhost", "root", "", "web");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Αποτυχία σύνδεσης στη βάση δεδομένων']);
    exit;
}

// Παίρνουμε το vehicle_id του τρέχοντος χρήστη
$stmt = $conn->prepare("SELECT vehicle FROM VOLUNTEER WHERE volunteer_user = ?");
$stmt->bind_param("s", $_SESSION['user']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Δεν βρέθηκε όχημα για τον χρήστη']);
    exit;
}
$vehicle_id = $result->fetch_assoc()['vehicle'];

$sql_inquiry = "SELECT c.latitude, c.longitude, 'inquiry' as type, i.inquiry_id as id,
                c.full_name, c.phone, p.item as product, i.inquiry_quantity as quantity,
                id.inquiry_date as registration_date, id.inquiry_status as status,
                id.approved_vehicle_id, id.approved_timestamp
                FROM CIVILIAN c
                JOIN INQUIRY i ON c.civilian_user = i.inquiry_user
                JOIN PRODUCTS p ON i.inquiry_product = p.product_id
                JOIN INQUIRY_DETAILS id ON i.inquiry_id = id.details_id
                WHERE c.latitude IS NOT NULL AND c.longitude IS NOT NULL
                AND (id.inquiry_status = 'pending' OR (id.inquiry_status = 'approved' AND id.approved_vehicle_id = ?))";

$sql_offer = "SELECT c.latitude, c.longitude, 'offer' as type, o.offer_id as id,
              c.full_name, c.phone, p.item as product, o.offer_quantity as quantity,
              od.offer_date as registration_date, od.offer_status as status,
              od.approved_vehicle_id, od.approved_timestamp
              FROM CIVILIAN c
              JOIN OFFERS o ON c.civilian_user = o.offer_user
              JOIN PRODUCTS p ON o.offer_product = p.product_id
              JOIN OFFERS_DETAILS od ON o.offer_id = od.details_id
              WHERE c.latitude IS NOT NULL AND c.longitude IS NOT NULL
              AND (od.offer_status = 'pending' OR (od.offer_status = 'approved' AND od.approved_vehicle_id = ?))";

$stmt_inquiry = $conn->prepare($sql_inquiry);
$stmt_inquiry->bind_param("s", $vehicle_id);
$stmt_inquiry->execute();
$result_inquiry = $stmt_inquiry->get_result();

$stmt_offer = $conn->prepare($sql_offer);
$stmt_offer->bind_param("s", $vehicle_id);
$stmt_offer->execute();
$result_offer = $stmt_offer->get_result();

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
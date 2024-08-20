<?php
session_start();
header('Content-Type: application/json');

// Ελέγχουμε αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

$conn = new mysqli("localhost", "root", "", "web");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Get the vehicle_id associated with the logged-in user
$userId = $_SESSION['user']; // Adjust this based on your session structure
$stmt = $conn->prepare("SELECT vehicle FROM VOLUNTEER WHERE volunteer_user = ?");
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'No vehicle found for the user']);
    exit;
}

$vehicleId = $result->fetch_assoc()['vehicle'];

// Παίρνουμε τα tasks με όλες τις ζητούμενες πληροφορίες, συμπεριλαμβανομένων των ημερομηνιών
$sql = "
SELECT * FROM (
    SELECT 'inquiry' as type, id.details_id, id.approved_vehicle_id, c.full_name, c.phone, 
           p.item, i.inquiry_quantity as quantity, id.inquiry_date as task_date,
           c.latitude, c.longitude,
           (SELECT COUNT(*) FROM INQUIRY_DETAILS id2 WHERE id2.details_id = id.details_id) as total_entries,
           (SELECT GROUP_CONCAT(inquiry_status ORDER BY inquiry_date ASC)
            FROM INQUIRY_DETAILS id3 
            WHERE id3.details_id = id.details_id) as status_sequence
    FROM INQUIRY_DETAILS id
    JOIN INQUIRY i ON id.details_id = i.inquiry_id
    JOIN CIVILIAN c ON i.inquiry_user = c.civilian_user
    JOIN PRODUCTS p ON i.inquiry_product = p.product_id
    WHERE id.inquiry_status = 'approved'
    AND id.approved_vehicle_id = ?  -- Filter by vehicle_id
    AND NOT EXISTS (
        SELECT 1 FROM INQUIRY_DETAILS id2 
        WHERE id2.details_id = id.details_id 
        AND id2.inquiry_status = 'finished'
    )

    UNION ALL

    SELECT 'offer' as type, od.details_id, od.approved_vehicle_id, c.full_name, c.phone, 
           p.item, o.offer_quantity as quantity, od.offer_date as task_date,
           c.latitude, c.longitude,
           (SELECT COUNT(*) FROM OFFERS_DETAILS od2 WHERE od2.details_id = od.details_id) as total_entries,
           (SELECT GROUP_CONCAT(offer_status ORDER BY offer_date ASC)
            FROM OFFERS_DETAILS od3 
            WHERE od3.details_id = od.details_id) as status_sequence
    FROM OFFERS_DETAILS od
    JOIN OFFERS o ON od.details_id = o.offer_id
    JOIN CIVILIAN c ON o.offer_user = c.civilian_user
    JOIN PRODUCTS p ON o.offer_product = p.product_id
    WHERE od.offer_status = 'approved'
    AND od.approved_vehicle_id = ?  -- Filter by vehicle_id
    AND NOT EXISTS (
        SELECT 1 FROM OFFERS_DETAILS od2 
        WHERE od2.details_id = od.details_id 
        AND od2.offer_status = 'finished'
    )
) AS subquery
WHERE (total_entries = 1 AND status_sequence = 'approved')
   OR (total_entries % 2 = 1 AND status_sequence REGEXP '^approved(,pending,approved)*$')
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $vehicleId, $vehicleId); // Bind vehicle_id for both inquiries and offers
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    $tasks = [];
    while($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
    echo json_encode(['success' => true, 'tasks' => $tasks]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

$stmt->close();
$conn->close();
?>
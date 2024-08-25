<?php
// Include session check
include('../main/session_check.php');

// Database credentials
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

// Get the current session user
$inquiry_user = $_SESSION['user']; 

// SQL query to fetch inquiry history
$sql = "SELECT i.inquiry_id, ih.history_status, i.inquiry_user, p.item, i.inquiry_quantity, id.inquiry_date, id.approved_vehicle_id, id.approved_timestamp
        FROM INQUIRY i
        JOIN INQUIRY_DETAILS id ON i.inquiry_id = id.details_id
        LEFT JOIN INQUIRY_HISTORY ih ON i.inquiry_id = ih.inquiry_history_id 
        JOIN PRODUCTS p ON i.inquiry_product = p.product_id
        WHERE i.inquiry_user = ?
        ORDER BY i.inquiry_id DESC, id.inquiry_date DESC";

// Prepare the SQL statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare statement failed: " . $conn->error);
}

// Bind parameters and execute the statement
$stmt->bind_param("s", $inquiry_user);
$stmt->execute();
$result = $stmt->get_result();

// Fetch results and group them by inquiry ID
$inquiries = [];
while ($row = $result->fetch_assoc()) {
    $inquiries[] = $row;
}

$stmt->close();
$conn->close();

// Group inquiries by ID and prepare the response
$groupedInquiries = [];
foreach ($inquiries as $inquiry) {
    if (!isset($groupedInquiries[$inquiry['inquiry_id']])) {
        $groupedInquiries[$inquiry['inquiry_id']] = [
            'id' => $inquiry['inquiry_id'],
            'item' => $inquiry['item'],
            'quantity' => $inquiry['inquiry_quantity'],
            'statuses' => []
        ];
    }
    $groupedInquiries[$inquiry['inquiry_id']]['statuses'][] = [
        'status' => $inquiry['history_status'] ?? $inquiry['inquiry_status'], // Use inquiry_status if history_status is NULL
        'date' => $inquiry['inquiry_date'],
        'approved_vehicle_id' => $inquiry['approved_vehicle_id'] ?? 'N/A',
        'approved_timestamp' => $inquiry['approved_timestamp'] ?? 'N/A'
    ];
}

// Debug: Log the grouped inquiries (optional, for debugging purposes)
error_log("Grouped Inquiries: " . print_r($groupedInquiries, true));

// Send the grouped inquiries as JSON
echo json_encode(array_values($groupedInquiries));
?>

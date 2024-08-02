<?php
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

// Retrieve input data
$announce_id = $_POST['announce_id'];
$offer_quantity = $_POST['offer_quantity'];
$offer_user = "chryssa"; // Set the user to "chryssa"

// Debugging output
error_log("Offer User: " . $offer_user);
error_log("Announcement ID: " . $announce_id);
error_log("Offer Quantity: " . $offer_quantity);

// Check if announcement exists
$sql = "SELECT announce_product, announce_quantity FROM ANNOUNCEMENTS WHERE announce_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $announce_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Announcement not found']);
    $stmt->close();
    $conn->close();
    exit();
}

$stmt->bind_result($announce_product, $announce_quantity);
$stmt->fetch();
$stmt->close();

// Check if offer quantity is valid
if ($announce_quantity >= $offer_quantity) {
    $new_quantity = $announce_quantity - $offer_quantity;

    // Update announcement quantity
    $sql = "UPDATE ANNOUNCEMENTS SET announce_quantity = ? WHERE announce_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $new_quantity, $announce_id);
    if (!$stmt->execute()) {
        error_log("Update failed: " . $stmt->error);
        echo json_encode(['success' => false, 'error' => 'Failed to update announcement']);
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();

    // Insert offer into the database
    $sql = "INSERT INTO OFFERS (offer_status, offer_user, offer_product, offer_quantity) VALUES ('pending', ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $offer_user, $announce_product, $offer_quantity);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        error_log("Insert failed: " . $stmt->error);
        echo json_encode(['success' => false, 'error' => 'Failed to insert offer']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'There is need only for ' . $announce_quantity]);
}

$conn->close();
?>

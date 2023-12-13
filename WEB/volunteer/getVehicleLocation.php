<?php
// Assuming you have a database connection already established

// Establish a database connection using mysqli
$conn = new mysqli("localhost", "root", "canmp168", "web");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query the database to get all vehicle coordinates
$query = "SELECT vehicle_id, latitude_vehicle, longitude_vehicle FROM VEHICLE";
$result = $conn->query($query);

if ($result) {
    // Fetch the results as an associative array
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Return the result as JSON
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    // Handle the error if the query fails
    echo json_encode(['error' => 'Failed to retrieve data']);
}

// Close the database connection
$conn->close();
?>

<?php
include('../main/session_check.php');
include('../main/database.php');

header('Content-Type: application/json');

try {
    $stmt = $conn->prepare("SELECT v.volunteer_user, v.vehicle, vh.latitude_vehicle, vh.longitude_vehicle 
                            FROM VOLUNTEER v 
                            JOIN VEHICLE vh ON v.vehicle = vh.vehicle_id");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'volunteers' => $result]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn = null;
?>
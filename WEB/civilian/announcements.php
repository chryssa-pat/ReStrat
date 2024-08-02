<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "web";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT a.announce_id, a.announce_product, a.announce_quantity, p.item
        FROM ANNOUNCEMENTS a 
        JOIN PRODUCTS p ON a.announce_product = p.product_id";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<button class='btn btn-primary announcement-button' data-announce-id='" . $row['announce_id'] . "'>THERE IS NEED OF " . $row['announce_quantity'] . " " . $row['item'] . "</button><br><br>";
    }
} else {
    echo "No announcements found.";
}

$conn->close();
?>

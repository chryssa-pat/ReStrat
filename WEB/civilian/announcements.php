<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "web";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update SQL query to exclude items with quantity 0
$sql = "SELECT a.announce_id, ai.announce_product, ai.quantity, p.item
        FROM ANNOUNCEMENTS a 
        JOIN ANNOUNCEMENT_ITEMS ai ON a.announce_id = ai.announce_id
        JOIN products p ON ai.announce_product = p.product_id
        WHERE ai.quantity > 0  -- Exclude items with quantity 0
        ORDER BY a.created_at DESC, a.announce_id DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $currentAnnounceId = null;
    echo "<div class='container'>";
    while($row = $result->fetch_assoc()) {
        if ($currentAnnounceId !== $row['announce_id']) {
            if ($currentAnnounceId !== null) {
                echo "</div></div>";
            }
            echo "<div class='card announcement-card'>";
            echo "<div class='card-header bg-primary text-white'>";
            echo "<h5 class='mb-0'>Announcement #" . $row['announce_id'] . "</h5>";
            echo "</div>";
            echo "<div class='card-body'>";
            $currentAnnounceId = $row['announce_id'];
        }
        echo "<button class='btn btn-outline-primary mb-2 me-2 announcement-button' data-announce-id='" . $row['announce_id'] . "' data-product-id='" . $row['announce_product'] . "'>";
        echo "NEED " . $row['quantity'] . " " . $row['item'];
        echo "</button>";
    }
    echo "</div></div>";
    echo "</div>";
} else {
    echo "<div class='alert alert-info' role='alert'>No announcements found.</div>";
}

$conn->close();
?>
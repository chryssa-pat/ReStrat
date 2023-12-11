<?php
session_start();

$conn = new mysqli("localhost", "root", "", "WEB");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT announce FROM ANNOUNCEMENTS";
$result = $conn->query($sql);

$output = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= '<div class="announcement-frame">';
        $output .= '<p>' . $row['announce'] . '</p>';
        $output .= '</div>';
    }
} else {
    $output .= '<p>No announcements available.</p>';
}

echo $output;

$conn->close();
?>
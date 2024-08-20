<?php
include('../main/session_check.php');

$host = "localhost";
$username = "root";
$password = "";
$database = "web";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = $_POST['categoryId'];
    $category_name = $_POST['categoryName'];

    // Check if the category ID already exists
    $check_stmt = $conn->prepare("SELECT category_id FROM CATEGORIES WHERE category_id = ?");
    $check_stmt->bind_param("i", $category_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Category ID already exists."]);
    } else {
        // Insert new category
        $insert_stmt = $conn->prepare("INSERT INTO CATEGORIES (category_id, category_name) VALUES (?, ?)");
        $insert_stmt->bind_param("is", $category_id, $category_name);
        
        if ($insert_stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Category added successfully!", "category_id" => $category_id]);
        } else {
            if ($insert_stmt->errno == 1062) { // Duplicate entry error (for category name)
                echo json_encode(["success" => false, "message" => "This category name already exists."]);
            } else {
                echo json_encode(["success" => false, "message" => "Error adding category: " . $insert_stmt->error]);
            }
        }
        
        $insert_stmt->close();
    }
    
    $check_stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}

$conn->close();
?>
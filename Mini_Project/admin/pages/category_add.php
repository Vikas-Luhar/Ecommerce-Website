<?php
include_once "../config/dbconnect.php";

header('Content-Type: application/json'); // Ensure JSON response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['category_name']) && !empty(trim($_POST['category_name']))) {
        // Sanitize input
        $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);

        // Check if category already exists
        $check_query = "SELECT * FROM category WHERE category_name = '$category_name' LIMIT 1";
        $result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($result) > 0) {
            echo json_encode(["success" => false, "message" => "Category name already exists!"]);
        } else {
            // Insert query
            $query = "INSERT INTO category (category_name, status) VALUES ('$category_name', 1)";

            // Execute the query
            if (mysqli_query($conn, $query)) {
                echo json_encode(["success" => true, "message" => "Category added successfully!"]);
            } else {
                echo json_encode(["success" => false, "message" => "Database error: " . mysqli_error($conn)]);
            }
        }
    } else {
        echo json_encode(["success" => false, "message" => "Category name is required!"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method!"]);
}
exit;
?>


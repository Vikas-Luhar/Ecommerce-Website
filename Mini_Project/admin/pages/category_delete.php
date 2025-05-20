<?php
include_once "../config/dbconnect.php";

// Debugging: Check if POST request is received
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $category_id = $_POST["id"];

    if (empty($category_id)) {
        echo json_encode(["success" => false, "message" => "Invalid category ID."]);
        exit();
    }

    // Debugging: Check database connection
    if (!$conn) {
        echo json_encode(["success" => false, "message" => "Database connection failed."]);
        exit();
    }

    // Prepare delete query
    $sql = "DELETE FROM category WHERE category_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "SQL Prepare failed: " . mysqli_error($conn)]);
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $category_id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["success" => true, "message" => "Category deleted successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete category."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>

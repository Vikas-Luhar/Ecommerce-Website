<?php
include_once "../config/dbconnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $category_id = intval($_POST["id"]); // Ensure it's an integer

    // Delete category query
    $query = "DELETE FROM category WHERE category_id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $category_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "success";
        } else {
            echo "error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "error: Query preparation failed.";
    }
} else {
    echo "error: Invalid request.";
}

mysqli_close($conn);
?>

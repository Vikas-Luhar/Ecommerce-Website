<?php
include_once "../config/dbconnect.php";

if (isset($_POST['product_id']) && isset($_POST['status'])) {
    $product_id = intval($_POST['product_id']);
    $status = intval($_POST['status']);

    $query = "UPDATE product SET status = '$status' WHERE product_id = '$product_id'";
    
    if (mysqli_query($conn, $query)) {
        echo "Product ID: $product_id updated to status: $status";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>

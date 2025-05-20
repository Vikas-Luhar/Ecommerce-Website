<?php
include_once "../config/dbconnect.php";

// Set response type to JSON
header('Content-Type: application/json');

if (isset($_POST['record'])) {
    $p_id = $_POST['record'];

    // Check if product exists
    $checkQuery = "SELECT * FROM product WHERE product_id='$p_id'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Delete the product
        $query = "DELETE FROM product WHERE product_id='$p_id'";
        $data = mysqli_query($conn, $query);

        if ($data) {
            echo json_encode(["status" => "success", "message" => "Product Item Deleted"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error deleting product: " . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Product not found."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}

exit();
?>

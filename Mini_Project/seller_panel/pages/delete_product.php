<?php
include_once "../config/dbconnect.php";

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Step 1: Delete from order_details where Product_ID matches
    $delOrderDetails = "DELETE FROM order_details WHERE Product_ID = ?";
    $stmt1 = $conn->prepare($delOrderDetails);
    $stmt1->bind_param("i", $product_id);
    $stmt1->execute();

    // Step 2: Now delete the product
    $query = "DELETE FROM product WHERE product_id = ?";
    $stmt2 = $conn->prepare($query);
    $stmt2->bind_param("i", $product_id);

    if ($stmt2->execute()) {
        echo "Product deleted successfully!";
    } else {
        echo "Error deleting product!";
    }
} else {
    echo "Invalid request!";
}
?>

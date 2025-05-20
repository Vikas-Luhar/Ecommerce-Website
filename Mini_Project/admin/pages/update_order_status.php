<?php
include_once "../config/dbconnect.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $orderId = $_POST['orderId'];
    $newStatus = $_POST['newStatus'];

    $sql = "UPDATE order_details SET Status = ? WHERE Order_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newStatus, $orderId);

    if ($stmt->execute()) {
        echo "Order status updated to $newStatus";
    } else {
        echo "Failed to update status";
    }
}
?>

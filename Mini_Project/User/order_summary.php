<?php
session_start();
include 'connection.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch order details
    $sql = "SELECT * FROM tblorder WHERE Order_ID = '$order_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        echo "<h3>Order Summary</h3>";
        echo "Order ID: " . $order['Order_ID'] . "<br>";
        echo "User ID: " . $order['User_ID'] . "<br>";
        echo "Total Amount: ₹" . $order['TotalAmount'] . "<br>";
        echo "Final Amount: ₹" . $order['FinalAmount'] . "<br>";
        echo "Created On: " . $order['CreatedON'] . "<br>";
        echo "Address ID: " . $order['Address_ID'] . "<br>";
    } else {
        echo "No order found!";
    }
} else {
    echo "Invalid request!";
}
?>

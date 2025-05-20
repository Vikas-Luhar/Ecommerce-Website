<?php
require_once 'connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_SESSION['user_name'];
    $email_id = $_SESSION['user_email'];
    $order_id = $_POST['order_id'];
    $product_name = $_POST['product_name'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];
    $feedback_date = date('Y-m-d');

    $sql = "INSERT INTO feedbacks (customer_name, email_id, order_id, product_name, rating, review, feedback_date)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssiss", $customer_name, $email_id, $order_id, $product_name, $rating, $review, $feedback_date);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Feedback submitted successfully!'); window.location.href='order-history.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

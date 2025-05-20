<?php
include('connection.php');

if(isset($_GET['category_id']) && isset($_GET['status'])){
    $category_id = $_GET['category_id'];
    $status = $_GET['status'];

    $sql = "UPDATE category SET status=$status WHERE category_id=$category_id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php#category"); // Redirect back to the category listing page
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<?php
include_once "../config/dbconnect.php";

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $newStatus = $_POST['status'] == 1 ? 0 : 1; // Toggle status

    $query = "UPDATE category SET status = $newStatus WHERE category_id = $id";
    
    if (mysqli_query($conn, $query)) {
        echo json_encode(["success" => true, "message" => "Status updated successfully!", "new_status" => $newStatus]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . mysqli_error($conn)]);
    }
}
?>

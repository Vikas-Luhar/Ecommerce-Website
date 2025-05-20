<?php
include_once "../config/dbconnect.php";

$c_id = $_POST['record'];
$query = "DELETE FROM category WHERE category_id='$c_id'";

$data = mysqli_query($conn, $query);

if ($data) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
}
?>

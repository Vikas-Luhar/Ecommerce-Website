<?php
session_start();
include_once "../config/dbconnect.php";

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: sign-in.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sellerId = $_POST['id'];
    $currentStatus = $_POST['status'];

    $newStatus = $currentStatus == 1 ? 0 : 1;
    $newApproved = $newStatus;

    // Get admin name from session (corrected)
    $approvedBy = isset($_SESSION['Admin_Name']) ? $_SESSION['Admin_Name'] : "Unknown";
    $approvedOn = date('Y-m-d H:i:s'); // Optional: log date and time

    $sql = "UPDATE seller 
            SET IsActive = '$newStatus', 
                IsApproved = '$newApproved', 
                ApprovedBy = '$approvedBy',
                ApprovedOn = '$approvedOn'
            WHERE Seller_ID = '$sellerId'";

    if (mysqli_query($conn, $sql)) {
        echo json_encode([
            "status" => "success",
            "newStatus" => $newStatus
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
}
?>

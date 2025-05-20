<?php
session_start();
include_once "../config/dbconnect.php";

// Set JSON response type
header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["status" => "error", "message" => "Admin not logged in"]);
    exit();
}

// Check request method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sellerId = $_POST['id'];
    $currentStatus = $_POST['status'];

    // Toggle status
    $newStatus = $currentStatus == 1 ? 0 : 1;
    $approvedOn = date("Y-m-d H:i:s");

    // Fetch admin name from database
    $adminId = $_SESSION['admin_id'];
    $adminQuery = mysqli_query($conn, "SELECT admin_name FROM admin WHERE admin_id = '$adminId'");
    $adminRow = mysqli_fetch_assoc($adminQuery);
    $approvedBy = $adminRow ? $adminRow['admin_name'] : "Unknown";

    // Build query
    if ($newStatus == 1) {
        // Approve
        $sql = "UPDATE seller 
                SET IsActive = '$newStatus',
                    IsApproved = '$newStatus',
                    ApprovedBy = '$approvedBy',
                    ApprovedOn = '$approvedOn'
                WHERE Seller_ID = '$sellerId'";
    } else {
        // Deactivate
        $sql = "UPDATE seller 
                SET IsActive = '$newStatus',
                    IsApproved = '$newStatus',
                    ApprovedBy = NULL,
                    ApprovedOn = NULL
                WHERE Seller_ID = '$sellerId'";
    }

    // Execute and respond
    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "success", "newStatus" => $newStatus]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
}
?>

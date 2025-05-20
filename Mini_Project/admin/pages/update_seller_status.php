<?php
include_once "../config/dbconnect.php";

if(isset($_POST['id'], $_POST['active'], $_POST['approved'])) {
    $id = intval($_POST['id']); // Ensure ID is an integer
    $currentActive = intval($_POST['active']);
    $currentApproved = intval($_POST['approved']);

    $newActiveStatus = $currentActive == 1 ? 0 : 1; // Toggle Active Status
    $newApprovedStatus = $currentApproved == 1 ? 0 : 1; // Toggle Approved Status
    $approvedOn = ($newApprovedStatus == 1) ? date('Y-m-d H:i:s') : NULL;

    $query = "UPDATE seller 
              SET IsActive = ?, 
                  IsApproved = ?, 
                  ApprovedOn = ?
              WHERE Seller_ID = ?";
    
    // Use prepared statement
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "iisi", $newActiveStatus, $newApprovedStatus, $approvedOn, $id);
        $success = mysqli_stmt_execute($stmt);

        if ($success) {
            echo json_encode(["status" => "success", "message" => "Seller status updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database update failed."]);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(["status" => "error", "message" => "Query preparation failed."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>

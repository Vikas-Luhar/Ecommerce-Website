<?php
// Start session
session_start();
include_once "../config/dbconnect.php";

// Ensure the seller is logged in
if (!isset($_SESSION['seller_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access"]);
    exit();
}

// Handle AJAX request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Prepare and execute query
    $query = "UPDATE order_details SET Status = ? WHERE Order_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Status updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Database update failed"]);
    }

    $stmt->close();
    $conn->close();
    exit();
}

// If request is invalid
echo json_encode(["success" => false, "message" => "Invalid request"]);
exit();
?>
<script>
    $(document).ready(function(){
    $(".change-status").click(function(e){
        e.preventDefault();
        var orderID = $(this).data("id");
        var newStatus = $(this).data("status");
        var button = $("#statusDropdown" + orderID);

        $.ajax({
            url: "../pages/update_order_status.php", // Update the URL
            type: "POST",
            data: { order_id: orderID, status: newStatus },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    button.text(newStatus);
                    button.removeClass("btn-warning btn-info btn-success btn-danger btn-secondary");

                    let statusClass = "btn-secondary";
                    if (newStatus === "Pending") statusClass = "btn-warning";
                    else if (newStatus === "Shipped") statusClass = "btn-info";
                    else if (newStatus === "Delivered") statusClass = "btn-success";
                    else if (newStatus === "Cancelled") statusClass = "btn-danger";

                    button.addClass(statusClass);
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert("AJAX Error: " + error);
            }
        });
    });
});

</script>
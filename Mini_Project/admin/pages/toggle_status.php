<?php
include_once "../config/dbconnect.php"; // Ensure the correct path

header('Content-Type: application/json'); // Send JSON response
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seller_id = isset($_POST['seller_id']) ? intval($_POST['seller_id']) : null;
    $current_status = isset($_POST['is_active']) ? intval($_POST['is_active']) : null;

    if (!$seller_id || !isset($current_status)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid input data"]);
        exit;
    }

    // Toggle status (1 → 0, 0 → 1)
    $new_status = $current_status ? 0 : 1;

    $sql = "UPDATE seller SET IsActive = ? WHERE Seller_ID = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Query preparation failed: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("ii", $new_status, $seller_id);
    $execute = $stmt->execute();

    if ($execute) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Seller status updated", "new_status" => $new_status]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to update status"]);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>

<?php
include_once "../config/dbconnect.php"; // Database connection

header('Content-Type: application/json'); // Ensure JSON response
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['query'])) {
    $search = trim($_GET['query']); 

    // Optimized SQL Query including `IsActive`
    $sql = "SELECT Seller_ID, Seller_Name, Seller_Shop_Name, Seller_Shop_Address, 
                   Email_Id, Seller_Mobile_No, Seller_Shop_Logo, IsActive 
            FROM seller 
            WHERE LOWER(Seller_Name) LIKE LOWER(?) 
            OR LOWER(Seller_Shop_Name) LIKE LOWER(?) 
            OR LOWER(Seller_Shop_Address) LIKE LOWER(?) 
            OR LOWER(Email_Id) LIKE LOWER(?) 
            OR Seller_Mobile_No LIKE ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Query preparation failed: " . $conn->error]);
        exit;
    }

    $searchParam = "%{$search}%";
    $stmt->bind_param("sssss", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();

    $sellers = [];
    while ($row = $result->fetch_assoc()) {
        // Escape output for security
        $row = array_map('htmlspecialchars', $row);
        
        // Convert IsActive to boolean for consistency in JSON response
        $row['IsActive'] = (bool) $row['IsActive'];

        $sellers[] = $row;
    }

    $stmt->close();
    $conn->close();

    if (!empty($sellers)) {
        http_response_code(200);
        echo json_encode(["status" => "success", "data" => $sellers]);
    } else {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "No sellers found"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Query parameter is missing"]);
}
?>

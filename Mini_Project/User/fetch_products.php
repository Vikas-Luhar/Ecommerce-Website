<?php
require_once 'connection.php';
session_start();

// Check if category_id is set in the request
$category_id = isset($_GET['category_id']) && $_GET['category_id'] !== '' ? intval($_GET['category_id']) : null;

// Prepare SQL query
if ($category_id === null) {
    // Fetch all products when no category is selected
    $sql = "SELECT p.*, c.status FROM product p 
            JOIN category c ON p.category_id = c.category_id 
            WHERE c.status = 1"; // Only fetch active categories
} else {
    // Fetch products for the selected category
    $sql = "SELECT p.*, c.status FROM product p 
            JOIN category c ON p.category_id = c.category_id
            WHERE p.category_id = ? AND c.status = 1";
}

$stmt = $conn->prepare($sql);
if ($category_id !== null) {
    $stmt->bind_param("i", $category_id);
}
$stmt->execute();
$result = $stmt->get_result();

// Generate product HTML
$output = '';
while ($row = mysqli_fetch_assoc($result)) {
    $image_path = "/Mini_Project/seller_panel/" . ltrim($row['product_image'], './');

    $output .= '
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5">
            <a href="product-details.php?product_id=' . $row["product_id"] . '" class="product-item">
                <img class="images_1" src="' . $image_path . '" 
                alt="' . htmlspecialchars($row['product_name']) . '" 
                onerror="this.src=\'/Mini_Project/seller_panel/uploads/default.jpg\';">
                <h3 class="product-title">' . htmlspecialchars($row['product_name']) . '</h3>
                <strong class="product-price">$' . number_format($row['price'], 2) . '</strong><br>
            </a>
        </div>';
}

// Return product HTML
echo $output;
?>

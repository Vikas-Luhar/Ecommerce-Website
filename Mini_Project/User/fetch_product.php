<?php
require_once 'connection.php';

$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : '';
$subcategory_id = isset($_GET['subcategory_id']) ? intval($_GET['subcategory_id']) : '';

$sql = "SELECT * FROM product WHERE 1";

if (!empty($category_id)) {
    $sql .= " AND category_id = $category_id";
}

if (!empty($subcategory_id)) {
    $sql .= " AND subcategory_id = $subcategory_id";
}

$result = $conn->query($sql);

while ($row = mysqli_fetch_assoc($result)) {
    echo '<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5">
            <a href="product-details.php?product_id='.$row['product_id'].'" class="product-item">
                <img class="product-image" src="/Mini_Project/seller_panel/'.$row['product_image'].'" 
                     alt="'.$row['product_name'].'" onerror="this.src=\'/Mini_Project/seller_panel/uploads/default.jpg\';">
                <h3 class="product-title">'.$row['product_name'].'</h3>
                <strong class="product-price">$'.$row['price'].'</strong>
            </a>
          </div>';
}

?>
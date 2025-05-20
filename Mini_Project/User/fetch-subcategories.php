<?php
require_once 'connection.php';

$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$subcategory_id = isset($_GET['subcategory_id']) ? intval($_GET['subcategory_id']) : 0;

if ($subcategory_id > 0) {
    $sql = "SELECT * FROM product WHERE sub_category_id = $subcategory_id";
} elseif ($category_id > 0) {
    $sql = "SELECT * FROM product WHERE category_id = $category_id";
} else {
    $sql = "SELECT * FROM product";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5">';
        echo '<a href="product-details.php?product_id=' . $row['product_id'] . '" class="product-item">';
        echo '<img class="product-image" src="/Mini_Project/seller_panel/' . htmlspecialchars($row['product_image']) . '" alt="' . htmlspecialchars($row['product_name']) . '" onerror="this.src=\'/Mini_Project/seller_panel/uploads/default.jpg\';">';
        echo '<h3 class="product-title">' . htmlspecialchars($row['product_name']) . '</h3>';
        echo '<strong class="product-price">$' . number_format($row['price'], 2) . '</strong>';
        echo '</a>';
        echo '</div>';
    }
} else {
    echo "<p>No products found for this selection.</p>";
}
?>

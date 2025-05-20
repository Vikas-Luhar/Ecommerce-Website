<?php
require_once 'connection.php';

// // Start session
// session_start();

// // Check if user is logged in, if not, redirect to login page
// if (!isset($_SESSION['user_name'])) {
//     header('Location: ../index.php'); // Redirect to login if not authenticated
//     exit();
// }

// Check if a category has been selected via GET parameter
if (isset($_GET['category_id'])) {
    $category_id = intval($_GET['category_id']); // Sanitize the category_id
    
    // Query to filter products by category and ensure the category is enabled
    $sql = "SELECT p.* 
            FROM product p 
            JOIN category c ON p.category_id = c.category_id
            WHERE p.category_id = $category_id AND c.status = 1";
} else {
    // Query to select all products and ensure their categories are enabled
    $sql = "SELECT p.* 
            FROM product p 
            JOIN category c ON p.category_id = c.category_id
            WHERE c.status = 1";
}

// Get the selected category name for the dropdown button text
$selected_category_name = 'All'; // Default is 'All'
if (isset($_GET['category_id']) && !empty($_GET['category_id'])) {
    $category_id = intval($_GET['category_id']);
    $result = $conn->query("SELECT category_name FROM category WHERE category_id = $category_id");
    if ($result->num_rows > 0) {
        $selected_category = $result->fetch_assoc();
        $selected_category_name = $selected_category['category_name'];
    }
}
// Fetch subcategories for the selected category
$subcategories = [];
if (isset($_GET['category_id']) && !empty($_GET['category_id'])) {
    $subcategory_query = "SELECT sub_category_id, sub_category_name FROM sub_category WHERE category_id = $category_id AND status = 1";
    $subcategories = $conn->query($subcategory_query);
}


$all_product = $conn->query($sql);

// Fetch categories for the dropdown menu
$sql_categories = "SELECT category_id, category_name FROM category WHERE status = 1"; // Only active categories
$categories = $conn->query($sql_categories);
// Check if category or subcategory is selected
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : '';
$subcategory_id = isset($_GET['subcategory_id']) ? intval($_GET['subcategory_id']) : '';

$sql = "SELECT p.* 
        FROM product p 
        JOIN category c ON p.category_id = c.category_id
        WHERE c.status = 1";

if (!empty($category_id)) {
    $sql .= " AND p.category_id = $category_id";
}

if (!empty($subcategory_id)) {
    $sql .= " AND p.subcategory_id = $subcategory_id"; // Ensure column exists
}

$all_product = $conn->query($sql);

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="Untree.co">
  <link rel="shortcut icon" href="box 1.png">

  <meta name="description" content="" />
  <meta name="keywords" content="bootstrap, bootstrap4" />

  <!-- Bootstrap CSS -->
  <link href="User-css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="User-css/tiny-slider.css" rel="stylesheet">
  <link href="User-css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="User-css/shop.css">
  <title>Marble & Tiles Online Depot</title>
</head>

<body>

  <?php include_once 'header.php'; ?>

  <!-- Start Hero Section -->
  <div class="hero">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-5">
          <div class="intro-excerpt">
            <h1>Shop</h1>
            <p class="mb-4">Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique.</p>
            <p><a href="shop.php" class="btn btn-secondary me-2">Shop Now</a></p>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="hero-img-wrap">
            <img src="images/photo 3.png" class="img-fluid">
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Hero Section -->
  <br> 
<!-- Start Category Dropdown -->
<div class="dropdown">
    <button class="btn btn-primary dropdown-toggle category-btn" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        <?php echo isset($selected_category_name) ? htmlspecialchars($selected_category_name) : 'All Categories'; ?>
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <li><a class="dropdown-item" href="shop.php">All Categories</a></li>

    <?php while ($category = mysqli_fetch_assoc($categories)) : ?>
        <li class="dropdown-submenu">
            <a class="dropdown-item dropdown-toggle" href="shop.php?category_id=<?= $category['category_id']; ?>">
                <?= htmlspecialchars($category['category_name']); ?>
            </a>

            <?php 
            // Fetch subcategories
            $sub_sql = "SELECT sub_category_id, sub_category_name FROM sub_category WHERE category_id = " . $category['category_id'];
            $subcategories = $conn->query($sub_sql);
            ?>

            <?php if ($subcategories->num_rows > 0) : ?>
                <ul class="dropdown-menu">
                    <?php while ($sub = mysqli_fetch_assoc($subcategories)) : ?>
                        <li>
                            <a class="dropdown-item" href="shop.php?category_id=<?= $category['category_id']; ?>&subcategory_id=<?= $sub['sub_category_id']; ?>">
                                <?= htmlspecialchars($sub['sub_category_name']); ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php endif; ?>
        </li>
    <?php endwhile; ?>
</ul>
</div>


<!-- Product Section -->
<div class="untree_co-section product-section before-footer-section" id="product-section">
    <div class="container">
        <div class="row" id="product-list">
            <?php while ($row = mysqli_fetch_assoc($all_product)): ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5">
                    <a href="product-details.php?product_id=<?php echo $row['product_id']; ?>" class="product-item">
                        <img class="product-image" 
                             src="/Mini_Project/seller_panel/<?php echo $row['product_image']; ?>" 
                             alt="<?php echo $row['product_name']; ?>" 
                             onerror="this.src='/Mini_Project/seller_panel/uploads/default.jpg';">
                        <h3 class="product-title"><?php echo $row['product_name']; ?></h3>
                        <strong class="product-price">₹<?php echo $row['price']; ?></strong>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<!-- End Product Section -->

  <!-- Start Footer Section -->
  <footer class="footer-section">
    <div class="container relative">
      <div class="sofa-img">
        <img src="images/Marble 11.png" alt="Image" class="img-fluid">
      </div>
      <div class="row g-5 mb-5">
        <div class="col-lg-4">
          <div class="mb-4 footer-logo-wrap"><a href="#" class="footer-logo">M&T<span>.</span></a></div>
          <p class="mb-4">Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant</p>
          <ul class="list-unstyled custom-social">
            <li><a href="#"><span class="fa fa-brands fa-facebook-f"></span></a></li>
            <li><a href="#"><span class="fa fa-brands fa-twitter"></span></a></li>
            <li><a href="#"><span class="fa fa-brands fa-instagram"></span></a></li>
            <li><a href="#"><span class="fa fa-brands fa-linkedin"></span></a></li>
          </ul>
        </div>
        <!-- <div class="col-lg-8">
          <div class="row links-wrap">
            <div class="col-6 col-sm-6 col-md-3">
              <ul class="list-unstyled">
                <li><a href="#">About us</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">Contact us</a></li>
              </ul>
            </div>
            <div class="col-6 col-sm-6 col-md-3">
              <ul class="list-unstyled">
                <li><a href="#">Support</a></li>
                <li><a href="#">Knowledge base</a></li>
                <li><a href="#">Live chat</a></li>
              </ul>
            </div>
            <div class="col-6 col-sm-6 col-md-3">
              <ul class="list-unstyled">
                <li><a href="#">Jobs</a></li>
                <li><a href="#">Our team</a></li>
                <li><a href="#">Leadership</a></li>
                <li><a href="#">Privacy Policy</a></li>
              </ul>
            </div>
            <div class="col-6 col-sm-6 col-md-3">
              <ul class="list-unstyled">
                <li><a href="#">Tiles</a></li>
                <li><a href="#">Marble</a></li>
                <li><a href="#">Tile</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer> -->
  <!-- End Footer Section -->

<script>$(document).ready(function () {
    $(".category-option").click(function (e) {
        e.preventDefault();
        let categoryId = $(this).data("id") || ""; // Set to empty string for "All"
        let categoryName = $(this).text(); // Get category name from dropdown

        // Update the button text
        $("#dropdownMenuButton").text(categoryName);

        // Fetch products dynamically
        $.ajax({
            url: "fetch_product.php",
            type: "GET",
            data: { category_id: categoryId },
            success: function (response) {
                $("#product-list").html(response);
            },
            error: function () {
                alert("Error fetching products. Please try again.");
            }
        });
    });
});
</script>
  <script src="user-js/bootstrap.bundle.min.js"></script>
  <script src="user-js/tiny-slider.js"></script>
  <script src="user-js/custom.js"></script>
  <script>
$(document).ready(function () {
    $(".dropdown-item").click(function (e) {
        e.preventDefault();
        
        let categoryId = $(this).data("category-id") || ""; // Empty for all
        let subcategoryId = $(this).data("subcategory-id") || ""; 

        $.ajax({
            url: "fetch_product.php",
            type: "GET",
            data: { category_id: categoryId, subcategory_id: subcategoryId },
            success: function (response) {
                $("#product-list").html(response);
            },
            error: function () {
                alert("Error fetching products. Please try again.");
            }
        });
    });
});
</script>
</script>
</body>
</html>

<?php
require_once 'connection.php';
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect if user is not logged in
if (!isset($_SESSION['user_email'])) {
    header('Location: ../index.php');
    exit();
}

// Fetch user ID using email from session
$sql_user = "SELECT user_id FROM user_form WHERE email = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $_SESSION['user_email']);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user_row = $result_user->fetch_assoc();
    $user_id = $user_row['user_id']; // Store user ID for cart operations
} else {
    echo "Error: User not found.";
    exit();
}

// Check if product_id is passed in URL
if (!isset($_GET['product_id']) || empty($_GET['product_id'])) {
    echo "Invalid product ID.";
    exit();
}

$product_id = intval($_GET['product_id']);

// Fetch product details
$sql = "SELECT * FROM product WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Product not found.";
    exit();
}

$row = $result->fetch_assoc();

// Add product to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']); // Get selected quantity
    
    if (!isset($user_id)) {
        echo "Error: User ID is missing!";
        exit();
    }
    
    // Check if the product is already in the cart
    $check_cart = $conn->prepare("SELECT * FROM tblcart WHERE User_ID = ? AND Product_ID = ?");
    $check_cart->bind_param("ii", $user_id, $product_id);
    $check_cart->execute();
    $result = $check_cart->get_result();
    
    if ($result->num_rows > 0) {
        // Update quantity
        $update_cart = $conn->prepare("UPDATE tblcart SET Quantity = Quantity + ?, Amount = Amount + (? * ?) WHERE User_ID = ? AND Product_ID = ?");
        $update_cart->bind_param("idiii", $quantity, $price, $quantity, $user_id, $product_id);
        $update_cart->execute();
    } else {
        // Insert new item
        $total_price = $price * $quantity;
        $insert_cart = $conn->prepare("INSERT INTO tblcart (User_ID, Product_ID, Price, Quantity, Amount) VALUES (?, ?, ?, ?, ?)");
        $insert_cart->bind_param("iidid", $user_id, $product_id, $price, $quantity, $total_price);
        $insert_cart->execute();
    }
    
    // Redirect to cart page
    header("Location: cart.php");
    exit();
}
// Fetch all images for the product
$sql_images = "SELECT ImageURL FROM tblimages WHERE Product_ID = ?";
$stmt_images = $conn->prepare($sql_images);
$stmt_images->bind_param("i", $product_id);
$stmt_images->execute();
$result_images = $stmt_images->get_result();

// Check if additional images exist
$images = [];
while ($img_row = $result_images->fetch_assoc()) {
    $images[] = '../seller_panel/' . ltrim($img_row['ImageURL'], './'); // Adjust path
}
// Fetch related products from the same category (excluding the current product)
$sql_related = "SELECT * FROM product WHERE category_id = ? AND product_id != ? LIMIT 4";
$stmt_related = $conn->prepare($sql_related);
$stmt_related->bind_param("ii", $row['category_id'], $product_id);
$stmt_related->execute();
$result_related = $stmt_related->get_result();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Product Details | Marble & Tiles Online Depot</title>
        
        <!-- Bootstrap & Font Awesome -->
        <link href="User-css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <link href="User-css/style.css" rel="stylesheet">
        
        <style>
            /* General Styling */
            body {
                background-color: #f8f9fa;
                font-family: 'Arial', sans-serif;
            }
            
            /* Main Product Details Container */
            .product-details {
                display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    gap: 30px;
    max-width: 1100px;
    margin: auto;
    padding: 30px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

/* Product Image Section */
.product-image {
    flex: 1;
    text-align: center;
}

.product-image img {
    width: 100%;
    max-width: 450px;
    height: auto;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease-in-out;
}

.product-image img:hover {
    transform: scale(1.05);
}

/* Product Carousel */
#productCarousel {
    max-width: 450px;
    margin: auto;
}

.carousel-inner img {
    border-radius: 10px;
}

.carousel-control-prev,
.carousel-control-next {
    top: 50%;
    transform: translateY(-50%);
    width: 8%;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    padding: 10px;
}

/* Product Information */
.product-info {
    flex: 1;
    padding: 20px;
}

.product-info h1 {
    font-size: 30px;
    font-weight: bold;
    color: #222;
    margin-bottom: 15px;
}

.product-info p {
    font-size: 18px;
    color: #555;
    line-height: 1.6;
}

.product-price {
    font-size: 26px;
    font-weight: bold;
    color: #e60023;
    margin: 20px 0;
}

/* Quantity Selector */
.quantity-box {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

.quantity-btn {
    background-color: #28a745;
    color: white;
    border: none;
    font-size: 20px;
    padding: 8px 18px;
    cursor: pointer;
    border-radius: 6px;
    transition: background 0.3s;
}

.quantity-btn:hover {
    background-color: #218838;
}

.quantity-input {
    width: 60px;
    text-align: center;
    font-size: 18px;
    border: 2px solid #ccc;
    border-radius: 6px;
    padding: 6px;
    background: #fff;
}

/* Add to Cart Button */
.add-to-cart {
    background: #28a745;
    color: white;
    padding: 16px 35px;
    border: none;
    font-size: 20px;
    font-weight: bold;
    cursor: pointer;
    border-radius: 8px;
    width: 100%;
    transition: background 0.3s, transform 0.2s;
}

.add-to-cart:hover {
    background: #218838;
    transform: scale(1.02);
}

/* Responsive Design */
@media (max-width: 768px) {
    .product-details {
        flex-direction: column;
        text-align: center;
        padding: 20px;
    }

    .product-image img {
        max-width: 100%;
    }
}
/* Related Products Section */
.related-title {
    font-size: 24px;
    font-weight: bold;
    text-align: center;
    margin: 40px 0 20px;
    color: #222;
}

.related-products {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.related-item {
    width: 250px;
    text-align: center;
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: 0.3s;
}

.related-item:hover {
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    transform: scale(1.05);
}

.related-item img {
    max-width: 100%;
    border-radius: 5px;
    height: 180px;
    object-fit: cover;
}

.related-item h3 {
    font-size: 18px;
    margin: 10px 0;
    color: #333;
}

.related-price {
    font-size: 20px;
    font-weight: bold;
    color: #e60023;
}

.view-btn {
    display: inline-block;
    margin-top: 10px;
    padding: 10px 15px;
    background: #28a745;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 16px;
    transition: 0.3s;
}

.view-btn:hover {
    background: #218838;
}

</style>
</head>
<body>

<?php include_once 'header.php'; ?>

<div class="container">
    <div class="product-details">
        <!-- Image Slider (Bootstrap Carousel) -->
<div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
<div class="carousel-inner">
    <!-- First Slide: Default Product Image -->
    <div class="carousel-item active">
        <img src="<?php echo '../seller_panel/' . ltrim($row['product_image'], './'); ?>" 
             class="d-block w-100 product-img" alt="Product Image">
    </div>

    <!-- Additional Images from tblimages -->
    <?php if (!empty($images)): ?>
        <?php foreach ($images as $img): ?>
            <div class="carousel-item">
                <img src="<?php echo $img; ?>" class="d-block w-100 product-img" alt="Product Image">
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>


        <!-- Product Information -->
        <div class="product-info">
            <h1><?php echo htmlspecialchars($row['product_name']); ?></h1>
            <p><?php echo nl2br(htmlspecialchars($row['product_desc'])); ?></p>
            <div class="product-price">₹<?php echo htmlspecialchars($row['price']); ?></div>

            <!-- Quantity Selector -->
            <div class="quantity-box">
                <button type="button" class="quantity-btn" onclick="updateQuantity(-1)">-</button>
                <input type="text" id="quantity" class="quantity-input" value="1" name="quantity" readonly>
                <button type="button" class="quantity-btn" onclick="updateQuantity(1)">+</button>
            </div>

            <!-- Add to Cart Form -->
            <form action="" method="post">
                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                <input type="hidden" name="price" value="<?php echo htmlspecialchars($row['price']); ?>">
                <input type="hidden" id="quantity-input" name="quantity" value="1"> <!-- Hidden input -->
                <button class="add-to-cart" type="submit" name="add_to_cart">Add to Cart</button>
            </form>
        </div>
    </div>
</div>
<div class="container">
    <h2 class="related-title">Related Products</h2>
    <div class="related-products">
        <?php while ($related = $result_related->fetch_assoc()): ?>
            <div class="related-item">
                <a href="product-details.php?product_id=<?php echo $related['product_id']; ?>">
                    <img src="<?php echo '../seller_panel/' . ltrim($related['product_image'], './'); ?>" 
                         alt="<?php echo htmlspecialchars($related['product_name']); ?>">
                </a>
                <h3><?php echo htmlspecialchars($related['product_name']); ?></h3>
                <div class="related-price">₹<?php echo number_format($related['price'], 2); ?></div>
                <a href="product_details.php?product_id=<?php echo $related['product_id']; ?>" class="view-btn">View Product</a>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
function updateQuantity(change) {
    let quantity = document.getElementById('quantity');
    let inputHidden = document.getElementById('quantity-input');
    let value = Math.max(1, parseInt(quantity.value) + change);
    quantity.value = value;
    inputHidden.value = value; // Ensure correct quantity is sent in form
}
</script>
<!-- Bootstrap Bundle (Includes Popper.js for Carousel) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Activate Bootstrap Carousel
    let productCarousel = new bootstrap.Carousel(document.querySelector("#productCarousel"), {
        interval: 3000, // Auto-slide every 3 seconds
        wrap: true // Infinite loop
    });
});
</script>


</body>
</html>
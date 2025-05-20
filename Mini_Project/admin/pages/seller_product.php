<?php
include_once "../config/dbconnect.php";

if (!isset($_GET['id'])) {
    die("Invalid request!");
}

$seller_id = intval($_GET['id']);

// Fetch seller's products
$productQuery = "SELECT * FROM product WHERE Seller_ID = ?";
$stmtProduct = $conn->prepare($productQuery);
$stmtProduct->bind_param("i", $seller_id);
$stmtProduct->execute();
$productResult = $stmtProduct->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Seller Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .product-container {
            max-width: 1200px;
            margin: auto;
        }
        .product-card {
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
            background: #fff;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .card-body {
            padding: 15px;
        }
        .product-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .product-price {
            font-size: 16px;
            color: #28a745;
            font-weight: bold;
        }
        .product-desc {
            font-size: 14px;
            color: #555;
            max-height: 40px; /* Limit to 2 lines */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .read-more {
            color: #007bff;
            font-size: 14px;
            cursor: pointer;
        }
    </style>
</head>
<body class="container mt-4">
    <a href="view_seller.php?id=<?= $seller_id ?>" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Back
    </a>

    <h3 class="text-center mb-4">Seller's Products</h3>

    <div class="row product-container">
    <?php if ($productResult->num_rows > 0) : ?>
        <?php while ($product = $productResult->fetch_assoc()) : ?>
            <div class="col-md-4 mb-4">
                <div class="card product-card shadow-sm">
                    <img src="/Mini_Project/seller_panel/<?= htmlspecialchars($product['product_image']) ?>" alt="Product Image">
                    <div class="card-body">
                        <h5 class="product-title"><?= htmlspecialchars($product['product_name']) ?></h5>
                        <p class="product-price">₹<?= htmlspecialchars($product['price']) ?></p>
                        <p class="product-desc">
                            <?= substr(htmlspecialchars($product['product_desc']), 0, 50) ?>...
                            <span class="read-more">Read more</span>
                        </p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else : ?>
        <div class="col-12">
            <p class="alert alert-warning text-center">This seller doesn't have any products.</p>
        </div>
    <?php endif; ?>
    </div>
</body>
</html>

    <?php
    include_once "../config/dbconnect.php"; // Database connection

    if (!isset($_GET['id'])) {
        die("Invalid request!");
    }

    $seller_id = intval($_GET['id']);

    // Fetch seller details
    $sql = "SELECT * FROM seller WHERE seller_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $seller_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $seller = $result->fetch_assoc();

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
    <title>Seller Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        .card-custom {
            border-radius: 15px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }
        .card-custom:hover {
            transform: translateY(-5px);
        }
        .seller-img {
            width: 130px;
            height: 130px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #007bff;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 30px;
            padding: 10px 20px;
        }
        .product-card img {
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
        }
        .status-active {
    color: green;
    font-weight: bold;
}
.status-inactive {
    color: red;
    font-weight: bold;
}

    </style>
</head>
<body class="container py-5">
    <a href="view-details.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Back</a>

    <div class="card card-custom p-4">
        <div class="row align-items-center">
            <div class="col-md-4 text-center">
                <img src="../../seller_panel/uploads/<?= htmlspecialchars($seller['Seller_Shop_Logo']) ?>" 
                    class="seller-img img-fluid" 
                    onerror="this.src='uploads/default_logo.jpg'">
            </div>
            <div class="col-md-8">
                <h3 class="text-primary mb-3"><strong><?= htmlspecialchars($seller['Seller_Name']) ?></strong></h3>
                <p><i class="fas fa-store"></i> <strong>Shop:</strong> <?= htmlspecialchars($seller['Seller_Shop_Name']) ?></p>
                <p><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> <?= htmlspecialchars($seller['Seller_Shop_Address']) ?></p>
                <p><i class="fas fa-envelope"></i> <strong>Email:</strong> <?= htmlspecialchars($seller['Email_Id']) ?></p>
                <p><i class="fas fa-phone"></i> <strong>Mobile:</strong> <?= htmlspecialchars($seller['Seller_Mobile_No']) ?></p>
                <p><i class="fas fa-check-circle"></i> <strong>Status:</strong> 
    <?php echo $seller['IsActive'] ? "<span class='text-success'>Active</span>" : "<span class='text-danger'>Inactive</span>"; ?>
</p>
<p><i class="fas fa-user-shield"></i> <strong>Approved By:</strong> 
    <?= $seller['ApprovedBy'] ? htmlspecialchars($seller['ApprovedBy']) : "Pending Approval"; ?>
</p>

                
                <div class="text-center mt-3">
                    <a href="seller_product.php?id=<?= $seller_id ?>" class="btn btn-custom">
                        <i class="fas fa-box"></i> Seller Product
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
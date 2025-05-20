<?php
require_once 'connection.php'; // Ensure database connection is included
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_name'])) {
    header('Location: ../index.php'); // Redirect to login if not authenticated
    exit();
}

$purchase_success = false;
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['purchase'])) {
    // Check if required fields are set
    if (isset($_POST['Address'], $_POST['name'], $_POST['pay_mode'], $_POST['product_id'], $_POST['qty'], $_POST['product_name'])) {
        // Fetch user input from the form
        $Address = htmlspecialchars($_POST['Address']);
        $name = htmlspecialchars($_POST['name']);
        $pay_mode = htmlspecialchars($_POST['pay_mode']);
        $product_id = intval($_POST['product_id']);
        $qty = intval($_POST['qty']);
        $price = floatval($_POST['price']);
        $total_price = $qty * $price;
        $product_name = htmlspecialchars($_POST['product_name']); // Fetch product name

        // Fetch user email (assuming it's stored in session)
        $email = htmlspecialchars($_SESSION['user_email']); // Make sure to set this in session when user logs in

        // First, fetch the category_id from the product table
        $product_sql = "SELECT category_id FROM product WHERE product_id = ?";
        $product_stmt = $conn->prepare($product_sql);
        $product_stmt->bind_param('i', $product_id);
        $product_stmt->execute();
        $product_result = $product_stmt->get_result();

        if ($product_result->num_rows > 0) {
            $product_row = $product_result->fetch_assoc();
            $category_id = $product_row['category_id'];

            // Now fetch the category name based on category_id
            $category_sql = "SELECT category_name FROM category WHERE category_id = ?";
            $category_stmt = $conn->prepare($category_sql);
            $category_stmt->bind_param('i', $category_id);
            $category_stmt->execute();
            $category_result = $category_stmt->get_result();

            if ($category_result->num_rows > 0) {
                $category_row = $category_result->fetch_assoc();
                $category_name = $category_row['category_name']; // Either 'marble' or 'tile'

                // Insert the purchase into the purchases table
                $sql = "INSERT INTO purchases (product_id, product_name, name, email, Address, pay_mode, category_name, qty, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                // Bind parameters (note the correct types)
                $stmt->bind_param('issssssii', $product_id, $product_name, $name, $email, $Address, $pay_mode, $category_name, $qty, $total_price);

                if ($stmt->execute()) {
                    $purchase_success = true; // Set flag to indicate success
                    $success_message = "Purchase successful! Thank you for buying $category_name.";
                } else {
                    $error_message = "Error: " . $conn->error;
                }
            } else {
                $error_message = "Category not found.";
            }
        } else {
            $error_message = "Product not found.";
        }
    } else {
        $error_message = "Missing form fields!";
    }
}
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
    <link href="User-css/shop.css" rel="stylesheet">

    <title>Marble & Tiles Online Depot</title>

    <style>
        .thank-you-message {
            text-align: center;
            margin-top: 100px;
        }
    </style>

    <script>
        function updateTotalPrice() {
            var price = parseFloat(document.getElementById('price').value);
            var qty = parseInt(document.getElementById('qty').value);
            var totalPrice = price * qty;
            document.getElementById('total_price').value = totalPrice.toFixed(2);
            document.getElementById('total_price_display').innerText = '$' + totalPrice.toFixed(2);
        }
    </script>
</head>
<body>
<?php include_once 'header.php'; ?>

<div class="container mt-5">

    <?php if ($purchase_success) { ?>
        <!-- Thank You message when purchase is successful -->
        <div class="thank-you-message">
            <h2>Thank You for Your Purchase!</h2>
            <p><?php echo $success_message; ?></p>
            <a href="order-history.php" class="btn btn-primary">View Order History</a>
        </div>
    <?php } else { ?>
        <!-- Purchase Form -->
        <h2>Complete your Purchase</h2>

        <!-- Display error message if any -->
        <?php if (!empty($error_message)) { ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php } ?>

        <form action="purchase.php" method="post">
            <!-- Assuming product_id should be hidden -->
            <input type="hidden" name="product_id" value="<?php echo isset($_POST['product_id']) ? htmlspecialchars($_POST['product_id']) : ''; ?>">
            <input type="hidden" name="product_image" value="<?php echo isset($_POST['product_image']) ? htmlspecialchars($_POST['product_image']) : ''; ?>">
            
            <!-- Hidden input for product name -->
            <input type="hidden" name="product_name" value="<?php echo isset($_POST['product_name']) ? htmlspecialchars($_POST['product_name']) : ''; ?>">
            
            <input type="hidden" id="price" name="price" value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
            
            <div class="form-group mb-3">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" 
                value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>" 
                readonly>
            </div>

            <div class="form-group mb-3">
                <label for="address">Address</label>
                <textarea name="Address" id="address" class="form-control" rows="4" cols="50" required></textarea>
            </div>
            
            <!-- Quantity Input -->
            <div class="form-group mb-3">
                <label for="qty">Quantity:</label>
                <input type="number" name="qty" id="qty" class="form-control" value="1" min="1" required oninput="updateTotalPrice()">
            </div>
            
            <div class="form-group mb-3">
                <label for="total_price">Total Price</label>
                <input type="text" id="total_price" class="form-control" value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>" readonly>
                <p id="total_price_display">$<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?></p>
            </div>
            
            <div class="form-group mb-3">
                <label for="pay_mode">Payment Mode</label>
                <div>
                    <label>
                        <input type="radio" name="pay_mode" value="COD" required> Cash on Delivery
                    </label>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block" name="purchase">Make Purchase</button>
        </form>
    <?php } ?>
</div>

<script src="user-js/bootstrap.bundle.min.js"></script>
<script src="user-js/tiny-slider.js"></script>
<script src="user-js/custom.js"></script>
</body>
</html>
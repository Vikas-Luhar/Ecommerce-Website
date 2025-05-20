<?php
session_start();
require_once 'connection.php';

// Redirect if user is not logged in
if (!isset($_SESSION['user_email'])) {
    header('Location: ../index.php');
    exit();
}

// Fetch user ID from session
$sql_user = "SELECT user_id FROM user_form WHERE email = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $_SESSION['user_email']);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user_row = $result_user->fetch_assoc();
    $user_id = $user_row['user_id'];
} else {
    echo "Error: User not found.";
    exit();
}

// Handle cart update request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    $cart_id = intval($_POST['cart_id']);
    $quantity = max(1, intval($_POST['quantity']));

    // ✅ Fetch the product price before updating
    $price_query = "SELECT product.price FROM product 
                    INNER JOIN tblcart ON product.product_id = tblcart.Product_ID 
                    WHERE tblcart.Cart_ID = ?";
    $stmt_price = $conn->prepare($price_query);
    $stmt_price->bind_param("i", $cart_id);
    $stmt_price->execute();
    $price_result = $stmt_price->get_result();
    $price_row = $price_result->fetch_assoc();
    $price = $price_row['price'];

    // ✅ Update quantity & total price in database
    $update_query = "UPDATE tblcart 
                     SET Quantity = ?, Amount = ? * ? 
                     WHERE Cart_ID = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("idii", $quantity, $price, $quantity, $cart_id);
    $stmt->execute();
    exit();
}

// Fetch cart items
$sql = "SELECT tblcart.*, product.product_name, product.product_image, product.price 
        FROM tblcart 
        INNER JOIN product ON tblcart.Product_ID = product.product_id 
        WHERE tblcart.User_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Remove item from cart
if (isset($_GET['remove'])) {
    $cart_id = intval($_GET['remove']);
    $delete_query = "DELETE FROM tblcart WHERE Cart_ID = ? AND User_ID = ?";
    $stmt_delete = $conn->prepare($delete_query);
    $stmt_delete->bind_param("ii", $cart_id, $user_id);
    $stmt_delete->execute();
    header("Location: cart.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="User-css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="User-css/style.css" rel="stylesheet">

    <style>
        .quantity-box {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        .quantity-btn {
            background-color: #28a745;
            color: white;
            border: none;
            font-size: 16px;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .quantity-btn:hover {
            background-color: #218838;
        }
        .quantity-input {
            width: 50px;
            text-align: center;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px;
        }
        .remove-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .remove-btn:hover {
            background-color: darkred;
        }
        .total-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .cart-product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
    </style>

    <script>
function updateQuantity(cartId, change) {
    let quantityInput = document.getElementById("quantity_" + cartId);
    let totalPrice = document.getElementById("total_" + cartId);
    let price = parseFloat(document.getElementById("price_" + cartId).innerText.replace(/[^0-9.]/g, "")); 
    let totalAmount = document.getElementById("total_amount");

    let value = parseInt(quantityInput.value) || 1;
    value = Math.max(1, value + change);
    quantityInput.value = value;

    // Update total price for product
    let newTotal = price * value;
    totalPrice.innerText = "₹" + newTotal.toFixed(2);

    // Update grand total
    let allTotals = document.querySelectorAll(".total-price");
    let grandTotal = 0;
    allTotals.forEach(item => {
        grandTotal += parseFloat(item.innerText.replace(/[^0-9.]/g, "")) || 0;
    });
    totalAmount.innerText = "₹" + grandTotal.toFixed(2);

    // Update quantity in database via AJAX
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "cart.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("cart_id=" + cartId + "&quantity=" + value);
}
    </script>
    <!-- Bootstrap JS -->
<script src="User-js/bootstrap.bundle.min.js"></script>

</head>
<body>
<?php include 'header.php' ?>

<div class="container mt-5">
    <h2 class="text-center">Shopping Cart</h2>
    <?php if ($result->num_rows > 0) { ?>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_cart_price = 0;
                while ($row = $result->fetch_assoc()) { 
                    $total_cart_price += $row['Amount'];
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td>
                            <img src="<?= '../seller_panel/' . ltrim($row['product_image'], './'); ?>" 
                                 alt="<?= htmlspecialchars($row['product_name']); ?>" 
                                 class="cart-product-img">
                        </td>
                        <td id="price_<?php echo $row['Cart_ID']; ?>">₹<?php echo number_format($row['Price'], 2); ?></td>
                        <td>
                            <div class="quantity-box">
                                <button type="button" class="quantity-btn" onclick="updateQuantity(<?php echo $row['Cart_ID']; ?>, -1)">-</button>
                                <input type="text" id="quantity_<?php echo $row['Cart_ID']; ?>" value="<?php echo $row['Quantity']; ?>" class="quantity-input" readonly>
                                <button type="button" class="quantity-btn" onclick="updateQuantity(<?php echo $row['Cart_ID']; ?>, 1)">+</button>
                            </div>
                        </td>
                        <td id="total_<?php echo $row['Cart_ID']; ?>" class="total-price">₹<?php echo number_format($row['Amount'], 2); ?></td>
                        <td><a href="cart.php?remove=<?php echo $row['Cart_ID']; ?>" class="remove-btn">Remove</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h4><strong>Total Price: <span id="total_amount">₹<?php echo number_format($total_cart_price, 2); ?></span></strong></h4>
        <form id="checkoutForm" action="checkout.php" method="POST">
        <!-- <input type="hidden" name="order_token" value="<?= $_SESSION['order_token']; ?>"> -->
    <input type="hidden" name="total_amount" id="total_amount_input" value="<?= $total_cart_price ?>">
    <button type="submit" name="checkout" class="btn btn-primary">Proceed to Checkout</button>
</form>



    <?php } else { ?>
        <p class="text-center">Your cart is empty.</p>
    <?php } ?>
</div>
<script>
function updateQuantity(cartId, change) {
    let quantityInput = document.getElementById("quantity_" + cartId);
    let totalPrice = document.getElementById("total_" + cartId);
    let price = parseFloat(document.getElementById("price_" + cartId).innerText.replace(/[^0-9.]/g, ""));
    let totalAmountInput = document.getElementById("total_amount_input"); // Hidden field

    let value = parseInt(quantityInput.value) || 1;
    value = Math.max(1, value + change);
    quantityInput.value = value;

    // Update total price for product
    let newTotal = price * value;
    totalPrice.innerText = "₹" + newTotal.toFixed(2);

    // Update grand total
    let allTotals = document.querySelectorAll(".total-price");
    let grandTotal = 0;
    allTotals.forEach(item => {
        grandTotal += parseFloat(item.innerText.replace(/[^0-9.]/g, "")) || 0;
    });

    document.getElementById("total_amount").innerText = "₹" + grandTotal.toFixed(2);
    totalAmountInput.value = grandTotal.toFixed(2); // Update hidden field

    // Update quantity in database via AJAX
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "cart.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("cart_id=" + cartId + "&quantity=" + value);
}

document.getElementById("checkoutForm").addEventListener("submit", function(event) {
    let totalAmountInput = document.getElementById("total_amount_input");
    let totalAmountDisplay = document.getElementById("total_amount").innerText.replace(/[^0-9.]/g, ""); 

    if (parseFloat(totalAmountDisplay) <= 0) {
        alert("Invalid total amount! Please check your cart.");
        event.preventDefault(); // Stop form submission
        return;
    }

    totalAmountInput.value = totalAmountDisplay; // ✅ Ensure hidden field is updated
});


$(document).ready(function(){
    $(".add-to-cart").click(function(){
        let productId = $(this).data("id");

        $.ajax({
            url: "checkSession.php",
            type: "GET",
            success: function(response){
                if(response === "logged_in") {
                    addToCart(productId);
                } else {
                    sessionStorage.setItem("cart_temp", productId); // Store product ID
                    window.location.href = "login.php"; // Redirect to login
                }
            }
        });
    });

    function addToCart(productId){
        $.ajax({
            url: "addToCart.php",
            type: "POST",
            data: { product_id: productId },
            success: function(response){
                alert("Product added to cart!");
                location.reload();
            }
        });
    }
});

</script>
<script>
    document.getElementById("total_price").value = calculateTotal();
</script>
</body>
</html>

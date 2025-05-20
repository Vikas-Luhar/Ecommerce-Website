<?php
session_start();
require_once 'connection.php';

// Redirect if user is not logged in
if (!isset($_SESSION['user_email'])) {
    header('Location: ../index.php');
    exit();
}

$payment_id = $_GET['payment_id'] ?? '';
$payment_method = $_REQUEST['payment_method'] ?? ($payment_id ? 'Online' : 'COD');
// Fetch user ID from user_form
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

// Fetch user address
$sql_address = "SELECT Address_ID, Address_Text FROM address WHERE user_id = ? LIMIT 1";
$stmt_address = $conn->prepare($sql_address);
$stmt_address->bind_param("i", $user_id);
$stmt_address->execute();
$result_address = $stmt_address->get_result();

if ($result_address->num_rows > 0) {
    $address_row = $result_address->fetch_assoc();
    $user_address_id = $address_row['Address_ID'];
    $user_address = $address_row['Address_Text'];
} else {
    echo "Error: Address not found.";
    exit();
}

// Fetch cart items
$subtotal = 0;
$sql_cart = "SELECT Product_ID, Price, Quantity FROM tblcart WHERE User_ID = ?";
$stmt_cart = $conn->prepare($sql_cart);
$stmt_cart->bind_param("i", $user_id);
$stmt_cart->execute();
$result_cart = $stmt_cart->get_result();

if ($result_cart->num_rows == 0) {
    echo "Error: Your cart is empty.";
    exit();
}

// Calculate subtotal
$cart_items = [];
while ($row = $result_cart->fetch_assoc()) {
    $cart_items[] = $row;
    $subtotal += $row['Price'] * $row['Quantity']; // ✅ Proper subtotal
}

// Delivery Fee Logic
$deliveryFee = ($subtotal >= 1000) ? 0 : 50;
$totalAmount = $subtotal + $deliveryFee;

// Insert into tblorder
$sql_order = "INSERT INTO tblorder (User_ID, TotalAmount, FinalAmount, CreatedON, Payment_ID, Payment_Method, Address_ID) 
              VALUES (?, ?, ?, NOW(), ?, ?, ?)";
$stmt_order = $conn->prepare($sql_order);
$stmt_order->bind_param("iisssi", $user_id, $subtotal, $totalAmount, $payment_id, $payment_method, $user_address_id);
$stmt_order->execute();

if ($stmt_order->affected_rows > 0) {
    $order_id = $stmt_order->insert_id;

    // Insert into order_details
    $stmt_details = $conn->prepare("INSERT INTO order_details (Order_ID, Product_ID, Quantity, Amount, Status) 
                                    VALUES (?, ?, ?, ?, ?)");
    $status = 'Pending';
    foreach ($cart_items as $cart_item) {
        $amount = $cart_item['Price'] * $cart_item['Quantity'];
        $stmt_details->bind_param("iiiss", $order_id, $cart_item['Product_ID'], $cart_item['Quantity'], $amount, $status);
        $stmt_details->execute();
    }

    // Admin Commission Logic
    $commission_percent = 10;
    $commission_data = [];

    foreach ($cart_items as $cart_item) {
        $product_id = $cart_item['Product_ID'];

        // Get Seller_ID
        $sql_seller = "SELECT Seller_ID FROM product WHERE Product_ID = ?";
        $stmt_seller = $conn->prepare($sql_seller);
        $stmt_seller->bind_param("i", $product_id);
        $stmt_seller->execute();
        $result_seller = $stmt_seller->get_result();

        if ($result_seller->num_rows > 0) {
            $row_seller = $result_seller->fetch_assoc();
            $seller_id = $row_seller['Seller_ID'];

            $amount = $cart_item['Price'] * $cart_item['Quantity']; // ✅ Proper amount
            $commission = ($amount * $commission_percent) / 100;

            if (!isset($commission_data[$seller_id])) {
                $commission_data[$seller_id] = 0;
            }
            $commission_data[$seller_id] += $commission;
        }
    }

    // Insert commission data
    $sql_commission = "INSERT INTO admin_commission (Order_ID, Seller_ID, Commission_Amount, Commission_Percentage) 
                       VALUES (?, ?, ?, ?)";
    $stmt_commission = $conn->prepare($sql_commission);

    foreach ($commission_data as $seller_id => $commission_amount) {
        $stmt_commission->bind_param("iidd", $order_id, $seller_id, $commission_amount, $commission_percent);
        $stmt_commission->execute();
    }

    // Clear the cart
    $sql_clear_cart = "DELETE FROM tblcart WHERE User_ID = ?";
    $stmt_clear_cart = $conn->prepare($sql_clear_cart);
    $stmt_clear_cart->bind_param("i", $user_id);
    $stmt_clear_cart->execute();

    // Redirect to thank you page
    header("Location: thankyou.php?order_id=" . $order_id);
    exit();
} else {
    echo "Error: Order not placed.";
}

?>

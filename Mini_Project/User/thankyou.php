<?php
session_start();
require_once 'connection.php';

// Redirect if user is not logged in
if (!isset($_SESSION['user_email'])) {
    header('Location: ../index.php');
    exit();
}
// Check if order ID is provided
if (!isset($_GET['order_id'])) {
    echo "Invalid access.";
    exit();
}

$order_id = $_GET['order_id'];

// Fetch order info
$sql_order = "SELECT o.*, a.Address_Text, u.name, u.email 
              FROM tblorder o
              JOIN address a ON o.Address_ID = a.Address_ID
              JOIN user_form u ON o.User_ID = u.user_id
              WHERE o.Order_ID = ?";
$stmt_order = $conn->prepare($sql_order);
$stmt_order->bind_param("i", $order_id);
$stmt_order->execute();
$result_order = $stmt_order->get_result();

if ($result_order->num_rows == 0) {
    echo "Order not found.";
    exit();
}

$order = $result_order->fetch_assoc();

// Fetch order items
$sql_items = "SELECT 
    od.Product_ID,
    p.Product_Name,
    p.Price,                  -- unit price
    od.Quantity,
    (p.Price * od.Quantity) AS Amount
FROM order_details od
JOIN product p ON od.Product_ID = p.Product_ID
WHERE od.Order_ID = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$result_items = $stmt_items->get_result();
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
		<title>Furni Free Bootstrap 5 Template for Furniture and Interior Design Websites by Untree.co </title>
		<style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            font-size: 16px;
        }
        .table th, .table td {
            vertical-align: middle !important;
        }
    </style>
	</head>

	<body class="bg-light">

		<?php
			include_once 'header.php';
		?>

		<!-- Start Hero Section -->
			<div class="hero">
				<div class="container">
					<div class="row justify-content-between">
						<div class="col-lg-5">
							<div class="intro-excerpt">
								<h1>Bill</h1>
							</div>
						</div>
						<div class="col-lg-7">
							
						</div>
					</div>
				</div>
			</div>
		<!-- End Hero Section -->

<div class="invoice-box">
    <h2 class="text-center text-success">Order Confirmation</h2>
    <hr>
    <div class="mb-3">
        <strong>Order ID:</strong> #<?= $order['Order_ID'] ?><br>
        <strong>Order Date:</strong> <?= date("d-m-Y H:i:s", strtotime($order['CreatedON'])) ?><br>
        <strong>Name:</strong> <?= htmlspecialchars($order['name']) ?><br>
        <strong>Email:</strong> <?= htmlspecialchars($order['email']) ?><br>
        <strong>Delivery Address:</strong> <?= htmlspecialchars($order['Address_Text']) ?>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr class="bg-light">
                <th>Product</th>
                <th>Amount (₹)</th>
                <th>Qty</th>
                <th>Total (₹)</th>
            </tr>
        </thead>
        <tbody>
        <?php
$subtotal = 0;
while ($item = $result_items->fetch_assoc()):
    $total = $item['Price'] * $item['Quantity'];
    $subtotal += $total;
?>
    <tr>
        <td><?= htmlspecialchars($item['Product_Name']) ?></td>
        <td>₹<?= number_format($item['Price'], 2) ?></td> <!-- ✅ Product Price -->
        <td><?= $item['Quantity'] ?></td>
        <td>₹<?= number_format($total, 2) ?></td>         <!-- Total = Price × Quantity -->
    </tr>
<?php endwhile; ?>


            <tr>
                <td colspan="3" class="text-end"><strong>Subtotal</strong></td>
                <td>₹<?= number_format($order['TotalAmount'], 2) ?></td>
            </tr>
            <tr>
                <td colspan="3" class="text-end"><strong>Delivery Fee</strong></td>
                <td>₹<?= number_format($order['FinalAmount'] - $order['TotalAmount'], 2) ?></td>
            </tr>
            <tr>
                <td colspan="3" class="text-end"><strong>Grand Total</strong></td>
                <td><strong>₹<?= number_format($order['TotalAmount'], 2) ?></strong></td>
            </tr>
        </tbody>
    </table>

    <div class="text-center mt-4">
        <button onclick="window.print()" class="btn btn-primary">Print Bill</button>
        <a href="../User/user.php" class="btn btn-secondary">Back to Home</a>
    </div>
</div>
<script src="user-js/bootstrap.bundle.min.js"></script>
		<script src="user-js/tiny-slider.js"></script>
		<script src="user-js/custom.js"></script>
</body>
</html>
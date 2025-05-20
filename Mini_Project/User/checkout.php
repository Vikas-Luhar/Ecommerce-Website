<?php
session_start();
require_once 'connection.php';

// Redirect if user is not logged in
if (!isset($_SESSION['user_email'])) {
    header('Location: ../index.php');
    exit();
}
// Fetch user's address details from the address table
$sql_address = "SELECT a.Address_Text, a.State_ID, a.City_ID
                FROM address a 
                JOIN user_form u ON a.user_id = u.user_id
                WHERE u.email = ?";
$stmt_address = $conn->prepare($sql_address);
$stmt_address->bind_param("s", $_SESSION['user_email']);
$stmt_address->execute();
$result_address = $stmt_address->get_result();

// Fetch address data if available
if ($result_address->num_rows > 0) {
    $address_row = $result_address->fetch_assoc();
    $address_text = $address_row['Address_Text'];
    $state_id = $address_row['State_ID'];
    $city_id = $address_row['City_ID'];
} else {
    $address_text = '';
    $state_id = '';
    $city_id = '';
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

// Initialize subtotal
$subtotal = 0; 

// Fetch cart items
$sql_cart = "SELECT c.Product_ID, p.Product_Name, c.Quantity, c.Price 
             FROM tblcart c 
             JOIN product p ON c.Product_ID = p.Product_ID 
             WHERE c.User_ID = ?";
$stmt_cart = $conn->prepare($sql_cart);
$stmt_cart->bind_param("i", $user_id);
$stmt_cart->execute();
$result_cart = $stmt_cart->get_result();

$cartItems = [];

while ($row = $result_cart->fetch_assoc()) {
    $cartItems[] = $row;
    $subtotal += $row['Price'] * $row['Quantity']; // Calculate subtotal
}

// Default delivery fee
$deliveryFee = 50;

// Free delivery if subtotal is ₹1000 or more
if ($subtotal >= 1000) {
    $deliveryFee = 0;
}

// Calculate total amount
$totalAmount = $subtotal + $deliveryFee;

?>

<!-- /*
* Bootstrap 5
* Template Name: Furni
* Template Author: Untree.co
* Template URI: https://untree.co/
* License: https://creativecommons.org/licenses/by/3.0/
*/ -->
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
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
	</head>

	<body>

		<?php
			include_once 'header.php';
		?>
<form action="process_order.php" method="POST">
		<div class="untree_co-section">
		    <div class="container">
		      
		          <div class="row mb-5">
		            <div class="col-md-12">
		              <h2 class="h3 mb-3 text-black">Your Order</h2>
		              <div class="p-3 p-lg-5 border bg-white">
		                
					  <table class="table site-block-order-table mb-5">
    <thead>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody id="order-summary">
    <?php if (!empty($cartItems)): ?>
        <?php foreach ($cartItems as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['Product_Name']) ?></td>
                <td><?= $item['Quantity'] ?></td>
                <td>₹<?= number_format($item['Price'] * $item['Quantity'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
        
        <!-- Subtotal Row -->
        <tr>
            <td class="text-black font-weight-bold"><strong>Subtotal</strong></td>
            <td></td>
            <td class="text-black"><strong>₹<?= number_format($subtotal, 2) ?></strong></td>
        </tr>

        <!-- Delivery Fee Row -->
        <tr>
            <td class="text-black font-weight-bold"><strong>Delivery Fee</strong></td>
            <td></td>
            <td class="text-black">
                <strong>
                    <?= ($deliveryFee == 0) ? "<span class='text-success'>FREE</span>" : "₹" . number_format($deliveryFee, 2) ?>
                </strong>
            </td>
        </tr>

        <!-- Final Order Total Row -->
        <tr>
            <td class="text-black font-weight-bold"><strong>Order Total</strong></td>
            <td></td>
            <td class="text-black font-weight-bold"><strong>₹<?= number_format($totalAmount, 2) ?></strong></td>
        </tr>

    <?php else: ?>
        <tr>
            <td colspan="3" class="text-center text-danger">No items in cart.</td>
        </tr>
    <?php endif; ?>
</tbody>
</table>


    <!-- Hidden Address -->
    <input type="hidden" id="c_address" name="c_address" value="your_address_value_here">

    <!-- Hidden State / Country -->
    <input type="hidden" id="c_state_country" name="c_state_country" value="your_state_country_value_here">

    <!-- Hidden Postal / Zip -->
    <input type="hidden" id="c_postal_zip" name="c_postal_zip" value="your_postal_zip_value_here">

    <!-- Hidden Email Address -->
    <input type="hidden" id="c_email_address" name="c_email_address" value="your_email_value_here">

    <!-- Hidden Phone -->
    <input type="hidden" id="c_phone" name="c_phone" value="your_phone_value_here">

    <!-- Submit Button (Triggers Modal) -->
<div class="form-group">
  <button type="button" class="btn btn-black btn-lg py-3 btn-block" data-bs-toggle="modal" data-bs-target="#paymentModal">
    Place Order
  </button>
</div>

</form>

		              </div>
		            </div>
		          </div>

		        </div>
		      </div>
		      <!-- </form> -->
		    </div>
		  </div>
<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">Choose Payment Method</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body text-center">
        <button type="button" class="btn btn-outline-primary w-100 mb-3" onclick="payWithRazorpay()">Pay with Razorpay</button>
        <button type="button" class="btn btn-outline-dark w-100" onclick="payWithCOD()">Cash on Delivery (COD)</button>
      </div>

    </div>
  </div>
</div>

		<!-- Start Footer Section -->
		<footer class="footer-section">
			<div class="container relative">

				<div class="sofa-img">
					<img src="images/Marble 11.png" alt="Image" class="img-fluid">
				</div>

				<br>

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

					<div class="col-lg-8">
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
									<li><a href="#">Nordic Chair</a></li>
									<li><a href="#">Kruzo Aero</a></li>
									<li><a href="#">Ergonomic Chair</a></li>
								</ul>
							</div>
						</div>
					</div>

				</div>
 <script>
function payWithRazorpay() {
    var rzp = new Razorpay({
        "key": "rzp_test_TlN9HRObSWAfBy", // Replace with your Razorpay key
        "amount": <?= $totalAmount * 100 ?>, // in paise
        "currency": "INR",
        "name": "OIS Store",
        "description": "Test Transaction",
        "image": "images/Marble 11.png",
        "handler": function (response) {
            // Redirect to order processing with Razorpay payment ID
            window.location.href = "process_order.php?payment_id=" + response.razorpay_payment_id + "&method=razorpay";
        },
        "prefill": {
            "name": "<?= $_SESSION['user_name'] ?? 'Customer' ?>",
            "email": "<?= $_SESSION['user_email'] ?>",
        },
        "theme": {
            "color": "#0d6efd"
        }
    });
    rzp.open();
}

function payWithCOD() {
    // Directly place order using COD
    window.location.href = "process_order.php?method=cod";
}
</script>




		<script src="user-js/bootstrap.bundle.min.js"></script>
		<script src="user-js/tiny-slider.js"></script>
		<script src="user-js/custom.js"></script>
	</body>

</html>

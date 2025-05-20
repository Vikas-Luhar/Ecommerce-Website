<?php
// Include the database connection file
require_once 'connection.php'; // Make sure this path is correct

// Start session
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_email'])) { // Change user_name to user_email
  header('Location: ../index.php'); // Redirect to login if not authenticated
  exit();
}

// Fetch order history for the logged-in user using email
$user_email = $_SESSION['user_email']; // Use email from session

// Join order_details and tblorder based on Order_ID
$sql = "
    SELECT 
        o.Order_ID, 
        od.Product_ID, 
        od.Quantity, 
        od.Amount AS Price, 
        (od.Amount * od.Quantity) AS Subtotal,
        o.TotalAmount, 
        o.FinalAmount, 
        o.Payment_Method,      -- ✅ Added this line
        o.CreatedON, 
        p.product_name, 
        a.Address_Text, 
        od.Status
    FROM tblorder o
    JOIN order_details od ON o.Order_ID = od.Order_ID
    JOIN product p ON od.Product_ID = p.Product_ID
    JOIN address a ON o.Address_ID = a.Address_ID
    WHERE o.User_ID = (
        SELECT user_id FROM user_form WHERE email = '$user_email'
    )
    ORDER BY o.CreatedON DESC, o.Order_ID DESC
";
$result = mysqli_query($conn, $sql); // Execute the query
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="shortcut icon" href="box 1.png">
  <link href="User-css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="User-css/style.css" rel="stylesheet">
  <title>Order History - Marble & Tiles Online Depot</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

</head>

<body>
  <?php include_once 'header.php'; ?>

  <div class="container mt-5">
  <h2 class="mb-4 text-center">🛒 Your Order History</h2>
  <table class="table table-bordered text-center align-middle shadow-sm">
    <thead class="table-dark">
      <tr>
        <th>📦 Product</th>
        <th>🏠 Address</th>
        <th>💳 Payment</th>
        <th>🔢 Qty</th>
        <th>💰 Total</th>
        <th>📅 Date</th>
        <th>🚚 Status</th>
        <th>📝 Feedback</th>
        <th>🧾 Bill</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['product_name'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($row['Address_Text'] ?? 'N/A'); ?></td>
            <td><?php echo $row['Payment_Method'] ?? 'COD'; ?></td>
            <td><?php echo $row['Quantity'] ?? 'N/A'; ?></td>
            <td><?php echo isset($row['FinalAmount']) ? '₹' . number_format($row['FinalAmount']) : 'N/A'; ?></td>
            <td><?php echo $row['CreatedON'] ?? 'N/A'; ?></td>
            <td>
              <?php 
              $status = $row['Status'] ?? 'Pending';
              if ($status == 'Pending') {
                echo '<span class="badge bg-warning"><i class="fas fa-clock"></i> Pending</span>';
              } elseif ($status == 'Shipped') {
                echo '<span class="badge bg-primary"><i class="fas fa-truck"></i> Shipped</span>';
              } elseif ($status == 'Delivered') {
                echo '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Delivered</span>';
              } elseif ($status == 'Cancelled') {
                echo '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> Cancelled</span>';
              } else {
                echo '<span class="badge bg-secondary"><i class="fas fa-question-circle"></i> Unknown</span>';
              }
              ?>
            </td>
            <td>
              <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#feedbackModal"
                data-order-id="<?php echo $row['Order_ID']; ?>"
                data-product-name="<?php echo htmlspecialchars($row['product_name']); ?>">
                <i class="fas fa-comment-dots"></i> Give Feedback
              </button>
            </td>
            <td>
              <a href="generate_bill.php?order_id=<?php echo $row['Order_ID']; ?>" class="btn btn-sm btn-outline-success">
                <i class="fas fa-file-invoice"></i> Download
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="9">No orders found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="submit_feedback.php" method="POST">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="feedbackModalLabel">Rate this Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <input type="hidden" name="order_id" id="order_id">
          <input type="hidden" name="product_name" id="product_name">
          
          <!-- Star Rating -->
          <div class="mb-3 text-center">
            <input type="hidden" name="rating" id="rating">
            <div id="star-container">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <i class="fas fa-star star-icon" data-value="<?php echo $i; ?>" style="font-size: 24px; color: #ccc; cursor: pointer;"></i>
              <?php endfor; ?>
            </div>
          </div>

          <!-- Review Text -->
          <div class="mb-3">
            <label for="review" class="form-label">Write Feedback</label>
            <textarea name="review" id="review" rows="3" class="form-control" required></textarea>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Submit</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>


  </div>
</div>

  <script src="user-js/bootstrap.bundle.min.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('.star-icon');
    const ratingInput = document.getElementById('rating');
    
    stars.forEach((star, index) => {
      star.addEventListener('click', () => {
        const rating = star.getAttribute('data-value');
        ratingInput.value = rating;

        stars.forEach(s => s.style.color = '#ccc'); // reset all
        for (let i = 0; i < rating; i++) {
          stars[i].style.color = '#ffc107'; // highlight
        }
      });
    });

    // Set order/product on modal open
    const feedbackModal = document.getElementById('feedbackModal');
    feedbackModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      document.getElementById('order_id').value = button.getAttribute('data-order-id');
      document.getElementById('product_name').value = button.getAttribute('data-product-name');

      // Reset stars & review
      stars.forEach(s => s.style.color = '#ccc');
      document.getElementById('review').value = '';
      ratingInput.value = '';
    });
  });
</script>



</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>

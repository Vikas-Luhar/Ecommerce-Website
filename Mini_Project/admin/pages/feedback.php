<?php
include_once "../config/dbconnect.php"; // Ensure the database connection is included
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
  admin Panel - Dashboard
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  <!-- Bootstrap & Font Awesome -->
  <!-- <link href="../assets/css/bootstrap.min.css" rel="stylesheet"> -->
  <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"> -->
  <!-- <link href="../assets/css/style.css" rel="stylesheet"> -->
</head>
<style>
 body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      padding: 20px;
    }
    .feedback-container {
      max-width: 800px;
      margin: auto;
    }
    .feedback-card {
      border: 1px solid #ddd;
      background: #fff;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 10px;
      box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
    }
    .feedback-card h3 {
      margin: 0;
      color: #333;
    }
    .rating {
      color: gold;
      font-size: 16px;
    }
    .feedback-date {
      font-size: 12px;
      color: #777;
    }
</style>

<body class="g-sidenav-show  bg-gray-100">
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="dashboard.php">
        <!-- <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">  -->
        <i class="fa-solid text-xxl fa-user-tie"></i>
        <span class="ms-1 text-xxl fw-bold text-dark">Admin page</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active bg-gradient-dark text-white" href="../pages/dashboard.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/view-details.php">
            <i class="fa-solid fa-store"></i>
            <span class="nav-link-text ms-1">Seller Details</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/order.php">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Orders</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/userdetail.php">
            <i class="fa-solid fa-users"></i>
            <span class="nav-link-text ms-1">User Details</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/category.php">
            <i class="material-symbols-rounded opacity-5">view_in_ar</i>
            <span class="nav-link-text ms-1">Category</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/feedback.php">
            <i class="material-symbols-rounded opacity-5">view_in_ar</i>
            <span class="nav-link-text ms-1">User Feedback</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/admin_commission.php">
            <i class="material-symbols-rounded opacity-5">view_in_ar</i>
            <span class="nav-link-text ms-1">Earning</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Account pages</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/profile.php">
            <i class="material-symbols-rounded opacity-5">person</i>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/sign-in.php">
            <i class="material-symbols-rounded opacity-5">login</i>
            <span class="nav-link-text ms-1">Log Out</span>
          </a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/sign-up.html">
            <i class="material-symbols-rounded opacity-5">assignment</i>
            <span class="nav-link-text ms-1">Sign Up</span>
          </a>
        </li>
      </ul>
    </div> -->
    <!-- <div class="sidenav-footer position-absolute w-100 bottom-0 ">
      <div class="mx-3">
        <a class="btn btn-outline-dark mt-4 w-100" href="https://www.creative-tim.com/learning-lab/bootstrap/overview/material-dashboard?ref=sidebarfree" type="button">Documentation</a>
        <a class="btn bg-gradient-dark w-100" href="https://www.creative-tim.com/product/material-dashboard-pro?ref=sidebarfree" type="button">Upgrade to pro</a>
      </div>
    </div> -->
</aside>
<div class="feedback-container">
  <h2 class="mb-4 fw-bold">
    <i class="fas fa-comments text-primary me-2"></i>Seller Feedbacks
  </h2>
  <div class="d-flex align-items-center gap-3 mb-4">
  <!-- Icon label -->
  <div class="d-flex align-items-center text-dark">
    <span class="material-symbols-rounded me-1">tune</span>
    <strong class="me-2">Filter:</strong>
  </div>

  <!-- Filter dropdown -->
  <div class="input-group shadow-sm" style="max-width: 220px;">
    <span class="input-group-text bg-white rounded-start">
      <!-- <span class="material-symbols-rounded text-dark">star_rate</span> -->
    </span>
    <select id="ratingFilter" class="form-select border-start-0 rounded-end">
      <option value="">All Ratings</option>
      <option value="5">⭐⭐⭐⭐⭐</option>
      <option value="4">⭐⭐⭐⭐</option>
      <option value="3">⭐⭐⭐</option>
      <option value="2">⭐⭐</option>
      <option value="1">⭐</option>
    </select>
  </div>
</div>

  
  <?php
  $sql = "SELECT 
  f.customer_name,
  f.review,
  f.rating,
  f.feedback_date,
  f.product_name,
  s.seller_name
FROM feedbacks f
JOIN product p ON f.product_name = p.product_name
JOIN seller s ON p.Seller_ID = s.seller_id
ORDER BY f.feedback_date DESC";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<div class='feedback-card' data-rating='{$row['rating']}'>
      <h5 class='mb-2 text-dark'>
        <i class='fas fa-user me-1 text-secondary'></i>{$row['customer_name']} 
        <span class='float-end badge bg-gradient-primary'>{$row['rating']} <i class='fas fa-star text-warning'></i></span>
      </h5>

      <p class='mb-1'><i class='fas fa-box text-success me-1'></i><strong>Product:</strong> {$row['product_name']}</p>

      <p class='mb-1'><i class='fas fa-store me-1 text-info'></i><strong>Seller:</strong> {$row['seller_name']}</p>

      <div class='bg-light p-2 rounded'>
        <i class='fas fa-quote-left text-muted me-1'></i><em>{$row['review']}</em>
      </div>

      <p class='feedback-date mt-2 text-muted'>
        <i class='fas fa-calendar-alt me-1'></i>Reviewed on: " . date("M d, Y", strtotime($row['feedback_date'])) . "
      </p>
    </div>";

    }
} else {
    echo "<div class='alert alert-info shadow-sm'><i class='fas fa-info-circle me-1'></i>No feedback available.</div>";
}

?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $('#ratingFilter').on('change', function () {
    var selected = $(this).val();
    $('.feedback-card').each(function () {
      var rating = $(this).data('rating').toString();
      if (selected === '' || rating === selected) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
  });
</script>

</div>

</body>

</html>
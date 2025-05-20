<?php
// Start session
session_start();
include_once "../config/dbconnect.php";

// Check if seller is logged in
if (!isset($_SESSION['seller_id'])) {
    header('Location: ../index.php');
    exit();
}

$seller_id = $_SESSION['seller_id'];

// Default WHERE clause
$ratingFilter = "";

// Apply rating filter based on dropdown
if (isset($_GET['sort_rating'])) {
    if ($_GET['sort_rating'] == 'high') {
        $ratingFilter = "AND f.rating >= 4";
    } elseif ($_GET['sort_rating'] == 'low') {
        $ratingFilter = "AND f.rating <= 3";
    }
}

// Final query
$sql = "
    SELECT f.product_name, f.rating, f.review, f.feedback_date, u.name AS customer_name
    FROM feedbacks f
    JOIN product p ON f.product_name = p.product_name
    JOIN user_form u ON f.email_id = u.email
    WHERE p.seller_id = '$seller_id' $ratingFilter
    ORDER BY f.feedback_date DESC
";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Seller Panel - Dashboard
  </title>
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  <style>
  body {
  overflow-x: hidden;
}

.card {
  overflow: hidden;
}

html, body {
  max-width: 100%;
  box-sizing: border-box;
}
  select.form-select option {
    padding-left: 10px;
  }
</style>

</head>

<body class="g-sidenav-show  bg-gray-100">
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="./dashboard.php" >
        <!-- <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo"> -->
        <i class="fa-solid fa-shop"></i>
        <span class="ms-1 text-sm text-dark">Seller Page</span>
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
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">View Details</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/order.php">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Orders</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/feedback.php">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">FeedBack</span>
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
    <a class="nav-link text-dark" href="../pages/logout.php">
        <i class="material-symbols-rounded opacity-5">logout</i>
        <span class="nav-link-text ms-1">Log Out</span>
    </a>
</li>
        
      </ul>
    </div>
   
</aside>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
<div class="container-fluid py-4">
  <h4 class="mb-4 text-dark fw-bold d-flex align-items-center">
    <i class="fas fa-star-half-alt text-warning me-2 fs-5"></i>
    Product Reviews
  </h4>
  <form method="GET" class="mb-4 d-flex align-items-center">
  <label class="me-2 fw-semibold" for="sort_rating">
    <i class="fas fa-filter text-primary me-1"></i>Filter by Rating:
  </label>
  <select name="sort_rating" id="sort_rating" class="form-select w-auto px-3 py-2 rounded-3 shadow-sm border border-secondary-subtle" onchange="this.form.submit()" style="min-width: 180px;">
    <option value="">⭐ All Ratings</option>
    <option value="high" <?php if (isset($_GET['sort_rating']) && $_GET['sort_rating'] == 'high') echo 'selected'; ?>>
      ⭐ High (4-5)
    </option>
    <option value="low" <?php if (isset($_GET['sort_rating']) && $_GET['sort_rating'] == 'low') echo 'selected'; ?>>
      ★ Low (1-3)
    </option>
  </select>
</form>


  <div class="row g-4">
    <?php if (mysqli_num_rows($result) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card shadow border-0 h-100 rounded-4">
            <div class="card-body d-flex flex-column justify-content-between">
              <!-- Product Name -->
              <h6 class="text-dark fw-bold mb-3 d-flex align-items-center">
                <i class="fa-solid fa-box-open text-success me-2 fs-6"></i>
                <?php echo htmlspecialchars($row['product_name']); ?>
              </h6>

              <!-- Rating -->
              <div class="mb-3">
                <?php for ($i = 0; $i < $row['rating']; $i++): ?>
                  <i class="fas fa-star text-warning"></i>
                <?php endfor; ?>
                <?php for ($i = $row['rating']; $i < 5; $i++): ?>
                  <i class="far fa-star text-warning"></i>
                <?php endfor; ?>
                <span class="ms-2 badge bg-gradient-primary text-white shadow-sm"><?php echo $row['rating']; ?>/5</span>
              </div>

              <!-- Review Text -->
              <div class="bg-gray-100 p-3 rounded text-dark mb-3 position-relative">
                <i class="fas fa-quote-left text-muted position-absolute top-0 start-0 mt-2 ms-2 fs-6 opacity-50"></i>
                <p class="mb-0 ms-3"><?php echo htmlspecialchars($row['review']); ?></p>
              </div>

              <!-- Reviewer Info -->
              <p class="text-xs text-muted mt-auto d-flex justify-content-between align-items-center">
                <span>
                  <i class="fas fa-user-circle me-1 text-primary"></i>
                  <strong><?php echo htmlspecialchars($row['customer_name']); ?></strong>
                </span>
                <span>
                  <i class="fas fa-calendar-alt me-1 text-muted"></i>
                  <?php echo date("M d, Y", strtotime($row['feedback_date'])); ?>
                </span>
              </p>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-info text-center shadow-sm">
          <i class="fas fa-info-circle me-2"></i>No reviews found for your products.
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>


    </main>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
</body>

</html>
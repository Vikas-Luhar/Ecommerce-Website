<?php
include_once "../config/dbconnect.php";

$search = isset($_GET['search']) ? $_GET['search'] : "";

$query = "SELECT 
            ac.ID, 
            ac.Order_ID, 
            COALESCE(s.Seller_Name, 'Admin') AS Seller_Name, 
            ac.Commission_Percentage, 
            ac.Commission_Amount, 
            o.CreatedON 
          FROM admin_commission ac
          LEFT JOIN seller s ON ac.Seller_ID = s.Seller_ID
          JOIN tblorder o ON ac.Order_ID = o.Order_ID";


if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query .= " WHERE s.Seller_Name LIKE '%$search%' OR ac.Order_ID LIKE '%$search%'";
}

$query .= " ORDER BY o.CreatedON DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Admin Panel - Earnings</title>
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
        .search-bar {
        color:white;
        background-color: white;
            max-width: 300px;
        }
        .table-container {
            overflow-x: auto;
        }
    </style>
</head>

<body class="g-sidenav-show bg-gray-100">
    <!-- Sidebar -->
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
          <a class="nav-link text-dark" href="../pages/logout.php">
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

    <!-- Main Content -->
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0"><i class="fas fa-money-bill-wave"></i> Admin Commission Report</h4>
                            <form method="GET" id="searchForm" class="d-flex search-bar">
    <input type="text" name="search" id="searchInput" class="form-control me-2" placeholder="Search Order ID or Seller">
    <button type="submit" class="btn btn-light"><i class="fas fa-search"></i></button>
</form>
                        </div>
                        <div class="card-body">
                            <div class="table-container">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead class="table-dark text-center">
                                        <tr>
                                            <th>ID</th>
                                            <th>Order ID</th>
                                            <th>Seller Name</th>
                                            <th>Commission (%)</th>
                                            <th>Commission Amount (₹)</th>
                                            <th>Order Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <?php if ($result->num_rows > 0): ?>
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?= $row['ID'] ?></td>
                                                    <td><span class="badge bg-info text-white"><?= $row['Order_ID'] ?></span></td>
                                                    <td><?= $row['Seller_Name'] ?></td>
                                                    <td><span class="badge bg-warning text-dark"><?= $row['Commission_Percentage'] ?>%</span></td>
                                                    <td><span class="fw-bold text-success">₹<?= number_format($row['Commission_Amount'], 2) ?></span></td>
                                                    <td><?= date('d M Y', strtotime($row['CreatedON'])) ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="6" class="text-center text-danger">No Commission Records Found</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#searchForm').on('submit', function(e) {
        e.preventDefault(); // Stop form from submitting
        let searchValue = $('#searchInput').val();

        $.ajax({
            url: './fetch_commission_data.php',
            type: 'GET',
            data: { search: searchValue },
            success: function(response) {
                $('tbody').html(response); // Replace table body
            }
        });
    });

    // Optional: Trigger search on keyup
    $('#searchInput').on('keyup', function() {
        $('#searchForm').submit();
    });
</script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

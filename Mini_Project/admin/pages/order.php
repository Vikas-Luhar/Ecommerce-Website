<!--
=========================================================
* Material Dashboard 3 - v3.2.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<?php
include_once "../config/dbconnect.php";  // Ensure this file exists
$search = isset($_GET['search']) ? $_GET['search'] : "";
$filter = isset($_GET['filter']) ? $_GET['filter'] : "";
$query = "SELECT 
    o.Order_ID, 
    u.name AS User_Name, 
    s.Seller_Name, 
    p.Product_Name AS Order_Name, 
    od.Quantity AS Seller_Order_Product, 
    od.Amount AS Product_Price,
    o.FinalAmount,
    o.CreatedON,
    od.Status AS Delivery_Status,
    a.Address_Text AS User_Address
FROM tblorder o
JOIN user_form u ON o.User_ID = u.user_id
JOIN order_details od ON o.Order_ID = od.Order_ID
JOIN product p ON od.product_id = p.Product_ID
JOIN seller s ON p.Seller_ID = s.Seller_ID
JOIN address a ON u.user_id = a.user_id
WHERE a.IsActive = 1";

if ($search) {
    $query .= " AND (u.name LIKE '%$search%' OR p.Product_Name LIKE '%$search%')";
}

if ($filter) {
    $query .= " AND od.Status = '$filter'";
}

$query .= " ORDER BY o.CreatedON DESC";
$result = $conn->query($query);
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  
</head>

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
        <!-- <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/notifications.html">
            <i class="material-symbols-rounded opacity-5">notifications</i>
            <span class="nav-link-text ms-1"></span>
          </a>
        </li> -->
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
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
  <div class="container mt-5">
    <div class="card shadow">
     <!-- Search & Filter Section -->
     <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search by User or Product">
                    </div>
                    <div class="col-md-8">
                        <button class="btn btn-secondary filter-btn" data-filter="">All</button>
                        <button class="btn btn-warning filter-btn" data-filter="Pending">Pending</button>
                        <button class="btn btn-success filter-btn" data-filter="Shipped">Shipped</button>
                        <button class="btn btn-danger filter-btn" data-filter="Cancelled">Cancelled</button>
                    </div>
                </div>

                <!-- Orders Table -->
                <div id="ordersTable">
                    <!-- AJAX will load orders here -->
                </div>
            </div>
        </div>
    </div>
      
      
      </div>
    </div>
  </div>
    </div>
  <!--   Core JS Files   -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    $(document).ready(function () {
    // Handle status change dropdown
    $(document).on("change", ".status-dropdown", function () {
        let orderId = $(this).data("order-id");
        let newStatus = $(this).val();

        $.ajax({
            url: "update_order_status.php",
            type: "POST",
            data: {
                orderId: orderId,
                newStatus: newStatus
            },
            success: function (response) {
                alert(response); // Optional: you can replace with a toast message
                // Reload orders table
                fetchOrders(); // Call your fetchOrders function to reload updated data
            },
            error: function () {
                alert("Something went wrong while updating the order status.");
            }
        });
    });
});
    $(document).ready(function() {
            function fetchOrders(search = "", filter = "") {
                $.ajax({
                    url: "fetch_orders.php",
                    type: "GET",
                    data: { search: search, filter: filter },
                    success: function(response) {
                        $("#ordersTable").html(response);
                    }
                });
            }

            // Load all orders on page load
            fetchOrders();

            // Search orders
            $("#searchInput").on("keyup", function() {
                let searchValue = $(this).val();
                fetchOrders(searchValue);
            });

            // Filter orders
            $(".filter-btn").click(function() {
                let filterValue = $(this).data("filter");
                fetchOrders("", filterValue);
            });
        });
  </script>
  
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
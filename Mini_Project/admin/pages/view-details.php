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
session_start();
include_once "../config/dbconnect.php";

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: sign-in.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

$sql = "SELECT Admin_Name FROM admin WHERE Admin_ID = '$admin_id'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $admin_name = $row['Admin_Name']; // Make sure column name matches exactly
} else {
    echo "Admin not found.";
}
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
  <!-- <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" /> -->
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  <!-- Bootstrap & Font Awesome -->
  <!-- <link href="../assets/css/bootstrap.min.css" rel="stylesheet"> -->
  <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"> -->
  <!-- <link href="../assets/css/style.css" rel="stylesheet"> -->
</head>
<style>
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #f0f2f5;
  }

  h3 {
    font-weight: 600;
    color: #343a40;
  }

  .container {
    padding: 40px 0;
  }

  .table {
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    overflow: hidden;
  }

  .table th {
    background-color: #343a40;
    color: white;
    font-weight: 500;
  }

  .table td,
  .table th {
    text-align: center;
    vertical-align: middle;
    padding: 12px 10px;
  }

  .btn-info {
    background: #00bcd4;
    color: #fff;
    font-weight: 500;
    border: none;
    border-radius: 6px;
    transition: background 0.3s ease;
  }

  .btn-info:hover {
    background: #0097a7;
  }

  .toggle-status {
    min-width: 110px;
    font-weight: 500;
    border: none;
    border-radius: 6px;
    transition: all 0.3s ease-in-out;
  }

  .btn-success {
    background-color: #28a745 !important;
  }

  .btn-danger {
    background-color: #dc3545 !important;
  }

  .btn-success:hover {
    background-color: #218838 !important;
  }

  .btn-danger:hover {
    background-color: #c82333 !important;
  }

  /* Search Bar */
  #searchInput {
    border-radius: 10px;
    border: 1px solid #ced4da;
    transition: 0.4s ease;
    padding: 10px 15px;
    width: 260px;
  }

  #searchInput:focus {
    outline: none;
    box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
    border-color: #3498db;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .btn-custom,
    .btn-cancel,
    .toggle-status {
      width: 100%;
      margin-bottom: 10px;
    }

    #searchInput {
      width: 100%;
      margin-top: 10px;
    }
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
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Sellers</li>
          </ol>
        </nav>


        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group input-group-outline">
              <label class="form-label"></label>
              <input type="text" id="searchInput" class="form-control" placeholder="Search sellers...">
            </div>
        </div>

        <ul class="navbar-nav d-flex align-items-center  justify-content-end">
          
        </ul>
      </div>
      </div>
    </nav>
    <div>
      <!-- Main Content -->
      <div class="container">
        <h3 class="mb-4">SELLER DETAILS</h3>
        <!-- <button type="button" class="btn btn-custom mb-3" data-toggle="modal" data-target="#myModal">
          <i class="fas fa-plus"></i> Add Product
        </button> -->



        <?php
        //  include "dbconnect.php";
        $sql = "SELECT Seller_ID, Seller_Name, Seller_Shop_Name,Email_ID, Seller_Mobile_No, IsActive, IsApproved 
        FROM seller ORDER BY Seller_ID ASC";
        $result = $conn->query($sql);
        $count = 1;
        ?>
<table class="table table-striped">
    <thead class="thead-dark">
        <tr>
            <th>S.N.</th>
            <th>Seller Name</th>
            <th>Shop Name</th>
            <th>View</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="searchResults">
        <?php
        $count = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$count}</td>
                    <td>{$row['Seller_Name']}</td>
                    <td>{$row['Seller_Shop_Name']}</td>
                    <td>
                        <a href='view_seller.php?id={$row['Seller_ID']}' class='btn btn-info'>View Details</a>
                    </td>
                    <td>
                        <button class='btn btn-" . ($row['IsActive'] ? 'danger' : 'success') . " toggle-status' 
                            data-id='{$row['Seller_ID']}' 
                            data-active='{$row['IsActive']}'
                            data-approved='{$row['IsApproved']}'
                            data-admin='{$_SESSION['admin_id']}'>
                            " . ($row['IsActive'] ? 'Deactivate' : 'Activate') . "
                        </button>
                    </td>
                  </tr>";
            $count++;
        }
        ?>
    </tbody>
</table>

      </div>
      </main>

<!-- jQuery and Bootstrap (Load only once) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom Script -->
<script>
$(document).ready(function () {
    // Toggle status on click
    $("body").on("click", ".toggle-status", function () {
        let button = $(this);
        let sellerId = button.data("id");
        let currentStatus = parseInt(button.data("active")); // 1 or 0

        $.ajax({
            url: "update_status.php", // Make sure this file contains the PHP with admin session handling
            type: "POST",
            data: {
                id: sellerId,
                status: currentStatus
            },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    let newStatus = response.newStatus;
                    let newText = newStatus === 1 ? "Deactivate" : "Activate";
                    let newClass = newStatus === 1 ? "btn-danger" : "btn-success";

                    // Update button text, class, and data
                    button.text(newText);
                    button.removeClass("btn-danger btn-success").addClass(newClass);
                    button.data("active", newStatus);
                    button.attr("data-active", newStatus); // Sync for DOM
                } else {
                    alert("Error: " + (response.message || "Unknown error"));
                }
            },
            error: function () {
                alert("AJAX request failed.");
            }
        });
    });

    // Live search
    $("#searchInput").on("keyup", function () {
        let query = $(this).val().trim();

        $.ajax({
            url: "search.php",
            method: "GET",
            data: { query: query },
            dataType: "json",
            success: function (response) {
                let output = "";

                if (response.status === "success" && response.data.length > 0) {
                    response.data.forEach((seller, index) => {
                        let buttonClass = seller.IsActive == 1 ? "btn-danger" : "btn-success";
                        let buttonText = seller.IsActive == 1 ? "Deactivate" : "Activate";

                        output += `<tr>
                            <td>${index + 1}</td>
                            <td>${seller.Seller_Name}</td>
                            <td>${seller.Seller_Shop_Name}</td>
                            <td>
                                <a href='view_seller.php?id=${seller.Seller_ID}' class='btn btn-info'>View Details</a>
                            </td>
                            <td>
                                <button class='btn ${buttonClass} toggle-status'
                                    data-id='${seller.Seller_ID}'
                                    data-active='${seller.IsActive}'>
                                    ${buttonText}
                                </button>
                            </td>
                        </tr>`;
                    });
                } else {
                    output = "<tr><td colspan='5' class='text-center text-danger'>No sellers found</td></tr>";
                }

                $("#searchResults").html(output);
            },
            error: function () {
                $("#searchResults").html("<tr><td colspan='5' class='text-center text-danger'>Error fetching data</td></tr>");
            }
        });
    });
});
</script>

</body>

</html>
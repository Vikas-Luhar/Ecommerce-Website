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
// Start session
session_start();
include_once "../config/dbconnect.php";

// Check if the seller is logged in
if (!isset($_SESSION['seller_id'])) {
    header("Location: sign-in.php"); // Redirect to login page if not logged in
    exit();
}
// Handle AJAX request for updating status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id']) && isset($_POST['status'])) {
  $order_id = $_POST['order_id'];
  $status = $_POST['status'];

  $query = "UPDATE order_details SET Status = ? WHERE Order_ID = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("si", $status, $order_id);

  if ($stmt->execute()) {
      echo json_encode(["success" => true, "message" => "Status updated successfully"]);
  } else {
      echo json_encode(["success" => false, "message" => "Database update failed"]);
  }
  exit();
}
// Get seller ID from session
$seller_id = $_SESSION['seller_id'];
$query = "SELECT 
    od.Order_ID,
    u.name AS user_name,
    p.product_name,
    od.Quantity,
    od.Amount AS item_price,
    (od.Quantity * od.Amount) AS total_price,
    od.Status,
    o.FinalAmount,  -- ✅ Add this line to get the final amount
    o.CreatedON,
    a.Address_Text
FROM order_details od
JOIN product p ON od.Product_ID = p.Product_ID
JOIN tblorder o ON od.Order_ID = o.Order_ID
JOIN user_form u ON o.User_ID = u.user_id
LEFT JOIN address a ON u.user_id = a.user_id
WHERE p.Seller_ID = '$seller_id'
ORDER BY o.CreatedON DESC";


$result = $conn->query($query);

// Debugging output
if (!$result) {
    die("Query Failed: " . $conn->error);
} else {
    // echo "Query executed successfully. Rows found: " . $result->num_rows;
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
    Seller Page
  </title>
  <style>
    html, body {
    overflow-x: hidden;
}
.table thead tr th {
    color: white !important;
}
.main-content {
    overflow-y: auto; /* Prevent unnecessary scrolling */
}

  </style>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
    <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand px-4 py-3 m-0" href="./dashboard.php">
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
        <!-- <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/rtl.html">
            <i class="material-symbols-rounded opacity-5">format_textdirection_r_to_l</i>
            <span class="nav-link-text ms-1"></span>
          </a>
        </li>
        <li class="nav-item">
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
    <a class="nav-link text-dark" href="../pages/logout.php">
        <i class="material-symbols-rounded opacity-5">logout</i>
        <span class="nav-link-text ms-1">Log Out</span>
    </a>
</li>
        <!-- <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/sign-up.html">
            <i class="material-symbols-rounded opacity-5">assignment</i>
            <span class="nav-link-text ms-1">Sign Up</span>
          </a>
        </li> -->
      </ul>
    </div>
    <!-- <div class="sidenav-footer position-absolute w-100 bottom-0 ">
      <div class="mx-3">
        <a class="btn btn-outline-dark mt-4 w-100" href="https://www.creative-tim.com/learning-lab/bootstrap/overview/material-dashboard?ref=sidebarfree" type="button">Documentation</a>
        <a class="btn bg-gradient-dark w-100" href="https://www.creative-tim.com/product/material-dashboard-pro?ref=sidebarfree" type="button">Upgrade to pro</a>
      </div>
    </div> -->
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
  <div class="container mt-5">
    <h2 class="mb-4 text-center">📦 Orders</h2>

    <div class="table-responsive" style="overflow: visible;">
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="thead-dark bg-primary text-white">
                <tr>
                    <th>📌 Order ID</th>
                    <th>👤 User Name</th>
                    <th>🛍 Product Name</th>
                    <th>🔢 Qty</th>
                    <th>💰 Total</th>
                    <th style="max-width: 150px;">📍 Address</th>
                    <th>📦 Status</th>
                    <th>🕒 Created On</th>
                </tr>
            </thead>
            <tbody class="table-light">
                <?php
                if ($result->num_rows == 0) {
                    echo "<tr><td colspan='8' class='text-center text-danger'>No orders found for this seller.</td></tr>";
                } else {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><strong># " . htmlspecialchars($row['Order_ID']) . "</strong></td>";
                        echo "<td>" . htmlspecialchars($row['user_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                        echo "<td class='text-center'>" . htmlspecialchars($row['Quantity']) . "</td>";
                        echo "<td class='text-success fw-bold'>₹" . htmlspecialchars($row['FinalAmount']) . "</td>";

                        // ✅ Address - text-truncate to avoid overflow
                        echo "<td class='text-wrap' style='max-width: 200px; white-space: normal; word-wrap: break-word;'>" 
                        . htmlspecialchars($row['Address_Text']) . 
                        "</td>";
                    

                        // ✅ Status Dropdown
                        $status = htmlspecialchars($row['Status']);
                        $statusBadge = "<span class='badge bg-secondary'>Unknown</span>";
                        if ($status == "Pending") {
                            $statusBadge = "<span class='badge bg-warning text-dark'>Pending</span>";
                        } elseif ($status == "Shipped") {
                            $statusBadge = "<span class='badge bg-info text-white'>Shipped</span>";
                        // } elseif ($status == "Delivered") {
                        //     $statusBadge = "<span class='badge bg-success text-white'>Delivered</span>";
                        } elseif ($status == "Cancelled") {
                            $statusBadge = "<span class='badge bg-danger text-white'>Cancelled</span>";
                        }

                        echo "<td>
                            <div class='dropdown'>
                                <button class='btn btn-sm btn-light border dropdown-toggle' type='button' id='statusDropdown{$row['Order_ID']}' data-bs-toggle='dropdown' aria-expanded='false'>
                                    $statusBadge
                                </button>
                                <ul class='dropdown-menu' aria-labelledby='statusDropdown{$row['Order_ID']}'>
                                    <li><a class='dropdown-item change-status' href='#' data-id='{$row['Order_ID']}' data-status='Pending'>🟡 Pending</a></li>
                                    <li><a class='dropdown-item change-status' href='#' data-id='{$row['Order_ID']}' data-status='Shipped'>🚚 Shipped</a></li>
                                    <li><a class='dropdown-item change-status' href='#' data-id='{$row['Order_ID']}' data-status='Cancelled'>❌ Cancelled</a></li>
                                    </ul>
                                    </div>
                                    </td>";
                                    
                                    echo "<td><span class='text-muted'>" . htmlspecialchars($row['CreatedON']) . "</span></td>";
                                    echo "</tr>";
                                  }
                                }
                                ?>
                                <!-- <li><a class='dropdown-item change-status' href='#' data-id='{$row['Order_ID']}' data-status='Delivered'>✅ Delivered</a></li> -->
            </tbody>
        </table>
    </div>
</div>

    <!--   Core JS Files   -->
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <!-- <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function(){
    $(".change-status").click(function(e){
        e.preventDefault();
        var orderID = $(this).data("id");
        var newStatus = $(this).data("status");
        var button = $("#statusDropdown" + orderID);

        $.ajax({
            url: "../pages/update_order_status.php", // Ensure correct URL
            type: "POST",
            data: { order_id: orderID, status: newStatus },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    let statusClass = "bg-secondary"; // Default unknown status
                    if (newStatus === "Pending") statusClass = "bg-warning text-dark";
                    else if (newStatus === "Shipped") statusClass = "bg-info text-white";
                    else if (newStatus === "Cancelled") statusClass = "bg-danger text-white";
                    
                    // ✅ Update the button with proper badge styling
                    button.html(`<span class="badge ${statusClass}">${newStatus}</span>`);
                  } else {
                    alert("Error: " + response.message);
                  }
                },
                error: function(xhr, status, error) {
                  alert("AJAX Error: " + error);
                }
              });
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
  <!-- else if (newStatus === "Delivered") statusClass = "bg-success text-white"; -->
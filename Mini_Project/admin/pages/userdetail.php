<?php
include_once "../config/dbconnect.php";

// Fetch user details
$sql = "SELECT * FROM user_form ORDER BY user_id ASC"; // Ensure the correct table name
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching users: " . $conn->error);
}
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];

  $query = "SELECT profile_image FROM user_form WHERE user_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $stmt->bind_result($img);
  if ($stmt->fetch() && $img) {
      $profile_image = "./images/" . $img;
  }
  $stmt->close();
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
    Admin Page - Dashboard
  </title>
  <!--     Fonts and icons     -->
       <!-- Add in your <head> if not already included -->
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> -->

  <!-- <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" /> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
  integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
  crossorigin="anonymous" referrerpolicy="no-referrer" />
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
      /* General Body Styling */
  body {
    background: #f8f9fa;
    font-family: 'Inter', sans-serif;
  }

  /* Container Styling */
  .container {
    margin-top: 40px;
  }

  /* Table Styling */
  .table {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
  }

  /* Table Header */
  .table thead {
    background: #2d3436;
    color: white;
  }

  .table thead th {
    color:white;
    text-transform: uppercase;
    font-size: 14px;
    padding: 12px;
  }

  /* Table Rows */
  .table tbody tr {
    transition: all 0.3s ease-in-out;
  }

  .table tbody tr:hover {
    background: #f1f2f6;
  }

  /* Profile Image */
  .profile-img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 50%;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
  }

  /* Buttons */
  .btn-toggle {
    padding: 8px 14px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
  }

  .btn-toggle.btn-success {
    background-color: #2ecc71;
    color: white;
    border: none;
  }

  .btn-toggle.btn-success:hover {
    background-color: #27ae60;
  }

  .btn-toggle.btn-danger {
    background-color: #e74c3c;
    color: white;
    border: none;
  }

  .btn-toggle.btn-danger:hover {
    background-color: #c0392b;
  }

  /* Navbar Fix */
  .navbar {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: white;
    box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
  }

  /* Align "User Details" heading to the left */
  h2.text-center {
    font-weight: 700;
    color: #2d3436;
    font-size: 26px;
    text-transform: uppercase;
    margin-bottom: 20px;
    text-align: left; /* Align text to the left */
    margin-left: 20px; /* Adjust left margin */
  }
  #navbar .btn {
    min-width: 150px;
  }

  #searchInput {
    border-radius: 0.375rem;
  }

    </style>
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

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
  <div class="container-fluid py-1 px-3">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm">
          <a class="opacity-5 text-dark" href="javascript:;">Pages</a>
        </li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Users</li>
      </ol>
    </nav>

    <!-- Filter Buttons + Search Bar -->
    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
      <div class="ms-md-auto pe-md-3 w-100">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">

          <!-- Filter Buttons -->
          <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-outline-success filter-btn" data-filter="active">
              <i class="fas fa-user-check"></i> Active Users
            </button>
            <button class="btn btn-outline-danger filter-btn" data-filter="deactivate">
              <i class="fas fa-user-times"></i> Deactivated Users
            </button>
            <button class="btn btn-outline-secondary filter-btn" data-filter="">
              <i class="fas fa-users"></i> All Users
            </button>
          </div>

          <!-- Search Box -->
          <div class="input-group" style="max-width: 250px;">
            <input type="text" id="searchInput" class="form-control" placeholder="Search Users...">
          </div>

        </div>
      </div>
    </div>

  </div>
</nav>

        <div class="container mt-5">
        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Registered</th>
                    <th>Profile</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['user_id']; ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['phone']); ?></td>
                        <td><?= $row['created_on']; ?></td>
                        <td><img 
    src="../../User/images/<?= $row['profile_image'] ? $row['profile_image'] : 'images.jpg'; ?>" 
    width="50" 
    class="profile-img" 
    onerror="this.onerror=null; this.src='../../User/images/image.jpg';"
  ></td>
  <td>
    <button class="btn btn-<?= $row['is_active'] ? 'danger' : 'success'; ?> btn-toggle" 
            data-id="<?= $row['user_id']; ?>" 
            data-status="<?= $row['is_active']; ?>">
        <i class="fas <?= $row['is_active'] ? 'fa-user-times' : 'fa-user-check'; ?>"></i>
        <?= $row['is_active'] ? 'Deactivate' : 'Activate'; ?>
    </button>
</td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
   <!-- Core JS Files -->
   <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
$(document).ready(function () {
    // Function to load users based on filter or search
    function loadUsers(query = "") {
        $.ajax({
            url: "../pages/search_user.php",
            type: "POST",
            data: { query: query },
            success: function (response) {
                $("#userTableBody").html(response); // Update table body
            }
        });
    }

    // Click event for filter buttons
    $(".filter-btn").on("click", function () {
        const filter = $(this).data("filter"); // 'active', 'deactivate', or ''
        loadUsers(filter); // Use it as search query
    });

    // Optional: Live search input
    $("#searchInput").on("keyup", function () {
        const searchTerm = $(this).val();
        loadUsers(searchTerm);
    });

    // Initial load (optional)
    loadUsers(); // Load all users on page load
});
</script>

    <script>

document.querySelectorAll('.btn-toggle').forEach(button => {
    button.addEventListener('click', function () {
        let userId = this.getAttribute('data-id');
        let currentStatus = parseInt(this.getAttribute('data-status'));
        let newStatus = currentStatus === 1 ? 0 : 1;
        let button = this;

        console.log("Toggling User:", userId, "Current Status:", currentStatus, "New Status:", newStatus);

        fetch('../pages/toggle_user_status.php', {  // Adjust path if needed
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + encodeURIComponent(userId) + '&status=' + encodeURIComponent(newStatus)
        })
        .then(response => response.text())
        .then(data => {
            console.log("Response from server:", data);

            if (data.trim() === "success") {
                button.setAttribute('data-status', newStatus);
                button.textContent = newStatus === 1 ? "Deactivate" : "Activate";
                button.classList.toggle('btn-danger', newStatus === 1);
                button.classList.toggle('btn-success', newStatus === 0);
            } else {
                alert("Error updating status! Response: " + data);
            }
        })
        .catch(error => console.error("Fetch Error:", error));
    });
});
$(document).ready(function () {
            // Search users
            $("#searchInput").on("keyup", function () {
                var searchQuery = $(this).val().trim();
                $.post("../pages/fetch_users.php", { query: searchQuery }, function (data) {
                    $("#userTableBody").html(data);
                });
            });
            
            // Toggle user status
            $(document).on("click", ".btn-toggle", function () {
                let button = $(this);
                let userId = button.data("id");
                let newStatus = button.data("status") === 1 ? 0 : 1;

                $.post("../pages/toggle_user_status.php", { id: userId, status: newStatus }, function (response) {
                    if (response.trim() === "success") {
                        button.data("status", newStatus);
                        button.toggleClass("btn-success btn-danger");
                        button.text(newStatus === 1 ? "Deactivate" : "Activate");
                    } else {
                        alert("Error updating status!");
                    }
                });
            });
        });
        $(document).ready(function () {
    // For filter buttons
    $(".filter-btn").on("click", function () {
        var filter = $(this).data("filter");

        $.ajax({
            url: "../admin/pages/fetch_users.php",
            method: "POST",
            data: { query: filter },
            success: function (data) {
                $("#userTableBody").html(data);
            }
        });
    });
      
    // Optional: live search as well
    $("#searchBox").on("keyup", function () {
        var query = $(this).val();

        $.ajax({
            url: "../admin/pages/fetch_users.php",
            method: "POST",
            data: { query: query },
            success: function (data) {
                $("#userTableBody").html(data);
            }
        });
    });
});
    </script>
</body>

</html>
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
  include_once "../config/dbconnect.php";
  // Write the SQL query to fetch all subcategories
$query = "SELECT * FROM sub_category";
$result = mysqli_query($conn, $query);
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['category_name']) && !empty(trim($_POST['category_name']))) {
        // Sanitize input
        $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);

        // Insert query
        $query = "INSERT INTO category (category_name, status) VALUES ('$category_name', 1)";

        // Execute the query
        if (mysqli_query($conn, $query)) {
            echo "success";
        } else {
            echo "Database error: " . mysqli_error($conn);
        }
    } else {
        echo "Category name is required!";
    }
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
/* General Page Styling */
body {
    background-color: #f8f9fa;
    font-family: 'Inter', sans-serif;
}

/* Category Management Title */
h3 {
    padding-top: 50px;
    font-weight: 700;
    color: #2d3436;
    font-size: 34px;
    text-transform: uppercase;
    margin-bottom: 20px;
    text-align: left;
    padding-left: 20px;
}

/* Add New Category Button */
.btn-primary {
    background-color: #ff3b77;
    border: none;
    padding: 10px 18px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
}

.btn-primary:hover {
    background-color: #e02a65;
}

/* Table Styling */
.table {
    width: 100%;
    background: white;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

.table th {
    background: #2d3436;
    color: white;
    font-size: 14px;
    text-transform: uppercase;
    padding: 12px;
    border: none;
}

.table td {
    padding: 12px;
    border: none;
    vertical-align: middle;
}

/* Status Buttons */
.btn-success, .btn-danger {
    border-radius: 5px;
    font-size: 14px;
    padding: 8px 12px;
    transition: all 0.3s ease-in-out;
}

.btn-success {
    background-color: #2ecc71;
    color: white;
}

.btn-success:hover {
    background-color: #27ae60;
}

.btn-danger {
    background-color: #e74c3c;
    color: white;
}

.btn-danger:hover {
    background-color: #c0392b;
}

/* Delete Button */
.btn-delete {
    background-color: #ff3b3b;
    color: white;
    font-weight: bold;
    border-radius: 5px;
    transition: all 0.3s ease-in-out;
}

.btn-delete:hover {
    background-color: #cc2929;
}
h3 {
    text-transform: none !important; /* Ensure text stays as typed */
}
/* Style for modal buttons */
.modal button {
    background-color: #ff3b77; /* Matching primary button color */
    color: white;
    border: none;
    padding: 10px 16px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
    cursor: pointer;
}
.fixed-btn {
    width: 120px; /* Adjust width as needed */
    text-align: center;
}

.modal button:hover {
    background-color: #e02a65;
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
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <h3 class="mb-4">Category Management</h2>

  <!-- Add Category Button -->
  <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
      Add New Category
  </button>

  <!-- Add Category Modal -->
  <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title " id="addCategoryModalLabel">Add New Category</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <form id="addCategoryForm">
                      <div class="mb-3">
                          <label for="category_name" class="form-label ">Category Name</label>
                          <input type="text" class="form-control" id="category_name" name="category_name" required>
                      </div>
                      <form method="POST" action="category_add.php">
      <button type="submit">Add Category</button>
  </form>

  <!-- Add Category Modal -->
  <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <form method="POST" action="category_add.php">
      <input type="text" name="category_name" placeholder="Category Name" required>
      <button type="submit">Add Category</button>
  </form>
              </div>
          </div>
      </div>
  </div>

                  </form>
              </div>
          </div>
      </div>
  </div>


<!-- Search Bar -->
<div class="d-flex justify-content-end mb-3">
  <input type="text" id="searchCategory" class="form-control w-auto" style="max-width: 300px;" placeholder="Search categories...">
</div>

 <!-- Category Table -->
<table class="table table-bordered">
    <thead class="thead-dark">
        <tr>
            <th>S.R</th>
            <th>Category Name</th>
            <th>Status</th>
            <th>Actions</th>
            <th>Manage Subcategory</th>
        </tr>
    </thead>
    <tbody id="categoryTableBody">
        <?php
        $sql = "SELECT * FROM category ORDER BY category_id ASC"; // Shows smallest ID first
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Query Failed: " . mysqli_error($conn));
        }

        $sn = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>{$sn}</td> 
                <td class='category-name'>{$row['category_name']}</td>
                <td>" . ($row['status'] ? 'Active' : 'Inactive') . "</td>
                <td>
                    <button class='btn btn-" . ($row['status'] ? 'danger' : 'success') . " toggle-status fixed-btn' 
                        data-id='{$row['category_id']}' 
                        data-status='{$row['status']}'>
                        " . ($row['status'] ? 'Deactivate' : 'Activate') . "
                    </button>
                    <a href='edit_category.php?category_id={$row['category_id']}' class='btn btn-primary fixed-btn'>✏️ Edit</a>
                    <button class='btn btn-warning delete-category fixed-btn' data-id='{$row['category_id']}'>🗑 Delete</button>
                </td>
                
                <td>
                    <a href='subcategory.php?category_id={$row['category_id']}' class='btn btn-info'>📂 Manage Subcategories</a>
                </td>
                
            </tr>";
            $sn++;
        }
        ?>
    </tbody>
</table>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
$(document).ready(function () {
    $("#addCategoryForm").submit(function (e) {
        e.preventDefault();

        var category_name = $("#category_name").val().trim();
        if (category_name === "") {
            alert("Category name cannot be empty!");
            return;
        }

        $.ajax({
            url: "category_add.php",
            type: "POST",
            data: { category_name: category_name },
            dataType: "json", // Expecting JSON response
            success: function (response) {
                console.log("Server Response:", response);

                if (response.success) {
                    alert(response.message);
                    $("#addCategoryModal").modal("hide"); // Hide the modal
                    setTimeout(function () {
                        location.reload(); // Reload page to show new category
                    }, 500);
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.log("AJAX Error:", xhr.responseText);
                alert("AJAX Error: " + xhr.responseText);
            }
        });
    });
});


$(document).ready(function () {
    // Toggle Category Status (No Alert, No Refresh)
    $(".toggle-status").click(function (e) {
        e.preventDefault(); // Prevent default button action

        var button = $(this);
        var categoryId = button.data("id");
        var currentStatus = button.data("status");

        $.ajax({
            url: "category_status.php",
            type: "POST",
            data: { id: categoryId, status: currentStatus },
            success: function (response) {
                if (response.trim() === "success") {
                    // Toggle button state visually without refresh
                    if (currentStatus == 1) {
                        button.removeClass("btn-danger").addClass("btn-success").text("Activate");
                        button.data("status", 0);
                    } else {
                        button.removeClass("btn-success").addClass("btn-danger").text("Deactivate");
                        button.data("status", 1);
                    }
                }
            }
        });
    });
});

// Delete Category (No Alert, No Refresh)
$(".delete-category").click(function (e) {
        e.preventDefault(); // Prevent default action

        var button = $(this);
        var categoryId = button.data("id");

        if (confirm("Are you sure you want to delete this category?")) {
            $.ajax({
                url: "delete_category.php",
                type: "POST",
                data: { id: categoryId },
                success: function (response) {
                    if (response.trim() === "success") {
                        button.closest("tr").fadeOut("slow", function () {
                            $(this).remove(); // Remove row from table
                        });
                    }
                }
            });
        }
    });
      $(document).ready(function () {
    // Search function
    $("#searchCategory").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#categoryTableBody tr").filter(function () {
            $(this).toggle($(this).find(".category-name").text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Toggle Category Status (No Page Refresh, No Alerts)
    $(".toggle-status").click(function (e) {
        e.preventDefault(); // Prevent default action

        var button = $(this);
        var categoryId = button.data("id");
        var currentStatus = button.data("status");

        $.ajax({
            url: "category_status.php",
            type: "POST",
            data: { id: categoryId, status: currentStatus },
            success: function () {
                // Update button state without refreshing or showing alerts
                if (currentStatus == 1) {
                    button.removeClass("btn-danger").addClass("btn-success").text("Activate");
                    button.data("status", 0); // Update data-status
                } else {
                    button.removeClass("btn-success").addClass("btn-danger").text("Deactivate");
                    button.data("status", 1); // Update data-status
                }
            }
        });
    });
});

  </script>
  </main>
  </body>

  </html>
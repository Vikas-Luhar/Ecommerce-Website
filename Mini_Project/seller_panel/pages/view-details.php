<?php
session_start();
include_once "../config/dbconnect.php";

if (!isset($_SESSION['seller_id'])) {
    header("Location: ../pages/sign-in.php");
    exit();
}
$seller_id = $_SESSION['seller_id'];

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    $check_sql = "SELECT * FROM product WHERE product_id = ? AND seller_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $product_id, $seller_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $delete_sql = "DELETE FROM product WHERE product_id = ? AND seller_id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("ii", $product_id, $seller_id);
        echo $stmt->execute() ? "Product deleted successfully!" : "Error deleting product!";
    } else {
        echo "Unauthorized action!";
    }
}
if (isset($_GET['category_id'])) {
  $category_id = intval($_GET['category_id']); // Get selected category ID
  
  $sql = "SELECT subcategory_id, subcategory_name FROM sub_category WHERE category_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $category_id);
  $stmt->execute();
  $result = $stmt->get_result();

  echo '<option selected disabled>Select Subcategory</option>';
  while ($row = $result->fetch_assoc()) {
      echo "<option value='{$row['subcategory_id']}'>{$row['subcategory_name']}</option>";
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
        Seller Panel - Dashboard
      </title>
      <style>
.search-bar {
  padding: 10px;
  border: 1px solid #ccc;
  max-width: 250px; /* Adjust width */
  margin-left: auto; /* Push to right */
}
.search-bar input {
  border: 1px solid black !important; /* Ensure the border stays */
  width: 100%;
  padding: 5px;
  outline: none; /* Remove default blue outline */
}

.search-bar input:focus {
  border: 1px solid black !important; /* Keep border on focus */
  box-shadow: none !important; /* Remove Bootstrap's default shadow */
}

.form-control {
  border-right: none; /* Remove border between input and button */
  box-shadow: none; /* Remove focus outline */
}

.btn-primary {
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
}

/* Ensure both elements are aligned correctly */
.search-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

</style>
          <!-- jQuery (Required for Bootstrap) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
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


  <link rel="stylesheet" href="../assets/css/style.css">
  <body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="./dashboard.php" >        <!-- <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo"> -->
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
    
    
        <!-- Main Content -->
        <div class="container">
          <h2 class="mb-4">Product Management</h2>
          
          <div class="d-flex justify-content-between align-items-center">
  <!-- Left: Add Product Button -->
  <button type="button" class="btn btn-custom mb-3" data-toggle="modal" data-target="#myModal">
    <i class="fas fa-plus"></i> Add Product
  </button>

  <!-- Right: Search Bar -->
  <div class="input-group search-bar w-auto">
  <input id="search-input" type="search" class="form-control rounded-start" 
       placeholder="Search..." onkeyup="searchTable()" />
    <button id="search-button" type="button" class="btn btn-primary">
      <i class="fas fa-search"></i>
    </button>
  </div>
</div>

</div>

          <table class="table table-striped">
            <thead class="thead-dark">
              <tr>
                <th>S.N.</th>
                <!-- <th>Product ID</th> -->
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Price</th>
                <th colspan="2">Action</th>
              </tr>
            </thead>
            <tbody>
            <?php
               $sql = "SELECT p.*, c.category_name FROM product p 
               JOIN category c ON p.category_id = c.category_id 
               WHERE p.Seller_ID = ? ORDER BY p.product_id ASC";
       $stmt = $conn->prepare($sql);
       $stmt->bind_param("i", $seller_id);
       $stmt->execute();
       $result = $stmt->get_result();
              $count = 1;
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $imagePath = (!empty($row["product_image"]) && file_exists("../uploads/" . $row["product_image"]))
                  ? "../uploads/" . $row["product_image"]
                  : "../assets/img/default.jpg";
                  ?>
                  <tr>
                    <td><?= $count ?></td>
                    <!-- <td><?= $row["product_id"] ?></td> -->
                    <?php
  // Remove unnecessary "./" and "../"
  $imagePath = "uploads/" . basename($row['product_image']); 

  // Full path for checking existence
  $fullPath = $_SERVER['DOCUMENT_ROOT'] . "/Mini_Project/seller_panel/" . $imagePath;

  // Check if file exists, else use default image
  if (!file_exists($fullPath) || empty($row['product_image'])) {
    $imagePath = "uploads/default.jpg"; // Ensure default.jpg is inside the correct folder
  }
  ?>

  <td>
    <img height="100px" src="/Mini_Project/seller_panel/<?= $imagePath ?>" class="product-image"
    onerror="this.src='/Mini_Project/seller_panel/uploads/default.jpg'">
  </td>


  <td><?= htmlspecialchars($row["product_name"]) ?></td>
  <td title="<?= htmlspecialchars($row["product_desc"]) ?>">
    <?= strlen($row["product_desc"]) > 50 ? substr($row["product_desc"], 0, 50) . "..." : $row["product_desc"]; ?>
  </td>
  <td><?= $row["category_name"] ?></td>
  <td>₹<?= $row["price"] ?></td>
  <!-- Edit Button inside Table -->
  <td>
  <a href="edit_product.php?id=<?= $row['product_id'] ?>" class="btn btn-warning">Edit</a>
</td>
<td>
    <button class="btn btn-danger" onclick="deleteProduct(<?= $row['product_id'] ?>)">Delete</button>
</td>


  </tr>
  <?php
                  $count++;
                }
              }
              ?>
</tbody>
        </table>
      </div>
      
      <!-- Add Product Modal -->
      <form enctype="multipart/form-data" action="../controller/addItemController.php" method="POST">
      <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add New Product</h4>
              <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                  <label>Product Name:</label>
                  <input type="text" class="form-control" name="p_name" required>
                </div>
                <div class="form-group">
                  <label>Price:</label>
                  <input type="number" class="form-control" name="p_price" required>
                </div>
                <div class="form-group">
                  <label>Description:</label>
                  <textarea class="form-control" name="p_desc" required></textarea>
                </div>
                <div class="form-group">
                        <label>Category:</label>
                        <select name="category" id="categoryDropdown" class="form-control" required>
                            <option selected disabled>Select Category</option>
                            <?php
                            $cat_sql = "SELECT * FROM category WHERE status = 1";
                            $cat_result = $conn->query($cat_sql);
                            while ($row = $cat_result->fetch_assoc()) {
                                echo "<option value='{$row['category_id']}'>{$row['category_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Subcategory:</label>
                        <select name="subcategory" id="subcategoryDropdown" class="form-control" required>
                            <option selected disabled>Select Subcategory</option>
                        </select>
                    </div>
                <div class="form-group">
                  <label>Product Image:</label>
                  <input type="file" class="form-control-file" name="file[]" multiple required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="upload" class="btn btn-custom">Add Product</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
  </form>

<!-- EDIT PRODUCT MODAL -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editProductForm">
                    <input type="hidden" id="edit_product_id">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" id="edit_product_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" id="edit_product_price" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea id="edit_product_desc" class="form-control"></textarea>
                    </div>
                    <button type="button" class="btn btn-success" onclick="saveChanges()">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $("#categoryDropdown").change(function () {
        var categoryId = $(this).val();
        if (categoryId) {
            $.ajax({
                url: "fetch_subcategories.php",
                type: "GET",
                data: { category_id: categoryId },
                success: function (response) {
                    $("#subcategoryDropdown").html(response);
                }
            });
        } else {
            $("#subcategoryDropdown").html('<option disabled selected>Select Subcategory</option>');
        }
    });
});
function searchTable() {
    let input = document.getElementById("search-input").value.toLowerCase(); // Get search value
    let table = document.querySelector(".table tbody");
    let rows = table.getElementsByTagName("tr");

    for (let i = 0; i < rows.length; i++) {
        let cells = rows[i].getElementsByTagName("td");
        let found = false;

        for (let j = 1; j < cells.length - 2; j++) { // Ignore action buttons (edit/delete)
            if (cells[j]) {
                let text = cells[j].innerText.toLowerCase();
                if (text.includes(input)) {
                    found = true;
                    break;
                }
            }
        }

        rows[i].style.display = found ? "" : "none"; // Show or hide row
    }
}
</script>

  <script>
function deleteProduct(productId) {
    if (confirm("Are you sure you want to delete this product?")) {
        fetch("delete_product.php?id=" + productId, { method: "GET" })
            .then(response => response.text())
            .then(data => {
                alert(data); // Show success or error message
                location.reload(); // Refresh the page after deletion
            })
            .catch(error => console.error("Error:", error));
    }
}
  </script>
  </body>
  </html>
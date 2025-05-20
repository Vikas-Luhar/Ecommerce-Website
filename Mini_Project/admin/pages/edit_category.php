<?php
  include_once "../config/dbconnect.php";   

if (!isset($_GET['category_id'])) {
    die("Category ID not provided.");
}

$category_id = intval($_GET['category_id']);

// Fetch category data
$sql = "SELECT * FROM category WHERE category_id = $category_id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    die("Category not found.");
}

$category = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['category_name']);
    $status = isset($_POST['status']) ? 1 : 0;

    $updateSql = "UPDATE category SET category_name = '$name', status = $status WHERE category_id = $category_id";
    if (mysqli_query($conn, $updateSql)) {
        echo "<script>alert('Category updated successfully'); window.location.href='category.php';</script>";
    } else {
        echo "Update failed: " . mysqli_error($conn);
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Category</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    .form-icon {
      position: absolute;
      top: 50%;
      left: 10px;
      transform: translateY(-50%);
      color: #6c757d;
    }
    .input-with-icon {
      position: relative;
    }
    .input-with-icon input {
      padding-left: 2.5rem;
    }
  </style>
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-lg border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="m-0"><i class="fas fa-edit me-2"></i>Edit Category</h5>
      <a href="category.php" class="btn btn-outline-light"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <div class="card-body">
      <form method="post">
        <!-- Category Name -->
        <div class="mb-4 input-with-icon">
          <i class="fas fa-tags form-icon"></i>
          <label for="category_name" class="form-label">Category Name</label>
          <input type="text" name="category_name" id="category_name" class="form-control" value="<?= htmlspecialchars($category['category_name']) ?>" required>
        </div>

        <!-- Active Status -->
        <div class="form-check form-switch mb-4">
          <input class="form-check-input" type="checkbox" id="status" name="status" <?= $category['status'] ? 'checked' : '' ?>>
          <label class="form-check-label" for="status">
            <i class="fas fa-toggle-on me-1 text-primary"></i> Active Status
          </label>
        </div>

        <!-- Submit & Cancel -->
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-success me-2">
            <i class="fas fa-save me-1"></i> Update Category
          </button>
          <a href="category.php" class="btn btn-secondary">
            <i class="fas fa-times me-1"></i> Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

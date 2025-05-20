<?php
include_once "../config/dbconnect.php";
session_start();

if (!isset($_GET['admin_id'])) {
    echo "Invalid request!";
    exit;
}

$admin_id = $_GET['admin_id'];

// Fetch admin details
$query = "SELECT * FROM admin WHERE admin_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if (!$admin) {
    echo "Admin not found!";
    exit;
}

// Handle form submission (with validation)
if (isset($_POST['update'])) {
    $name = trim($_POST['Admin_Name']);
    $email = trim($_POST['Email_ID']);
    $phone = trim($_POST['Phone']);

    // Check if any field is empty
    if (empty($name) || empty($email) || empty($phone)) {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
        exit;
    }

    // Check if values are unchanged
    if (
        $name === $admin['Admin_Name'] &&
        $email === $admin['Email_ID'] &&
        $phone === $admin['phone']
    ) {
        echo "<script>alert('No changes detected!'); window.history.back();</script>";
        exit;
    }

    // Proceed to update admin details
    $update_query = "UPDATE admin SET Admin_Name = ?, Email_ID = ?, Phone = ? WHERE admin_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssi", $name, $email, $phone, $admin_id);

    if ($stmt->execute()) {
        echo "<script>alert('Admin updated successfully!'); window.location='profile.php';</script>";
    } else {
        echo "<script>alert('Error updating admin: " . $conn->error . "');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Admin</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body style="background-color: #f8f9fa;">

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="card shadow border-0">
          <div class="card-header bg-primary text-white text-center py-3">
            <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i>Edit Admin Profile</h4>
          </div>
          <div class="card-body p-4">
            <form method="POST">
              <div class="mb-3">
                <label class="form-label"><i class="fas fa-user me-2 text-info"></i>Admin Name</label>
                <input type="text" name="Admin_Name" value="<?= htmlspecialchars($admin['Admin_Name']) ?>" class="form-control" required>
              </div>

              <div class="mb-3">
                <label class="form-label"><i class="fas fa-envelope me-2 text-warning"></i>Email</label>
                <input type="email" name="Email_ID" value="<?= htmlspecialchars($admin['Email_ID']) ?>" class="form-control" required>
              </div>

              <div class="mb-3">
                <label class="form-label"><i class="fas fa-phone me-2 text-success"></i>Phone</label>
                <input type="text" name="Phone" value="<?= htmlspecialchars($admin['phone']) ?>" class="form-control" required>
              </div>

              <div class="d-flex justify-content-between">
                <button type="submit" name="update" class="btn btn-success px-4">
                  <i class="fas fa-save me-1"></i>Update
                </button>
                <a href="profile.php" class="btn btn-secondary px-4">
                  <i class="fas fa-times me-1"></i>Cancel
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

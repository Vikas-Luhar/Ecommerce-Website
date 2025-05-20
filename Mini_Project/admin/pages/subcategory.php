<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "swiss_collection";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

// Fetch Category Name
$category_result = $conn->query("SELECT * FROM category WHERE Category_ID = $category_id");
$category = $category_result->fetch_assoc();
// ✅ Add Subcategory (Prevent Duplicates)
if (isset($_POST['add']) && !empty($_POST['name']) && $category_id > 0) {
    $name = trim($_POST['name']);

    // Check if the subcategory already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM sub_category WHERE Sub_category_name = ? AND Category_ID = ?");
    $stmt->bind_param("si", $name, $category_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        // Show alert and stop execution
        echo "<script>alert('Subcategory already exists!'); window.location='subcategory.php?category_id=$category_id';</script>";
        exit();
    } else {
        // Insert only if subcategory does not exist
        $stmt = $conn->prepare("INSERT INTO sub_category (Sub_category_name, Category_ID, status) VALUES (?, ?, 1)");
        $stmt->bind_param("si", $name, $category_id);
        
        if ($stmt->execute()) {
            header("Location: subcategory.php?category_id=$category_id");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Update Subcategory
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $conn->query("UPDATE sub_category SET Sub_category_name='$name' WHERE Sub_category_ID=$id");
    header("Location: subcategory.php?category_id=$category_id");
}

// Delete Subcategory
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM sub_category WHERE Sub_category_ID=$id");
    header("Location: subcategory.php?category_id=$category_id");
}

// Activate/Deactivate Subcategory
if (isset($_GET['status'])) {
    $id = $_GET['status'];
    $currentStatus = $_GET['current'];
    $newStatus = $currentStatus == 1 ? 0 : 1;
    $conn->query("UPDATE sub_category SET status=$newStatus WHERE Sub_category_ID=$id");
    header("Location: subcategory.php?category_id=$category_id");
}

// Fetch all Subcategories
$result = $conn->query("SELECT * FROM sub_category WHERE Category_ID=$category_id");

// Fetch single subcategory for editing
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_data = $conn->query("SELECT * FROM sub_category WHERE Sub_category_ID=$id")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subcategories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    
    <a href="category.php" class="btn btn-secondary mb-3">🔙 Back</a>

    <h2 class="mb-3">Manage Subcategories</h2>

    <div class="mb-3">
        <label class="form-label">Category</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($category['category_name']) ?>" disabled>
    </div>
    <!-- Add / Edit Subcategory Form -->
    <form method="POST" class="mb-3">
        <input type="hidden" name="id" value="<?= $edit_data['Sub_category_ID'] ?? '' ?>">
        <label class="form-label">Subcategory Name</label>
        <div class="input-group mb-2">
            <input type="text" name="name" class="form-control" placeholder="Enter subcategory name" value="<?= $edit_data['Sub_category_name'] ?? '' ?>" required>
            <button type="submit" name="<?= isset($edit_data) ? 'update' : 'add' ?>" class="btn btn-primary">
                <?= isset($edit_data) ? 'Update Subcategory' : 'Add Subcategory' ?>
            </button>
            <?php if (isset($edit_data)): ?>
                <a href="subcategory.php?category_id=<?= $category_id ?>" class="btn btn-secondary">Cancel</a>
            <?php endif; ?>
        </div>
    </form>

    <h4>Subcategories List</h4>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Subcategory Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php $count = 1; while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $count++ ?></td>
                <td><?= $row['Sub_category_name'] ?></td>
                <td>
                    <a href="?category_id=<?= $category_id ?>&status=<?= $row['Sub_category_ID'] ?>&current=<?= $row['status'] ?>" 
                       class="btn btn-sm <?= $row['status'] == 1 ? 'btn-success' : 'btn-danger' ?>">
                        <?= $row['status'] == 1 ? "Active" : "Inactive" ?>
                    </a>
                </td>
                <td>
                    <a href="?category_id=<?= $category_id ?>&edit=<?= $row['Sub_category_ID'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="?category_id=<?= $category_id ?>&delete=<?= $row['Sub_category_ID'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>

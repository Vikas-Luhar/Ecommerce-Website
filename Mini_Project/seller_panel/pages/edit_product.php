<?php
include_once "../config/dbconnect.php";

// Check if product_id is set
if (!isset($_GET['id'])) {
    echo "Invalid request!";
    exit;
}

$product_id = $_GET['id'];

// Fetch product details (Main Image)
$query = "SELECT * FROM product WHERE product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found!";
    exit;
}

// Fetch multiple images from tblimages
$images_query = "SELECT ImageURL FROM tblimages WHERE Product_ID = ?";
$stmt = $conn->prepare($images_query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

$product_images = [];
while ($row = $result->fetch_assoc()) {
    $product_images[] = $row['ImageURL'];
}

// Handle delete request (AJAX) **BEFORE OUTPUTTING ANYTHING**
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_image'])) {
    $imagePath = $_POST['delete_image'];

    // Delete from database
    $stmt = $conn->prepare("DELETE FROM tblimages WHERE ImageURL = ?");
    $stmt->bind_param("s", $imagePath);
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        // Remove image file from server
        $filePath = "../" . $imagePath;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        echo "success";
    } else {
        echo "error";
    }
    exit; // Stop further execution
}
// Handle form submission
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $desc = $_POST['desc'];
    $price = $_POST['price'];

    // Validate price (prevent negative values)
    if ($price < 1) {
        echo "<script>alert('Price must be at least 1!'); window.history.back();</script>";
        exit;
    }

    // Handle main image update
    if (!empty($_FILES['image']['name'])) {
        $image = "./uploads/" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . basename($_FILES['image']['name']));
    } else {
        $image = $product['product_image'];
    }

    // Update product details
    $update_query = "UPDATE product SET product_name = ?, product_desc = ?, price = ?, product_image = ? WHERE product_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssdsi", $name, $desc, $price, $image, $product_id);

    if ($stmt->execute()) {
        echo "<script>alert('Product updated successfully!'); window.location='view-details.php';</script>";
    } else {
        echo "Error updating product: " . $conn->error;
    }

    // Handle multiple image uploads
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $file_name = basename($_FILES['images']['name'][$key]);
            $target_file = "./uploads/" . $file_name;

            if (move_uploaded_file($_FILES['images']['tmp_name'][$key], "../uploads/" . $file_name)) {
                $img_query = "INSERT INTO tblimages (Product_ID, ImageURL) VALUES (?, ?)";
                $stmt = $conn->prepare($img_query);
                $stmt->bind_param("is", $product_id, $target_file);
                $stmt->execute();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" value="<?= $product['product_name'] ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="desc" class="form-control" required><?= $product['product_desc'] ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" name="price" value="<?= $product['price'] ?>" class="form-control" min="1" required>
            </div>

            <!-- Main Image -->
            <div class="mb-3">
                <label class="form-label">Current Main Image</label><br>
                <img src="../<?= $product['product_image'] ?>" alt="Product Image" width="150" class="img-thumbnail">
            </div>
            <div class="mb-3">
                <label class="form-label">Upload New Main Image</label>
                <input type="file" name="image" class="form-control">
            </div>

            <!-- Multiple Images -->
            <div class="mb-3">
                <label class="form-label">Additional Images</label><br>
                <?php foreach ($product_images as $img): ?>
                    <img src="../<?= $img ?>" alt="Product Image" width="100" class="img-thumbnail m-1">
                <button type="button" class="btn btn-danger btn-sm mt-1 delete-image" data-image="<?= $img ?>" data-product="<?= $product_id ?>">Delete</button>
                <?php endforeach; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Upload More Images</label>
                <input type="file" name="images[]" class="form-control" multiple>
            </div>

            <button type="submit" name="update" class="btn btn-success">Update Product</button>
            <a href="view-details.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script>
        document.querySelector("form").addEventListener("submit", function(event) {
            let priceInput = document.querySelector("input[name='price']");
            if (priceInput.value < 1) {
                alert("Price cannot be negative!");
                event.preventDefault(); // Stop form submission
            }
        });
    </script>
     <script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".delete-image").forEach(button => {
        button.addEventListener("click", function () {
            let imagePath = this.getAttribute("data-image");
            let buttonElement = this;

            if (confirm("Are you sure you want to delete this image?")) {
                fetch(window.location.href, { // Ensure it sends to the same PHP file
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `delete_image=${encodeURIComponent(imagePath)}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "success") {
                        buttonElement.previousElementSibling.remove(); // Remove the image only
                        buttonElement.remove(); // Remove the button itself
                    } else {
                        alert("Failed to delete image.");
                    }
                })
                .catch(error => console.error("Error:", error));
            }
        });
    });
});
    </script>
</body>
</html>

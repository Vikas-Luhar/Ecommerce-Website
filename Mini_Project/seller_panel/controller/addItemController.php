<?php
session_start();
include_once "../config/dbconnect.php";

if (!isset($_SESSION['seller_id'])) {
    echo "<script>alert('Error: You must be logged in to add a product!'); window.history.back();</script>";
    exit();
}

if (isset($_POST['upload'])) {
    $ProductName = trim($_POST['p_name'] ?? '');
    $desc = trim($_POST['p_desc'] ?? '');
    $price = trim($_POST['p_price'] ?? '');
    $category = $_POST['category'] ?? '';
    $subcategory = $_POST['subcategory'] ?? '0'; // If not selected, store 0
    $seller_id = $_SESSION['seller_id']; // Get seller ID from session

    // Validate inputs
    if (empty($ProductName) || empty($desc) || empty($price) || empty($category)) {
        echo "<script>alert('Error: All fields except subcategory are required!'); window.history.back();</script>";
        exit();
    }

    if (!is_numeric($price) || $price <= 0) {
        echo "<script>alert('Error: Price must be a valid positive number!'); window.history.back();</script>";
        exit();
    }

    // Check if product already exists for this seller
    $stmt = $conn->prepare("SELECT * FROM product WHERE product_name = ? AND seller_id = ?");
    $stmt->bind_param("si", $ProductName, $seller_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Error: You have already added this product!'); window.history.back();</script>";
        exit();
    }
    $stmt->close();

    // Insert product with subcategory (0 if not provided)
    $stmt = $conn->prepare("INSERT INTO product (product_name, price, product_desc, category_id, subcategory_id, seller_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsiii", $ProductName, $price, $desc, $category, $subcategory, $seller_id);
    if (!$stmt->execute()) {
        echo "<script>alert('Database Error: " . $stmt->error . "'); window.history.back();</script>";
        exit();
    }
    $product_id = $stmt->insert_id;
    $stmt->close();

    // Upload Directory
    $uploadDir = "../uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $firstImagePath = "";

    if (!empty($_FILES['file']['name'][0])) {
        foreach ($_FILES['file']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['file']['error'][$key] == 0) {
                // Generate unique filename
                $imageName = time() . "_" . basename($_FILES['file']['name'][$key]);
                $uploadPath = $uploadDir . $imageName;

                if (move_uploaded_file($tmp_name, $uploadPath)) {
                    $imagePath = "uploads/" . $imageName;

                    // First Image → product table
                    if ($key == 0) {
                        $firstImagePath = $imagePath;
                        $stmt = $conn->prepare("UPDATE product SET product_image=? WHERE product_id=?");
                        $stmt->bind_param("si", $firstImagePath, $product_id);
                        $stmt->execute();
                        $stmt->close();
                    } else {
                        // Other Images → tblimages
                        $stmt = $conn->prepare("INSERT INTO tblimages (Product_ID, ImageURL) VALUES (?, ?)");
                        $stmt->bind_param("is", $product_id, $imagePath);
                        $stmt->execute();
                        $stmt->close();
                    }
                } else {
                    echo "<script>alert('Error: Failed to upload " . $_FILES['file']['name'][$key] . "'); window.history.back();</script>";
                    exit();
                }
            }
        }
    } else {
        echo "<script>alert('Error: At least one image is required!'); window.history.back();</script>";
        exit();
    }

    // Redirect on success
    echo "<script>alert('Product uploaded successfully!'); window.location.href='../pages/view-details.php';</script>";
}
?>

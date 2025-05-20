<?php
require '../config/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['product_id'];
    $name = $_POST['product_name'];
    $price = $_POST['product_price'];
    $description = $_POST['product_description'];

    // Update product details
    $query = $conn->prepare("UPDATE product SET product_name=?, product_desc=?, price=? WHERE product_id=?");
    $query->bind_param("ssdi", $name, $description, $price, $id);
    $query->execute();

    // Handle Multiple Image Upload
    if (!empty($_FILES['product_images']['name'][0])) {
        foreach ($_FILES['product_images']['tmp_name'] as $key => $tmp_name) {
            $image_name = $_FILES['product_images']['name'][$key];
            $image_tmp = $_FILES['product_images']['tmp_name'][$key];
            $target = "../uploads/" . basename($image_name);

            if (move_uploaded_file($image_tmp, $target)) {
                $insertImg = $conn->prepare("INSERT INTO tblimages (Product_ID, ImageURL) VALUES (?, ?)");
                $insertImg->bind_param("is", $id, $image_name);
                $insertImg->execute();
            }
        }
    }

    echo "Product updated successfully!";
}
?>

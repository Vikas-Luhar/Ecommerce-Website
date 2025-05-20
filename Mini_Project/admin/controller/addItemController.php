<?php
include_once "../config/dbconnect.php";

if(isset($_POST['upload'])) {

    $ProductName = trim($_POST['p_name']); // Trim spaces to avoid duplication issues
    $desc = $_POST['p_desc'];
    $price = $_POST['p_price'];
    $category = $_POST['category'];

    // Check if product already exists
    $checkQuery = mysqli_query($conn, "SELECT * FROM product WHERE product_name = '$ProductName'");
    
    if(mysqli_num_rows($checkQuery) > 0) {
        echo "<script>alert('Error: Product already exists!'); window.location.href='../pages/view-details.php';</script>";
        exit(); // Stop execution if duplicate exists
    }

    // File Upload Handling
    $name = $_FILES['file']['name'];
    $temp = $_FILES['file']['tmp_name'];

    $uploadDir = "../uploads/"; // Correct folder location
    $imagePath = "uploads/" . $name; // Save only relative path in DB
    $finalImage = $uploadDir . $name;

    // Ensure upload directory exists
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Move uploaded file
    if(move_uploaded_file($temp, $finalImage)) {

        // Insert into database (without status)
        $insert = mysqli_query($conn, "INSERT INTO product
        (product_name, product_image, price, product_desc, category_id) 
        VALUES ('$ProductName', '$imagePath', '$price', '$desc', '$category')");

        if(!$insert) {
            echo "<script>alert('Database Error: " . mysqli_error($conn) . "');</script>";
        } else {
            echo "<script>alert('Product added successfully!'); window.location.href='../pages/view-details.php';</script>";
        }
    } else {
        echo "<script>alert('Image upload failed. Please check file permissions.');</script>";
    }
}
?>

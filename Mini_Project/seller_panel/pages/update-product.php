<?php
include_once "../config/dbconnect.php";

if(isset($_POST['product_id'])) {
    $id = $_POST['product_id'];
    $name = $_POST['p_name'];
    $desc = $_POST['p_desc'];
    $price = $_POST['p_price'];
    $category = $_POST['category'];
    
    // Handle image upload
    if(isset($_FILES['newImage']['name']) && $_FILES['newImage']['name'] != "") {
        $imageName = $_FILES['newImage']['name'];
        $imageTmp = $_FILES['newImage']['tmp_name'];
        $uploadPath = "../uploads/" . $imageName;
        move_uploaded_file($imageTmp, $uploadPath);
        $updateImage = ", product_image='$imageName'";
    } else {
        $updateImage = "";
    }

    $query = "UPDATE product SET product_name='$name', product_desc='$desc', price='$price', category_id='$category' $updateImage WHERE product_id='$id'";

    if(mysqli_query($conn, $query)) {
        echo "Product updated successfully!";
    } else {
        echo "Error updating product: " . mysqli_error($conn);
    }
}
?>

<?php
    include_once "../config/dbconnect.php";

    $product_id = $_POST['product_id'];
    $p_name = $_POST['p_name'];
    $p_desc = $_POST['p_desc'];
    $p_price = $_POST['p_price'];
    $category = $_POST['category'];
    $stock_quantity = $_POST['stock_qty'];

    if (isset($_FILES['newImage']) && $_FILES['newImage']['error'] == 0) {
        // Upload new image if provided
        $location = "./uploads/";  // Public location for the image
        $img = $_FILES['newImage']['name'];
        $tmp = $_FILES['newImage']['tmp_name'];
        $dir = '../uploads/';  // Server-side upload directory
        $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'webp', 'avif');

        // Generate a unique image name
        $image = rand(1000, 1000000) . "." . $ext;
        $final_image = $location . $image;

        // Check if the file extension is valid
        if (in_array($ext, $valid_extensions)) {
            // Move the uploaded file to the server
            if (move_uploaded_file($tmp, $dir . $image)) {
                // Success
            } else {
                echo "Failed to upload image.";
                exit;
            }
        } else {
            echo "Invalid image format.";
            exit;
        }
    } else {
        // Use the existing image if no new image was uploaded
        $final_image = $_POST['existingImage'];
    }

    // Update the product information in the database
    $updateItem = mysqli_query($conn, "UPDATE product SET 
        product_name='$p_name', 
        product_desc='$p_desc', 
        price='$p_price',
        category_id=$category,
        product_image='$final_image' 
        WHERE product_id='$product_id'");

    // Check if the update was successful
    if ($updateItem) {
        echo "true";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
?>



<!-- <?php
    include_once "../config/dbconnect.php";

    $product_id=$_POST['product_id'];
    $p_name= $_POST['p_name'];
    $p_desc= $_POST['p_desc'];
    $p_price= $_POST['p_price'];
    $category= $_POST['category'];

    if( isset($_FILES['newImage']) ){
        
        $location="./uploads/";
        $img = $_FILES['newImage']['name'];
        $tmp = $_FILES['newImage']['tmp_name'];
        $dir = '../uploads/';
        $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif','webp', 'avif');
        $image =rand(1000,1000000).".".$ext;
        $final_image=$location. $image;
        if (in_array($ext, $valid_extensions)) {
            $path = UPLOAD_PATH . $image;
            move_uploaded_file($tmp, $dir.$image);
        }
    }else{
        $final_image=$_POST['existingImage'];
    }
    
    $updateItem = mysqli_query($conn,"UPDATE product SET 
        product_name='$p_name', 
        product_desc='$p_desc', 
        price='$p_price',
        category_id=$category,
        product_image='$final_image' 
        WHERE product_id='$product_id'");


    if($updateItem)
    {
        echo "true";
    }
    // else
    // {
    //     echo mysqli_error($conn);
    // }
?> -->
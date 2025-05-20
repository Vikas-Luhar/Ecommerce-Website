
<div class="container p-5">
    <h4>Edit Product Detail</h4>
    <?php
    include_once "../config/dbconnect.php";
    $ID = $_POST['record'];

    // Fetch product details
    $qry = mysqli_query($conn, "SELECT * FROM product WHERE product_id='$ID'");
    $numberOfRow = mysqli_num_rows($qry);

    if ($numberOfRow > 0) {
        while ($row1 = mysqli_fetch_array($qry)) {
            $catID = $row1["category_id"];
    ?>
    <form id="update-Items" onsubmit="updateItems()" enctype='multipart/form-data'>
        <input type="hidden" id="product_id" value="<?= $row1['product_id'] ?>">

        <div class="form-group">
            <label>Product Name:</label>
            <input type="text" class="form-control" id="p_name" value="<?= $row1['product_name'] ?>">
        </div>

        <div class="form-group">
            <label>Product Description:</label>
            <input type="text" class="form-control" id="p_desc" value="<?= $row1['product_desc'] ?>">
        </div>

        <div class="form-group">
            <label>Unit Price:</label>
            <input type="number" class="form-control" id="p_price" value="<?= $row1['price'] ?>">
        </div>

        <div class="form-group">
            <label>Category:</label>
            <select id="category">
                <?php
                $sql = "SELECT * FROM category WHERE category_id='$catID'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['category_id'] . "'>" . $row['category_name'] . "</option>";
                    }
                }
                ?>
                <?php
                $sql = "SELECT * FROM category WHERE category_id!='$catID'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['category_id'] . "'>" . $row['category_name'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>

        <!-- Display Existing Images -->
        <div class="form-group">
            <label>Existing Images:</label>
            <div id="existing_images">
                <?php
                $imgQuery = mysqli_query($conn, "SELECT * FROM tblimages WHERE Product_ID='$ID'");
                while ($imgRow = mysqli_fetch_assoc($imgQuery)) {
                    echo "<div class='img-container' style='display:inline-block; margin:5px;'>
                            <img src='../" . $imgRow['ImageURL'] . "' width='100px' height='100px'>
                            <button type='button' class='btn btn-danger btn-sm' onclick='deleteImage(" . $imgRow['ID'] . ")'>X</button>
                          </div>";
                }
                ?>
            </div>
        </div>

        <!-- Upload Multiple Images -->
        <div class="form-group">
            <label>Upload New Images:</label>
            <input type="file" id="newImages" name="product_images[]" multiple>
        </div>

        <div class="form-group">
            <button type="submit" style="height:40px" class="btn btn-primary">Update Item</button>
        </div>
    </form>
    <?php
        }
    }
    ?>
</div>

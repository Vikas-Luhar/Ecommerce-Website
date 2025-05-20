<?php
include_once "../config/dbconnect.php";

if (isset($_POST['record'])) {
    $ID = $_POST['record'];

    // Debugging
    error_log("Received Product ID: " . $ID);

    $qry = mysqli_query($conn, "SELECT * FROM product WHERE product_id='$ID'");
    if (mysqli_num_rows($qry) > 0) {
        $row1 = mysqli_fetch_assoc($qry);
?>
<div class="container p-4">
    <h4>Edit Product</h4>
    <form id="update-Items" enctype="multipart/form-data">
        <input type="hidden" id="product_id" value="<?= $row1['product_id'] ?>">

        <div class="form-group">
            <label>Product Name:</label>
            <input type="text" class="form-control" id="p_name" value="<?= htmlspecialchars($row1['product_name']) ?>">
        </div>

        <div class="form-group">
            <label>Description:</label>
            <textarea class="form-control" id="p_desc"><?= htmlspecialchars($row1['product_desc']) ?></textarea>
        </div>

        <div class="form-group">
            <label>Price:</label>
            <input type="number" class="form-control" id="p_price" value="<?= $row1['price'] ?>">
        </div>

        <div class="form-group">
            <label>Category:</label>
            <select id="category" class="form-control">
                <?php
                $sql = "SELECT * FROM category WHERE category_id='" . $row1['category_id'] . "'";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['category_id'] . "' selected>" . $row['category_name'] . "</option>";
                }

                $sql = "SELECT * FROM category WHERE category_id!='" . $row1['category_id'] . "'";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['category_id'] . "'>" . $row['category_name'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Current Image:</label><br>
            <img width="200px" height="150px" src="../uploads/<?= $row1['product_image'] ?>" onerror="this.src='../assets/img/default.jpg'">
        </div>

        <div class="form-group">
            <label>Choose New Image:</label>
            <input type="file" id="newImage">
        </div>

        <button type="button" class="btn btn-primary" onclick="updateItems()">Update</button>
    </form>
</div>

<?php
    } else {
        echo "<h5>No Product Found</h5>";
    }
} else {
    echo "<h5>Invalid Request</h5>";
}
?>

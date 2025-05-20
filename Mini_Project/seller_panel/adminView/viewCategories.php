<div>
    <h3>Category Items</h3>
    <table class="table">
        <thead>
            <tr>
                <th class="text-center">S.N.</th>
                <th class="text-center">Category Name</th>
                <th class="text-center">Action</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <?php
        include_once "../config/dbconnect.php";
        $sql = "SELECT * from category";
        $result = $conn->query($sql);
        $count = 1;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td><?=$count?></td>
            <td><?=$row["category_name"]?></td>
            <td><button class="btn btn-danger" style="height:40px" onclick="categoryDelete('<?=$row['category_id']?>', this)">Delete</button></td>
            <td>
                <?php
                if ($row['status'] == 1) {
                    echo '<p><a href="status.php?category_id='.$row['category_id'].'&status=0" class="btn btn-success" style="height:40px">Enabled</a></p>';
                } else {
                    echo '<p><a href="status.php?category_id='.$row['category_id'].'&status=1" class="btn btn-danger" style="height:40px">Disabled</a></p>';
                }
                ?>
            </td>
        </tr>
        <?php
                $count++;
            }
        }
        ?>
    </table>

    <!-- Trigger the modal with a button -->
    <button type="button" class="btn btn-secondary" style="height:40px" data-toggle="modal" data-target="#myModal">
        Add Category
    </button>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Category Item</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form enctype='multipart/form-data' action="./controller/addCatController.php" method="POST">
                        <div class="form-group">
                            <label for="c_name">Category Name:</label>
                            <input type="text" class="form-control" name="c_name" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-secondary" name="upload" style="height:40px">Add Category</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function categoryDelete(id, row) {
    if (confirm("Are you sure you want to delete this category?")) {
        $.ajax({
            url: './controller/catDeleteController.php', // Path to your PHP delete file
            type: 'POST',
            data: { record: id },
            success: function(response) {
                var result = JSON.parse(response); // Parse the JSON response
                if (result.success) {
                    $(row).closest('tr').fadeOut(); // Fade out the deleted row
                } else {
                    alert("Failed to delete category: " + result.error); // Show error if deletion fails
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Log any errors to the console
                alert("Failed to delete category.");
            }
        });
    }
}

</script>

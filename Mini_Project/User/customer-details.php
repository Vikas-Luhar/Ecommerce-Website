<?php
include_once "./config/dbconnect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    
// Fetch category list
$sql_category_list = "SELECT * FROM category";
$result_category_list = $conn->query($sql_category_list);
?>

<h2>Category Items</h2>
<button class='btn btn-primary' onclick='addCategory()'>Add Category</button>
<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>S.N.</th>
            <th>Category Name</th>
            <th>Action</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if ($result_category_list->num_rows > 0) {
            $sn = 1;
            while ($row = $result_category_list->fetch_assoc()) {
                $statusBtn = $row['status'] == 1 ? "<button class='btn btn-danger' onclick='toggleStatus({$row['id']}, 0)'>Disable</button>" : "<button class='btn btn-success' onclick='toggleStatus({$row['id']}, 1)'>Enable</button>";
                echo "<tr>
                        <td>{$sn}</td>
                        <td>{$row['category_name']}</td>
                        <td><button class='btn btn-danger' onclick='deleteCategory({$row['id']})'>Delete</button></td>
                        <td>{$statusBtn}</td>
                      </tr>";
                $sn++;
            }
        } else {
            echo "<tr><td colspan='4'>No categories found.</td></tr>";
        }
        ?>
    </tbody>
</table>

<script>
    function addCategory() {
        alert("Add Category Clicked");
    }
    function deleteCategory(id) {
        alert("Delete Category: " + id);
    }
    function toggleStatus(id, status) {
        alert("Toggle Status: " + id + " to " + (status ? "Enable" : "Disable"));
    }
</script>
</body>
</html>

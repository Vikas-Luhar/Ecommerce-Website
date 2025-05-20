<?php
include_once "../config/dbconnect.php"; // Ensure correct path

if (!isset($_GET['category_id']) || empty($_GET['category_id'])) {
    echo "<option disabled selected>Error: No category selected!</option>";
    exit();
}

$category_id = intval($_GET['category_id']); // Ensure it's an integer

$sql = "SELECT Sub_category_ID, Sub_category_name FROM sub_category WHERE Category_ID = ? AND status = 1";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "<option disabled selected>Error: SQL preparation failed!</option>";
    exit();
}

$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo '<option selected disabled>Select Subcategory</option>';
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['Sub_category_ID']}'>{$row['Sub_category_name']}</option>";
    }
} else {
    echo "<option disabled selected>No subcategories found!</option>";
}
$stmt->close();
?>

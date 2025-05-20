<?php
@include 'config.php';

if (isset($_POST['state_id'])) {
    $state_id = $_POST['state_id'];
    $cities = mysqli_query($conn, "SELECT * FROM tblcity WHERE State_ID = '$state_id' ORDER BY City_Name ASC");

    echo '<option value="">Select City</option>';
    while ($row = mysqli_fetch_assoc($cities)) {
        echo '<option value="' . $row['City_ID'] . '">' . $row['City_Name'] . '</option>';
    }
}
?>

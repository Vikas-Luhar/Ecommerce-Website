<?php
@include 'config.php';

if (isset($_POST['state_id'])) {
    $state_id = $_POST['state_id'];
    $cityQuery = "SELECT * FROM tblcity WHERE State_ID = '$state_id'";
    $cityResult = mysqli_query($conn, $cityQuery);

    echo '<option value="">Select City</option>';
    while ($row = mysqli_fetch_assoc($cityResult)) {
        echo '<option value="' . $row['City_ID'] . '">' . $row['City_Name'] . '</option>';
    }
}
?>

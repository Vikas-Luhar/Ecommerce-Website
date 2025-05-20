<?php
@include 'config.php';

if (isset($_POST['action'])) {
    if ($_POST['action'] == "getStates") {
        $states = [];
        $query = "SELECT * FROM tblstate";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            $states[] = $row;
        }

        echo json_encode($states);
        exit();
    }

    if ($_POST['action'] == "getCities" && isset($_POST['state_id'])) {
        $state_id = mysqli_real_escape_string($conn, $_POST['state_id']);
        $cities = [];
        
        $query = "SELECT * FROM tblcity WHERE State_ID = '$state_id'";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            $cities[] = $row;
        }

        echo json_encode($cities);
        exit();
    }
}
?>

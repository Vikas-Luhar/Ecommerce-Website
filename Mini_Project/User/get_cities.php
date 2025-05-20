<?php
include 'connection.php';

if (isset($_POST['state_id'])) {
    $state_id = $_POST['state_id'];

    $stmt = $conn->prepare("SELECT City_ID, City_Name FROM tblcity WHERE State_ID = ?");
    $stmt->bind_param("i", $state_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select City</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['City_ID'] . '">' . htmlspecialchars($row['City_Name']) . '</option>';
    }

    $stmt->close();
    $conn->close();
}
?>

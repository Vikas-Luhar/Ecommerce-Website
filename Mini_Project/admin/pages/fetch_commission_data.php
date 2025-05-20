<?php
include_once "../config/dbconnect.php";

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : "";

$query = "SELECT 
            ac.ID, 
            ac.Order_ID, 
            COALESCE(s.Seller_Name, 'Admin') AS Seller_Name, 
            ac.Commission_Percentage, 
            ac.Commission_Amount, 
            o.CreatedON 
          FROM admin_commission ac
          LEFT JOIN seller s ON ac.Seller_ID = s.Seller_ID
          JOIN tblorder o ON ac.Order_ID = o.Order_ID";

if (!empty($search)) {
    $query .= " WHERE s.Seller_Name LIKE '%$search%' OR ac.Order_ID LIKE '%$search%'";
}

$query .= " ORDER BY o.CreatedON DESC";
$result = $conn->query($query);

$output = "";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr>
            <td>{$row['ID']}</td>
            <td><span class='badge bg-info text-white'>{$row['Order_ID']}</span></td>
            <td>{$row['Seller_Name']}</td>
            <td><span class='badge bg-warning text-dark'>{$row['Commission_Percentage']}%</span></td>
            <td><span class='fw-bold text-success'>₹" . number_format($row['Commission_Amount'], 2) . "</span></td>
            <td>" . date('d M Y', strtotime($row['CreatedON'])) . "</td>
        </tr>";
    }
} else {
    $output .= "<tr><td colspan='6' class='text-center text-danger'>No Commission Records Found</td></tr>";
}

echo $output;
?>

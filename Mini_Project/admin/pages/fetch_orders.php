<?php
include_once "../config/dbconnect.php";

$search = isset($_GET['search']) ? $_GET['search'] : "";
$filter = isset($_GET['filter']) ? $_GET['filter'] : "";

$query = "SELECT 
    o.Order_ID, 
    u.name AS User_Name, 
    s.Seller_Name, 
    p.Product_Name AS Order_Name, 
    od.Quantity AS Seller_Order_Product, 
    od.Amount AS Product_Price,
    o.FinalAmount,
    o.CreatedON,
    od.Status AS Delivery_Status,
    a.Address_Text AS User_Address
FROM tblorder o
JOIN user_form u ON o.User_ID = u.user_id
JOIN order_details od ON o.Order_ID = od.Order_ID
JOIN product p ON od.product_id = p.Product_ID
JOIN seller s ON p.Seller_ID = s.Seller_ID
JOIN address a ON u.user_id = a.user_id
WHERE a.IsActive = 1";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query .= " AND (u.name LIKE '%$search%' OR p.Product_Name LIKE '%$search%')";
}

if (!empty($filter)) {
    $filter = $conn->real_escape_string($filter);
    $query .= " AND od.Status = '$filter'";
}

$query .= " ORDER BY o.CreatedON DESC";
$result = $conn->query($query);
?>

<style>
        html, body {
        margin: 0;
        padding: 0;
        overflow-x: hidden; /* Remove horizontal scroll */
        font-family: 'Segoe UI', sans-serif;
    }

    th {
        background-color: #f50257 !important;
        color: white !important;
        text-align: center;
        white-space: nowrap;
    }

    td {
        vertical-align: middle !important;
        text-align: center;
        white-space: nowrap;
    }

    td.wrap-text {
        white-space: normal !important;
        word-wrap: break-word !important;
        word-break: break-word !important;
        max-width: 200px;
    }

    table {
        width: 100%;
        table-layout: auto;
    }

    .form-select-sm {
        font-size: 0.85rem;
    }

    .container-fluid {
        padding: 15px;
        max-width: 100%;
        overflow-x: hidden;
    }

    .table-responsive {
        overflow-x: auto;
    }
    body {
        overflow-x: hidden;
    }
    .table td, .table th {
        word-wrap: break-word;
        max-width: 150px;
    }
    .status-dropdown {
        border-radius: 50px;
        padding: 5px 15px;
        width: 100%;
    }
    th {
    background-color: #f50257 !important;
    color: white !important;
    text-align: center;
    padding: 8px;
    white-space: normal !important; /* allow text wrapping */
    font-size: 0.85rem;
    word-break: break-word;
}

th i {
    margin-right: 5px;
    font-size: 0.9rem;
}

</style>
<div class="container-fluid">
    <div class="card-header mb-3 rounded" style="background-color: #f50257; color: white;">
        <h4 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Orders</h4>
    </div>

    <div class="table-responsive"> <!-- optional: for mobile fallback -->
    <table class="table table-bordered table-hover shadow-sm">
        <thead>
            <tr>
                <th><i class="fas fa-hashtag"></i> Order ID</th>
                <th><i class="fas fa-user"></i> User Name</th>
                <th><i class="fas fa-store"></i> Seller Name</th>
                <th><i class="fas fa-box-open"></i> Order Name</th>
                <th><i class="fas fa-boxes"></i> Quantity</th>
                <th><i class="fas fa-rupee-sign"></i> Price</th>
                <th><i class="fas fa-money-bill"></i> Total</th>
                <th><i class="fas fa-calendar-alt"></i> Order Date</th>
                <th><i class="fas fa-map-marker-alt"></i> Address</th>
                <th><i class="fas fa-info-circle"></i> Status</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $currentStatus = $row['Delivery_Status'];
                $orderId = $row['Order_ID'];

                $statusBadges = [
                    "Pending" => "bg-warning text-dark",
                    "Shipped" => "bg-info text-dark",
                    "Delivered" => "bg-success text-white",
                    "Cancelled" => "bg-danger text-white"
                ];

                echo "<tr>
                    <td>#{$row['Order_ID']}</td>
                    <td>{$row['User_Name']}</td>
                    <td>{$row['Seller_Name']}</td>
                    <td class='wrap-text'>{$row['Order_Name']}</td>
                    <td>{$row['Seller_Order_Product']}</td>
                    <td class='text-success'>₹{$row['Product_Price']}</td>
                    <td class='text-success'>₹{$row['FinalAmount']}</td>
                    <td>" . date("d M Y", strtotime($row['CreatedON'])) . "</td>
                    <td class='wrap-text'>{$row['User_Address']}</td>
                    <td>";

                if (in_array($currentStatus, ['Delivered', 'Cancelled'])) {
                    echo "<span class='badge {$statusBadges[$currentStatus]} px-3 py-2 rounded-pill'>
                            {$currentStatus}
                          </span>";
                } elseif ($currentStatus == 'Pending') {
                    echo "<select class='form-select form-select-sm border-warning text-dark' disabled 
                                style='min-width: 140px; border-radius: 50px; padding: 5px 15px;'>
                                <option selected>🟡 Pending</option>
                          </select>";
                } elseif ($currentStatus == 'Shipped') {
                    echo "<select class='form-select form-select-sm status-dropdown border-primary' 
                                data-order-id='$orderId' 
                                style='min-width: 140px; border-radius: 50px; padding: 5px 15px;'>
                                <option value='Shipped' selected>🚚 Shipped</option>
                                <option value='Delivered'>✅ Delivered</option>
                          </select>";
                }

                echo "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='10' class='text-center text-danger'>No orders found</td></tr>";
        }
        ?>
        </tbody>
    </table>
    </div>
</div>


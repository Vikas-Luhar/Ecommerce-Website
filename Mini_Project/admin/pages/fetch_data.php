<?php
include_once "../config/dbconnect.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["action"])) {
        if ($_POST["action"] === "fetch_products" && isset($_POST["seller_id"])) {
            $seller_id = intval($_POST["seller_id"]);
            $sql = "SELECT * FROM product WHERE Seller_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $seller_id);
            $stmt->execute();
            $result = $stmt->get_result();
        
            while ($product = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$product['Product_ID']}</td>
                        <td>{$product['Product_Name']}</td>
                        <td><img src='{$product['Image_URL']}' width='80'></td>
                        <td>\${$product['Price']}</td>
                        <td><button class='btn btn-primary viewOrders' data-product='{$product['Product_ID']}'>View Orders</button></td>
                     </tr>";
            }
            exit;
        }

        if ($_POST["action"] === "fetch_orders" && isset($_POST["product_id"])) {
            $product_id = intval($_POST["product_id"]);
            $sql = "SELECT o.Order_ID, o.User_ID, od.Quantity, o.TotalAmount, o.FinalAmount, o.CreatedON, od.Status 
                    FROM tblorder o 
                    INNER JOIN order_details od ON o.Order_ID = od.Order_ID 
                    WHERE od.Product_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
        
            while ($order = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$order['Order_ID']}</td>
                        <td>{$order['User_ID']}</td>
                        <td>{$order['Quantity']}</td>
                        <td>\${$order['TotalAmount']}</td>
                        <td>\${$order['FinalAmount']}</td>
                        <td>{$order['CreatedON']}</td>
                        <td><span class='badge badge-".($order['Status'] === 'Completed' ? 'success' : 'warning')."'>{$order['Status']}</span></td>
                     </tr>";
            }
            exit;
        }
    }
}
?>

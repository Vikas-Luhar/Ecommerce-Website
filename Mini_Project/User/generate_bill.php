<?php
require_once 'connection.php';

if (!isset($_GET['order_id'])) {
    die("Invalid request.");
}

$order_id = $_GET['order_id'];

// Fetch order data
$sql = "
SELECT o.Order_ID, o.TotalAmount, o.FinalAmount, o.CreatedON, 
       od.Product_ID, od.Quantity, od.Amount, 
       p.product_name, a.Address_Text, u.name, u.email
FROM tblorder o
JOIN order_details od ON o.Order_ID = od.Order_ID
JOIN product p ON od.Product_ID = p.Product_ID
JOIN address a ON o.Address_ID = a.Address_ID
JOIN user_form u ON o.User_ID = u.user_id
WHERE o.Order_ID = '$order_id'
";

$result = mysqli_query($conn, $sql);
if (!$result || mysqli_num_rows($result) == 0) {
    die("Order not found.");
}

$orderData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $orderData[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice - Order #<?php echo $order_id; ?></title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 40px;
      background: #f9f9f9;
    }

    .invoice-box {
      background: #fff;
      padding: 30px;
      border: 1px solid #ddd;
      max-width: 800px;
      margin: auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 5px;
    }

    h3 {
      text-align: center;
      margin-top: 0;
      color: #555;
    }

    p {
      margin: 5px 0;
    }

    .info {
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    table, th, td {
      border: 1px solid #ccc;
    }

    th, td {
      padding: 10px;
      text-align: left;
    }

    .totals {
      margin-top: 20px;
      font-weight: bold;
      text-align: right;
    }

    .download-btn {
      margin-top: 30px;
      text-align: center;
    }

    button {
      padding: 10px 20px;
      background: #28a745;
      border: none;
      color: #fff;
      cursor: pointer;
      font-size: 16px;
      border-radius: 5px;
    }

    button:hover {
      background: #218838;
    }

    @media print {
      .download-btn {
        display: none;
      }
    }
  </style>
</head>
<body>

<div class="invoice-box">
  <h2>Online Interior Shop</h2>
  <h3>Invoice - Order #<?php echo $order_id; ?></h3>

  <div class="info">
    <p><strong>Date:</strong> <?php echo $orderData[0]['CreatedON']; ?></p>
    <p><strong>Customer:</strong> <?php echo $orderData[0]['name']; ?> (<?php echo $orderData[0]['email']; ?>)</p>
    <p><strong>Address:</strong> <?php echo $orderData[0]['Address_Text']; ?></p>
  </div>

  <table>
    <thead>
      <tr>
        <th>Product</th>
        <th>Qty</th>
        <th>Price (₹)</th>
        <th>Subtotal (₹)</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($orderData as $item): ?>
        <tr>
          <td><?php echo htmlspecialchars($item['product_name']); ?></td>
          <td><?php echo $item['Quantity']; ?></td>
          <td><?php echo number_format($item['Amount'], 2); ?></td>
          <td><?php echo number_format($item['Amount'], 2); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="totals">
    <p>Total: ₹<?php echo number_format($orderData[0]['TotalAmount'], 2); ?></p>
    <p>Final Amount (after discount/tax): ₹<?php echo number_format($orderData[0]['FinalAmount'], 2); ?></p>
  </div>

  <div class="download-btn">
    <button onclick="window.print()">Download / Print PDF</button>
  </div>
</div>

</body>
</html>

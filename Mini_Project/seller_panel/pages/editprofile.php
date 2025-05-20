<?php
include_once "../config/dbconnect.php";

// Check if seller_id is set
if (!isset($_GET['seller_id'])) {
    echo "Invalid request!";
    exit;
}

$seller_id = $_GET['seller_id'];

// Fetch seller details
$query = "SELECT * FROM seller WHERE seller_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
$seller = $result->fetch_assoc();

if (!$seller) {
    echo "Seller not found!";
    exit;
}

// Handle form submission
if (isset($_POST['update'])) {
    $name = $_POST['Seller_Name'];
    $shop_name = $_POST['Seller_Shop_Name'];
    $shop_address = $_POST['Seller_Shop_Address'];
    $mobile_no = $_POST['Seller_Mobile_No'];

    if (!empty($_FILES['Seller_Shop_Logo']['name'])) {
        $logo = "./uploads/" . basename($_FILES['Seller_Shop_Logo']['name']);
        move_uploaded_file($_FILES['Seller_Shop_Logo']['tmp_name'], "../uploads/" . basename($_FILES['Seller_Shop_Logo']['name']));
    } else {
        $logo = $seller['Seller_Shop_Logo'];
    }

    $update_query = "UPDATE seller SET Seller_Name = ?, Seller_Shop_Name = ?, Seller_Shop_Address = ?, Seller_Mobile_No = ?, Seller_Shop_Logo = ? WHERE seller_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssssi", $name, $shop_name, $shop_address, $mobile_no, $logo, $seller_id);

    if ($stmt->execute()) {
        echo "<script>alert('Seller updated successfully!'); window.location='profile.php';</script>";
    } else {
        echo "Error updating seller: " . $conn->error;
    }

    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $file_name = basename($_FILES['images']['name'][$key]);
            $target_file = "./uploads/" . $file_name;

            if (move_uploaded_file($_FILES['images']['tmp_name'][$key], "../uploads/" . $file_name)) {
                $img_query = "INSERT INTO tblimages (Seller_ID, ImageURL) VALUES (?, ?)";
                $stmt = $conn->prepare($img_query);
                $stmt->bind_param("is", $seller_id, $target_file);
                $stmt->execute();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Seller</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap + Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            max-width: 600px;
            margin-top: 40px;
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        .form-label i {
            color: #198754;
            margin-right: 6px;
        }

        input.form-control, textarea.form-control {
            font-size: 14px;
            padding: 6px 10px;
            height: 36px;
        }

        textarea.form-control {
            height: auto;
        }

        .form-control {
            border-radius: 8px;
        }

        .btn {
            border-radius: 6px;
            padding: 6px 14px;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 4px;
        }

        img.w-150px {
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .title {
            text-align: center;
            font-weight: 600;
            margin-bottom: 20px;
            color: #198754;
        }
    </style>
</head>
<body>
<div class="container">
    <h4 class="title"><i class="bi bi-pencil-square"></i> Edit Seller</h4>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label"><i class="bi bi-person-circle"></i> Seller Name</label>
            <input type="text" name="Seller_Name" value="<?= htmlspecialchars($seller['Seller_Name']) ?>" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label"><i class="bi bi-shop"></i> Shop Name</label>
            <input type="text" name="Seller_Shop_Name" value="<?= htmlspecialchars($seller['Seller_Shop_Name']) ?>" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label"><i class="bi bi-geo-alt-fill"></i> Shop Address</label>
            <textarea name="Seller_Shop_Address" class="form-control" required><?= htmlspecialchars($seller['Seller_Shop_Address']) ?></textarea>
        </div>
        
        <div class="mb-3">
            <label class="form-label"><i class="bi bi-telephone-fill"></i> Mobile Number</label>
            <input type="text" name="Seller_Mobile_No" value="<?= htmlspecialchars($seller['Seller_Mobile_No']) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="bi bi-image-fill"></i> Current Shop Logo</label><br>
            <img src="../<?= htmlspecialchars($seller['Seller_Shop_Logo']) ?>" alt="Shop Logo" width="150px" class="w-150px shadow-sm">
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="bi bi-upload"></i> Upload New Shop Logo</label>
            <input type="file" name="Seller_Shop_Logo" class="form-control">
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" name="update" class="btn btn-success">
                <i class="bi bi-check-circle-fill"></i> Update
            </button>
            <a href="profile.php" class="btn btn-secondary">
                <i class="bi bi-x-circle-fill"></i> Cancel
            </a>
        </div>
    </form>
</div>
</body>
</html>

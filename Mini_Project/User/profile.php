<?php
session_start();
include 'connection.php';

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details along with state and city information
$query = "
    SELECT u.*, s.State_Name, c.City_Name 
    FROM user_form u
    LEFT JOIN tblstate s ON u.State_ID = s.State_ID
    LEFT JOIN tblcity c ON u.City_ID = c.City_ID
    WHERE u.user_id = '$user_id'
";
$result = mysqli_query($conn, $query);
// Fetch address from the address table (only 1 active address)
$address_query = "
    SELECT a.Address_Text, s.State_Name, c.City_Name 
    FROM address a
    LEFT JOIN tblstate s ON a.State_ID = s.State_ID
    LEFT JOIN tblcity c ON a.City_ID = c.City_ID
    WHERE a.user_id = '$user_id' AND a.IsActive = 1
    LIMIT 1
";
$address_result = mysqli_query($conn, $address_query);

if ($address_row = mysqli_fetch_assoc($address_result)) {
    $address = $address_row['Address_Text'];
    $state_name = $address_row['State_Name'];
    $city_name = $address_row['City_Name'];
} else {
    $address = "No address found.";
    $state_name = "N/A";
    $city_name = "N/A";
}
if ($row = mysqli_fetch_assoc($result)) {
    $user_name = $row['name'];
    $user_email = $row['email'];
    $phone = $row['phone'];
    // $address = $row['address'];
    $profile_image = $row['profile_image'] ? "images/{$row['profile_image']}" : "images/image.jpg"; // Default image if none foun
    $state_name = $row['State_Name'];
    $city_name = $row['City_Name'];
} else {
    echo "User not found!";
    exit();
}
// Handle profile image upload
if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === 0) {
    $img_name = $_FILES['profile_img']['name'];
    $img_tmp = $_FILES['profile_img']['tmp_name'];
    $img_ext = pathinfo($img_name, PATHINFO_EXTENSION);
    $allowed = ['jpg', 'jpeg', 'png', 'gif','avif'];

    if (in_array(strtolower($img_ext), $allowed)) {
        $new_name = 'user_' . $user_id . '_' . time() . '.' . $img_ext;
        $upload_path = "images/" . $new_name;

        if (move_uploaded_file($img_tmp, $upload_path)) {
            // Update DB
            $update_img = "UPDATE user_form SET profile_image = ? WHERE user_id = ?";
            $stmt = $conn->prepare($update_img);
            $stmt->bind_param("si", $new_name, $user_id);
            $stmt->execute();
            $stmt->close();
            
            // Reload to update image
            header("Location: profile.php");
            exit();
        } else {
            echo "<script>alert('Failed to upload image');</script>";
        }
    } else {
        echo "<script>alert('Only JPG, JPEG, PNG & GIF allowed');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="User-css/profile.css"> <!-- Custom CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            font-family: 'Segoe UI', sans-serif;
        }
        .profile-card {
            max-width: 400px;
            margin: 60px auto;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
        }
        .profile-card img {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border: 4px solid #0d6efd;
            transition: transform 0.3s ease;
        }
        .profile-card img:hover {
            transform: scale(1.05);
        }
        .profile-card h2 {
            font-weight: 600;
        }
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 12px;
            margin-top: 20px;
            text-align: left;
        }
        .info-box p {
            margin: 8px 0;
            font-size: 15px;
        }
        .btn-group a {
            flex: 1;
            margin: 0 5px;
        }
        .back-btn {
    transition: all 0.3s ease;
    font-weight: 500;
}
.back-btn:hover {
    background-color: #0d6efd;
    color: white;
    transform: translateX(-2px);
}
    </style>
</head>
<body>

<div class="profile-card">
<div class="text-start mb-3">
    <a href="user.php" class="btn btn-light shadow-sm back-btn">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

    <form method="POST" action="" enctype="multipart/form-data">
    <label for="profile_img" style="cursor:pointer;">
        <img src="<?= htmlspecialchars($profile_image); ?>" class="rounded-circle mb-3" alt="User Avatar">
        <p class="text-muted small">(Click image to change)</p>
    </label>
    <input type="file" name="profile_img" id="profile_img" style="display: none;" onchange="this.form.submit()">
</form>

    <h2><?= htmlspecialchars($user_name); ?></h2>
    <p class="text-muted"><?= htmlspecialchars($user_email); ?></p>

    <div class="info-box">
        <p><strong>📞 Phone:</strong> <?= htmlspecialchars($phone); ?></p>
        <p><strong>📍 Address:</strong> <?= htmlspecialchars($address); ?></p>
        <p><strong>🌆 City:</strong> <?= htmlspecialchars($city_name); ?></p>
        <p><strong>🗺️ State:</strong> <?= htmlspecialchars($state_name); ?></p>
    </div>

    <div class="btn-group d-flex justify-content-between mt-4">
        <a href="edit_profile.php" class="btn btn-outline-primary">Edit</a>
        <a href="change_password.php" class="btn btn-outline-secondary">Change Password</a>
        <a href="../logout.php" class="btn btn-outline-danger">Logout</a>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

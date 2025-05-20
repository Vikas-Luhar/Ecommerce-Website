<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user basic details
$sql = "SELECT name, email, phone FROM user_form WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($current_name, $current_email, $current_phone);
$stmt->fetch();
$stmt->close();

// Fetch address details from address table
$address_query = "
    SELECT Address_ID, Address_Text, State_ID, City_ID 
    FROM address 
    WHERE user_id = ? AND IsActive = 1 
    LIMIT 1";
$stmt = $conn->prepare($address_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($address_id, $current_address, $current_state_id, $current_city_id);
$stmt->fetch();
$stmt->close();

// Fetch states
$sql_states = "SELECT State_ID, State_Name FROM tblstate";
$result_states = $conn->query($sql_states);

// Fetch cities based on current state
$sql_cities = "SELECT City_ID, City_Name FROM tblcity WHERE State_ID = ?";
$stmt_cities = $conn->prepare($sql_cities);
$stmt_cities->bind_param("i", $current_state_id);
$stmt_cities->execute();
$result_cities = $stmt_cities->get_result();

// Handle form update
if (isset($_POST['Update'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $new_address = $_POST['address'];
    $new_state_id = $_POST['state_id'];
    $new_city_id = $_POST['city_id'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email');</script>";
        exit();
    }
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        echo "<script>alert('Invalid phone number');</script>";
        exit();
    }

    $stmt = $conn->prepare("UPDATE user_form SET name=?, email=?, phone=?, State_ID=?, City_ID=? WHERE user_id=?");
    $stmt->bind_param("sssiii", $username, $email, $phone, $new_state_id, $new_city_id, $user_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("UPDATE address SET Address_Text=?, State_ID=?, City_ID=? WHERE Address_ID=?");
    $stmt->bind_param("siii", $new_address, $new_state_id, $new_city_id, $address_id);

    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('Failed to update address');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background-image: url("../admin/images/img-grid-1.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            font-family: 'Segoe UI', sans-serif;
            color: #333;
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            max-width: 450px;
            margin: 80px auto;
            padding: 30px;
        }
        .form-label {
            font-weight: 600;
        }
        .form-control {
            border-radius: 8px;
        }
        .input-group-text {
            background-color: #f1f1f1;
            border-right: 0;
            border-radius: 8px 0 0 8px;
        }
        .input-group .form-control {
            border-left: 0;
            border-radius: 0 8px 8px 0;
        }
        .btn-success {
            background-color: #198754;
            border-color: #198754;
        }
        .btn-success:hover {
            background-color: #157347;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

<div class="card">
    <h3 class="text-center mb-4 text-success"><i class="bi bi-pencil-square"></i> Edit Profile</h3>
    <form method="POST" action="edit_profile.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($current_name); ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope-at"></i></span>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($current_email); ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                <input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($current_phone); ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                <input type="text" name="address" id="address" class="form-control" value="<?php echo htmlspecialchars($current_address); ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="state_id" class="form-label">State</label>
            <select name="state_id" id="state_id" class="form-select" required>
                <option value="">Select State</option>
                <?php while ($row = $result_states->fetch_assoc()) { ?>
                    <option value="<?php echo $row['State_ID']; ?>" <?php echo ($row['State_ID'] == $current_state_id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['State_Name']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-4">
            <label for="city_id" class="form-label">City</label>
            <select name="city_id" id="city_id" class="form-select" required>
                <option value="">Select City</option>
                <?php while ($row = $result_cities->fetch_assoc()) { ?>
                    <option value="<?php echo $row['City_ID']; ?>" <?php echo ($row['City_ID'] == $current_city_id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['City_Name']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" name="Update" class="btn btn-success">
                <i class="bi bi-check-circle-fill"></i> Update
            </button>
            <a href="./profile.php" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Cancel
            </a>
        </div>
    </form>
</div>

<!-- jQuery + AJAX for city change -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#state_id').on('change', function () {
            var stateID = $(this).val();
            if (stateID) {
                $.ajax({
                    type: 'POST',
                    url: 'get_cities.php',
                    data: { state_id: stateID },
                    success: function (html) {
                        $('#city_id').html(html);
                    }
                });
            } else {
                $('#city_id').html('<option value="">Select City</option>');
            }
        });
    });
</script>
</body>
</html>

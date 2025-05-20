<?php

include('connection.php');

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = md5($_POST['current_password']); // Hash the entered current password using MD5
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];
    $user_email = $_SESSION['user_email']; // Get the logged-in user's email from session

    // Check if the current password matches the one in the database
    $query = "SELECT password FROM user_form WHERE email = ?";
    $stmt = $conn->prepare($query);  // $conn must be initialized properly in config.php
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $user['password'] === $current_password) {
        // Check if new password and confirm password match
        if ($new_password === $confirm_new_password) {
            $hashed_new_password = md5($new_password); // Hash the new password

            // Update the password in the database
            $update_query = "UPDATE user_form SET password = ? WHERE email = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ss", $hashed_new_password, $user_email);
            if ($stmt->execute()) {
                $success = "Password updated successfully!";
            } else {
                $error = "Something went wrong, please try again.";
            }
        } else {
            $error = "New password and confirm password do not match!";
        }
    } else {
        $error = "Incorrect current password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>

    <!-- jQuery CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Custom CSS file link -->
    <style>
        /* Your existing CSS styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #395B49; /* Dark green background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        h3 {
            margin-bottom: 20px;
            color: #333;
        }

        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"].form-btn {
            width: 100%;
            background-color: #3c9b3c; /* Green color for button */
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"].form-btn:hover {
            background-color: #348a34; /* Darker green on hover */
        }

        a {
            color: #333;
            text-decoration: none;
            font-size: 14px;
            margin-top: 20px;
            display: inline-block;
        }

        a:hover {
            text-decoration: underline;
        }

        .error-msg, .success-msg {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            color: white;
            padding: 10px;
            border-radius: 5px;
        }

        .error-msg {
            background-color: #ff4d4d; /* Red for error */
        }

        .success-msg {
            background-color: #4CAF50; /* Green for success */
        }

        /* Additional styling for the buttons to match the screenshot */
        button.logout-btn {
            background-color: #ff4d4d; /* Red color */
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.logout-btn:hover {
            background-color: #e04040; /* Darker red on hover */
        }
    </style>
</head>
<body>

<div class="form-container">

    <form action="" method="post" id="passwordForm">
        <h3>Change Password</h3>

        <?php
        if (isset($error)) {
            echo '<span class="error-msg">' . $error . '</span>';
        }
        if (isset($success)) {
            echo '<span class="success-msg">' . $success . '</span>';
        }
        ?>

        <input type="password" name="current_password" id="current_password" required placeholder="Enter current password">
        <input type="password" name="new_password" id="new_password" required placeholder="Enter new password">
        <input type="password" name="confirm_new_password" id="confirm_new_password" required placeholder="Confirm new password">
        <input type="submit" name="submit" value="Change Password" class="form-btn">
        <p><a href="profile.php">Back to Profile</a></p>
    </form>

</div>

<!-- jQuery Validation -->
<script>
// Password Validation
$("#txtPwd").blur(function() {
    let val = this.value;

    // New regex pattern for password validation
    let pat = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/;

    // Check if the password meets the criteria
    if (!pat.test(val)) {
        $("#passwordError").text("Password must be at least 6 characters long, contain at least one letter and one number.");
        passwordValid = false;
    } else {
        $("#passwordError").text("");
        passwordValid = true;
    }

    // Debugging line to ensure the validation works as expected
    console.log("Password Valid: ", passwordValid);
});

</script>

</body>
</html>

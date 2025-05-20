<?php
@include 'connection.php';

$success_message = "";
$error_message = "";

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Check if the email exists in the database
    $query = "SELECT * FROM user_form WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $error_message = "Invalid email. Please request a new password reset.";
    } else {
        $user = $result->fetch_assoc();
        $current_password = $user['password']; // Fetch current password from database
    }

    // Handle password reset
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $new_password = $_POST['new_password'];
        $confirm_new_password = $_POST['confirm_new_password'];

        // Hash the new password for comparison (use the same hashing mechanism)
        $hashed_new_password = md5($new_password); // Consider using password_hash() instead

        // Check if the new password is the same as the current password
        if ($hashed_new_password === $current_password) {
            $error_message = "The new password cannot be the same as your current password!";
        } elseif ($new_password === $confirm_new_password) {
            // If passwords match and new password is not the same as the current password, update it
            $update_query = "UPDATE user_form SET password = ? WHERE email = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ss", $hashed_new_password, $email);
            if ($stmt->execute()) {
                // Redirect to the login page after successful password update
                header("Location: index.php");
                exit(); // Stop further script execution
            } else {
                $error_message = "Failed to update password. Please try again.";
            }
        } else {
            $error_message = "New passwords do not match!";
        }
    }
} else {
    $error_message = "No email provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            outline: none;
            border: none;
            text-decoration: none;
        }

        body {
            background-color: #f3f3f3;
        }

        .form-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f3f3f3;
            padding: 20px;
        }

        .form-container form {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .form-container form h3 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
            text-transform: uppercase;
        }

        .form-container form input[type="password"],
        .form-container form input[type="email"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            background-color: #f9f9f9;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            color: #333;
        }

        .form-container form button.form-btn {
            width: 100%;
            background-color: #503B32;
            color: #fff;
            padding: 12px;
            font-size: 18px;
            font-weight: 500;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container form button.form-btn:hover {
            background-color: #503B32;
        }

        .message-container {
            margin-bottom: 15px;
            text-align: center;
        }

        .success-msg,
        .error-msg {
            font-size: 16px;
            padding: 10px;
            border-radius: 5px;
            max-width: 350px;
            margin: 0 auto 20px auto;
        }

        .success-msg {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error-msg {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
<div class="form-container">
    <form action="" method="post">
        <div class="message-container">
            <?php
            if (!empty($error_message)) {
                echo "<p class='error-msg'>$error_message</p>";
            }
            ?>
        </div>

        <h3>Reset Password</h3>
        <input type="password" name="new_password" required placeholder="Enter new password">
        <input type="password" name="confirm_new_password" required placeholder="Confirm new password">
        <button type="submit" class="form-btn">Reset Password</button>
    </form>
</div>
</body>
</html>

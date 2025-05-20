<?php
@include 'connection.php';

$error_message = '';  // Variable to store error message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $query = "SELECT * FROM user_form WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email exists, now check if the user is an admin
        $user = $result->fetch_assoc();
        
        if ($user['user_type'] === 'admin') {
            // Email exists and user is admin, redirect to reset password page
            header("Location: admin-reset.php?email=" . urlencode($email));
            exit();
        } else {
            // User exists but is not an admin
            $error_message = "No account found with that email address.";
        }
    } else {
        // No account found with that email address
        $error_message = "No account found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/forgot.css">
    <!-- <link rel="stylesheet" href="css/style.css"> -->
</head>
<body>

<div class="form-container">
    <form action="" method="POST">
        <h3>Forgot Password</h3>
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit" class="form-btn">Check Email</button>

        <!-- Display error message if the email is not found -->
        <?php
        if (!empty($error_message)) {
            echo "<p class='error-msg'>$error_message</p>";
        }
        ?>

        <p>Remember your password? <a href="index.php">Login now</a></p>
    </form>
</div>

</body>
</html>
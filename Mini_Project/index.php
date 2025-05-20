<?php  
session_start(); 
@include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {    
    $email = mysqli_real_escape_string($conn, $_POST['email']);    
    $password = md5($_POST['password']); // Hash the entered password using MD5     

    // Query to check if the user exists and password matches    
    $query = "SELECT * FROM user_form WHERE email = ? AND password = ?";    
    $stmt = $conn->prepare($query);    
    $stmt->bind_param("ss", $email, $password);    
    $stmt->execute();    
    $result = $stmt->get_result();     

    if ($result->num_rows > 0) {        
        $user = $result->fetch_assoc();        
        // Check if the user is active        
        if ($user['is_active'] == 0) {            
            $error[] = 'Your account has some problem, please Wait.'; // Deactivated user message
        } else {
            // Store user info in session        
            $_SESSION['user_id'] = $user['user_id']; // Store user_id in session        
            $_SESSION['user_name'] = $user['name'];        
            $_SESSION['user_email'] = $user['email'];        

            // Check if a product was stored before login        
            if (isset($_SESSION['cart_temp'])) {            
                $productId = $_SESSION['cart_temp'];            
                $stmt = $conn->prepare("INSERT INTO tblcart (User_ID, Product_ID, Quantity) VALUES (?, ?, 1)");            
                $stmt->bind_param("ii", $user['user_id'], $productId);            
                $stmt->execute();            
                unset($_SESSION['cart_temp']); // Clear temp session        
            }        

            // Redirect to a protected page (e.g., User dashboard)        
            header("Location: User/user.php");        
            exit();
        }
    } else {        
        // Invalid credentials        
        $error[] = 'Incorrect email or password!';    
    } 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="User/css/style.css">
</head>
<body>

<div class="form-container">

    <form action="" method="post">
        <h3>Login Now</h3>
        <?php
        if (isset($error)) {
            foreach ($error as $err) {
                echo '<span class="error-msg">' . $err . '</span>';
            }
        }
        ?>
        <input type="email" name="email" required placeholder="Enter your email">
        <input type="password" name="password" required placeholder="Enter your password">
        
        <!-- Forgot password link -->
        <div class="forgot-password">
            <a href="forgot-password.php">Forgot your password?</a> <!-- Link to Forgot Password page -->
        </div>
        
        <input type="submit" name="submit" value="Login Now" class="form-btn">
        <p>Don't have an account? <a href="register_form.php">Register now</a></p>
    </form>

</div>

</body>
</html>
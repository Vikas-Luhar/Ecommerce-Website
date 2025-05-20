<?php
include_once "./config/dbconnect.php";

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $cpassword = password_hash($_POST['cpassword'], PASSWORD_DEFAULT);
    $user_type = $_POST['user_type'];

    // Check if 'address' column exists in your table before using it
    $address = isset($_POST['address']) ? mysqli_real_escape_string($conn, $_POST['address']) : '';

    // Check if the email already exists
    $select = "SELECT * FROM user_form WHERE email = '$email'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $error[] = 'User already exists!';
    } else {
        // Fix variable names in password check
        if ($_POST['password'] !== $_POST['cpassword']) {
            $error[] = 'Passwords do not match!';
        } else {
            // Check if 'address' column exists before including it in the query
            $insert = "INSERT INTO user_form(name, email, password, phone, user_type";
            
            if (!empty($address)) {
                $insert .= ", address";
            }

            $insert .= ") VALUES('$name','$email','$password','$phone','$user_type'";

            if (!empty($address)) {
                $insert .= ",'$address'";
            }

            $insert .= ")";

            if (mysqli_query($conn, $insert)) {
                header('location:index.php');
                exit();
            } else {
                $error[] = 'Registration failed! Please try again.';
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Registration Form</title>

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="User-css/login.css">

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>

<div class="form-container">
   <form id="registerForm" action="" method="post">
      <h3>Register as Admin</h3>
      <?php
      if (isset($error)) {
         foreach ($error as $error) {
            echo '<span class="error-msg-1">'.$error.'</span>';
         }
      }
      ?>
      <input id="Name" type="text" name="name" required placeholder="Enter your name">
      <span id="nameError" class="error-msg-1"></span>
    
      <input id="Email" type="email" name="email" required placeholder="Enter your email">
      <span id="emailError" class="error-msg-1"></span>
    
      <input id="txtPwd" type="password" name="password" required placeholder="Enter your password">
      <span id="passwordError" class="error-msg-1"></span>
    
      <input id="txtCPwd" type="password" name="cpassword" required placeholder="Confirm your password">
      <span id="confirmPasswordError" class="error-msg-1"></span>

      <input id="Address" type="text" name="address" required placeholder="Enter your address">
      <span id="addressError" class="error-msg-1"></span>

      <input id="Phone" type="text" name="phone" required placeholder="Enter your phone number">
      <span id="phoneError" class="error-msg-1"></span>
    
      <input type="hidden" name="user_type" value="admin"> <!-- Default user type is admin -->

      <input type="submit" name="submit" value="Register Now" class="form-btn">
      <p>Already have an account? <a href="index.php">Login now</a></p>
   </form>
</div>

<script>
$(document).ready(function() {
    let nameValid = false;
    let emailValid = false;
    let passwordValid = false;
    let confirmPasswordValid = false;
    let addressValid = false;
    let phoneValid = false;

    // Name Validation
    $("#Name").blur(function() {
        let val = this.value;
        let pat = /^[A-Za-z]{3,}$/;
        if (!pat.test(val)) {
            $("#nameError").text("Name is not valid. It should contain at least 3 alphabetic characters.");
            nameValid = false;
        } else {
            $("#nameError").text("");
            nameValid = true;
        }
    });

    // Email Validation
    $("#Email").blur(function() {
        let val = this.value;
        let pat = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!pat.test(val)) {
            $("#emailError").text("Please enter a valid email address.");
            emailValid = false;
        } else {
            $("#emailError").text("");
            emailValid = true;
        }
    });

    // Password Validation
    $("#txtPwd").blur(function() {
        let val = this.value;
        let pat = /^[a-zA-Z0-9]{6,}$/;
        if (!pat.test(val)) {
            $("#passwordError").text("Password must be at least 6 characters long and contain only letters and numbers.");
            passwordValid = false;
        } else {
            $("#passwordError").text("");
            passwordValid = true;
        }
    });

    // Confirm Password Validation
    $("#txtCPwd").blur(function() {
        let txtPwd = $("#txtPwd").val();
        let txtCPwd = this.value;
        if (txtPwd != txtCPwd) {
            $("#confirmPasswordError").text("Passwords do not match.");
            confirmPasswordValid = false;
        } else {
            $("#confirmPasswordError").text("");
            confirmPasswordValid = true;
        }
    });


    // Address Validation
    $("#Address").blur(function() {
        let val = this.value;
        if (val.length < 5) {
            $("#addressError").text("Address must be at least 5 characters long.");
            addressValid = false;
        } else {
            $("#addressError").text("");
            addressValid = true;
        }
    });

    // Phone Number Validation
    $("#Phone").blur(function() {
        let val = this.value;
        let pat = /^[0-9]{10}$/; // For 10-digit phone number
        if (!pat.test(val)) {
            $("#phoneError").text("Phone number must be 10 digits.");
            phoneValid = false;
        } else {
            $("#phoneError").text("");
            phoneValid = true;
        }
    });

});
</script>

</body>
</html>
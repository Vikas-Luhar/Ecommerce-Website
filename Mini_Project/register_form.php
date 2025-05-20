<?php
@include 'config.php';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $pass = md5($_POST['password']);
    $cpass = md5($_POST['cpassword']);
    $user_type = $_POST['user_type'];
    $state_id = $_POST['state'];
    $city_id = $_POST['city'];
    $address_tag = mysqli_real_escape_string($conn, $_POST['address_tag']);
    $address_text = mysqli_real_escape_string($conn, $_POST['address_text']);
    $is_active = 1;

    $select = "SELECT * FROM user_form WHERE email = '$email' OR phone = '$phone'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $error[] = 'User already exists!';
    } elseif ($pass != $cpass) {
        $error[] = 'Passwords do not match!';
    } else {
        $insert = "INSERT INTO user_form (name, email, phone, password, user_type, State_ID, City_ID) 
                   VALUES ('$name', '$email', '$phone', '$pass', '$user_type', '$state_id', '$city_id')";
        if (mysqli_query($conn, $insert)) {
            $user_id = mysqli_insert_id($conn);
            $insert_address = "INSERT INTO address (user_id, Address_Tag, Address_Text, State_ID, City_ID, IsActive) 
                               VALUES ('$user_id', '$address_tag', '$address_text', '$state_id', '$city_id', '$is_active')";
            mysqli_query($conn, $insert_address);
            header('Location: index.php');
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

$states = mysqli_query($conn, "SELECT * FROM tblstate ORDER BY State_Name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="User/css/style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>

<div class="form-container">
    <form id="registerForm" action="" method="post">
        <h3>Register Now</h3>
        <?php
        if (isset($error)) {
            foreach ($error as $error) {
                echo '<span class="error-msg-1">' . $error . '</span>';
            }
        }
        ?>
        <input id="Name" type="text" name="name" required placeholder="Enter your name">
        <span id="nameError" class="error-msg-1"></span>

        <input id="Email" type="email" name="email" required placeholder="Enter your email">
        <span id="emailError" class="error-msg-1"></span>

        <input id="Phone" type="text" name="phone" required placeholder="Enter your phone number">
        <span id="phoneError" class="error-msg-1"></span>

        <input id="AddressText" type="text" name="address_text" required placeholder="Enter your address">
        <span id="addressError" class="error-msg-1"></span>
        <select id="AddressTag" name="address_tag" required>
        <option value="">Select Address Tag</option>
        <option value="Home">Home</option>
        <option value="Office">Office</option>
    </select>
    <span id="addressTagError" class="error-msg-1"></span>
        <input id="txtPwd" type="password" name="password" required placeholder="Enter your password">
        <span id="passwordError" class="error-msg-1"></span>

        <input id="txtCPwd" type="password" name="cpassword" required placeholder="Confirm your password">
        <span id="confirmPasswordError" class="error-msg-1"></span>
        <select name="state" id="stateDropdown" required>
            <option value="">Select State</option>
            <?php while ($row = mysqli_fetch_assoc($states)) { ?>
                <option value="<?= $row['State_ID'] ?>"><?= $row['State_Name'] ?></option>
            <?php } ?>
        </select><br>

        <select name="city" id="cityDropdown" required>
            <option value="">Select City</option>
        </select><br>

<!-- Hidden Inputs for New State/City (if needed) -->
<input type="hidden" name="state_name" id="state_name" value="">
<input type="hidden" name="city_name" id="city_name" value="">

<span id="cityError" class="error-msg-1"></span>
        <input type="hidden" name="user_type" value="user">
        <input type="submit" name="submit" value="Register Now" class="form-btn">
        <p>Already have an account? <a href="index.php">Login now</a></p>
    </form>
</div>

<script>
$(document).ready(function() {
    let nameValid = false;
    let emailValid = false;
    let phoneValid = false;  // Phone number validation
    let addressValid = false; // Address validation
    let passwordValid = false;
    let confirmPasswordValid = false;

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

    // Phone Number Validation
    $("#Phone").blur(function() {
        let val = this.value;
        let pat = /^[0-9]{10}$/; // Validates 10-digit phone number
        if (!pat.test(val)) {
            $("#phoneError").text("Please enter a valid 10-digit phone number.");
            phoneValid = false;
        } else {
            $("#phoneError").text("");
            phoneValid = true;
        }
    });

    // Password Validation
    $("#txtPwd").blur(function() {
        let val = this.value;
        let pat = /^[a-zA-Z0-9]{6,}$/; // At least 6 characters, one letter, one number
        if (!pat.test(val)) {
            $("#passwordError").text("Password must be at least 6 characters long and contain at least one letter and one number.");
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
        if (txtPwd !== txtCPwd) {
            $("#confirmPasswordError").text("Passwords do not match.");
            confirmPasswordValid = false;
        } else {
            $("#confirmPasswordError").text("");
            confirmPasswordValid = true;
        }
    });
    let addressTagValid = false;
    let addressTextValid = false;

    // Address Tag Validation
    $("#AddressTag").blur(function() {
        let val = this.value;
        if (val.length < 3) {  // Check if address tag is at least 3 characters
            $("#addressTagError").text("Address tag should be at least 3 characters long.");
            addressTagValid = false;
        } else {
            $("#addressTagError").text("");
            addressTagValid = true;
        }
    });

    // Address Text Validation
    $("#AddressText").blur(function() {
        let val = this.value;
        if (val.length < 10) {  // Check if address text is at least 10 characters
            $("#addressTextError").text("Address should be at least 10 characters long.");
            addressTextValid = false;
        } else {
            $("#addressTextError").text("");
            addressTextValid = true;
        }
    });

    // Form Submission Validation
    $("form").submit(function(event) {
        if (!addressTagValid || !addressTextValid) {
            event.preventDefault();  // Prevent form submission if validation fails
            alert("Please correct the errors in the form.");
        }
    });
});
    $(document).ready(function() {
        $('#stateDropdown').change(function() {
            var stateID = $(this).val();
            if (stateID) {
                $.ajax({
                    type: 'POST',
                    url: 'fetch_cities.php',
                    data: { state_id: stateID },
                    success: function(response) {
                        $('#cityDropdown').html(response);
                    }
                });
            } else {
                $('#cityDropdown').html('<option value="">Select City</option>');
            }
        });
    });
    </script>
</body>
</html>

</body>
</html>
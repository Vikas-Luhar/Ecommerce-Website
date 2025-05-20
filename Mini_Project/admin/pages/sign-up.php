<?php
// Include connection file
include_once "../config/dbconnect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['Admin_Name']) ? mysqli_real_escape_string($conn, $_POST['Admin_Name']) : '';
    $email = isset($_POST['Email_ID']) ? mysqli_real_escape_string($conn, $_POST['Email_ID']) : '';
    $phone = isset($_POST['phone']) ? mysqli_real_escape_string($conn, $_POST['phone']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $cpassword = isset($_POST['cpassword']) ? $_POST['cpassword'] : '';

    $error = [];

    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($cpassword)) {
        $error[] = 'All fields are required!';
    }

    if ($password != $cpassword) {
        $error[] = 'Passwords do not match!';
    }

    if (empty($error)) {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE Email_ID = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error[] = 'User already exists!';
        } else {
            $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO admin (Admin_Name, Email_ID, password, phone) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $passwordHashed, $phone);
            if ($stmt->execute()) {
                header('Location: sign-in.php');
                exit();
            } else {
                $error[] = 'Something went wrong. Please try again.';
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <title>
    Admin Sign-up
  </title>
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  <style>
/* General Input Field Styling */
.input-group {
    position: relative;
    width: 100%;
}

.form-control {
    border-radius: 5px;
    padding-right: 40px; /* Space for error icon */
    border: 1px solid #ced4da;
    transition: all 0.3s ease-in-out;
}

/* Highlight input fields when there is an error */
.form-control.error {
    border: 2px solid #e74c3c !important;
    background: #fef0f0;
}

/* Success Styling */
.form-control.success {
    border: 2px solid #2ecc71 !important;
    background: #f0fff0;
}

/* Error Message Styling */
.error-msg-1 {
    font-size: 12px;
    color: #e74c3c;
    font-weight: bold;
    margin-top: 5px; /* Space between input field and error message */
    display: none; /* Initially hidden */
}

/* Error Icon Styling */
.error-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    color: #e74c3c;
    display: none; /* Initially hidden */
}

/* Make sure error messages are shown when an input field has an error */
.form-control.error ~ .error-msg-1 {
    display: block;
}

/* Show error icon when there is an error */
.form-control.error ~ .error-icon {
    display: inline;
}

/* Responsive Fix for Small Screens */
@media (max-width: 768px) {
    .error-msg-1 {
        font-size: 10px;
        right: 5px;
    }
    .error-icon {
        font-size: 14px;
        right: 5px;
    }
}

  </style>
</head>

<body class="">
 
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
              <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center" style="background-image: url('../assets/img/illustrations/illustration-signup.jpg'); background-size: cover;">
              </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
              <div class="card card-plain">
                <div class="card-header">
                  <h4 class="font-weight-bolder">Sign Up</h4>
                  <p class="mb-0">Enter your email and password to register</p>
                </div>

                <div class="card-body">
                  <form role="form" id="registerForm" action="" method="post">
                    <?php if (!empty($error)): ?>
                        <div class="error">
                            <?php foreach ($error as $err): ?>
                                <p><?php echo $err; ?></p>
                            <?php endforeach; ?>
                        </div>  
                    <?php endif; ?>
                    
                    <div class="input-group input-group-outline mb-3">
                      <input id="Name" class="form-control" type="text" name="Admin_Name" required placeholder="Name">
                      <span id="nameError" class="error-msg-1"></span>
                    </div>

                    <div class="input-group input-group-outline mb-3">
                      <input id="Email" class="form-control" type="email" name="Email_ID" required placeholder="Email">
                      <span id="emailError" class="error-msg-1"></span>
                    </div>

                    <div class="input-group input-group-outline mb-3">
                      <input id="txtPwd" class="form-control" type="password" name="password" required placeholder="Password">
                      <span id="passwordError" class="error-msg-1"></span>
                    </div>

                    <div class="input-group input-group-outline mb-3">
                      <input id="txtCPwd" class="form-control" type="password" name="cpassword" required placeholder="Confirm password">
                      <span id="confirmPasswordError" class="error-msg-1"></span>
                    </div>

                    <!-- <div class="input-group input-group-outline mb-3">
                      <input id="Address" class="form-control" type="text" name="Address" required placeholder="Address">
                      <span id="addressError" class="error-msg-1"></span>
                    </div> -->

                    <div class="input-group input-group-outline mb-3">
                      <input id="Phone" class="form-control" type="text" name="phone" required placeholder="Phone number">
                      <span id="phoneError" class="error-msg-1"></span>
                    </div>

                    <div class="text-center">
                      <button type="submit" name="button" value="sign up" class="btn btn-lg bg-gradient-dark btn-lg w-100 mt-4 mb-0">Sign Up</button>
                    </div>

                  </form>
                </div>
                
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-2 text-sm mx-auto">
                    Already have an account?
                    <a href="../pages/sign-in.php" class="text-primary text-gradient font-weight-bold">Sign in</a>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <script>
$(document).ready(function() {
    function showError(input, message) {
        let errorSpan = $("#" + input.attr("id") + "Error");
        errorSpan.text(message).show();
        input.addClass("error");
    }

    function clearError(input) {
        let errorSpan = $("#" + input.attr("id") + "Error");
        errorSpan.text("").hide();
        input.removeClass("error");
    }

    function validateName() {
        let input = $("#Name");
        let val = input.val().trim();
        let pat = /^[A-Za-z\s]{3,}$/;
        
        if (val === "") {
            showError(input, "Name is required.");
            return false;
        } else if (!pat.test(val)) {
            showError(input, "Name must contain at least 3 alphabetic characters.");
            return false;
        } else {
            clearError(input);
            return true;
        }
    }

    function validateEmail() {
        let input = $("#Email");
        let val = input.val().trim();
        let pat = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        
        if (val === "") {
            showError(input, "Email is required.");
            return false;
        } else if (!pat.test(val)) {
            showError(input, "Enter a valid email address.");
            return false;
        } else {
            clearError(input);
            return true;
        }
    }

    function validatePhone() {
        let input = $("#Phone");
        let val = input.val().trim();
        let pat = /^[0-9]{10}$/;

        if (val === "") {
            showError(input, "Phone number is required.");
            return false;
        } else if (!pat.test(val)) {
            showError(input, "Phone number must be 10 digits.");
            return false;
        } else {
            clearError(input);
            return true;
        }
    }

    function validatePassword() {
        let input = $("#txtPwd");
        let val = input.val().trim();

        if (val === "") {
            showError(input, "Password is required.");
            return false;
        } else if (val.length < 6) {
            showError(input, "Password must be at least 6 characters long.");
            return false;
        } else {
            clearError(input);
            return true;
        }
    }

    function validateConfirmPassword() {
        let input = $("#txtCPwd");
        let val = input.val().trim();
        let password = $("#txtPwd").val().trim();

        if (val === "") {
            showError(input, "Confirm password is required.");
            return false;
        } else if (val !== password) {
            showError(input, "Passwords do not match.");
            return false;
        } else {
            clearError(input);
            return true;
        }
    }

    // Attach blur event to inputs
    $("#Name").blur(validateName);
    $("#Email").blur(validateEmail);
    $("#Phone").blur(validatePhone);
    $("#txtPwd").blur(validatePassword);
    $("#txtCPwd").blur(validateConfirmPassword);

    $("#registerForm").submit(function(e) {
        let isValid = validateName() && validateEmail() && validatePhone() && validatePassword() && validateConfirmPassword();
        
        if (!isValid) {
            e.preventDefault(); // Prevent form submission
            alert("Please fix the errors before submitting the form.");
        }
    });
}); 
function showError(input, message) {
    let errorSpan = $("#" + input.attr("id") + "Error");
    errorSpan.text(message).show(); // Show error message
    input.addClass("error");
    input.siblings(".error-icon").show(); // Show error icon
}

function clearError(input) {
    let errorSpan = $("#" + input.attr("id") + "Error");
    errorSpan.text("").hide(); // Hide error message
    input.removeClass("error");
    input.siblings(".error-icon").hide(); // Hide error icon
}

  </script>
</body>

</html>

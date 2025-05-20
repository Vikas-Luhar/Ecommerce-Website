<?php
// Include connection file
include_once "../config/dbconnect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = []; // Initialize error array

    // Sanitize and trim input data
    $name = isset($_POST['Seller_Name']) ? mysqli_real_escape_string($conn, trim($_POST['Seller_Name'])) : '';
    $email = isset($_POST['Email_ID']) ? trim($_POST['Email_ID']) : '';
    $phone = isset($_POST['Seller_Mobile_No']) ? mysqli_real_escape_string($conn, trim($_POST['Seller_Mobile_No'])) : '';
    $shopname = isset($_POST['Seller_Shop_Name']) ? mysqli_real_escape_string($conn, trim($_POST['Seller_Shop_Name'])) : '';
    $shopaddress = isset($_POST['Seller_Shop_Address']) ? mysqli_real_escape_string($conn, trim($_POST['Seller_Shop_Address'])) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $cpassword = isset($_POST['cpassword']) ? trim($_POST['cpassword']) : '';

    // File upload setup
    $uploadDir = "../uploads/"; // Folder where images will be stored
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create directory if not exists
    }

    $logoPath = ""; // Initialize logo path

    if (!empty($_FILES['Seller_Shop_Logo']['name'])) {
        $logoName = $_FILES['Seller_Shop_Logo']['name'];
        $logoTmpName = $_FILES['Seller_Shop_Logo']['tmp_name'];
        $logoSize = $_FILES['Seller_Shop_Logo']['size'];
        $logoError = $_FILES['Seller_Shop_Logo']['error'];
        $logoExt = strtolower(pathinfo($logoName, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($logoExt, $allowed)) {
            $error[] = "❌ Error: Only JPG, JPEG, PNG, and GIF files are allowed.";
        } elseif ($logoSize > 2 * 1024 * 1024) { // 2MB limit
            $error[] = "❌ Error: File size must be less than 2MB.";
        } elseif ($logoError === 0) {
            $newLogoName = time() . "_" . basename($logoName); // Unique filename
            $logoPath = $uploadDir . $newLogoName; // Full path for saving

            if (!move_uploaded_file($logoTmpName, $logoPath)) {
                $error[] = "❌ Error: File upload failed. Please check folder permissions.";
            } else {
                // Debug: Check if the file exists after upload
                if (!file_exists($logoPath)) {
                    $error[] = "⚠️ Warning: File uploaded but not found in directory.";
                }
            }
        } else {
            $error[] = "❌ Error: Unexpected file upload error. Code: " . $logoError;
        }
    } else {
        $error[] = "⚠️ Warning: No logo uploaded. Please select a logo.";
    }

    // Validation
    if (empty($name) || empty($email) || empty($phone) || empty($shopname) || empty($shopaddress) || empty($password) || empty($cpassword)) {
        $error[] = '❌ Error: All fields are required!';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = '❌ Error: Invalid email format!';
    }

    if ($password !== $cpassword) {
        $error[] = '❌ Error: Passwords do not match!';
    }

    if (empty($error)) {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT Email_Id FROM seller WHERE Email_Id = ?");
        if (!$stmt) {
            die("❌ Error: Query failed - " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error[] = '❌ Error: User already exists!';
            $stmt->close();
        } else {
            $stmt->close();

            // Insert new user
            $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO seller (Seller_Name, Email_Id, password, Seller_Mobile_No, Seller_Shop_Name, Seller_Shop_Address, Seller_Shop_Logo) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");

            if (!$stmt) {
                die("❌ Error: Insert query failed - " . $conn->error);
            }

            $stmt->bind_param("sssssss", $name, $email, $passwordHashed, $phone, $shopname, $shopaddress, $logoPath);

            if ($stmt->execute()) {
                $stmt->close();
                header('Location: ./sign-in.php');
                exit();
            } else {
                $error[] = '❌ Error: Something went wrong. Please try again.';
                $stmt->close();
            }
        }
    }
}

// Show error messages
if (!empty($error)) {
    foreach ($error as $err) {
        echo "<p style='color:red;'>$err</p>";
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
  <title>
       Seller Sign-Up
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>

<body class="">
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
              <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center" style="background-image: url('../assets/img/illustrations/illustration-reset.jpg'); background-size: cover;">
              </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
              <div class="card card-plain">
                <div class="card-header">
                  <h4 class="font-weight-bolder">Seller Sign Up</h4>
                  <p class="mb-0">Enter your Detail to register</p>
                </div>
                <div class="card-body">
                  
                <form role="form" id="registerForm" action="" method="post" enctype="multipart/form-data">
                    <?php if (!empty($error)): ?>
                        <div class="error">
                            <?php foreach ($error as $err): ?>
                                <p><?php echo $err; ?></p>
                            <?php endforeach; ?>
                        </div>  
                    <?php endif; ?>

                  <div class="input-group input-group-outline mb-3">
                      <input id="Name" class="form-control" type="text" name="Seller_Name" required placeholder="Name">
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

                    <div class="input-group input-group-outline mb-3">
                      <input id="shopname" class="form-control" type="text" name="Seller_Shop_Name" required placeholder="Shop name">
                      <span id="shopnameError" class="error-msg-1"></span>
                    </div>

                    <div class="input-group input-group-outline mb-3">
                      <input id="shopaddress" class="form-control" type="text" name="Seller_Shop_Address" required placeholder="Shop Address">
                      <span id="shopaddressError" class="error-msg-1"></span>
                    </div>

                    <div class="input-group input-group-outline mb-3">
                      <input id="Phone" class="form-control" type="text" name="Seller_Mobile_No" required placeholder="Phone number">
                      <span id="phoneError" class="error-msg-1"></span>
                    </div>
                    
                    <div class="mb-3">
    <label for="Seller_Shop_Logo" class="form-label d-block">Seller Logo</label>
    <input type="file" class="form-control" name="Seller_Shop_Logo" id="Seller_Shop_Logo" required>
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
        function validateName() {
            let val = $("#Name").val();
            let pat = /^[A-Za-z]{3,}$/;
            if (!pat.test(val)) {
                $("#nameError").text("Name must contain at least 3 alphabetic characters.");
                return false;
            } else {
                $("#nameError").text("");
                return true;
            }
        }

        function validateEmail() {
            let val = $("#Email").val();
            let pat = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!pat.test(val)) {
                $("#emailError").text("Please enter a valid email address.");
                return false;
            } else {
                $("#emailError").text("");
                return true;
            }
        }

        function validatePhone() {
            let val = $("#Phone").val();
            let pat = /^[0-9]{10}$/;
            if (!pat.test(val)) {
                $("#phoneError").text("Phone number must be 10 digits.");
                return false;
            } else {
                $("#phoneError").text("");
                return true;
            }
        }

        function validatePassword() {
            let val = $("#txtPwd").val();
            if (val.length < 6) {
                $("#passwordError").text("Password must be at least 6 characters long.");
                return false;
            } else {
                $("#passwordError").text("");
                return true;
            }
        }

        function validateConfirmPassword() {
            let txtPwd = $("#txtPwd").val();
            let txtCPwd = $("#txtCPwd").val();
            if (txtPwd !== txtCPwd) {
                $("#confirmPasswordError").text("Passwords do not match.");
                return false;
            } else {
                $("#confirmPasswordError").text("");
                return true;
            }
        }

        function validateshopName() {
            let val = $("#shopName").val();
            let pat = /^[A-Za-z]{2,}$/;
            if (!pat.test(val)) {
                $("#shopnameError").text("Shop Name must contain at least 2 alphabetic characters.");
                return false;
            } else {
                $("#shopnameError").text("");
                return true;
            }
        }

        function validateshopaddress() {
            let val = $("#shopaddress").val();
            let pat = /^[A-Za-z]{3,}$/;
            if (!pat.test(val)) {
                $("#shopaddressError").text("Shop Name must contain at least 3 alphabetic characters.");
                return false;
            } else {
                $("#shopaddressError").text("");
                return true;
            }
        }

        $("#Name").blur(validateName);
        $("#Email").blur(validateEmail);
        $("#Phone").blur(validatePhone);
        $("#txtPwd").blur(validatePassword);
        $("#txtCPwd").blur(validateConfirmPassword);
        $("#shopName").blur(validateshopName);
        $("#shopaddress").blur(validateshopaddress);


        $("#registerForm").submit(function(e) {
            let isValid = validateName() && validateEmail() && validatePhone() && validatePassword() && validateConfirmPassword() && validateshopName() && validateshopaddress();
            if (!isValid) {
                e.preventDefault();
                alert("Please fix the errors before submitting the form.");
            }
        });
    });
  </script>

  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
 
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
</body>

</html>
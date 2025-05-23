        <?php
    include_once "../config/dbconnect.php";
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = trim($_POST['email']);

        // Check if email exists
        $query = "SELECT Admin_ID FROM admin WHERE Email_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $_SESSION['reset_email'] = $email; // Store email in session
            header("Location: reset-password.php");
            exit();
        } else {
            $message = "<p class='text-danger text-center'>Email not found!</p>";
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
            Admin Login
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

        <body class="bg-gray-200">
        <div class="container position-sticky z-index-sticky top-0">
            <div class="row">
            <div class="col-12">
                <!-- Navbar -->
                <!-- <nav class="navbar navbar-expand-lg blur border-radius-xl top-0 z-index-3 shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
                <div class="container-fluid ps-2 pe-0">
                    <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="../pages/dashboard.html">
                    Material Dashboard 3
                    </a>
                    <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon mt-2">
                        <span class="navbar-toggler-bar bar1"></span>
                        <span class="navbar-toggler-bar bar2"></span>
                        <span class="navbar-toggler-bar bar3"></span>
                    </span>
                    </button>
                    <div class="collapse navbar-collapse" id="navigation">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                        <a class="nav-link d-flex align-items-center me-2 active" aria-current="page" href="../pages/dashboard.html">
                            <i class="fa fa-chart-pie opacity-6 text-dark me-1"></i>
                            Dashboard
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link me-2" href="../pages/profile.html">
                            <i class="fa fa-user opacity-6 text-dark me-1"></i>
                            Profile
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link me-2" href="../pages/sign-up.html">
                            <i class="fas fa-user-circle opacity-6 text-dark me-1"></i>
                            Sign Up
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link me-2" href="../pages/sign-in.html">
                            <i class="fas fa-key opacity-6 text-dark me-1"></i>
                            Sign In
                        </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav d-lg-flex d-none">
                        <li class="nav-item d-flex align-items-center">
                        <a class="btn btn-outline-primary btn-sm mb-0 me-2" target="_blank" href="https://www.creative-tim.com/builder?ref=navbar-material-dashboard">Online Builder</a>
                        </li>
                        <li class="nav-item">
                        <a href="https://www.creative-tim.com/product/material-dashboard" class="btn btn-sm mb-0 me-1 bg-gradient-dark">Free download</a>
                        </li>
                    </ul>
                    </div>
                </div>
                </nav> -->
                <!-- End Navbar -->
            </div>
            </div>
        </div>
        <main class="main-content  mt-0">
            <div class="page-header align-items-start min-vh-100" style="background-image: url(https://cdn.pixabay.com/photo/2017/06/14/08/20/map-of-the-world-2401458_1280.jpg);">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container my-auto">
                <div class="row">
                <div class="col-lg-4 col-md-8 col-12 mx-auto">
                    <div class="card z-index-0 fadeIn3 fadeInBottom">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                        <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Forgot Password</h4>
                        <div class="row mt-3">
                            <div class="col-2 text-center ms-auto">
                            <a class="btn btn-link px-3" href="javascript:;">
                                <i class="fa fa-facebook text-white text-lg"></i>
                            </a>
                            </div>
                            <div class="col-2 text-center px-1">
                            <a class="btn btn-link px-3" href="javascript:;">
                                <i class="fa fa-github text-white text-lg"></i>
                            </a>
                            </div>
                            <div class="col-2 text-center me-auto">
                            <a class="btn btn-link px-3" href="javascript:;">
                                <i class="fa fa-google text-white text-lg"></i>
                            </a>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="card-body">
                    <?php if (!empty($message)) echo $message; ?> <!-- Display error message -->
            <form method="POST" >
            <div class="input-group input-group-outline my-4 w-100 mx-auto">
        <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
    </div>

                
                <button type="submit" class="btn btn-primary mt-3 w-100">Confirm </button>
            </form>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </main>
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
        <!-- Github buttons -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
        <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
        </body>

        </html>
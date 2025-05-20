<?php
session_start();

// If admin session is not set, redirect to sign-in page
if (!isset($_SESSION['admin_id'])) {
  header("Location: sign-in.php");
  exit();
}
include "../config/dbconnect.php";
$sql = "SELECT COUNT(*) AS total_users FROM user_form;";
$sql2 = "SELECT COUNT(*) AS total_order FROM tblorder;";
$sql3 = "SELECT COUNT(*) AS total_seller FROM seller";
// $sql4 = "SELECT DATE_FORMAT('%Y-%m') AS Month, SUM(FinalAmount) AS totalsales FROM tblorder GROUP BY Month ORDER BY Month;";
// $sql3= "SELECT DATE_FORMAT(CreatedON, '%Y-%m') AS month, 
//        SUM(FinalAmount) AS total_sales
// FROM tblorder
// GROUP BY month
// ORDER BY month;";

$result = $conn->query($sql);
$result2 = $conn->query($sql2);
$result3 = $conn->query($sql3);
// $result4 = $conn->query($sql4);


// Check if query executed successfully
if (!$result) {
  die("Query error: " . $conn->error);
}

// Fetch result as an associative array
$row = $result->fetch_assoc();
$row1 = $result2->fetch_assoc();
$row2 = $result3->fetch_assoc();
// $row3 = $result4->fetch_assoc();
// echo "tblorder:" . $row1['total_order'];
// echo "Total Users: " . $row['total_users']; 

// if ($row) {
//     echo "Total Users: " . $row['total_users']; // Correct way to access data
// } else {
//     echo "Error: No data returned.";
// }

// Close the connection
// echo "totalsales". $row['totalsales'];

// Monthly sales from admin_commission (same as total commission logic)
$sales_query = "
    SELECT DATE_FORMAT(CreatedON, '%b') AS order_month, SUM(Commission_Amount) AS total_sales
    FROM admin_commission
    WHERE YEAR(CreatedON) = YEAR(CURRENT_DATE())  -- Ensure only current year data
    GROUP BY order_month
    ORDER BY STR_TO_DATE(order_month, '%b') ASC
";

$result = $conn->query($sales_query);

// Predefined months (Jan–Dec)
$all_months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
$sales_data = array_fill_keys($all_months, 0); // Default all months to 0 sales

// Store results in array (overwrite default 0 values if sales exist)
while ($row4 = $result->fetch_assoc()) {
  $sales_data[$row4['order_month']] = $row4['total_sales'];
}

// Convert to JSON for JavaScript
$months_json = json_encode(array_keys($sales_data));
$sales_json = json_encode(array_values($sales_data));
// Fetch daily sales (only for the current month) from admin_commission table
$sales_query = "SELECT DAYOFWEEK(CreatedON) AS weekday, SUM(Commission_Amount) AS total_sales
                FROM admin_commission
                WHERE MONTH(CreatedON) = MONTH(CURRENT_DATE()) 
                AND YEAR(CreatedON) = YEAR(CURRENT_DATE()) 
                GROUP BY DAYOFWEEK(CreatedON)
                ORDER BY DAYOFWEEK(CreatedON) ASC";

$result = $conn->query($sales_query);

// Define the weekday labels (Sunday = 1, Saturday = 7 in MySQL DAYOFWEEK)
$weekdays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

// Initialize an array for daily sales, starting with zero sales for each day of the week
$daily_sales_data = array_fill(0, 7, 0);  // Index 0 = Sunday, Index 6 = Saturday

// Store the results in the array, mapping the weekdays
while ($row5 = $result->fetch_assoc()) {
    // MySQL DAYOFWEEK returns 1 for Sunday, 7 for Saturday
    $weekday_index = $row5['weekday'] - 1;  // Adjust to match our $weekdays array (0 = Sunday)
    $daily_sales_data[$weekday_index] = $row5['total_sales'];
}

// Prepare the data for JavaScript
// Convert the weekday labels (first letter) and daily sales to JSON format
$weekdays_json = json_encode(array_map(function($day) { return substr($day, 0, 1); }, $weekdays));  // First letter of each weekday
$daily_sales_json = json_encode($daily_sales_data);  // Sales data for each weekday

// Optional: Debug output to check the generated data
// echo $weekdays_json;
// echo $daily_sales_json;



$total_commission = 0;
$stmt = $conn->prepare("
    SELECT SUM(Commission_Amount) 
    FROM admin_commission 
    WHERE MONTH(CreatedON) = MONTH(CURRENT_DATE()) 
    AND YEAR(CreatedON) = YEAR(CURRENT_DATE())
");
$stmt->execute();
$stmt->bind_result($total_commission);
$stmt->fetch();
$stmt->close();

$admin_id = $_SESSION['admin_id']; // Fetch admin name from session
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>
    admin Panel - Dashboard
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

<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
    <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand px-4 py-3 m-0" href="dashboard.php">
      <!-- <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">  -->
      <i class="fa-solid text-xxl fa-user-tie"></i>
      <span class="ms-1 text-xxl fw-bold text-dark">Admin page</span>
    </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active bg-gradient-dark text-white" href="../pages/dashboard.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/view-details.php">
            <i class="fa-solid fa-store"></i>
            <span class="nav-link-text ms-1">Seller Details</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/order.php">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Orders</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/userdetail.php">
            <i class="fa-solid fa-users"></i>
            <span class="nav-link-text ms-1">User Details</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/category.php">
            <i class="material-symbols-rounded opacity-5">view_in_ar</i>
            <span class="nav-link-text ms-1">Category</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/feedback.php">
            <i class="material-symbols-rounded opacity-5">view_in_ar</i>
            <span class="nav-link-text ms-1">User Feedback</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/admin_commission.php">
            <i class="material-symbols-rounded opacity-5">view_in_ar</i>
            <span class="nav-link-text ms-1">Earning</span>
          </a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/notifications.html">
            <i class="material-symbols-rounded opacity-5">notifications</i>
            <span class="nav-link-text ms-1"></span>
          </a>
        </li> -->
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Account pages</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/profile.php">
            <i class="material-symbols-rounded opacity-5">person</i>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/logout.php">
            <i class="material-symbols-rounded opacity-5">login</i>
            <span class="nav-link-text ms-1">Log Out</span>
          </a>
        </li>
        <button class="btn btn-primary mt-3" onclick="(new bootstrap.Modal(document.getElementById('adminReportModal'))).show()">
  📊 View Admin Report
</button>
        <!-- <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/sign-up.html">
            <i class="material-symbols-rounded opacity-5">assignment</i>
            <span class="nav-link-text ms-1">Sign Up</span>
          </a>
        </li>
      </ul>
    </div> -->
        <!-- <div class="sidenav-footer position-absolute w-100 bottom-0 ">
      <div class="mx-3">
        <a class="btn btn-outline-dark mt-4 w-100" href="https://www.creative-tim.com/learning-lab/bootstrap/overview/material-dashboard?ref=sidebarfree" type="button">Documentation</a>
        <a class="btn bg-gradient-dark w-100" href="https://www.creative-tim.com/product/material-dashboard-pro?ref=sidebarfree" type="button">Upgrade to pro</a>
      </div>
    </div> -->
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
          </ol>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <!-- <div class="input-group input-group-outline">
              <label class="form-label">Type here...</label>
              <input type="text" class="form-control">
            </div> -->
          </div>
          <ul class="navbar-nav d-flex align-items-center  justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <!-- <a class="btn btn-outline-primary btn-sm mb-0 me-3" target="_blank" href="https://www.creative-tim.com/builder?ref=navbar-material-dashboard">Online Builder</a> -->
            </li>
            <!-- <li class="mt-1">
              <a class="github-button" href="https://github.com/creativetimofficial/material-dashboard" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star creativetimofficial/material-dashboard on GitHub">Star</a>
            </li> -->
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
            <li class="nav-item px-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0">
                <!-- <i class="material-symbols-rounded fixed-plugin-button-nav">settings</i> -->
              </a>
            </li>
            <!-- <li class="nav-item dropdown pe-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="material-symbols-rounded">notifications</i>
              </a>
              <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="my-auto">
                        <img src="../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          <span class="font-weight-bold">New message</span> from Laur
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          13 minutes ago
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="my-auto">
                        <img src="../assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark  me-3 ">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          <span class="font-weight-bold">New album</span> by Travis Scott
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          1 day
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                          <title>credit-card</title>
                          <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                              <g transform="translate(1716.000000, 291.000000)">
                                <g transform="translate(453.000000, 454.000000)">
                                  <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                  <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                </g>
                              </g>
                            </g>
                          </g>
                        </svg>
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          Payment successfully completed
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          2 days
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
              </ul>
            </li> -->
            <li class="nav-item d-flex align-items-center">
              <a href="../pages/profile.php" class="nav-link text-body font-weight-bold px-0">
                <i class="material-symbols-rounded">account_circle</i>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-3">
          <h3 class="mb-0 h4 font-weight-bolder">Dashboard</h3>
          <p class="mb-4">
            Check the sales, value and bounce rate by country.
          </p>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-2 ps-3">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="text-sm mb-0 text-capitalize">Total Order</p>
                  <h4 class="mb-0"><?php echo "" . $row1['total_order']; ?></h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                  <i class="material-symbols-rounded opacity-10">weekend</i>
                </div>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
              <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+55% </span>than last week</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-2 ps-3">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="text-sm mb-0 text-capitalize">Total Users</p>
                  <h4 class="mb-0"><?php echo "" . $row['total_users'];   ?></h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                  <i class="material-symbols-rounded opacity-10">person</i>
                </div>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
              <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+3% </span>than last month</p>
            </div>
          </div>
        </div>
      
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-header p-2 ps-3">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="text-sm mb-0 text-capitalize">Total Seller</p>
                  <h4 class="mb-0"><?php echo "" . $row2['total_seller']; ?></h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                  <i class="material-symbols-rounded opacity-10">weekend</i>
                </div>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
              <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+5% </span>than yesterday</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-header p-2 ps-3">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="text-sm mb-0 text-capitalize">This Month's Commission</p>
                  <h4 class="mb-0">₹<?= number_format($total_commission, 2) ?></h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                  <i class="material-symbols-rounded opacity-10">weekend</i>
                </div>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
              <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+5% </span>than yesterday</p>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4 col-md-6 mt-4 mb-4">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-0 ">Monthly Sales</h6>
              <p class="text-sm ">Sales Performance by Day</p>
              <div class="pe-2">
                <div class="chart">
                  <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
                </div>
              </div>
              <hr class="dark horizontal">
              <div class="d-flex ">
                <i class="material-symbols-rounded text-sm my-auto me-1">schedule</i>
                <p class="mb-0 text-sm"> campaign sent 2 days ago </p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mt-4 mb-4">
          <div class="card ">
            <div class="card-body">
              <h6 class="mb-0 "> Daily Sales </h6>
              <p class="text-sm "> (<span class="font-weight-bolder">+15%</span>) increase in today sales. </p>
              <div class="pe-2">
                <div class="chart">
                  <canvas id="chart-line" class="chart-canvas" height="170"></canvas>
                </div>
              </div>
              <hr class="dark horizontal">
              <div class="d-flex ">
                <i class="material-symbols-rounded text-sm my-auto me-1">schedule</i>
                <p class="mb-0 text-sm"> updated 4 min ago </p>
              </div>
            </div>
          </div>
        </div>
    </div>
  </main>
  <div class="fixed-plugin">
    <!-- <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="material-symbols-rounded py-2">settings</i>
    </a> -->
    <div class="card shadow-lg">
      <div class="card-header pb-0 pt-3">
        <div class="float-start">
          <h5 class="mt-3 mb-0">Material UI Configurator</h5>
          <p>See our dashboard options.</p>
        </div>
        <div class="float-end mt-4">
          <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
            <i class="material-symbols-rounded">clear</i>
          </button>
        </div>
        <!-- End Toggle Button -->
      </div>
      <hr class="horizontal dark my-1">
      <div class="card-body pt-sm-3 pt-0">
        <!-- Sidebar Backgrounds -->
        <div>
          <h6 class="mb-0">Sidebar Colors</h6>
        </div>
        <a href="javascript:void(0)" class="switch-trigger background-color">
          <div class="badge-colors my-2 text-start">
            <span class="badge filter bg-gradient-primary" data-color="primary" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-dark active" data-color="dark" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
          </div>
        </a>
        <!-- Sidenav Type -->
        <div class="mt-3">
          <h6 class="mb-0">Sidenav Type</h6>
          <p class="text-sm">Choose between different sidenav types.</p>
        </div>
        <div class="d-flex">
          <button class="btn bg-gradient-dark px-3 mb-2" data-class="bg-gradient-dark" onclick="sidebarType(this)">Dark</button>
          <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-transparent" onclick="sidebarType(this)">Transparent</button>
          <button class="btn bg-gradient-dark px-3 mb-2  active ms-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
        </div>
        <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
        <!-- Navbar Fixed -->
        <div class="mt-3 d-flex">
          <h6 class="mb-0">Navbar Fixed</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
          </div>
        </div>
        <hr class="horizontal dark my-3">
        <div class="mt-2 d-flex">
          <h6 class="mb-0">Light / Dark</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
          </div>
        </div>
        <hr class="horizontal dark my-sm-4">
        <!-- <a class="btn bg-gradient-info w-100" href="https://www.creative-tim.com/product/material-dashboard-pro">Free Download</a>
          <a class="btn btn-outline-dark w-100" href="https://www.creative-tim.com/learning-lab/bootstrap/overview/material-dashboard">View documentation</a> -->
        <div class="w-100 text-center">
          <a class="github-button" href="https://github.com/creativetimofficial/material-dashboard" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star creativetimofficial/material-dashboard on GitHub">Star</a>
          <h6 class="mt-3">Thank you for sharing!</h6>
          <a href="https://twitter.com/intent/tweet?text=Check%20Material%20UI%20Dashboard%20made%20by%20%40CreativeTim%20%23webdesign%20%23dashboard%20%23bootstrap5&amp;url=https%3A%2F%2Fwww.creative-tim.com%2Fproduct%2Fsoft-ui-dashboard" class="btn btn-dark mb-0 me-2" target="_blank">
            <i class="fab fa-twitter me-1" aria-hidden="true"></i> Tweet
          </a>
          <a href="https://www.facebook.com/sharer/sharer.php?u=https://www.creative-tim.com/product/material-dashboard" class="btn btn-dark mb-0 me-2" target="_blank">
            <i class="fab fa-facebook-square me-1" aria-hidden="true"></i> Share
          </a>
        </div>
      </div>
    </div>
  </div>
<!-- Admin Report Modal -->
<div class="modal fade" id="adminReportModal" tabindex="-1" aria-labelledby="adminReportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content shadow rounded-4">
      <div class="modal-header bg-dark text-white rounded-top-4">
        <h5 class="modal-title" id="adminReportModalLabel">🔎 Admin Dashboard Report</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row g-3">
          <div class="col-12">
            <div class="border rounded p-3 bg-light">
              <h6 class="mb-1 text-muted">👥 Total Users</h6>
              <h4 class="fw-bold text-primary"><?= $row['total_users'] ?></h4>
            </div>
          </div>
          <div class="col-12">
            <div class="border rounded p-3 bg-light">
              <h6 class="mb-1 text-muted">📦 Total Orders</h6>
              <h4 class="fw-bold text-success"><?= $row1['total_order'] ?></h4>
            </div>
          </div>
          <div class="col-12">
            <div class="border rounded p-3 bg-light">
              <h6 class="mb-1 text-muted">🧑‍💼 Total Sellers</h6>
              <h4 class="fw-bold text-warning"><?= $row2['total_seller'] ?></h4>
            </div>
          </div>
          <div class="col-12">
            <div class="border rounded p-3 bg-light">
              <h6 class="mb-1 text-muted">💰 This Month's Commission</h6>
              <h4 class="fw-bold text-danger">₹<?= number_format($total_commission, 2) ?></h4>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-end p-3">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


 <!--   Core JS Files   -->
 <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/chartjs.min.js"></script><script>
$(document).ready(function() {
  $('#salesReportModal').on('show.bs.modal', function () {
    // Load the report via AJAX
    $("#reportContent").html(
      <div class="text-center">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="text-muted">Fetching report...</p>
      </div>
    );

    $.ajax({
      url: '../fetch_sales_report.php', // Update path accordingly
      method: 'GET',
      success: function(data) {
        $("#reportContent").html(data);
      },
      error: function(xhr, status, error) {
        $("#reportContent").html('<div class="text-danger">❌ Failed to load report. Please try again.</div>');
      }
    });
  });
});
</script>
  <script>
    var months = <?php echo $months_json; ?>;
    var sales = <?php echo $sales_json; ?>;

    var ctx = document.getElementById("chart-bars").getContext("2d");

    new Chart(ctx, {
      type: "bar", // Changed from bar to line
      data: {
        labels: ["J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D"],
        datasets: [{
          label: "Sales (₹)",
          tension: 0.4,
          borderWidth: 3, // Makes line more visible
          borderColor: "#43A047",
          backgroundColor: "rgba(67, 160, 71, 0.2)",
          fill: true, // Adds a light background under the line
          data: sales,
          pointRadius: 6, // Make points larger
          pointStyle: 'triangle', // Changes points to arrows
          pointRotation: 180, // Rotates triangles to point downward
          pointBackgroundColor: "#43A047",
          pointBorderColor: "#fff"
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            min: 0,
            suggestedMax: Math.max(...sales) + 5000, // Adjust max value dynamically
            grid: {
              drawBorder: false,
              drawOnChartArea: true,
              borderDash: [5, 5],
              color: '#e5e5e5'
            },
            ticks: {
              padding: 10,
              color: "#737373",
              font: {
                size: 14,
                lineHeight: 2
              }
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#737373',
              padding: 10,
              font: {
                size: 14,
                lineHeight: 2
              }
            }
          },
        },
      },
    });
  </script>
  </script>
  <script>
var weekdays = <?php echo $weekdays_json; ?>;  // Weekday labels (e.g., ["S", "M", "T", "W", "T", "F", "S"])
var daily_sales = <?php echo $daily_sales_json; ?>;  // Sales data for each weekday

var ctx = document.getElementById("chart-line").getContext("2d");

new Chart(ctx, {
  type: "line",
  data: {
    labels: weekdays,  // ["S", "M", "T", "W", "T", "F", "S"]
    datasets: [{
      label: "Daily Sales (₹)",
      tension: 0,  // No curve in the line (straight line)
      borderWidth: 2,
      pointRadius: 3,
      pointBackgroundColor: "#43A047",
      pointBorderColor: "transparent",
      borderColor: "#43A047",
      backgroundColor: "transparent",
      fill: true,  // Fill area under the line
      data: daily_sales,  // Sales data for each weekday
    }],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false,  // Hide the legend
      },
      tooltip: {
        callbacks: {
          title: function(context) {
            // Display full weekday names in the tooltip
            const fullDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            return fullDays[context[0].dataIndex];  // Show full day name on hover
          },
          label: function(context) {
            return `₹${context.parsed.y}`;  // Format the sales amount in ₹
          }
        }
      }
    },
    interaction: {
      intersect: false,
      mode: 'index',
    },
    scales: {
      y: {
        grid: {
          drawBorder: false,
          display: true,
          drawOnChartArea: true,
          drawTicks: false,
          borderDash: [4, 4],
          color: '#e5e5e5'
        },
        ticks: {
          display: true,
          color: '#737373',
          padding: 10,
          font: {
            size: 12,
            lineHeight: 2
          },
        }
      },
      x: {
        grid: {
          drawBorder: false,
          display: false,
          drawOnChartArea: false,
          drawTicks: false,
          borderDash: [5, 5]
        },
        ticks: {
          display: true,
          color: '#737373',
          padding: 10,
          font: {
            size: 12,
            lineHeight: 2
          },
        }
      },
    },
  },
});
  </script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const monthLabels = <?= $months_json ?>;
  const monthData = <?= $sales_json ?>;
  const dayLabels = <?= $days_json ?>;
  const dayData = <?= $daily_sales_json ?>;

  const monthlyCtx = document.getElementById('monthlySalesChart').getContext('2d');
  new Chart(monthlyCtx, {
    type: 'bar',
    data: {
      labels: monthLabels,
      datasets: [{
        label: 'Monthly Sales (₹)',
        data: monthData,
        backgroundColor: '#0d6efd'
      }]
    }
  });

  const dailyCtx = document.getElementById('dailySalesChart').getContext('2d');
  new Chart(dailyCtx, {
    type: 'line',
    data: {
      labels: dayLabels,
      datasets: [{
        label: 'Daily Sales (₹)',
        data: dayData,
        borderColor: '#198754',
        backgroundColor: 'rgba(25,135,84,0.1)',
        fill: true
      }]
    }
  });
</script>
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
<?php
session_start();
include_once "./config/dbconnect.php";

// Fetch total users (excluding admins)
$sql_users = "SELECT COUNT(*) AS total_users FROM user_form WHERE user_type = 'user'";
$result_users = $conn->query($sql_users);
$totalUsers = $result_users->fetch_assoc()['total_users'];

// Fetch total categories
$sql_categories = "SELECT COUNT(*) AS total_categories FROM category";
$result_categories = $conn->query($sql_categories);
$totalCategories = $result_categories->fetch_assoc()['total_categories'];

// Fetch total products
$sql_products = "SELECT COUNT(*) AS total_products FROM product";
$result_products = $conn->query($sql_products);
$totalProducts = $result_products->fetch_assoc()['total_products'];

// Fetch total orders
$sql_orders = "SELECT COUNT(*) AS total_orders FROM orders";
$result_orders = $conn->query($sql_orders);
$totalOrders = $result_orders->fetch_assoc()['total_orders'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background: #1a1a1a;  /* Black background for entire body */
            color: #fff;           /* White text color for contrast */
            font-family: Arial, sans-serif;
        }
        
        /* Cards section (to stand out with different colors) */
        .card {
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: 0.3s;
            background-color: #333; /* Default card background darker */
            color: #fff;            /* Text color inside cards */
        }

        .card:hover {
            transform: scale(1.05);
            background-color: #444;  /* Slightly lighter on hover */
        }

        /* Specific background color for different card types */
        .bg-primary { background: #007bff !important; }
        .bg-success { background: #28a745 !important; }
        .bg-warning { background: #ffc107 !important; }
        .bg-danger { background: #dc3545 !important; }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #222; /* Dark background for the entire table */
            color: #fff !important; /* White text color for all table text */
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #444;
            text-align: left;
        }

        table th {
            background: #333; /* Darker gray for header */
            color: white;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background: #2a2a2a; /* Darker shade of gray for even rows */
        }

        table tr:nth-child(odd) {
            background: #222; /* Even darker for odd rows */
        }

        table tr:hover {
            background: #444; /* Lighten on hover for better visibility */
        }

        /* Specific fix for faded text inside table cells */
        table td {
            opacity: 1 !important; /* Ensure full visibility of the text */
        }

        /* Ensure all page areas have a clean appearance */
        #main-content {
            background-color: #222;  /* Dark background for main content */
            padding: 20px;
        }

        /* Ensure modal background is white and text is dark */
.modal-content {
    background-color: #fff; /* Set the modal background to white */
    color: #000; /* Set the text color to black */
}

.modal-header, .modal-body, .modal-footer {
    background-color: #fff; /* Ensure the modal sections also have a white background */
    color: #000; /* Set text color to black for readability */
}

.modal-header .close {
    color: #000; /* Ensure close button text is dark for visibility */
}

input, select, textarea {
    background-color: #fff; /* Set input fields to have a white background */
    color: #000; /* Set input field text to black */
}

button {
    background-color: #007bff; /* Set a button background color */
    color: #fff; /* Button text color */
}

button:hover {
    background-color: #0056b3; /* Change button color on hover */
}

/* Ensure modal text and elements are visible when background is white */
.modal-body input,
.modal-body select,
.modal-body textarea {
    background-color: #f8f9fa; /* Light gray background for form inputs */
    color: #000; /* Dark text for input fields */
}

/* When you open the modal, it should have white background with dark text */
    /* Align the row content to the right */
.row {
    justify-content: flex-end; /* Moves items to the right */
}

/* Ensures cards stay in line and don't stretch */
.col-sm-3 {
    display: flex;
    justify-content: flex-end;
}

    </style>
</head>
<body>
    <?php
        // Include sidebar for navigation
        include "./sidebar.php";
    ?>

    <div id="main-content" class="container allContent-section py-4">
        <div class="row">
            <div class="col-sm-3">
                <div class="card bg-primary text-white">
                    <i class="fa fa-users mb-2" style="font-size: 70px;"></i>
                    <h4>Total Users</h4>
                    <h5><?php echo $totalUsers; ?></h5>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card bg-success text-white">
                    <i class="fa fa-th-large mb-2" style="font-size: 70px;"></i>
                    <h4>Total Categories</h4>
                    <h5><?php echo $totalCategories; ?></h5>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card bg-warning text-white">
                    <i class="fa fa-th mb-2" style="font-size: 70px;"></i>
                    <h4>Total Products</h4>
                    <h5><?php echo $totalProducts; ?></h5>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card bg-danger text-white">
                    <i class="fa fa-list mb-2" style="font-size: 70px;"></i>
                    <h4>Total Orders</h4>
                    <h5><?php echo $totalOrders; ?></h5>
                </div>
            </div>
        </div>
        
        <!-- Charts Row -->
        <div class="row mt-4">
            <div class="col-md-6">
                <canvas id="dashboardChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Bar chart displaying total counts (Users, Categories, Products, Orders)
            var ctx1 = document.getElementById('dashboardChart').getContext('2d');
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: ['Users', 'Categories', 'Products', 'Orders'],
                    datasets: [{
                        label: 'Total Count',
                        data: [<?php echo "$totalUsers, $totalCategories, $totalProducts, $totalOrders"; ?>],
                        backgroundColor: ['blue', 'blue', 'blue', 'blue']
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Line chart displaying orders per month (example data for now)
            var ctx2 = document.getElementById('ordersChart').getContext('2d');
            new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Orders Per Month',
                        data: [5, 10, 3, 15, 7, 8], // Example data, replace with dynamic data
                        borderColor: 'blue',
                        fill: false
                    }]
                },
                options: {
                    responsive: true
                }
            });
        });
    </script>

    <!-- External JavaScript and Bootstrap -->
    <script src="./assets/js/ajaxWork.js"></script>    
    <script src="./assets/js/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</body>
</html> 
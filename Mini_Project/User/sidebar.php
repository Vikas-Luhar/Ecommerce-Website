<!-- Sidebar -->
<div class="sidebar" id="mySidebar">
<!-- <div class="side-header">
    <img src="./assets/images/logo.png" width="120" height="120" alt="Swiss Collection"> 
    <h5 style="margin-top:10px;">Hello, Admin</h5>
</div> -->

<hr style="border:1px solid; background-color:#8a7b6d; border-color:#3B3131;">
    <!-- <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a> -->
    <a href="admin.php" ><i class="fa fa-home"></i> Dashboard</a>
    <a href="#customers"  onclick="showCustomers()" ><i class="fa fa-users"></i> Customers</a>
    <a href="#category"   onclick="showCategory()" ><i class="fa fa-th-large"></i> Category</a>
    <a href="#products"   onclick="showProductItems()" ><i class="fa fa-th"></i> Products</a>
    <a href="#orders" onclick="showOrders()"><i class="fa fa-list"></i> Orders</a>
    <a href="admin_logout.php" class="logout-btn">
    <i class="fa fa-sign-out"></i> Logout
</a>



  <!---->
</div>
 

<div id="main">
    <button class="openbtn" onclick="openNav()"><i class="fa fa-home"></i></button>
</div>

<style>
 /* Sidebar Styling */
.sidebar {
    width: 260px; /* Sidebar width */
    height: 100vh;
    background: #181818; /* Dark background */
    padding-top: 20px;
    position: fixed;
    left: 0;
    top: 0;
    overflow-y: auto; /* Enable vertical scrolling if needed */
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3); /* Subtle shadow for depth */
    transition: all 0.3s ease-in-out; /* Smooth transition for sidebar movements */
}

/* Sidebar Items */
.sidebar ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.sidebar ul li {
    padding: 15px 20px;
    font-size: 16px;
    font-weight: 500;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    color: #ccc; /* Light text color */
    cursor: pointer;
    border-left: 4px solid transparent; /* Default border for consistency */
}

/* Sidebar Item Hover Effects */
.sidebar ul li:hover {
    background: #333; /* Darker background on hover */
    color: #fff; /* White text color on hover */
    border-left: 4px solid #007bff; /* Blue highlight on hover */
}

/* Active Sidebar Item */
.sidebar ul li.active {
    background: #007bff;
    color: #fff;
    font-weight: bold;
    border-left: 4px solid #0056b3; /* Deeper blue on active item */
}

/* Sidebar Icons */
.sidebar ul li i {
    margin-right: 12px; /* Space between icon and text */
    font-size: 20px; /* Larger icon size */
}

/* Ensure main content shifts correctly */
#main-content {
    margin-left: 260px; /* Space for the sidebar */
    padding: 20px;
    transition: margin-left 0.3s ease; /* Smooth transition for content shift */
}

/* Responsive Sidebar (collapses on smaller screens) */
@media (max-width: 768px) {
    .sidebar {
        width: 200px; /* Reduce sidebar width on smaller screens */
    }

    #main-content {
        margin-left: 200px; /* Adjust content space */
    }
}

/* Sidebar Toggle Button (for mobile view) */
.sidebar-toggle {
    display: none; /* Hidden by default */
    position: absolute;
    top: 20px;
    left: 220px; /* Position of toggle button */
    background-color: #007bff;
    color: white;
    padding: 10px;
    border: none;
    cursor: pointer;
    font-size: 18px;
}

/* Display toggle button on smaller screens */
@media (max-width: 768px) {
    .sidebar-toggle {
        display: block; /* Show toggle button */
    }

    .sidebar {
        width: 0;
        padding-top: 50px; /* Adjust padding when collapsed */
        transition: width 0.3s ease;
    }

    #main-content {
        margin-left: 0;
    }

    .sidebar.active {
        width: 200px; /* Show sidebar when active */
    }
}

</style>

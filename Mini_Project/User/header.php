<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'header.php';

// Assign session variable to local variable after the check
$user_id = $_SESSION['user_id'] ?? 0; // Default to 0 if not set
include_once 'connection.php';
// Query to get the cart item count
$query = "SELECT COUNT(*) FROM tblcart WHERE User_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($cart_count);
$stmt->fetch();
$stmt->close();
$profile_image = "images/image.jpg"; // Default

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $query = "SELECT profile_image FROM user_form WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($img);
    if ($stmt->fetch() && $img) {
        $profile_image = "images/" . $img;
    }
    $stmt->close();
}
?>
<style>
/* Navbar background color and padding */ 
.custom-navbar {
    background-color: #2c3e50; /* Darker shade for a modern look */
    padding: 10px 20px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); /* Adds a subtle shadow effect */
}

/* Navbar brand style */
.navbar-brand {
    font-size: 24px;
    font-weight: bold;
    color: #f39c12; /* Bright accent color for the brand */
    text-transform: uppercase;
}

.navbar-brand span {
    color: #e74c3c; /* Color of the period in the brand */
}

/* Navbar links style */
.custom-navbar-nav .nav-link {
    color: #ecf0f1; /* Light text for better contrast */
    font-size: 16px;
    margin-right: 20px;
    position: relative;
    transition: color 0.3s ease;
}

.custom-navbar-nav .nav-link:hover {
    color: #f39c12; /* Highlight on hover */
}

.custom-navbar-nav .nav-link:before {
    content: "";
    position: absolute;
    width: 0;
    height: 2px;
    bottom: -5px;
    left: 0;
    background-color: #f39c12; /* Underline effect on hover */
    visibility: hidden;
    transition: all 0.3s ease-in-out;
}

.custom-navbar-nav .nav-link:hover:before {
    visibility: visible;
    width: 100%;
}

/* User icon dropdown */
.custom-navbar-cta .nav-item .dropdown-toggle img {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 2px solid #f39c12;
    transition: transform 0.3s ease;
}

.custom-navbar-cta .nav-item .dropdown-toggle:hover img {
    transform: scale(1.1); /* Grows slightly when hovered */
}

.dropdown-menu {
    background-color: #34495e; /* Dark background for the dropdown */
    border: none;
    border-radius: 10px;
    padding: 10px 0;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2); /* Dropdown shadow */
}

.dropdown-menu .dropdown-item {
    color: #ecf0f1; /* Light text */
    padding: 10px 20px;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.dropdown-menu .dropdown-item:hover {
    background-color: #f39c12; /* Highlight dropdown items */
    color: #2c3e50; /* Darker text on hover */
}

.dropdown-divider {
    border-top: 1px solid #ecf0f1; /* Light border for the divider */
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .navbar-brand {
        font-size: 20px;
    }

    .custom-navbar-nav .nav-link {
        font-size: 14px;
        margin-right: 10px;
    }

    .custom-navbar-cta .nav-item .dropdown-toggle img {
        width: 25px;
        height: 25px;
    }
}
/* Align Wishlist & Cart Icons Properly */
.custom-navbar-cta {
    display: flex;
    align-items: center;
    gap: 1px; /* Adjust spacing between icons */
}

/* Wishlist Icon Styling */
.wishlist-link {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 5px;
}

.wishlist-link i {
    font-size: 20px;
    color: #fff;
    transition: color 0.3s ease-in-out;
}

.wishlist-link:hover i {
    color: #f39c12; /* Highlight on hover */
}


</style>
<!-- Start Header/Navigation -->
<nav class="custom-navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">
    <div class="container">
        <a class="navbar-brand" href="user.php"><span>.</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsFurni">
            <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link" href="user.php">Home</a>
                </li>
                <li><a class="nav-link" href="shop.php">Shop</a></li>
                <li><a class="nav-link" href="about.php">About us</a></li>
                <li><a class="nav-link" href="contact.php">Contact us</a></li>
                <!-- Cart Icon with Item Count -->
<ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
    <li class="nav-item">
        <a class="nav-link" href="cart.php">
            <i class="fas fa-shopping-cart" title="Add To Cart"></i> <!-- Cart Icon -->
            <!-- <span class="badge bg-danger"><?php echo $cart_count; ?></span> Cart Item Count -->
        </a>
    </li>
    <!-- <li class="nav-item">
        <a class="nav-link wishlist-link" href="wishlist.php">
            <i class="fas fa-heart"></i> 
        </a>
    </li> -->
</ul>
            </ul>

            <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
    <?php if (!empty($_SESSION['user_name'])): ?> 
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?= htmlspecialchars($profile_image); ?>" alt="User Avatar" width="32" height="32" class="rounded-circle me-2" style="object-fit: cover;">
                <span class="username"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span> 
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="profile.php">View Profile</a></li>
                <li><a class="dropdown-item" href="order-history.php">Order History</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
            </ul>
        </li>
    <?php else: ?>
        <li class="nav-item">
            <a class="nav-link" href="../index.php">Login</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../register_form.php">Register</a>
        </li>
    <?php endif; ?>
</ul>

        </div>
    </div>
</nav>
<!-- End Header/Navigation -->

<?php
// sales.php
session_start();
include "database.php";

// Default date filter (today)
$date_filter = 'today';
$start_date = $end_date = date('Y-m-d');

// Filter selection based on user input
if (isset($_GET['filter'])) {
    $date_filter = $_GET['filter'];
    switch ($date_filter) {
        case 'daily':
            $start_date = date('Y-m-d'); // Today's date
            $end_date = date('Y-m-d');
            break;
        case 'monthly':
            $start_date = date('Y-m-01'); // First day of the current month
            $end_date = date('Y-m-t');   // Last day of the current month
            break;
        case 'yearly':
            $start_date = date('Y-01-01'); // First day of the current year
            $end_date = date('Y-12-31');   // Last day of the current year
            break;
    }
}

// Fetch the latest sale (most recent)
$stmt = $pdo->prepare("SELECT o.order_id, o.total_amount, o.order_date 
                       FROM orders o 
                       ORDER BY o.order_date DESC LIMIT 1");
$stmt->execute();
$latest_sale = $stmt->fetch();

// Fetch sales data within the date range
$stmt = $pdo->prepare("SELECT COUNT(order_id) AS total_sales_count, SUM(total_amount) AS total_sales_amount 
                       FROM orders 
                       WHERE order_date BETWEEN ? AND ?");
$stmt->execute([$start_date, $end_date]);
$sales_summary = $stmt->fetch();

// Fetch all orders within the date range
$stmt = $pdo->prepare("SELECT order_id, total_amount, order_date 
                       FROM orders 
                       WHERE order_date BETWEEN ? AND ?");
$stmt->execute([$start_date, $end_date]);
$sales = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sales Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
           /* Navbar styling */
.navbar {
    background-color: #343a40; /* Dark background for the navbar */
    padding: 10px 20px; /* Padding for spacing */
}

/* Navbar brand styling */
.navbar-brand {
    color: #fff; /* White text color */
    font-size: 1.5rem; /* Font size for the brand */
    text-decoration: none; /* Remove underline */
}

/* Center navbar links */
.navbar-nav {
    display: flex;
    justify-content: center; /* Center the links */
    flex-grow: 1; /* Allow it to take up available space */
}

/* Styling for nav links */
.navbar-nav .nav-link {
    color: #fff; /* White text color for links */
    margin: 0 15px; /* Space between the links */
    text-decoration: none; /* Remove underline */
    font-size: 1rem; /* Set a font size for the links */
}

/* Add hover effect to links */
.navbar-nav .nav-link:hover {
    color: #007bff; /* Change to blue on hover */
}

/* Style for Logout link */
.logout {
    color: #fff;
    text-decoration: none;
    font-size: 1rem;
    margin-left: 20px; /* Add some margin for spacing */
}

.logout:hover {
    color: blue; /* Red color for hover effect on logout */
}
.navbar-brand span{
    color: black;
}
    </style>
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-danger sticky-top">
    <a class="navbar-brand ms-3" href="dashboard.php">Book<span>Flix</span> Admin</a>
    <div class="collapse navbar-collapse">
        <div class="navbar-nav mx-auto"> <!-- Added mx-auto to center the nav items -->
        <a href="adminpage.php" class="nav-link">Dashboard</a>
            <a href="categories.php" class="nav-link">Categories</a>
            <a href="books.php" class="nav-link">Books</a>
            <a href="sales.php" class="nav-link">Sales</a>
            <a href="messages.php" class="nav-link">Messages</a>
        </div>
        <a href="login.php" class="nav-link logout">Logout</a>
    </div>
</nav>
<div class="container mt-4">
    <h2>Sales Report</h2>

    <!-- Latest Sale -->
    <?php if ($latest_sale): ?>
        <div class="alert alert-info">
            <strong>Latest Sale:</strong> 
            <br> 
            <strong>Order ID:</strong> <?php echo $latest_sale['order_id']; ?>
            <br>
            <strong>Total Amount:</strong> ₱<?php echo number_format($latest_sale['total_amount'], 2); ?>
            <br>
            <strong>Sale Date:</strong> <?php echo $latest_sale['order_date']; ?>
        </div>
    <?php endif; ?>

    <!-- Filter Buttons -->
    <div class="mb-3">
        <a href="sales.php?filter=daily" class="btn btn-primary">Daily</a>
        <a href="sales.php?filter=monthly" class="btn btn-primary">Monthly</a>
        <a href="sales.php?filter=yearly" class="btn btn-primary">Yearly</a>
    </div>

    <!-- Total Sales Summary -->
    <div class="alert alert-info">
        <strong>Total Sales for <?php echo ucfirst($date_filter); ?>:</strong> 
        ₱<?php echo number_format($sales_summary['total_sales_amount'], 2); ?> (<?php echo $sales_summary['total_sales_count']; ?> orders)
    </div>

    <!-- Sales Table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Total Amount</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($sales) > 0): ?>
                <?php foreach ($sales as $sale): ?>
                    <tr>
                        <td><?php echo $sale['order_id']; ?></td>
                        <td>$<?php echo number_format($sale['total_amount'], 2); ?></td>
                        <td><?php echo $sale['order_date']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No sales found for this period.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
include "database.php";

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch counts for summary cards
$total_categories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$total_books = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();

// Fetch total sales amount and count for today
$total_sales_today = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE DATE(order_date) = CURDATE()")->fetchColumn();
$total_orders_today = $pdo->query("SELECT COUNT(*) FROM orders WHERE DATE(order_date) = CURDATE()")->fetchColumn();

// Fetch today's sales details
$sales_today_details = $pdo->query("
    SELECT o.order_id, u.username, o.total_amount, o.order_date 
    FROM orders o 
    JOIN users u ON o.user_id = u.user_id 
    WHERE DATE(o.order_date) = CURDATE()
")->fetchAll();

// Fetch out-of-stock books count and titles
$out_of_stock_count = $pdo->query("SELECT COUNT(*) FROM books WHERE stock = 0")->fetchColumn();
$out_of_stock_books = $pdo->query("SELECT title FROM books WHERE stock = 0")->fetchAll();

// Fetch categories and books titles
$categories = $pdo->query("SELECT name FROM categories")->fetchAll();
$books = $pdo->query("SELECT title FROM books")->fetchAll();

// Fetch latest books
$latest_books = $pdo->query("SELECT title, author, stock, image FROM books ORDER BY book_id ASC LIMIT 5")->fetchAll();

// Fetch latest orders
$orders = $pdo->query("SELECT o.order_id, u.username, o.total_amount, o.order_date FROM orders o JOIN users u ON o.user_id = u.user_id ORDER BY o.order_date DESC LIMIT 5")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background-color: #f1f3f5; /* Light background color */
        }

        .card {
            box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
            border-radius: 10px; 
        }

        .dashboard-icon {
            font-size: 2rem;
            color: white;
        }

        .card:hover {
            cursor: pointer;
        }

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

        /* Summary Card Styles */
        .card.bg-primary {
            background-color: #007bff; /* Blue */
        }

        .card.bg-success {
            background-color: #28a745; /* Green */
        }

        .card.bg-danger {
            background-color: #dc3545; /* Red */
        }

        .card.bg-warning {
            background-color: #ffc107; /* Yellow */
        }

        /* Modernize Table */
        .table {
            border-collapse: separate;
            border-spacing: 0 15px;
            width: 100%;
        }

        .table th, .table td {
            padding: 15px 20px;
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #007bff; /* Darker background for headers */
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8f9fa;
        }

        .table-striped tbody tr:nth-of-type(even) {
            background-color: #ffffff;
        }

        /* Hover Effect for Table Rows */
        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 10px;
        }

        .modal-header {
            background-color: #007bff; /* Matching blue header */
            color: white;
            border-bottom: none;
        }

        .modal-body {
            max-height: 60vh;
            overflow-y: auto;
        }

        /* Text Styles */
        h2.card {
            color: #495057;
            font-weight: bold;
        }

        /* Add color to footer if needed */
        footer {
            background-color: #343a40;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 14px;
        }

        /* Modify Button Style */
        .btn-outline-light {
            color: #f8f9fa;
            border-color: #f8f9fa;
        }

        .btn-outline-light:hover {
            background-color: #f8f9fa;
            color: #343a40;
        }

        .btn-danger {
            background-color: blue;
            border-color: blue;
        }

        .btn-danger:hover {
            background-color: blue;
            border-color: blue;
        }
        .title {
                text-align: center; /* Center the title horizontally */
                margin-bottom: 30px; /* Space below the title */
            }

            .title h2 {
                font-size: 2.5rem; /* Large font size */
                font-weight: bold; /* Bold text */
                color: black; /* Dark text color for a sleek look */
                letter-spacing: 1px; /* Add spacing between letters for a clean effect */
                text-transform: uppercase; /* Make the text uppercase */
                padding: 10px 0; /* Add padding for better spacing around the title */
                width: fit-content; /* Make sure the border is only as wide as the title */
                margin: 0 auto; /* Center the title horizontally */
            }
.navbar-brand span{
    color: black;
}
    </style>
</head>
<body>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Get the modal and modal content elements
        const modal = document.getElementById('dashboardModal');
        const modalBodyContent = document.getElementById('modal-body-content');
        const modalTitle = document.getElementById('dashboardModalLabel');

        // Add event listeners to the cards
        document.querySelectorAll('.card[data-bs-whatever]').forEach(card => {
            card.addEventListener('click', () => {
                const cardType = card.getAttribute('data-bs-whatever');

                let title = '';
                let content = '';

                // Set content based on the card type
                if (cardType === 'categories') {
                    title = 'Categories';
                    content = `<?php foreach ($categories as $category): ?>
                                <li><?php echo htmlspecialchars($category['name']); ?></li>
                            <?php endforeach; ?>`;
                } else if (cardType === 'books') {
                    title = 'Books';
                    content = `<?php foreach ($books as $book): ?>
                                <li><?php echo htmlspecialchars($book['title']); ?></li>
                            <?php endforeach; ?>`;
                } else if (cardType === 'sales_today') {
                    title = 'Sales Today';
                    content = `<?php foreach ($sales_today_details as $sale): ?>
                                <tr>
                                    <td><?php echo $sale['order_id']; ?></td>
                                    <td><?php echo htmlspecialchars($sale['username']); ?></td>
                                    <td>$<?php echo number_format($sale['total_amount'], 2); ?></td>
                                    <td><?php echo $sale['order_date']; ?></td>
                                </tr>
                            <?php endforeach; ?>`;
                } else if (cardType === 'out_of_stock') {
                    title = 'Out of Stock Books';
                    content = `<?php foreach ($out_of_stock_books as $book): ?>
                                <li><?php echo htmlspecialchars($book['title']); ?></li>
                            <?php endforeach; ?>`;
                }

                // Update the modal content
                modalTitle.textContent = title;
                modalBodyContent.innerHTML = content;
            });
        });
    });
</script>

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


    <!-- Dashboard Content -->
    <div class="container mt-4">
        <div class="title">
            <h2 >Admin Dashboard</h2>
        </div>

        <!-- Summary Cards -->
        <div class="row ms-5">
            <div class="col-md-3">
                <div class="card  text-black" data-bs-toggle="modal" data-bs-target="#dashboardModal" data-bs-whatever="categories">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Total Categories</h5>
                            <h3><?php echo $total_categories; ?></h3>
                        </div>
                        <i class="fas fa-list dashboard-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card  text-black" data-bs-toggle="modal" data-bs-target="#dashboardModal" data-bs-whatever="books">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Total Books</h5>
                            <h3><?php echo $total_books; ?></h3>
                        </div>
                        <i class="fas fa-book dashboard-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row ms-5">
        <div class="col-md-3 mt-4">
                <div class="card  text-black" data-bs-toggle="modal" data-bs-target="#dashboardModal" data-bs-whatever="sales_today">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Sales Today</h5>
                            <h3><?php echo $total_sales_today ?? '0.00'; ?></h3>
                        </div>
                        <i class="fas fa-dollar-sign dashboard-icon"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mt-4 ">
                <div class="card text-black" data-bs-toggle="modal" data-bs-target="#dashboardModal" data-bs-whatever="out_of_stock">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Out of Stock Books</h5>
                            <h3><?php echo $out_of_stock_count; ?></h3>
                        </div>
                        <i class="fas fa-exclamation-circle dashboard-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="mt-5">
            <h4>Latest Orders</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Total Amount</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo htmlspecialchars($order['username']); ?></td>
                        <td>₱<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><?php echo $order['order_date']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Latest Books -->
        <div class="mt-5">
            <h4>Latest Books</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($latest_books as $book): ?>
                    <tr>
                        <td>
                            <?php if (!empty($book['image'])): ?>
                                <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="Book Image" style="width: 50px; height: 50px; object-fit: cover;">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                        <td><?php echo $book['stock']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal for displaying details -->
        <div class="modal fade" id="dashboardModal" tabindex="-1" aria-labelledby="dashboardModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="dashboardModalLabel">Dashboard Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-body-content">
                        <!-- Modal content will be dynamically filled -->
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </div>
</body>
</html>

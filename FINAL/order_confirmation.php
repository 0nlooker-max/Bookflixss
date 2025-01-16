<?php
session_start();
include "database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$order_id = $_GET['order_id'] ?? null;

if ($order_id) {
    // Fetch order details
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = :order_id");
    $stmt->execute([':order_id' => $order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        // Fetch cart items related to this order
        $stmt = $pdo->prepare("
            SELECT c.cart_id, b.title, b.price, c.quantity 
            FROM cart c
            JOIN books b ON c.book_id = b.book_id
            WHERE c.user_id = :user_id
        ");
        $stmt->execute([':user_id' => $order['user_id']]);
        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "Order not found.";
        exit;
    }
} else {
    echo "Invalid order ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            color: #495057;
        }

        .navbar {
            background-color: #007bff;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .navbar-nav .nav-link {
            color: #ffffff !important;
            padding: 12px 20px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            background-color: #0056b3;
            color: #ffffff !important;
            border-radius: 5px;
        }

        .container {
            margin-top: 30px;
            padding: 40px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2, h3 {
            font-weight: 600;
            color: #343a40;
        }

        h4 {
            color: #6c757d;
        }

        table {
            margin-top: 20px;
        }

        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }

        .table th {
            background-color: #f1f3f5;
            color: #495057;
        }

        .table td {
            background-color: #ffffff;
        }

        .table td, .table th {
            padding: 15px;
        }

        .table td:first-child {
            text-align: left;
        }

        .btn {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        #logout {
            color: white;
            font-weight: bold;
            text-decoration: none;
        }

        #logout:hover {
            color: #ffc107;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand ms-3" href="user_dashboard.php">Bookstore</a>
    <div class="navbar-nav mx-auto">
        <a class="nav-link" href="user_page.php">Home</a>
        <a class="nav-link" href="cart.php">Cart</a>
        <a class="nav-link" href="contact.php">Contact Admin</a>
    </div>
    <a class="nav-link" href="logout.php" id="logout">Logout</a>
</nav>

<div class="container">
    <h2>Order Confirmation</h2>
    <h4>Order ID: <?php echo $order['order_id']; ?></h4>
    <h4>Order Summary</h4>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Book</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                    <td>₱<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
                <?php $total += $item['price'] * $item['quantity']; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Total: ₱<?php echo number_format($total, 2); ?></h3>

    <a href="user_page.php" class="btn">Back to Home</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
include "database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch cart items for the user
$stmt = $pdo->prepare("
    SELECT c.cart_id, b.title, b.price, c.quantity, b.book_id
    FROM cart c
    JOIN books b ON c.book_id = b.book_id
    WHERE c.user_id = :user_id
");
$stmt->execute([':user_id' => $user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total amount
$total_amount = 0;
foreach ($cart_items as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Insert the order into the orders table
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount) VALUES (:user_id, :total_amount)");
    $stmt->execute([':user_id' => $user_id, ':total_amount' => $total_amount]);

    // Get the inserted order ID
    $order_id = $pdo->lastInsertId();

    // Update cart items to 'completed' and remove from the cart
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);

    // Redirect to the order confirmation page
    header("Location: order_confirmation.php?order_id=$order_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Custom fonts and general layout */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar-nav {
            display: flex;
            justify-content: center;
            flex-grow: 1;
        }

        /* Style for individual links */
        .nav-link {
            color: white !important;
            padding: 10px 15px;
            text-decoration: none;
            transition: color 0.3s ease, background-color 0.3s ease;
        }

        /* Hover effect for the links */
        .nav-link:hover {
            color: #ffc107 !important;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        /* Align the logout link to the right */
        #logout {
            margin-left: auto;
            color: white !important;
            padding: 10px 15px;
            text-decoration: none;
        }

        /* Hover effect for logout */
        #logout:hover {
            color: #ffc107 !important;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }


        .container {
            max-width: 900px;
            margin-top: 30px;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-weight: 600;
            color: #333;
        }

        h4 {
            color: #555;
        }

        .table th {
            background-color: #f1f1f1;
            font-weight: 600;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 12px 24px;
            font-weight: 600;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .card-body {
            font-size: 16px;
        }

        .card {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border: none;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand ms-3" href="user_dashboard.php">Bookstore</a>
        <div class="navbar-nav mx-auto">
            <a class="nav-link" href="user_page.php">Home</a>
            <a class="nav-link" href="cart.php">Cart</a>
            <a class="nav-link" href="contact.php">Contact Admin</a>
        </div>
        <a class="nav-link" href="logout.php" id="logout">Logout</a>
    </nav>

<div class="container">
    <h2>Checkout</h2>
    <h4>Order Summary</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Book</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                    <td>₱<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        <h3>Total: ₱<?php echo number_format($total_amount, 2); ?></h3>
        <form action="checkout.php" method="POST">
            <button type="submit" class="btn btn-primary">Complete Order</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

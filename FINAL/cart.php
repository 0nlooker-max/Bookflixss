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


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id']) && isset($_POST['quantity'])) {
    $book_id = $_POST['book_id'];
    $quantity = $_POST['quantity']; // Get the quantity from the form

    // Ensure quantity is a valid number
    if ($quantity < 1) {
        $quantity = 1; // Default to 1 if an invalid value is provided
    }

    // Check if there's enough stock for the book
    $stmt = $pdo->prepare("SELECT stock FROM books WHERE book_id = :book_id");
    $stmt->execute([':book_id' => $book_id]);
    $book = $stmt->fetch();

    if ($book && $book['stock'] >= $quantity) {
        // Check if the book is already in the cart for this user
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id AND book_id = :book_id");
        $stmt->execute([':user_id' => $user_id, ':book_id' => $book_id]);
        $existing_cart_item = $stmt->fetch();

        if ($existing_cart_item) {
            // Update quantity if the book already exists in the cart
            $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + :quantity WHERE cart_id = :cart_id");
            if ($stmt->execute([':quantity' => $quantity, ':cart_id' => $existing_cart_item['cart_id']])) {
                echo "Updated quantity in cart.";
            } else {
                echo "Failed to update quantity in cart.";
            }
        } else {
            // Insert new cart item
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, book_id, quantity) VALUES (:user_id, :book_id, :quantity)");
            if ($stmt->execute([':user_id' => $user_id, ':book_id' => $book_id, ':quantity' => $quantity])) {
                echo "Item added to cart successfully.";
            } else {
                echo "Failed to add item to cart.";
            }
        }

        // Reduce stock of the book in the books table
        $new_stock = $book['stock'] - $quantity;
        $stmt = $pdo->prepare("UPDATE books SET stock = :stock WHERE book_id = :book_id");
        if ($stmt->execute([':stock' => $new_stock, ':book_id' => $book_id])) {
            echo "Stock updated.";
        } else {
            echo "Failed to update stock.";
        }

        // Redirect back to the cart page
        header("Location: cart.php");
        exit;
    } else {
        // Handle case where not enough stock is available
        echo "Not enough stock available for this book.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        /* Custom styles for modernizing the table */
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        .table thead {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: bold;
        }
        .table tbody tr {
            transition: background-color 0.3s ease;
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .table td {
            background-color: #ffffff;
        }
        .table td .btn {
            padding: 5px 10px;
            font-size: 0.875rem;
        }
        h3 {
            color: #007bff;
            font-weight: 500;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
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

    <div class="container mt-4">
        <h2>Your Cart</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
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
                        <td>
                            <form action="remove.php" method="POST">
                                <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                    </tr>
                    <?php $total += $item['price'] * $item['quantity']; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h3>Total: ₱<?php echo number_format($total, 2); ?></h3>
        <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
    </div>
</body>
</html>

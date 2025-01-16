<?php
session_start();
include "database.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

// Check if the cart_id is provided in the POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cart_id'])) {
    $cart_id = $_POST['cart_id'];
    $user_id = $_SESSION['user_id'];

    // Fetch the cart item to get the book_id and quantity
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE cart_id = :cart_id AND user_id = :user_id");
    $stmt->execute([':cart_id' => $cart_id, ':user_id' => $user_id]);
    $cart_item = $stmt->fetch();

    if ($cart_item) {
        // Get the book_id and quantity of the removed item
        $book_id = $cart_item['book_id'];
        $quantity = $cart_item['quantity'];

        // Restore the stock of the book
        $stmt = $pdo->prepare("SELECT stock FROM books WHERE book_id = :book_id");
        $stmt->execute([':book_id' => $book_id]);
        $book = $stmt->fetch();

        if ($book) {
            // Update the stock by adding the quantity back
            $new_stock = $book['stock'] + $quantity;
            $stmt = $pdo->prepare("UPDATE books SET stock = :stock WHERE book_id = :book_id");
            $stmt->execute([':stock' => $new_stock, ':book_id' => $book_id]);
        }

        // Remove the cart item from the cart
        $stmt = $pdo->prepare("DELETE FROM cart WHERE cart_id = :cart_id");
        $stmt->execute([':cart_id' => $cart_id]);

        // Redirect back to the cart page after removal
        header("Location: cart.php");
        exit;
    } else {
        // Handle the case if the cart item doesn't exist or doesn't belong to the user
        echo "Cart item not found or you don't have permission to remove it.";
    }
} else {
    // If no cart_id was provided, redirect to the cart page
    header("Location: cart.php");
    exit;
}
?>

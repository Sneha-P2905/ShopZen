<?php
session_start();
include 'includes/db.php';

// ✅ Check for valid product_id in GET
if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
    $product_id = (int)$_GET['product_id'];

    // ✅ Remove from session cart if exists
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);

        // If cart is now empty, unset the entire cart session
        if (empty($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }

        $_SESSION['success'] = "✅ Product removed from cart.";
    }

    // ✅ Remove from DB cart if user is logged in
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $stmt->close();
    }
}

// 🔁 Redirect to cart
header("Location: cart.php");
exit();

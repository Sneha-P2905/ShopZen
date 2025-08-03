<?php
session_start();
include 'includes/db.php';

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ✅ Redirect to cart if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

// ✅ Process POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id        = $_SESSION['user_id'];
    $name           = trim($_POST['name'] ?? '');
    $address        = trim($_POST['address'] ?? '');
    $phone          = trim($_POST['phone'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? '');
    $created_at     = date('Y-m-d H:i:s');
    $cart           = $_SESSION['cart'];

    // Insert each product in cart as separate order row
    foreach ($cart as $product_id => $item) {
        $product_name  = $item['name'];
        $product_price = (int)$item['price'];
        $product_image = $item['image'];
        $quantity      = (int)$item['quantity'];
        $total_price   = $product_price * $quantity;

        $stmt = $conn->prepare("INSERT INTO orders (
            user_id, customer_name, address, phone,
            product_id, product_name, product_price, product_image,
            quantity, total_price, payment_method, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "isssisssiiss",
            $user_id, $name, $address, $phone,
            $product_id, $product_name, $product_price, $product_image,
            $quantity, $total_price, $payment_method, $created_at
        );

        $stmt->execute();
        $stmt->close();
    }

    // ✅ Clear cart and redirect
    unset($_SESSION['cart']);
    $_SESSION['order_success'] = true;

    header("Location: order_success.php");
    exit();
} else {
    header("Location: payment.php");
    exit();
}

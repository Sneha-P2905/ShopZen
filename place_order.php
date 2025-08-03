<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['cart']) || !isset($_POST['payment_method'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['delivery_name'] ?? '';
$address = $_SESSION['delivery_address'] ?? '';
$phone = $_SESSION['delivery_phone'] ?? '';
$payment_method = $_POST['payment_method'];

$cart = $_SESSION['cart'];
$total_amount = 0;

// First, calculate total amount
foreach ($cart as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

// Insert each product in cart into orders table
foreach ($cart as $product_id => $item) {
    $product_name = $item['name'];
    $product_price = $item['price'];
    $product_image = $item['image'];
    $quantity = $item['quantity'];
    $total_price = $product_price * $quantity;
    $status = "Pending";

    $stmt = $conn->prepare("INSERT INTO orders (
        user_id, customer_name, address, phone,
        product_id, product_name, product_price, product_image,
        quantity, payment_method, total_amount, created_at, status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)");

    // 13 values → 13 types: i s s s i s i s i i s d s
    $stmt->bind_param(
        "isssisssiids",  // 12 here...
        $user_id,
        $name,
        $address,
        $phone,
        $product_id,
        $product_name,
        $product_price,
        $product_image,
        $quantity,
        $payment_method,
        $total_amount,
        $status             // This is the 13th parameter — must be added!
    );

    $stmt->execute();
}

unset($_SESSION['cart']);
$_SESSION['success'] = "🎉 Order placed successfully!";
header("Location: order_success.php");
exit();
?>

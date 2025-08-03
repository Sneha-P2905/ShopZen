<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Support both POST and GET methods
$product_id = $_POST['product_id'] ?? $_GET['id'] ?? null;

if (!is_numeric($product_id)) {
    header("Location: product.php");
    exit();
}

$product_id = (int)$product_id;

// ✅ Fetch product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($product = $result->fetch_assoc()) {
    // ✅ Update session cart
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => 1
        ];
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    }

    // ✅ Update or Insert into cart DB table
    $check = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $check->bind_param("ii", $user_id, $product_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $update = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
        $update->bind_param("ii", $user_id, $product_id);
        $update->execute();
        $update->close();
    } else {
        $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $insert->bind_param("ii", $user_id, $product_id);
        $insert->execute();
        $insert->close();
    }

    $check->close();
    $_SESSION['success'] = "✅ Added to cart successfully.";
}

$stmt->close();

// ✅ Redirect back to where user came from
$redirect_to = $_SERVER['HTTP_REFERER'] ?? 'product.php';
header("Location: $redirect_to");
exit();
?>

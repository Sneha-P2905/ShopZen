<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: product.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = (int)$_POST['product_id'] ?? 0;

// Avoid duplicate wishlist entries
$stmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    $insert = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
    $insert->bind_param("ii", $user_id, $product_id);
    $insert->execute();
    $insert->close();
} else {
    $stmt->close();
}

header("Location: product.php");
exit();

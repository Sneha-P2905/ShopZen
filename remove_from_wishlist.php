<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: product.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = (int)$_POST['product_id'] ?? 0;

$stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$stmt->close();

header("Location: product.php");
exit();

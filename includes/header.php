<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ShopZen</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="/ecommerce_php/assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm header-gradient">
  <div class="container">
    <a class="navbar-brand" href="/ecommerce_php/index.php">🛍️ ShopZen</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="/ecommerce_php/product.php">Products</a>
        </li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="/ecommerce_php/cart.php">Cart</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/ecommerce_php/my_orders.php">My Orders</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/ecommerce_php/wishlist.php">Wishlist</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/ecommerce_php/logout.php">Logout</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="/ecommerce_php/login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/ecommerce_php/register.php">Register</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Page Wrapper Start -->
<div class="d-flex flex-column min-vh-100">

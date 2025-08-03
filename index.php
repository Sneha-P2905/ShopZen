<?php
session_start();

// 🚀 Smart redirection based on role
if (isset($_SESSION['user_id'])) {
    // Admin goes to dashboard
    if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        // Regular user goes to home
        header("Location: product.php");
    }
} else {
    // Not logged in — redirect to login
    header("Location: login.php");
}
exit();

<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $action = $_POST['action'];

    // Update session-based cart
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = ['quantity' => 0];
    }

    if ($action === 'increase') {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    } elseif ($action === 'decrease') {
        $_SESSION['cart'][$product_id]['quantity'] -= 1;
        if ($_SESSION['cart'][$product_id]['quantity'] <= 0) {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    // If user is logged in, update cart table as well
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Check if the item exists in DB
        $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update or delete
            $row = $result->fetch_assoc();
            $new_quantity = $row['quantity'];

            if ($action === 'increase') {
                $new_quantity++;
                $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
                $update->bind_param("iii", $new_quantity, $user_id, $product_id);
                $update->execute();
            } elseif ($action === 'decrease') {
                $new_quantity--;
                if ($new_quantity <= 0) {
                    $delete = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
                    $delete->bind_param("ii", $user_id, $product_id);
                    $delete->execute();
                } else {
                    $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
                    $update->bind_param("iii", $new_quantity, $user_id, $product_id);
                    $update->execute();
                }
            }
        }
    }
}

// Redirect back to the referring page
$redirect = $_SERVER['HTTP_REFERER'] ?? 'product_detail.php';
header("Location: $redirect");
exit;

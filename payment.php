<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Redirect to delivery info if data not set
if (!isset($_SESSION['delivery_name']) || !isset($_SESSION['delivery_address']) || !isset($_SESSION['delivery_phone'])) {
    header("Location: delivery_info.php");
    exit();
}

$name = $_SESSION['delivery_name'];
$address = $_SESSION['delivery_address'];
$phone = $_SESSION['delivery_phone'];

// You can now use $name, $address, $phone in the form or insert logic
?>

<div class="container my-5">
    <h2 class="text-center mb-4">💳 Payment</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($address) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($phone) ?></p>

    <form action="place_order.php" method="POST">
        <div class="mb-3">
            <label for="payment_method" class="form-label">Select Payment Method:</label>
            <select class="form-select" name="payment_method" id="payment_method" required>
                <option value="cod">Cash on Delivery</option>
                <option value="card">Card</option>
                <option value="upi">UPI</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Place Order</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

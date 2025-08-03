<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Redirect if not logged in or cart is empty
if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

$name = $_SESSION['delivery_name'] ?? '';
$address = $_SESSION['delivery_address'] ?? '';
$phone = $_SESSION['delivery_phone'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');


    if (empty($name) || empty($address) || empty($phone)) {
        $error = "❌ All fields are required.";
    } else {
        // Store data in session and redirect to payment
        $_SESSION['delivery_name'] = $name;
        $_SESSION['delivery_address'] = $address;
        $_SESSION['delivery_phone'] = $phone;

        header("Location: payment.php");
        exit();
    }
}
?>

<div class="container my-5">
    <h2 class="text-center mb-4">🚚 Delivery Information</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>

    <form action="delivery_info.php" method="POST" class="w-75 mx-auto p-4 border rounded shadow-sm bg-light">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($name) ?>" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Full Address</label>
            <textarea name="address" id="address" class="form-control" rows="4" required><?= htmlspecialchars($address) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" name="phone" id="phone" class="form-control" value="<?= htmlspecialchars($phone) ?>" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-lg">➡️ Proceed to Payment</button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

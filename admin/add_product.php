<?php
session_start();
include '../includes/db.php';

// Ensure only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$message = '';
$alertClass = 'info';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price       = (int) $_POST['price'];
    $image       = $_FILES['image']['name'];

    $target_dir = "../images/";
    $target_file = $target_dir . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $name, $description, $price, $image);

        if ($stmt->execute()) {
            $message = "✅ Product added successfully!";
            $alertClass = "success";
        } else {
            $message = "❌ Failed to add product.";
            $alertClass = "danger";
        }
    } else {
        $message = "❌ Failed to upload image.";
        $alertClass = "warning";
    }
}
?>

<?php include '../includes/header_admin.php'; ?>

<div class="container py-5">
    <h2 class="mb-4 text-center">➕ Add New Product</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $alertClass ?> text-center"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="mx-auto p-4 border rounded bg-white shadow-sm" style="max-width: 600px;">
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Price (₹)</label>
            <input type="number" name="price" class="form-control" min="1" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Product Image</label>
            <input type="file" name="image" class="form-control" accept="image/*" required onchange="previewImage(event)">
            <img id="preview" src="#" alt="" class="mt-3 img-fluid d-none rounded" style="max-height: 200px;">
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">Upload Product</button>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    preview.src = URL.createObjectURL(event.target.files[0]);
    preview.classList.remove('d-none');
}
</script>

<?php include '../includes/footer.php'; ?>

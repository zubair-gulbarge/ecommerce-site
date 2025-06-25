<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user'])) {
    die("Please <a href='login.php'>login</a>.");
}

$id = $_GET['id'] ?? null;
if (!$id) die("Invalid product ID.");

// Fetch product to edit
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user']['id']]);
$product = $stmt->fetch();

if (!$product) die("Product not found or access denied.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $desc  = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $image = $_POST['image_url'] ?? '';

    $update = $pdo->prepare("UPDATE products SET title = ?, description = ?, price = ?, image_url = ? WHERE id = ?");
    $update->execute([$title, $desc, $price, $image, $id]);
    echo "✅ Product updated! <a href='products.php'>Back to list</a>";
    exit;
}
?>

<h2>Edit Product</h2>
<form method="post">
  <input name="title" value="<?= htmlspecialchars($product['title']) ?>" required><br><br>
  <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea><br><br>
  <input name="price" type="number" step="0.01" value="<?= htmlspecialchars($product['price']) ?>" required><br><br>
  <input name="image_url" value="<?= htmlspecialchars($product['image_url']) ?>"><br><br>
  <button type="submit">Save Changes</button>
</form>

<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user'])) {
    die("Please <a href='login.php'>login</a> to add products.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $desc  = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $image = $_POST['image_url'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO products (user_id, title, description, price, image_url)
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user']['id'], $title, $desc, $price, $image]);

    echo "✅ Product added! <a href='products.php'>View products</a>";
}
?>

<h2>Add Product</h2>
<form method="post">
  <input name="title" placeholder="Product Name" required><br><br>
  <textarea name="description" placeholder="Description"></textarea><br><br>
  <input name="price" type="number" step="0.01" placeholder="Price" required><br><br>
  <input name="image_url" placeholder="Image URL"><br><br>
  <button type="submit">Add Product</button>
</form>

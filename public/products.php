<?php
session_start();
require 'config.php';

$search = $_GET['search'] ?? '';
$searchParam = "%$search%";

$stmt = $pdo->prepare("SELECT p.*, u.username FROM products p 
                       JOIN users u ON p.user_id = u.id
                       WHERE p.title LIKE ? OR p.description LIKE ?
                       ORDER BY p.created_at DESC");
$stmt->execute([$searchParam, $searchParam]);
$products = $stmt->fetchAll();
?>


<h2>All Products</h2>

<form method="get">
  <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search products...">
  <button type="submit">Search</button>
</form>
<?php if (!empty($search)): ?>
  <a href="products.php">❌ Clear Search</a>
<?php endif; ?>


<?php if (empty($products)): ?>
  <p>No products found.</p>
<?php endif; ?>

<?php foreach ($products as $p): ?>
  <div style="border:1px solid #ccc; padding:10px; margin:10px;">
    <h3><?= htmlspecialchars($p['title']) ?> - ₹<?= number_format($p['price'], 2) ?></h3>

    <?php if ($p['image_url']): ?>
      <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="product image" width="200"><br>
    <?php endif; ?>

    <p><?= nl2br(htmlspecialchars($p['description'])) ?></p>
    <small>Seller: <?= htmlspecialchars($p['username']) ?></small><br>

    <!-- ✅ Add to Cart here -->
    <a href="add-to-cart.php?id=<?= $p['id'] ?>">🛒 Add to Cart</a>

    <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $p['user_id']): ?>
      <br>
      <a href="edit-product.php?id=<?= $p['id'] ?>">✏️ Edit</a> |
      <a href="delete-product.php?id=<?= $p['id'] ?>" onclick="return confirm('Delete this product?')">🗑️ Delete</a>
    <?php endif; ?>
  </div>
<?php endforeach; ?>

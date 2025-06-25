<?php
session_start();
require 'config.php';

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
  echo "<h2>Your cart is empty.</h2>";
  exit;
}

// Prepare a query to fetch all cart products
$ids = implode(',', array_map('intval', array_keys($cart)));
$stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Your Cart</h2>

<?php foreach ($products as $p): 
  $qty = $cart[$p['id']];
  $subtotal = $qty * $p['price'];
?>
  <div style="border:1px solid #ccc; padding:10px; margin:10px;">
    <h3><?= htmlspecialchars($p['title']) ?> - ₹<?= number_format($p['price'], 2) ?> × <?= $qty ?> = ₹<?= number_format($subtotal, 2) ?></h3>
    <a href="remove-from-cart.php?id=<?= $p['id'] ?>" onclick="return confirm('Remove item?')">🗑️ Remove</a>
  </div>
<?php endforeach; ?>

<a href="checkout.php">Proceed to Checkout</a>

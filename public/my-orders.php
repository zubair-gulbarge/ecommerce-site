<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user'])) {
  die("Login required. <a href='login.php'>Login</a>");
}

$userId = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();
?>

<h2>My Orders</h2>

<?php foreach ($orders as $order): ?>
  <div style="border:1px solid #ccc; padding:10px; margin:10px;">
    <strong>Order #<?= $order['id'] ?></strong><br>
    Total: ₹<?= number_format($order['total'], 2) ?><br>
    Placed on: <?= $order['created_at'] ?><br>
    Ship to: <?= htmlspecialchars($order['address']) ?>
  </div>
<?php endforeach; ?>

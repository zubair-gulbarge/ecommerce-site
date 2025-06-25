<?php
session_start();
require 'config.php';

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) die("Cart is empty. <a href='products.php'>Go back</a>");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';

    // Fetch cart products
    $ids = implode(',', array_map('intval', array_keys($cart)));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total = 0;
    foreach ($products as $p) {
        $qty = $cart[$p['id']];
        $total += $p['price'] * $qty;
    }

    // Insert order
    $orderStmt = $pdo->prepare("INSERT INTO orders (user_id, customer_name, email, address, total) VALUES (?, ?, ?, ?, ?)");
    $orderStmt->execute([$_SESSION['user']['id'], $name, $email, $address, $total]);
    $orderId = $pdo->lastInsertId();

    // Insert order items
    $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($products as $p) {
        $itemStmt->execute([$orderId, $p['id'], $cart[$p['id']], $p['price']]);
    }

    // Clear cart
    unset($_SESSION['cart']);

    // Redirect to thank you
    header("Location: thank-you.php");
    exit;
}
?>

<h2>Checkout</h2>
<form method="post">
  <input name="name" placeholder="Full Name" required><br><br>
  <input name="email" type="email" placeholder="Email" required><br><br>
  <textarea name="address" placeholder="Shipping Address" required></textarea><br><br>
  <button type="submit">Place Order</button>
</form>

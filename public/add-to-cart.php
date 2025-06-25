<?php
session_start();
$id = $_GET['id'] ?? null;

if (!$id) {
  die("Invalid product ID.");
}

// Initialize cart array
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// If product already in cart, increase quantity
if (isset($_SESSION['cart'][$id])) {
  $_SESSION['cart'][$id]++;
} else {
  $_SESSION['cart'][$id] = 1;
}

header("Location: cart.php");
exit;

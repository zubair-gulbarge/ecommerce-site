<?php
session_start();
$id = $_GET['id'] ?? null;

if (isset($_SESSION['cart'][$id])) {
  unset($_SESSION['cart'][$id]);
}

header("Location: cart.php");
exit;

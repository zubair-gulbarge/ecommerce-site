<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user'])) {
    die("Please <a href='login.php'>login</a>.");
}

$id = $_GET['id'] ?? null;
if (!$id) die("Invalid product ID.");

$stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user']['id']]);

header("Location: products.php");
exit;

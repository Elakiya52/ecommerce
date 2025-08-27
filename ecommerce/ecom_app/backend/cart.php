<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product = json_decode(file_get_contents("php://input"), true);
    $_SESSION['cart'][] = $product;
}

header('Content-Type: application/json');
echo json_encode($_SESSION['cart']);


?>

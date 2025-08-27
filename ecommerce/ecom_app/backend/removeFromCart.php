<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}
$customer_name = "Static Customer";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $product_id = (int)$_POST['id'];

    // Get current quantity
    $stmt = $conn->prepare("SELECT quantity FROM cart WHERE product_id = ? AND customer_name = ?");
    $stmt->bind_param("is", $product_id, $customer_name);
    $stmt->execute();
    $stmt->bind_result($quantity);
    $stmt->fetch();
    $stmt->close();

    if ($quantity > 1) {
        // Decrement quantity by 1
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity - 1 WHERE product_id = ? AND customer_name = ?");
        $stmt->bind_param("is", $product_id, $customer_name);
        $stmt->execute();
        $stmt->close();
    } else {
        // Quantity is 1, so delete the item
        $stmt = $conn->prepare("DELETE FROM cart WHERE product_id = ? AND customer_name = ?");
        $stmt->bind_param("is", $product_id, $customer_name);
        $stmt->execute();
        $stmt->close();
    }
}
header("Location: mycard.php");
exit;
?>

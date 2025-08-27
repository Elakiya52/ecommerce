<?php
session_start();
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // For CORS

$servername = "localhost";
$username = "root";
$password = ""; // WAMP default no password
$dbname = "ecommerce";

// Connect DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "DB connection failed: " . $conn->connect_error]);
    exit;
}

// Create table if not exists (only first time)
$tableSql = "CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($tableSql);

// Read POST data (JSON product info)
$input = json_decode(file_get_contents("php://input"), true);
$product_id = isset($input['id']) ? (int)$input['id'] : 0;
$customer_name = "Static Customer"; // Static customer (change later with login system)
$quantity = 1;

if ($product_id <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid product ID"]);
    exit;
}

// Check if product already in cart for customer
$stmt = $conn->prepare("SELECT quantity FROM cart WHERE product_id = ? AND customer_name = ?");
$stmt->bind_param("is", $product_id, $customer_name);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Update quantity if product exists
    $stmt->bind_result($currentQty);
    $stmt->fetch();
    $newQty = $currentQty + 1;
    $stmt->close();

    $updateStmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE product_id = ? AND customer_name = ?");
    $updateStmt->bind_param("iis", $newQty, $product_id, $customer_name);
    $updateStmt->execute();
    $updateStmt->close();
} else {
    // Insert new product to cart
    $stmt->close();
    $insertStmt = $conn->prepare("INSERT INTO cart (customer_name, product_id, quantity) VALUES (?, ?, ?)");
    $insertStmt->bind_param("sii", $customer_name, $product_id, $quantity);
    $insertStmt->execute();
    $insertStmt->close();
}

// ✅ Return updated cart with full product details
$sql = "SELECT c.product_id, c.quantity, p.name, p.price, p.image
        FROM cart c 
        JOIN products p ON c.product_id = p.id
        WHERE c.customer_name = ?";
$cartStmt = $conn->prepare($sql);
$cartStmt->bind_param("s", $customer_name);
$cartStmt->execute();
$result = $cartStmt->get_result();

$cart = [];
while ($row = $result->fetch_assoc()) {
    $cart[] = $row;
}

$conn->close();

echo json_encode(["success" => true, "cart" => $cart]);
?>

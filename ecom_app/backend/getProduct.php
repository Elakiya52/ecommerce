
<?php
include "db.php";

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if (!$result) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Failed to fetch products: " . $conn->error]);
    $conn->close();
    exit;
}

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

header('Content-Type: application/json');
echo json_encode($products);

$conn->close();
?>
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

// ✅ get only selected products from form
if (!isset($_POST['order_items']) || count($_POST['order_items']) === 0) {
    die("No items selected.");
}
$selectedItems = $_POST['order_items'];
$placeholders = implode(',', array_fill(0, count($selectedItems), '?'));

// Build query for only selected cart items
$sql = "SELECT c.product_id, c.quantity, p.price, p.name 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.customer_name = ? 
        AND c.product_id IN ($placeholders)";

$stmt = $conn->prepare($sql);

// bind dynamic params (customer + product_ids)
$types = str_repeat('i', count($selectedItems)); // all product ids = int
$params = array_merge([$customer_name], $selectedItems);
$bindTypes = "s" . $types; // first param is string, rest are ints
$stmt->bind_param($bindTypes, ...$params);

$stmt->execute();
$result = $stmt->get_result();

$cart = [];
$total = 0;
while ($row = $result->fetch_assoc()) {
    $cart[] = $row;
    $total += $row['quantity'] * $row['price'];
}
$stmt->close();

// Create order only if something is in cart
if (count($cart) > 0) {
    $orderStmt = $conn->prepare("INSERT INTO orders (customer_name, total_amount) VALUES (?, ?)");
    $orderStmt->bind_param("sd", $customer_name, $total);
    $orderStmt->execute();
    $order_id = $orderStmt->insert_id;
    $orderStmt->close();

    // insert order_items
    $itemStmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cart as $item) {
        $itemStmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        $itemStmt->execute();
    }
    $itemStmt->close();

    // clear only ordered items from cart
    $clearSql = "DELETE FROM cart WHERE customer_name = ? AND product_id IN ($placeholders)";
    $clearStmt = $conn->prepare($clearSql);
    $clearStmt->bind_param($bindTypes, ...$params);
    $clearStmt->execute();
    $clearStmt->close();

    // show summary
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head><title>Order Summary</title></head>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
           
            margin: 0;
            padding: 0;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            padding: 40px 32px;
            max-width: 620px;
            width: 92%;
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            color: #28a745;
            text-align: center;
            font-size: 2rem;
            margin: 10px 0 18px;
            font-weight: 600;
        }

        .success-icon {
            display: block;
            font-size: 68px;
            text-align: center;
            margin-bottom: 12px;
            color: #28a745;
            animation: pop 0.6s ease forwards;
        }

        @keyframes pop {
            0% { transform: scale(0.3); opacity: 0; }
            80% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); }
        }

        .order-id {
            background: linear-gradient(45deg, #007bff, #00c6ff);
            color: white;
            display: block;
            width: fit-content;
            margin: 0 auto 25px;
            padding: 10px 18px;
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 500;
            letter-spacing: 0.4px;
            box-shadow: 0 3px 10px rgba(0,123,255,0.3);
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0 0 22px 0;
        }

        ul li {
            background: #fff;
            padding: 14px 18px;
            margin: 10px 0;
            border-radius: 10px;
            font-size: 1rem;
            color: #444;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: 0.25s ease;
        }

        ul li:hover {
            background: #f5faff;
            transform: translateY(-3px);
            box-shadow: 0 6px 14px rgba(0,0,0,0.12);
        }

        .total {
            text-align: right;
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 12px;
            color: #ff3b6b;
        }

        .btn-home {
            display: block;
            margin: 28px auto 0;
            background: linear-gradient(45deg, #ff6ec4, #7873f5);
            color: #fff;
            border: none;
            padding: 12px 26px;
            font-size: 1rem;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.25);
        }
    </style>
    <body>
    <div class="container">
        <span class="success-icon">✅</span>
        <h2>Order Placed Successfully!</h2>
        <div class="order-id">Order ID: <?php echo $order_id; ?></div>
        
        <ul>
            <?php foreach ($cart as $item): ?>
                <li>
                    <span><?php echo $item['name']; ?> x <?php echo $item['quantity']; ?></span>
                    <strong>₹<?php echo $item['price'] * $item['quantity']; ?></strong>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="total">Total: ₹<?php echo $total; ?></div>
        <a href="../index.php" class="btn-home">🏠 Back to Home</a>
    </div>
</body>
    </html>
    <?php
} else {
    echo "No valid items found.";
}

$conn->close();
?>

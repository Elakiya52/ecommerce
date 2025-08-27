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
$sql = "SELECT c.product_id, c.quantity, p.name, p.price, p.image 
        FROM cart c 
        JOIN products p ON c.product_id = p.id
        WHERE c.customer_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $customer_name);
$stmt->execute();
$result = $stmt->get_result();
$cart = [];
while ($row = $result->fetch_assoc()) {
    $cart[] = $row;
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>My Cart</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<style>
    /* Your CSS styles here (same as before) */
    body { font-family: 'Segoe UI', Arial, sans-serif; background:#f9f9f9; margin:0; padding:0; min-height:100vh;}
    .cart-container { max-width: 800px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 4px 24px rgba(0,0,0,0.10); padding:40px 24px;}
    h2 { text-align:center; font-size:2rem; margin-bottom:32px; color: #2c3e50;}
    .cart-item { display: flex; align-items: center; gap:28px; border-bottom:1px solid #eee; padding:20px 0;}
    .cart-item:last-child {border-bottom:none;}
    .cart-item img { width: 92px; height: 92px; border-radius:8px; object-fit:cover; box-shadow:0 1px 6px rgba(0,0,0,0.10);}
    .cart-item-details {flex:1;}
    .cart-item-details h4 { margin: 0 0 12px 0; font-size:1.1rem; color: #333;}
    .cart-item-details p {margin:6px 0; color: #788392; font-size:1rem;}
    .remove-btn { background: #e74c3c; color: #fff; border: none; padding: 7px 18px; border-radius: 6px; cursor: pointer; font-weight: 500; transition: background 0.2s;}
    .remove-btn:hover { background: #c0392b;}
    .total-section {display:flex; justify-content: flex-end; align-items:center; gap:50px; margin-top:32px;}
    .total-label { font-weight:600; font-size:1.05rem;}
    .total-value { font-size:1.3rem; color:#159957;}
    .order-btn {margin-left:20px; background:#159957; color: #fff; border:none; border-radius:7px; padding: 11px 28px; font-size:1.1rem; font-weight:500; cursor:pointer; box-shadow:0 2px 5px 0 rgba(21,153,87,0.12); transition:background 0.18s;}
    .order-btn:hover { background: #138f68;}
    @media (max-width:600px){
        .cart-container{ padding:20px 6px;}
        .cart-item{flex-direction:column; align-items:stretch; gap:16px;}
        .cart-item img { width: 100%; height: 70vw; min-height:100px;}
        .total-section{ flex-direction:column; gap:18px;}
    }
    .empty-cart { text-align:center; color:#789; font-size:1.15rem; margin-top:60px;}
    .select-checkbox { width: 20px; height: 20px; cursor: pointer; }
</style>
</head>
<body>
<div class="cart-container">
    <h2>🛒 Your Cart</h2>
    <?php if (count($cart) > 0): ?>
    
        <?php foreach ($cart as $index => $item): ?>
            <div class="cart-item">
                <!-- Remove button outside of the form below -->
                <button type="button" class="remove-btn" onclick="submitRemoveForm(<?php echo $item['product_id']; ?>)">Remove</button>
                <!-- The hidden form to submit the remove request -->
                <form id="removeForm<?php echo $item['product_id']; ?>" method="post" action="removeFromCart.php" style="display:none;">
                    <input type="hidden" name="id" value="<?php echo $item['product_id']; ?>">
                </form>

                <input 
                    type="checkbox" 
                    class="select-checkbox" 
                    data-price="<?php echo $item['price']; ?>" 
                    data-quantity="<?php echo $item['quantity']; ?>"
                    name="order_items[]" 
                    value="<?php echo $item['product_id']; ?>" 
                    checked
                    onchange="updateTotal()" 
                />
                <img src="../assets/images/<?php echo htmlspecialchars($item['image']);?>" alt="<?php echo htmlspecialchars($item['name']); ?>"/>
                <div class="cart-item-details">
                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                    <p>Price: <strong>₹<?php echo $item['price']; ?></strong></p>
                    <p>Quantity: <strong><?php echo $item['quantity']; ?></strong></p>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- ORDER FORM starts HERE: only order inputs and button inside the form -->
        <form id="orderForm" method="post" action="placeOrder.php" onsubmit="return validateSelection()">
            <?php foreach ($cart as $item): ?>
                <input type="hidden" name="order_items[]" value="<?php echo $item['product_id']; ?>" />
            <?php endforeach; ?>
            <div class="total-section">
                <span class="total-label">Total Selected Amount:</span>
                <span id="totalAmount" class="total-value">₹0</span>
                <button type="submit" class="order-btn">Place Order</button>
            </div>
        </form>

    <?php else: ?>
        <div class="empty-cart"><p>Your cart is empty 🚫</p></div>
    <?php endif; ?>
</div>

<script>
function updateTotal() {
    const checkboxes = document.querySelectorAll('.select-checkbox');
    const orderForm = document.getElementById('orderForm');
    let total = 0;
    let orderIds = [];
    checkboxes.forEach((cb) => {
        if(cb.checked) {
            const price = parseFloat(cb.getAttribute('data-price'));
            const quantity = parseInt(cb.getAttribute('data-quantity'));
            total += price * quantity;
            orderIds.push(cb.value);
        }
    });
    document.getElementById('totalAmount').textContent = '₹' + total.toFixed(2);

    // Update hidden inputs in order form to match checked items
    orderForm.innerHTML = '';
    orderIds.forEach(id => {
        let input = document.createElement("input");
        input.type = "hidden";
        input.name = "order_items[]";
        input.value = id;
        orderForm.appendChild(input);
    });

    // Also add order button again (since replaced innerHTML)
    let div = document.createElement('div');
    div.classList.add('total-section');
    div.innerHTML = `
      <span class="total-label">Total Selected Amount:</span>
      <span id="totalAmount" class="total-value">₹${total.toFixed(2)}</span>
      <button type="submit" class="order-btn">Place Order</button>
    `;
    orderForm.appendChild(div);
}

function validateSelection() {
    const checkboxes = document.querySelectorAll('.select-checkbox');
    let isAnySelected = false;
    checkboxes.forEach((cb) => {
        if(cb.checked) { isAnySelected = true; }
    });
    if(!isAnySelected) {
        alert("Please select at least one item to place order.");
        return false;
    }
    return true;
}

function submitRemoveForm(productId) {
    document.getElementById('removeForm' + productId).submit();
}

// Initialize total on page load
updateTotal();
</script>
</body>
</html>

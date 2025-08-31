<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Mini E-Commerce</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
  <!-- Navbar -->
  <div class="navbar">
    <h1>🛒 My Shop</h1>
    <a href="backend/mycard.php" class="cart-link">
      <span class="cart-icon" id="cart1">🛍️</span>
    </a>
    

    <div>
  <?php if (isset($_SESSION['user'])): ?>
      <a href="logout.php" class="btn-link">Logout</a>
  <?php endif; ?>
    </div>

  </div>

  <!-- Products -->
  <h2 class="section-title">✨ Products</h2>
  <div class="products" id="products"></div>

  <script src="assets/js/script.js"></script>
</body>
</html>

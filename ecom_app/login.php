<?php
session_start();
require __DIR__ . '/backend/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['username'];
        header("Location: index.php");
    } else {
        echo "<p style='color:red;text-align:center;'>Invalid email or password!</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="auth-container">
    <form class="auth-form" method="post">
        <h2>Login</h2>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
        <a href="register.php">New user? Register here</a>
    </form>
  </div>
</body>
</html>

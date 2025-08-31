<?php
session_start();
require __DIR__ . '/backend/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        $_SESSION['user'] = $username;
        header("Location: index.php");
    } else {
        echo "<p style='color:red;text-align:center;'>Error: " . $stmt->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="auth-container">
    <form class="auth-form" method="post">
        <h2>Register</h2>
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Register</button>
        <a href="login.php">Already have an account? Login</a>
    </form>
  </div>
</body>
</html>

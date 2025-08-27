<?php
$host = "localhost"; 
$user = "root";     // default WAMP user
$pass = "";         // default WAMP has no password
$db   = "ecommerce";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
} 
?>

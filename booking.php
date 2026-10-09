<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);
    $orderDate = date("Y-m-d H:i:s");

    // Save booking in DB (optional)
    $stmt = $conn->prepare("INSERT INTO bookings (name, email, phone, address, order_date) VALUES (?,?,?,?,?)");
    $stmt->execute([$name, $email, $phone, $address, $orderDate]);

    // Clear cart after booking
    unset($_SESSION['cart']);

    $success = "Your booking has been placed successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking - Omkar Furniture</title>
<style>
body{margin:0;font-family:'Segoe UI',sans-serif;background:#f8f9fb;color:#333;}
header{background:#fff;padding:15px 30px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #eee;}
header h1{margin:0;font-size:22px;color:#333;}
nav{display:flex;gap:20px;align-items:center;}
nav a{text-decoration:none;color:#444;font-weight:500;}
nav a:hover{color:#ff7043;}
.container{max-width:600px;margin:40px auto;padding:20px;background:#fff;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.1);}
h2{text-align:center;color:#333;}
form{display:flex;flex-direction:column;gap:15px;}
input,textarea{padding:10px;border:1px solid #ccc;border-radius:5px;font-size:15px;}
.btn{background:#ff7043;color:#fff;padding:10px;border:none;border-radius:5px;cursor:pointer;}
.btn:hover{background:#e85b2e;}
.success{background:#28a745;color:#fff;padding:10px;border-radius:5px;text-align:center;margin-bottom:15px;}
</style>
</head>
<body>

<header>
  <h1>Omkar Furniture</h1>
  <nav>
    <a href="index.php">Home</a>
    <a href="products.php">Shop</a>
    <a href="cart.php">Cart</a>
    <a href="about.php">About</a>
    <a href="#">Contact</a>
  </nav>
</header>

<div class="container">
  <h2>Booking Form</h2>

  <?php if(isset($success)): ?>
    <div class="success"><?php echo $success; ?></div>
  <?php endif; ?>

  <form method="POST">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email Address" required>
    <input type="text" name="phone" placeholder="Phone Number" required>
    <textarea name="address" placeholder="Delivery Address" rows="4" required></textarea>
    <button type="submit" class="btn">Confirm Booking</button>
  </form>
</div>

</body>
</html>

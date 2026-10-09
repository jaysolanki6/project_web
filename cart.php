<?php
session_start();
include 'connect.php';

// Handle quantity update or delete
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST['product_id'];

    if (isset($_POST['update_qty'])) {
        $newQty = (int)$_POST['quantity'];
        if ($newQty > 0) {
            $_SESSION['cart'][$productId]['quantity'] = $newQty;
        } else {
            unset($_SESSION['cart'][$productId]);
        }
    }

    if (isset($_POST['delete_item'])) {
        unset($_SESSION['cart'][$productId]);
    }

    header("Location: cart.php"); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cart - Omkar Furniture</title>
<style>
body{margin:0;font-family:'Segoe UI',sans-serif;background:#f8f9fb;color:#333;}
header{background:#fff;padding:15px 30px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #eee;}
header h1{margin:0;font-size:22px;color:#333;}
nav{display:flex;gap:20px;align-items:center;}
nav a{text-decoration:none;color:#444;font-weight:500;}
nav a:hover{color:#ff7043;}
.btn{background:#ff7043;color:#fff;padding:6px 12px;border:none;border-radius:5px;cursor:pointer;text-decoration:none;}
.btn:hover{background:#e85b2e;}
.delete-btn{background:red;color:#fff;border:none;padding:4px 8px;border-radius:4px;cursor:pointer;}
.delete-btn:hover{background:#cc0000;}
table{width:100%;border-collapse:collapse;background:#fff;margin-top:20px;}
th,td{padding:10px;text-align:left;border-bottom:1px solid #ddd;}
th{background:#ff7043;color:#fff;}
.total{text-align:right;font-weight:bold;}
.qty-form{display:flex;gap:5px;align-items:center;}
.qty-form input{width:50px;text-align:center;}
</style>
</head>
<body>

<!-- Header -->
<header>
  <h1>Omkar Furniture</h1>
  <nav>
    <a href="index.php">Home</a>
    <a href="products.php">Shop</a>
    <a href="cart.php">Cart</a>
    <a href="about.php">About</a>
    <a href="#">Contact</a>
    <?php if(isset($_SESSION['user'])): ?>
      <a href="profile.php" class="btn">Profile</a>
    <?php else: ?>
      <a href="login.php" class="btn">Login</a>
    <?php endif; ?>
  </nav>
</header>

<h2 style="text-align:center;margin-top:20px;">Your Cart</h2>

<?php
if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){
    $total = 0;
    echo "<table>
            <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th>Action</th></tr>";

    foreach($_SESSION['cart'] as $id => $item){
        $name = isset($item['name']) ? $item['name'] : 'Unknown';
        $price = isset($item['price']) ? (float)$item['price'] : 0;
        $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
        $subtotal = $price * $quantity;
        $total += $subtotal;

        echo "<tr>
                <td>".htmlspecialchars($name)."</td>
                <td>₹ ".number_format($price,2)."</td>
                <td>
                  <form method='POST' class='qty-form'>
                    <input type='hidden' name='product_id' value='$id'>
                    <input type='number' name='quantity' value='$quantity' min='1'>
                    <button type='submit' name='update_qty' class='btn'>Update</button>
                  </form>
                </td>
                <td>₹ ".number_format($subtotal,2)."</td>
                <td>
                  <form method='POST'>
                    <input type='hidden' name='product_id' value='$id'>
                    <button type='submit' name='delete_item' class='delete-btn'>Delete</button>
                  </form>
                </td>
              </tr>";
    }

    echo "<tr><td colspan='3' class='total'>Total</td><td>₹ ".number_format($total,2)."</td><td></td></tr>";
    echo "</table>";

    echo "<div style='text-align:center; margin-top:20px;'>
            <a href='index.php' class='btn'>Continue Shopping</a>
            <a href='booking.php' class='btn' style='margin-left:10px;background:#28a745;'>Book Now</a>
          </div>";
} else {
    echo "<p style='text-align:center;'>Your cart is empty. <a href='index.php'>Shop Now</a></p>";
}
?>

</body>
</html>

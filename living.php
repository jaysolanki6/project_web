<?php
session_start();
include 'connect.php';

// Agar product id na mile to shop page par bhej do
if(!isset($_GET['id']) || empty($_GET['id'])){
    header("Location: products.php");
    exit;
}

$product_id = intval($_GET['id']);

// Product fetch karo
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$product){
    echo "<h2 style='text-align:center; margin-top:50px;'>Product not found!</h2>";
    exit;
}

// Add to cart logic
if(isset($_POST['add_to_cart'])){
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    if(isset($_SESSION['cart'][$product_id])){
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => 1
        ];
    }

    header("Location: cart.php");
    exit;
}

// Image path
$imgName = str_replace(" ","%20",$product['image']);
$imagePath = (filter_var($product['image'], FILTER_VALIDATE_URL)) 
             ? $product['image'] 
             : (file_exists("uploads/".$product['image']) 
                ? "uploads/".$imgName 
                : "https://via.placeholder.com/500x400?text=No+Image");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($product['name']); ?> - Omkar Furniture</title>
  <style>
    body { margin:0; font-family:'Segoe UI', sans-serif; background:#f8f9fb; color:#333; }
    header { background:#fff; padding:15px 30px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #eee; }
    header h1 { margin:0; font-size:22px; color:#333; }
    nav { display:flex; gap:20px; align-items:center; }
    nav a { text-decoration:none; color:#444; font-weight:500; }
    nav a:hover { color:#ff7043; }
    .btn { background:#ff7043; color:#fff; padding:10px 20px; border:none; border-radius:25px; cursor:pointer; text-decoration:none; display:inline-block; font-size:15px; }
    .btn:hover { background:#e85b2e; }

    .container { max-width:1100px; margin:40px auto; padding:20px; background:#fff; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.08); display:grid; grid-template-columns:1fr 1fr; gap:40px; }
    .product-img { width:100%; height:400px; object-fit:cover; border-radius:12px; }
    .details h2 { font-size:28px; margin-bottom:15px; }
    .details .price { font-size:22px; font-weight:bold; color:#ff7043; margin:15px 0; }
    .details p { color:#555; line-height:1.6; }
    .details form { margin-top:25px; }
    .back-link { display:inline-block; margin:20px 0; color:#444; text-decoration:none; font-weight:500; }
    .back-link:hover { color:#ff7043; }
    footer { background:#333; color:#fff; text-align:center; padding:20px; margin-top:40px; }

    @media(max-width:768px){
      .container { grid-template-columns:1fr; }
      .product-img { height:300px; }
    }
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
  </nav>
</header>

<!-- Product Details -->
<div class="container">
  <div>
    <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-img">
  </div>
  <div class="details">
    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
    <p class="price">₹ <?php echo htmlspecialchars($product['price']); ?></p>
    <p>
      <?php 
        echo !empty($product['description']) 
              ? nl2br(htmlspecialchars($product['description'])) 
              : "No description available for this product."; 
      ?>
    </p>
    <form method="POST">
      <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
      <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['price']); ?>">
      <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
    </form>
    <a href="products.php" class="back-link">← Back to Shop</a>
  </div>
</div>

<footer>
  <p>© 2025 Omkar Furniture. All rights reserved.</p>
</footer>

</body>
</html>

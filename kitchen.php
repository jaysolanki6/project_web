<?php
session_start();
include 'connect.php';

// Fetch only kitchen products
$stmt = $conn->prepare("SELECT * FROM products WHERE category = 'kitchen'");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kitchen Furniture - Omkar Furniture</title>
  <style>
    body { margin:0; font-family:'Segoe UI', sans-serif; background:#f8f9fb; color:#333; }
    header { background:#fff; padding:15px 30px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #eee; }
    header h1 { margin:0; font-size:22px; color:#333; }
    nav { display:flex; gap:20px; align-items:center; }
    nav a { text-decoration:none; color:#444; font-weight:500; }
    nav a:hover { color:#ff7043; }
    .container { max-width:1200px; margin:40px auto; padding:20px; }
    h2 { font-size:26px; margin-bottom:25px; color:#333; }
    .grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:25px; }
    .card { background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 4px 15px rgba(0,0,0,0.08); transition:transform 0.2s; }
    .card:hover { transform:translateY(-5px); }
    .card img { width:100%; height:200px; object-fit:cover; }
    .card-body { padding:15px; }
    .card-body h3 { font-size:18px; margin:0 0 10px; color:#333; }
    .card-body p { font-size:14px; color:#666; margin:0 0 12px; }
    .price { font-size:16px; font-weight:bold; color:#ff7043; margin-bottom:12px; }
    .btn { display:inline-block; background:#ff7043; color:#fff; padding:8px 16px; border-radius:25px; text-decoration:none; font-size:14px; }
    .btn:hover { background:#e85b2e; }
    footer { background:#333; color:#fff; text-align:center; padding:20px; margin-top:40px; }
  </style>
</head>
<body>

<!-- Header -->
<header>
  <h1>Omkar Furniture</h1>
  <nav>
    <a href="index.php">Home</a>
    <a href="products.php">Shop</a>
    <a href="kitchen.php" style="color:#ff7043;">Kitchen</a>
    <a href="cart.php">Cart</a>
    <a href="about.php">About</a>
    <a href="contact.php">Contact</a>
  </nav>
</header>

<!-- Kitchen Products -->
<div class="container">
  <h2>Kitchen Furniture</h2>
  <div class="grid">
    <?php if($products): ?>
      <?php foreach($products as $row): ?>
        <?php 
          $imgName = str_replace(" ","%20",$row['image']);
          $imagePath = (filter_var($row['image'], FILTER_VALIDATE_URL)) ? $row['image'] : (file_exists("uploads/".$row['image']) ? "uploads/".$imgName : "https://via.placeholder.com/400x300?text=No+Image");
        ?>
        <div class="card">
          <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
          <div class="card-body">
            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
            <p><?php echo substr(htmlspecialchars($row['description']),0,60).'...'; ?></p>
            <div class="price">₹ <?php echo htmlspecialchars($row['price']); ?></div>
            <a href="living.php?id=<?php echo $row['id']; ?>" class="btn">View Details</a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No kitchen products available.</p>
    <?php endif; ?>
  </div>
</div>

<!-- Footer -->
<footer>
  <p>© 2025 Omkar Furniture. All rights reserved.</p>
</footer>

</body>
</html>

<?php  
session_start(); 
include 'connect.php';  

// Add to cart logic
if(isset($_POST['add_to_cart'])){
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    // If product already in cart, increase quantity
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
?>  

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shop - Omkar Furniture</title>
  <style>
    body { margin:0; font-family:'Segoe UI', sans-serif; background:#f8f9fb; color:#333; }
    header { background:#fff; padding:15px 30px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #eee; }
    header h1 { margin:0; font-size:22px; color:#333; }
    nav { display:flex; gap:20px; align-items:center; }
    nav a { text-decoration:none; color:#444; font-weight:500; }
    nav a:hover { color:#ff7043; }
    .btn { background:#ff7043; color:#fff; padding:8px 16px; border:none; border-radius:20px; cursor:pointer; text-decoration:none; display:inline-block; }
    .btn:hover { background:#e85b2e; }
    .profile-link { display:inline-block; border-radius:50%; overflow:hidden; width:40px; height:40px; }
    .profile-img { width:100%; height:100%; object-fit:cover; border-radius:50%; border:2px solid #ff7043; transition:0.3s; }
    .profile-img:hover { transform:scale(1.1); }
    .menu-toggle { display:none; flex-direction:column; cursor:pointer; }
    .menu-toggle span { height:3px; width:25px; background:#333; margin:4px 0; border-radius:5px; }
    @media(max-width:768px){
      nav{position:absolute;top:60px;left:0;right:0;background:#fff;flex-direction:column;align-items:center;display:none;padding:15px 0;box-shadow:0 4px 6px rgba(0,0,0,0.05);}
      nav.active{display:flex;} 
      .menu-toggle{display:flex;}
    }

    /* Hero Section */
    .hero { position:relative; display:flex; align-items:center; justify-content:center; padding:50px 20px; height:300px; background:#ffefd5; }
    .hero-text { text-align:center; max-width:600px; }
    .hero-text h2 { font-size:36px; color:#333; margin:0 0 20px 0; }
    .hero-text p { margin:0; color:#555; font-size:18px; }

    /* Products Grid */
    .products { padding:50px 20px; background:#fff; }
    .products h2 { text-align:center; margin-bottom:30px; font-size:28px; color:#333; font-weight:600; }
    .grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:25px; }

    .product-card {
      background:#fff;
      border-radius:12px;
      overflow:hidden;
      box-shadow:0 4px 10px rgba(0,0,0,0.08);
      transition:transform 0.3s, box-shadow 0.3s;
      display:flex;
      flex-direction:column;
      text-align:center;
    }
    .product-card:hover {
      transform:translateY(-5px);
      box-shadow:0 6px 15px rgba(0,0,0,0.12);
    }
    .product-card a {
      text-decoration:none;
      color:inherit;
      flex:1;
      display:flex;
      flex-direction:column;
      align-items:center;
      padding:15px;
    }
    .product-card img {
      width:100%;
      height:200px;
      object-fit:cover;
      border-radius:10px;
      margin-bottom:12px;
    }
    .product-card h3 { font-size:18px; margin:8px 0 4px; color:#333; }
    .product-card p.price { font-size:16px; font-weight:bold; color:#ff7043; margin:0; }
    .product-card form {
      padding:15px;
      border-top:1px solid #f0f0f0;
    }

    /* Footer */
    footer { background:#333; color:#fff; text-align:center; padding:20px; margin-top:30px; }
  </style>
</head>
<body>

<!-- Header -->
<header>
  <h1>Omkar Furniture</h1>
  <nav id="menu">
    <a href="index.php">Home</a>
    <a href="products.php">Shop</a>
    <a href="cart.php">Cart</a>
    <a href="about.php">About</a>
    <a href="#">Contact</a>
    <?php if(isset($_SESSION['user'])): ?>
      <a href="profile.php" class="profile-link">
        <?php
          $stmtUser = $conn->prepare("SELECT profile_pic FROM users WHERE name = ?");
          $stmtUser->execute([$_SESSION['user']]);
          $userRow = $stmtUser->fetch(PDO::FETCH_ASSOC);
          $profilePic = (!empty($userRow['profile_pic']) && file_exists("uploads/".$userRow['profile_pic'])) ? "uploads/".htmlspecialchars($userRow['profile_pic']) : "https://via.placeholder.com/40x40?text=U";
        ?>
        <img src="<?php echo $profilePic; ?>" alt="Profile" class="profile-img">
      </a>
    <?php else: ?>
      <a href="login.php" class="btn">Login</a>
    <?php endif; ?>
  </nav>
  <div class="menu-toggle" onclick="toggleMenu()"><span></span><span></span><span></span></div>
</header>

<!-- Hero -->
<section class="hero">
  <div class="hero-text">
    <h2>Shop Our Furniture Collection</h2>
    <p>Browse through all our products and find the perfect pieces for your home.</p>
  </div>
</section>

<!-- Products -->
<section class="products">
  <h2>All Products</h2>
  <div class="grid">
    <?php
      $stmt = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $imgName = str_replace(" ","%20",$row['image']);
        $imagePath = (filter_var($row['image'], FILTER_VALIDATE_URL)) ? $row['image'] : (file_exists("uploads/".$row['image']) ? "uploads/".$imgName : "https://via.placeholder.com/300x200?text=No+Image");

        echo "
        <div class='product-card'>
          <a href='living.php?id=".htmlspecialchars($row['id'])."'>
            <img src='{$imagePath}' alt='".htmlspecialchars($row['name'])."'>
            <h3>".htmlspecialchars($row['name'])."</h3>
            <p class='price'>₹ ".htmlspecialchars($row['price'])."</p>
          </a>
          <form method='POST'>
            <input type='hidden' name='product_id' value='".htmlspecialchars($row['id'])."'>
            <input type='hidden' name='product_name' value='".htmlspecialchars($row['name'])."'>
            <input type='hidden' name='product_price' value='".htmlspecialchars($row['price'])."'>
            <button type='submit' name='add_to_cart' class='btn'>Add to Cart</button>
          </form>
        </div>
        ";
      }
    ?>
  </div>
</section>

<footer>
  <p>© 2025 Omkar Furniture. All rights reserved.</p>
</footer>

<script>
function toggleMenu(){
  document.getElementById("menu").classList.toggle("active");
}
</script>

</body>
</html>

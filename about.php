<?php session_start(); ?>
<?php include 'connect.php'; ?> 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - Omkar Furniture</title>
  <style>
    body { margin:0; font-family:'Segoe UI', sans-serif; background:#f8f9fb; color:#333; }

    /* Header */
    header { background:#fff; padding:15px 30px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #eee; position:relative; }
    header h1 { margin:0; font-size:22px; color:#333; }
    nav { display:flex; gap:20px; align-items:center; }
    nav a { text-decoration:none; color:#444; font-weight:500; }
    nav a:hover { color:#ff7043; }
    .btn { background:#ff7043; color:#fff; padding:8px 16px; border:none; border-radius:20px; cursor:pointer; text-decoration:none; }
    .btn:hover { background:#e85b2e; }

    /* Profile Image */
    .profile-link { display:inline-block; border-radius:50%; overflow:hidden; width:40px; height:40px; }
    .profile-img { width:100%; height:100%; object-fit:cover; border-radius:50%; border:2px solid #ff7043; transition:0.3s; }
    .profile-img:hover { transform:scale(1.1); }

    /* Hamburger */
    .menu-toggle { display:none; flex-direction:column; cursor:pointer; }
    .menu-toggle span { height:3px; width:25px; background:#333; margin:4px 0; border-radius:5px; }
    @media(max-width:768px){
      nav { position:absolute; top:60px; left:0; right:0; background:#fff; flex-direction:column; align-items:center; display:none; padding:15px 0; box-shadow:0 4px 6px rgba(0,0,0,0.05); }
      nav.active { display:flex; }
      .menu-toggle { display:flex; }
    }

    /* About Section */
    .about { padding:60px 20px; max-width:1100px; margin:auto; }
    .about h2 { text-align:center; font-size:32px; margin-bottom:20px; color:#ff7043; }
    .about p { font-size:17px; line-height:1.7; text-align:center; max-width:800px; margin:10px auto; color:#555; }

    .about-grid { margin-top:50px; display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:30px; }
    .about-card { background:#fff; padding:25px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.1); transition:0.3s; text-align:center; }
    .about-card:hover { transform:translateY(-5px); }
    .about-card h3 { margin-bottom:10px; color:#ff7043; font-size:20px; }
    .about-card p { color:#666; font-size:15px; }

    /* Footer */
    footer { background:#222; color:#fff; padding:50px 20px 20px; margin-top:30px; font-size:15px; }
    .footer-container { display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:30px; max-width:1200px; margin:auto; }
    .footer-section h3 { margin-bottom:15px; font-size:18px; color:#ff7043; }
    .footer-section p, .footer-section ul, .footer-section li { margin:0; padding:0; list-style:none; line-height:1.6; color:#ccc; }
    .footer-section ul li a { color:#ccc; text-decoration:none; transition:0.3s; }
    .footer-section ul li a:hover { color:#ff7043; }
    .newsletter { display:flex; gap:10px; margin-top:10px; }
    .newsletter input { padding:8px; border:none; border-radius:5px; flex:1; }
    .newsletter button { padding:8px 15px; border:none; background:#ff7043; color:#fff; border-radius:5px; cursor:pointer; transition:0.3s; }
    .newsletter button:hover { background:#e85b2e; }
    .social-icons { margin-top:15px; display:flex; gap:10px; }
    .social-icons a { display:flex; align-items:center; justify-content:center; width:40px; height:40px; background:#333; border-radius:50%; color:#fff; font-size:18px; transition:0.3s; }
    .social-icons a:hover { background:#ff7043; transform:scale(1.1); }
    .footer-bottom { text-align:center; margin-top:30px; border-top:1px solid rgba(255,255,255,0.1); padding-top:15px; font-size:14px; color:#aaa; }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<!-- Header -->
<header>
  <h1>Omkar Furniture</h1>
  <nav id="menu">
    <a href="index.php">Home</a>
    <a href="products.php">Shop</a>
    <a href="cart.php">Cart</a>
    <a href="about.php" style="color:#ff7043;">About</a>
    <a href="contact.php">Contact</a>
    <?php if(isset($_SESSION['user'])): ?>
      <a href="profile.php" class="profile-link">
        <?php
          $stmtUser = $conn->prepare("SELECT profile_pic FROM users WHERE name = ?");
          $stmtUser->execute([$_SESSION['user']]);
          $userRow = $stmtUser->fetch(PDO::FETCH_ASSOC);
          if(!empty($userRow['profile_pic']) && file_exists("uploads/".$userRow['profile_pic'])){
              $profilePic = "uploads/".htmlspecialchars($userRow['profile_pic']);
          } else {
              $profilePic = "https://via.placeholder.com/40x40?text=U";
          }
        ?>
        <img src="<?php echo $profilePic; ?>" alt="Profile" class="profile-img">
      </a>
    <?php else: ?>
      <a href="login.php" class="btn">Login</a>
    <?php endif; ?>
  </nav>
  <div class="menu-toggle" onclick="toggleMenu()">
    <span></span><span></span><span></span>
  </div>
</header>

<!-- About Section -->
<section class="about">
  <h2>About Omkar Furniture</h2>
  <p>At Omkar Furniture, we believe that your home deserves the best. For years, we have been committed to delivering high-quality, stylish, and affordable furniture that not only adds beauty but also provides comfort to your living spaces.</p>
  <p>Our mission is to blend craftsmanship with modern designs, ensuring every piece of furniture speaks elegance and durability. Whether it’s your living room, bedroom, or office, Omkar Furniture has something special for you.</p>

  <div class="about-grid">
    <div class="about-card">
      <h3>Our Mission</h3>
      <p>To provide elegant, durable, and affordable furniture that transforms houses into homes.</p>
    </div>
    <div class="about-card">
      <h3>Our Vision</h3>
      <p>To become India’s most trusted furniture brand known for quality and innovation.</p>
    </div>
    <div class="about-card">
      <h3>Our Values</h3>
      <p>We stand by quality, trust, customer satisfaction, and timeless design.</p>
    </div>
  </div>
</section>

<!-- Footer -->
<footer>
  <div class="footer-container">
    <!-- About -->
    <div class="footer-section">
      <h3>Omkar Furniture</h3>
      <p>We bring you stylish, modern, and affordable furniture crafted with care. Make your home elegant and cozy with our unique collection.</p>
    </div>

    <!-- Quick Links -->
    <div class="footer-section">
      <h3>Quick Links</h3>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="products.php">Shop</a></li>
        <li><a href="cart.php">Cart</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </div>

    <!-- Contact Info -->
    <div class="footer-section">
      <h3>Contact Us</h3>
      <p>📍 Omkar Furniture, Ram Nagar Khara Vistar, Una 362560, Gujarat, India</p>
      <p>📞 +91 7622880021</p>
      <p>📧 jaysb0009@gmail.com</p>
    </div>

    <!-- Newsletter -->
    <div class="footer-section">
      <h3>Stay Updated</h3>
      <p>Subscribe to get our latest offers & updates.</p>
      <form class="newsletter">
        <input type="email" placeholder="Enter your email" required>
        <button type="submit">Subscribe</button>
      </form>
      <div class="social-icons">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="https://www.instagram.com/jayyyy.008/"><i class="fab fa-instagram"></i></a>
        <a href="https://x.com/jaysolanki_16"><i class="fab fa-twitter"></i></a>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    <p>© 2025 Omkar Furniture. All rights reserved. | Designed with ❤️ by Solanki Group Of Company</p>
  </div>
</footer>

<script>
function toggleMenu(){
  document.getElementById("menu").classList.toggle("active");
}
</script>

</body>
</html>

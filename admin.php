<?php
session_start();
include 'connect.php';

// ✅ Add Product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name  = $_POST['name'];
    $desc  = $_POST['description'];
    $price = $_POST['price'];

    $image = "";

    // Agar image URL diya hai
    if (!empty($_POST['image_url'])) {
        $image = $_POST['image_url'];
    }
    // Agar file upload ki gayi hai
    elseif (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?,?,?,?)");
    if ($stmt->execute([$name, $desc, $price, $image])) {
        $msg = "✅ Product Added Successfully!";
    } else {
        $msg = "❌ Failed to add product!";
    }
}

// ✅ Delete Product
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $conn->prepare("SELECT image FROM products WHERE id=?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Agar file uploads me hai to delete karo
    if ($product && file_exists("uploads/" . $product['image'])) {
        unlink("uploads/" . $product['image']);
    }

    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    if ($stmt->execute([$id])) {
        $msg = "🗑️ Product Deleted Successfully!";
    } else {
        $msg = "❌ Failed to delete product!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Omkar Furniture</title>
  <style>
    body {font-family: 'Segoe UI', sans-serif;background:#f4f6f9;margin:0; padding:0;color:#333;}
    header {background: #4a90e2;color: #fff;padding: 20px;text-align: center;}
    header h1 {margin:0; font-size:24px;}
    nav {background: #e9eff6;padding: 10px;text-align: center;border-bottom:1px solid #ccc;}
    nav a {margin: 0 10px;text-decoration: none;color:#4a90e2;font-weight:500;padding:6px 12px;border-radius:5px;transition: background 0.2s;}
    nav a:hover {background:#d6e6fa;}
    .container {padding: 20px;max-width: 1000px;margin: auto;}
    .section {background: #fff;padding: 20px;margin-bottom: 25px;border-radius: 8px;border:1px solid #ddd;}
    .section h2 {margin-top:0;color:#4a90e2;font-size:20px;}
    form input, form textarea, form button {width: 100%;padding: 10px;margin: 6px 0;border-radius: 6px;border:1px solid #ccc;font-size:14px;background:#fdfdfd;}
    form button {background: #4a90e2;border:none;color:#fff;font-size:15px;cursor:pointer;}
    form button:hover {background:#3c7bc0;}
    table {width:100%;border-collapse:collapse;margin-top:15px;}
    th, td {padding:10px;border:1px solid #ddd;text-align:center;font-size:14px;}
    th {background: #f1f6fb;color:#333;}
    tr:nth-child(even) {background:#fafafa;}
    img {border-radius:4px;}
    .msg {text-align:center;padding:10px;font-weight:500;border-radius:6px;margin-bottom:15px;background:#e8f9e8;color:#2e7d32;}
    .delete-btn {background:#e74c3c;color:#fff;padding:6px 10px;border-radius:5px;text-decoration:none;}
    .delete-btn:hover {background:#c0392b;}
  </style>
</head>
<body>

<header>
  <h1>🛠 Omkar Furniture - Admin Dashboard</h1>
</header>
<nav>
  <a href="#products">Manage Products</a>
  <a href="#users">Manage Users</a>
  <a href="index.php">Go to Website</a>
</nav>

<div class="container">

<?php if(isset($msg)) echo "<p class='msg'>$msg</p>"; ?>

<!-- ✅ Add Product Section -->
<div class="section" id="products">
  <h2>➕ Add New Product</h2>
  <form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Product Name" required>
    <textarea name="description" placeholder="Description"></textarea>
    <input type="number" step="0.01" name="price" placeholder="Price (₹)" required>
    
    <p><b>Choose Product Image:</b></p>
    <input type="file" name="image">
    <input type="url" name="image_url" placeholder="Or paste image URL">
    
    <button type="submit" name="add_product">Add Product</button>
  </form>

  <h2>📦 All Products</h2>
  <table>
    <tr>
      <th>ID</th><th>Name</th><th>Price</th><th>Image</th><th>Created</th><th>Action</th>
    </tr>
    <?php
    $stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $imagePath = (filter_var($row['image'], FILTER_VALIDATE_URL)) ? $row['image'] : "uploads/".$row['image'];
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>₹ {$row['price']}</td>
                <td><img src='{$imagePath}' width='70'></td>
                <td>{$row['created_at']}</td>
                <td><a href='?delete={$row['id']}' class='delete-btn' onclick=\"return confirm('Are you sure you want to delete this product?')\">Delete</a></td>
              </tr>";
    }
    ?>
  </table>
</div>

<!-- ✅ Users Section -->
<div class="section" id="users">
  <h2>👥 Registered Users</h2>
  <table>
    <tr>
      <th>ID</th><th>Name</th><th>Email</th><th>Joined</th>
    </tr>
    <?php
    $stmt = $conn->query("SELECT * FROM users ORDER BY id DESC");
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['created_at']}</td>
              </tr>";
    }
    ?>
  </table>
</div>

</div>
</body>
</html>

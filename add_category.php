<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $desc = $_POST['description'];

    // File upload
    $image = "";
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        $image = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $image;
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
    }

    $stmt = $conn->prepare("INSERT INTO categories (name, description, image) VALUES (?, ?, ?)");
    $stmt->execute([$name, $desc, $image]);

    $success = "Category added successfully!";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Add Category</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f5f5f5; padding:20px; }
    form { background:#fff; padding:20px; border-radius:8px; width:350px; margin:auto; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
    input, textarea { width:100%; padding:10px; margin:10px 0; border:1px solid #ccc; border-radius:5px; }
    button { background:#ff7043; color:#fff; padding:10px 20px; border:none; border-radius:5px; cursor:pointer; }
    button:hover { background:#e85b2e; }
    .msg { color:green; text-align:center; margin-bottom:10px; }
  </style>
</head>
<body>

<h2 style="text-align:center;">Add New Category</h2>

<?php if(isset($success)) echo "<p class='msg'>$success</p>"; ?>

<form method="POST" enctype="multipart/form-data">
  <input type="text" name="name" placeholder="Category Name" required>
  <textarea name="description" placeholder="Description"></textarea>
  <input type="file" name="image" accept="image/*" required>
  <button type="submit">Add Category</button>
</form>

</body>
</html>

<?php
session_start();
include 'connect.php';

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Profile picture upload
    $profilePic = null;
    if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0){
        $targetDir = "uploads/"; 
        if(!is_dir($targetDir)){
            mkdir($targetDir, 0777, true);
        }
        $fileName = time() . "_" . basename($_FILES["profile_pic"]["name"]);
        $targetFile = $targetDir . $fileName;
        if(move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)){
            $profilePic = $fileName;
        }
    }

    // Check if email already exists
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->execute([$email]);
    if($check->rowCount() > 0){
        $error = "⚠️ Email already exists! Please use another email.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name,email,password,address,profile_pic) VALUES (?,?,?,?,?)");
        if($stmt->execute([$name,$email,$password,$address,$profilePic])){
            $_SESSION['user'] = $name;
            $success = "✅ Signup successful! You can now login.";
        } else {
            $error = "❌ Error in signup!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Sign Up</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #74ebd5 0%, #ACB6E5 100%);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .container {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      width: 400px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      text-align: center;
      animation: fadeIn 0.8s ease-in-out;
    }
    h2 {
      margin-bottom: 20px;
      color: #333;
    }
    input, textarea {
      width: 100%;
      padding: 12px;
      margin: 8px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
      outline: none;
      transition: 0.3s;
    }
    input:focus, textarea:focus {
      border-color: #007bff;
      box-shadow: 0 0 6px rgba(0,123,255,0.3);
    }
    button {
      width: 100%;
      padding: 12px;
      background: #007bff;
      border: none;
      border-radius: 6px;
      color: #fff;
      font-size: 16px;
      cursor: pointer;
      margin-top: 10px;
      transition: 0.3s;
    }
    button:hover {
      background: #0056b3;
    }
    .success {
      color: green;
      font-weight: bold;
      margin-bottom: 10px;
    }
    .error {
      color: red;
      font-weight: bold;
      margin-bottom: 10px;
    }
    p {
      margin-top: 15px;
      font-size: 14px;
    }
    a {
      color: #007bff;
      text-decoration: none;
      font-weight: bold;
    }
    a:hover {
      text-decoration: underline;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Sign Up</h2>

    <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <textarea name="address" placeholder="Enter your address" required></textarea>
      <input type="file" name="profile_pic" accept="image/*">
      <button type="submit">Sign Up</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
  </div>
</body>
</html>

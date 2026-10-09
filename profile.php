<?php
session_start();
include 'connect.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

// Fetch user details
$stmt = $conn->prepare("SELECT * FROM users WHERE name=?");
$stmt->execute([$_SESSION['user']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$success = '';
$error = '';

// Handle form submission
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $name = $_POST['name'];
    $address = $_POST['address'];

    // Handle profile image upload
    if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error']==0){
        $fileName = time()."_".$_FILES['profile_pic']['name'];
        $fileTmp = $_FILES['profile_pic']['tmp_name'];
        $uploadDir = "uploads/";
        if(move_uploaded_file($fileTmp, $uploadDir.$fileName)){
            $profile_pic = $fileName;
            // Update DB with new profile pic
            $stmtUpdate = $conn->prepare("UPDATE users SET name=?, address=?, profile_pic=? WHERE name=?");
            $stmtUpdate->execute([$name, $address, $profile_pic, $_SESSION['user']]);
        } else {
            $error = "Profile picture upload failed!";
        }
    } else {
        // Update without changing profile pic
        $stmtUpdate = $conn->prepare("UPDATE users SET name=?, address=? WHERE name=?");
        $stmtUpdate->execute([$name, $address, $_SESSION['user']]);
    }

    $_SESSION['user'] = $name; // update session name
    $success = "Profile updated successfully!";
    
    // Refresh user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE name=?");
    $stmt->execute([$_SESSION['user']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Profile</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background:#f8f9fb;
      margin:0;
      display:flex;
      justify-content:center;
      align-items:center;
      min-height:100vh;
    }
    .profile-card {
      background:#fff;
      padding:30px;
      border-radius:12px;
      width:400px;
      text-align:center;
      box-shadow:0 6px 20px rgba(0,0,0,0.1);
      animation: fadeIn 0.8s ease-in-out;
    }
    .profile-card img {
      width:120px;
      height:120px;
      border-radius:50%;
      object-fit:cover;
      border:3px solid #ff7043;
      margin-bottom:15px;
    }
    h2 { margin:10px 0; color:#333; }
    p { margin:6px 0; color:#555; font-size:15px; }
    input[type="text"], input[type="file"], textarea {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      border-radius:6px;
      border:1px solid #ccc;
      font-size:14px;
    }
    textarea { resize: none; height:60px; }
    button {
      margin-top:15px;
      padding:10px 20px;
      background:#ff7043;
      color:#fff;
      border:none;
      border-radius:25px;
      cursor:pointer;
      font-weight:500;
      transition:0.3s;
    }
    button:hover { background:#e85b2e; }
    .message { margin:10px 0; font-weight:bold; }
    .success { color:green; }
    .error { color:red; }
    .btn-logout, .btn-home {
      display:block;
      margin:15px auto 0 auto;
      padding:8px 15px;
      border-radius:25px;
      text-decoration:none;
      color:#fff;
      width:120px;
      text-align:center;
      transition:0.3s;
    }
    .btn-logout { background:#555; }
    .btn-logout:hover { background:#333; }
    .btn-home { background:#ff7043; }
    .btn-home:hover { background:#e85b2e; }
    @keyframes fadeIn {
      from { opacity:0; transform:translateY(-20px); }
      to { opacity:1; transform:translateY(0); }
    }
  </style>
</head>
<body>
  <div class="profile-card">
    <?php
      if(!empty($user['profile_pic']) && file_exists("uploads/".$user['profile_pic'])){
          $profilePic = "uploads/".htmlspecialchars($user['profile_pic']);
      } else {
          $profilePic = "https://via.placeholder.com/120x120?text=U";
      }
    ?>
    <img src="<?php echo $profilePic; ?>" alt="Profile">

    <?php if($success) echo "<p class='message success'>$success</p>"; ?>
    <?php if($error) echo "<p class='message error'>$error</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
      <input type="text" name="name" placeholder="Full Name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
      <textarea name="address" placeholder="Address"><?php echo htmlspecialchars($user['address']); ?></textarea>
      <input type="file" name="profile_pic" accept="image/*">
      <button type="submit">Update Profile</button>
    </form>

    <a href="index.php" class="btn-home">Home</a>
    <a href="logout.php" class="btn-logout">Logout</a>
  </div>
</body>
</html>

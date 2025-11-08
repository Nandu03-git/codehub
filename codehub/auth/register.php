<?php
require_once __DIR__ . '/../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $stmt = $conn->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
  $stmt->bind_param('sss', $name, $email, $password);

  if ($stmt->execute()) {
    set_flash("Registration successful. Please login.", "success");
    header("Location: login.php");
    exit;
  } else {
    set_flash("Error: " . $conn->error, "danger");
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - CodeHub</title>
  <link rel="stylesheet" href="/codehub/assets/css/bootstrap.min.css">
  <style>
    /* From Uiverse.io by Yaya12085 */
    body {
      background-color: #0f172a;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: 'Inter', sans-serif;
    }

    .form-container {
      width: 320px;
      border-radius: 0.75rem;
      background-color: rgba(17, 24, 39, 1);
      padding: 2rem;
      color: rgba(243, 244, 246, 1);
      box-shadow: 0 0 20px rgba(0,0,0,0.3);
    }

    .title {
      text-align: center;
      font-size: 1.5rem;
      line-height: 2rem;
      font-weight: 700;
    }

    .form {
      margin-top: 1.5rem;
    }

    .input-group {
      margin-top: 0.75rem;
      font-size: 0.875rem;
      line-height: 1.25rem;
    }

    .input-group label {
      display: block;
      color: rgba(156, 163, 175, 1);
      margin-bottom: 4px;
    }

    .input-group input {
      width: 100%;
      border-radius: 0.375rem;
      border: 1px solid rgba(55, 65, 81, 1);
      outline: 0;
      background-color: rgba(17, 24, 39, 1);
      padding: 0.75rem 1rem;
      color: rgba(243, 244, 246, 1);
      transition: border-color 0.2s ease;
    }

    .input-group input:focus {
      border-color: rgba(167, 139, 250);
    }

    .sign {
      display: block;
      width: 100%;
      background-color: rgba(167, 139, 250, 1);
      padding: 0.75rem;
      text-align: center;
      color: rgba(17, 24, 39, 1);
      border: none;
      border-radius: 0.375rem;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.2s ease;
      margin-top: 1rem;
    }

    .sign:hover {
      background-color: rgba(129, 92, 255, 1);
    }

    .signup {
      text-align: center;
      font-size: 0.75rem;
      line-height: 1rem;
      color: rgba(156, 163, 175, 1);
      margin-top: 1rem;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2 class="title">Register</h2>

    <?php if(isset($_SESSION['flash_message'])): ?>
      <div class="alert alert-<?=$_SESSION['flash_type'] ?? 'info'?> mt-3">
        <?=htmlspecialchars($_SESSION['flash_message'])?>
      </div>
      <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>

    <form method="post" class="form">
      <div class="input-group">
        <label>Name</label>
        <input type="text" name="name" required>
      </div>

      <div class="input-group">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>

      <div class="input-group">
        <label>Password</label>
        <input type="password" name="password" required>
      </div>

      <button class="sign">Register</button>
    </form>

    <p class="signup">
      Already have an account?
      <a href="/codehub/auth/login.php">Login</a>
    </p>
  </div>
</body>
</html>

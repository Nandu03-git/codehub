<?php
require_once __DIR__ . '/../includes/config.php';

$token = $_GET['token'] ?? '';
if (!$token) { die("Invalid token."); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $pw = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token=? AND expires_at > NOW()");
  $stmt->bind_param('s', $token);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($row = $res->fetch_assoc()) {
    $email = $row['email'];
    $stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
    $stmt->bind_param('ss', $pw, $email);
    $stmt->execute();

    $conn->query("DELETE FROM password_resets WHERE email='$email'");
    set_flash("Password reset successful! Please log in.", "success");
    header("Location: /codehub/auth/login.php");
    exit;
  } else {
    set_flash("Invalid or expired token.", "danger");
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password - CodeHub</title>
  <link rel="stylesheet" href="/codehub/assets/css/bootstrap.min.css">
</head>
<body class="bg-dark d-flex align-items-center justify-content-center" style="height:100vh;">
  <div class="form-container p-4 bg-secondary text-white rounded">
    <h2 class="mb-3 text-center">Reset Password</h2>

    <?php if(isset($_SESSION['flash_message'])): ?>
      <div class="alert alert-<?=$_SESSION['flash_type'] ?? 'info'?> mt-3">
        <?=htmlspecialchars($_SESSION['flash_message'])?>
      </div>
      <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>

    <form method="post">
      <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button class="btn btn-light w-100">Reset Password</button>
    </form>
  </div>
</body>
</html>

<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../PHPMailer/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/../PHPMailer/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$msg = '';
$mode = 'email'; // default view

// Handle reset link (when user clicks email link)
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token=? AND expires_at > NOW()");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $email = $row['email'];
        $mode = 'reset'; // show password reset form
    } else {
        $msg = '<div class="alert alert-danger">Invalid or expired token.</div>';
    }
}

// Handle password reset form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    $email = $_POST['email'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $conn->query("UPDATE users SET password='$new_password' WHERE email='$email'");
    $conn->query("DELETE FROM password_resets WHERE email='$email'");
    $msg = '<div class="alert alert-success">Password has been reset successfully! <a href="login.php">Login now</a></div>';
    $mode = 'done';
}

// Handle email request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && !isset($_POST['new_password'])) {
    $email = $_POST['email'];
    $res = $conn->query("SELECT * FROM users WHERE email='$email'");

    if ($res->num_rows > 0) {
        $token = bin2hex(random_bytes(32));
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $conn->query("DELETE FROM password_resets WHERE email='$email'");
        $conn->query("INSERT INTO password_resets (email, token, expires_at) VALUES ('$email', '$token', '$expires_at')");

        $resetLink = "http://localhost/codehub/auth/forgot_password.php?token=$token";

        // send mail
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tr.nandan.05@gmail.com'; // change this
            $mail->Password = 'gmqv lmbl vvok smyy';   // change this
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('yourgmail@gmail.com', 'CodeHub');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset - CodeHub';
            $mail->Body = "Click the link below to reset your password:<br><br>
                          <a href='$resetLink'>$resetLink</a><br><br>
                          Link expires in 1 hour.";

            $mail->send();
            $msg = '<div class="alert alert-success">Password reset link has been sent to your email.</div>';
        } catch (Exception $e) {
            $msg = '<div class="alert alert-danger">Mailer Error: ' . $mail->ErrorInfo . '</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger">Email not found.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forgot Password - CodeHub</title>
<link rel="stylesheet" href="/codehub/assets/css/bootstrap.min.css">
<style>
body {
  background-color: #0f172a;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  font-family: 'Inter', sans-serif;
}
.form-container {
  width: 340px;
  border-radius: 0.75rem;
  background-color: rgba(17, 24, 39, 1);
  padding: 2rem;
  color: rgba(243, 244, 246, 1);
  box-shadow: 0 0 20px rgba(0,0,0,0.3);
}
.title {
  text-align: center;
  font-size: 1.5rem;
  font-weight: 700;
}
.sign {
  display: block;
  width: 100%;
  background-color: rgba(167, 139, 250, 1);
  padding: 0.75rem;
  border: none;
  border-radius: 0.375rem;
  font-weight: 600;
  cursor: pointer;
}
.sign:hover { background-color: rgba(129, 92, 255, 1); }
.input-group { margin-top: 1rem; }
.input-group label { font-size: 0.875rem; color: rgba(156, 163, 175, 1); }
.input-group input {
  width: 100%; padding: 0.75rem 1rem;
  border-radius: 0.375rem; border: 1px solid rgba(55, 65, 81, 1);
  background-color: rgba(17, 24, 39, 1); color: rgba(243, 244, 246, 1);
}
</style>
</head>
<body>
<div class="form-container">
  <h2 class="title"><?= $mode === 'reset' ? 'Reset Password' : 'Forgot Password' ?></h2>
  <?= $msg ?>

  <?php if ($mode === 'email'): ?>
    <form method="post">
      <div class="input-group">
        <label>Email Address</label>
        <input type="email" name="email" required>
      </div>
      <button class="sign" type="submit">Send Reset Link</button>
    </form>
    <p class="mt-3 text-center"><a href="login.php" style="color:#a78bfa;">Back to Login</a></p>

  <?php elseif ($mode === 'reset'): ?>
    <form method="post">
      <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
      <div class="input-group">
        <label>New Password</label>
        <input type="password" name="new_password" required>
      </div>
      <button class="sign" type="submit">Reset Password</button>
    </form>

  <?php endif; ?>
</div>
</body>
</html>

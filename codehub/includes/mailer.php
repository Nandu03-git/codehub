<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ✅ Correct path based on your structure
require __DIR__ . '/../PHPMailer/PHPMailer-master/src/Exception.php';
require __DIR__ . '/../PHPMailer/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/PHPMailer-master/src/SMTP.php';

function send_reset_email($to, $token) {
  $mail = new PHPMailer(true);
  try {
    // Gmail SMTP setup
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'youremail@gmail.com'; // 🔹 your Gmail address
    $mail->Password = 'your-app-password';   // 🔹 your Gmail app password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Email content
    $mail->setFrom('youremail@gmail.com', 'CodeHub Support');
    $mail->addAddress($to);

    $mail->isHTML(true);
    $mail->Subject = 'Reset Your CodeHub Password';
    $mail->Body = "
      <h2>Password Reset Request</h2>
      <p>Click below to reset your password:</p>
      <a href='http://localhost/codehub/auth/reset_password.php?token=$token'
         style='background:#7c3aed;color:#fff;padding:10px 15px;border-radius:6px;text-decoration:none;'>
         Reset Password
      </a>
      <p style='margin-top:10px;color:#555;'>If you didn’t request this, ignore this email.</p>
    ";

    $mail->send();
    return true;
  } catch (Exception $e) {
    error_log("Mailer Error: " . $mail->ErrorInfo);
    return false;
  }
}
?>

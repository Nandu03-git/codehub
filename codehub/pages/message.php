<?php
require_once __DIR__ . '/../includes/config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $to = intval($_POST['to']);
  $subject = $_POST['subject'];
  $body = $_POST['body'];

  $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, subject, body) VALUES (?, ?, ?, ?)");
  $stmt->bind_param('iiss', $_SESSION['user_id'], $to, $subject, $body);
  $stmt->execute();

  set_flash("Message Sent", "success");
  header("Location: message.php");
  exit;
}

include __DIR__ . '/../includes/header.php';
$users = $conn->query("SELECT id, name, email FROM users WHERE id != " . intval($_SESSION['user_id']));
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Send Message</title>
  <style>
    /* Container styling */
    .form-container {
      width: 380px;
      margin: 40px auto;
      border-radius: 0.75rem;
      background-color: rgba(17, 24, 39, 1);
      padding: 2rem;
      color: rgba(243, 244, 246, 1);
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
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
      margin-top: 1rem;
      font-size: 0.875rem;
      line-height: 1.25rem;
    }

    .input-group label {
      display: block;
      color: rgba(156, 163, 175, 1);
      margin-bottom: 4px;
    }

    .input-group input,
    .input-group select {
      width: 100%;
      border-radius: 0.375rem;
      border: 1px solid rgba(55, 65, 81, 1);
      outline: 0;
      background-color: rgba(17, 24, 39, 1);
      padding: 0.75rem 1rem;
      color: rgba(243, 244, 246, 1);
    }

    .input-group input:focus,
    .input-group select:focus {
      border-color: rgba(167, 139, 250, 1);
    }

    /* ✅ Message box styling (only changed part) */
    textarea[name="body"] {
      width: 100%;
      min-height: 180px;
      border-radius: 8px;
      border: 1px solid rgba(55, 65, 81, 1);
      outline: none;
      background-color: rgba(17, 24, 39, 1);
      color: rgba(243, 244, 246, 1);
      padding: 12px 15px;
      font-size: 0.95rem;
      resize: vertical;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    textarea[name="body"]:focus {
      border-color: rgba(167, 139, 250, 1);
      box-shadow: 0 0 8px rgba(167, 139, 250, 0.4);
    }

    .btn {
      display: block;
      width: 100%;
      background-color: rgba(167, 139, 250, 1);
      padding: 0.75rem;
      text-align: center;
      color: rgba(17, 24, 39, 1);
      border: none;
      border-radius: 0.375rem;
      font-weight: 600;
      margin-top: 1rem;
      cursor: pointer;
    }

    .btn:hover {
      background-color: rgba(147, 119, 230, 1);
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h3 class="title">Send Message</h3>
    <form method="post" class="form">
      <div class="input-group">
        <label>To</label>
        <select name="to" required>
          <?php while ($u = $users->fetch_assoc()): ?>
            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name'] . ' (' . $u['email'] . ')') ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="input-group">
        <label>Subject</label>
        <input name="subject" placeholder="Subject">
      </div>

      <div class="input-group">
        <label>Message</label>
        <textarea name="body" placeholder="Write your message here..."></textarea>
      </div>

      <button class="btn">Send</button>
    </form>
  </div>
</body>
</html>

<?php include __DIR__ . '/../includes/footer.php'; ?>

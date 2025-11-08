<?php
require_once __DIR__ . '/config.php';
$user = null;
if(isset($_SESSION['user_id'])){
  $stmt = $conn->prepare("SELECT id,name,email,photo FROM users WHERE id=?");
  $stmt->bind_param('i', $_SESSION['user_id']);
  $stmt->execute();
  $res = $stmt->get_result();
  $user = $res->fetch_assoc();
}
$flash = get_flash();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CodeHub</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/codehub/assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="/codehub/pages/home.php">CodeHub</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navmenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="/codehub/pages/home.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="/codehub/pages/upload.php">Upload</a></li>
        <li class="nav-item"><a class="nav-link" href="/codehub/pages/compiler.php">Compiler</a></li>
        <li class="nav-item"><a class="nav-link" href="/codehub/pages/message.php">Message</a></li>
        <li class="nav-item"><a class="nav-link" href="/codehub/pages/inbox.php">Inbox</a></li>
      </ul>
      <ul class="navbar-nav">
      <?php if($user): ?>
        <li class="nav-item"><a class="nav-link" href="/codehub/pages/profile.php"><?=htmlspecialchars($user['name'])?></a></li>
        <li class="nav-item"><a class="nav-link" href="/codehub/auth/logout.php">Logout</a></li>
      <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="/codehub/auth/login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="/codehub/auth/register.php">Register</a></li>
      <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-4">
<?php if($flash): ?>
  <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show">
    <?= htmlspecialchars($flash['msg']) ?>
    <button class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

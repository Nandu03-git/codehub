<?php
require_once __DIR__ . '/../includes/config.php';
require_login();
include __DIR__ . '/../includes/header.php';

$stmt = $conn->prepare("SELECT id,name,email,photo,created_at FROM users WHERE id=?");
$stmt->bind_param('i', $_SESSION['user_id']); $stmt->execute(); $me = $stmt->get_result()->fetch_assoc();

// update photo
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_FILES['photo'])){
  $file = $_FILES['photo'];
  $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
  $allowed = ['jpg','jpeg','png','gif'];
  if(in_array(strtolower($ext), $allowed)){
    $target = __DIR__ . '/../assets/uploads/profile_'.$_SESSION['user_id'].'.'.$ext;
    if(move_uploaded_file($file['tmp_name'],$target)){
      $stmt2 = $conn->prepare("UPDATE users SET photo=? WHERE id=?");
      $name = 'assets/uploads/'.basename($target); $stmt2->bind_param('si',$name,$_SESSION['user_id']); $stmt2->execute();
      set_flash("Profile updated","success");
      header("Location: profile.php"); exit;
    }
  } else set_flash("Invalid photo", "danger");
}
?>
<h3>Profile</h3>
<div class="row">
  <div class="col-md-4">
    <img src="/codehub/<?= htmlspecialchars($me['photo'] ?? 'assets/default-avatar.png') ?>" class="img-fluid rounded" alt="Profile">
    <p><strong><?=htmlspecialchars($me['name'])?></strong><br><?=htmlspecialchars($me['email'])?></p>
    <form method="post" enctype="multipart/form-data">
      <div class="mb-2"><input type="file" name="photo" accept="image/*" class="form-control"></div>
      <button class="btn btn-sm btn-primary">Update Photo</button>
    </form>
  </div>
  <div class="col-md-8">
    <h5>Uploaded Files</h5>
    <?php include 'files_view.php'; ?>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>

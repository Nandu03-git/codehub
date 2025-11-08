<?php
require_once __DIR__ . '/../includes/config.php';
require_login();

if(isset($_GET['delete'])){
  $id = intval($_GET['delete']);
  $stmt=$conn->prepare("DELETE FROM messages WHERE id=? AND receiver_id=?"); $stmt->bind_param('ii',$id,$_SESSION['user_id']); $stmt->execute();
  set_flash("Message deleted","success"); header("Location: inbox.php"); exit;
}
if(isset($_GET['read'])){
  $id = intval($_GET['read']);
  $stmt=$conn->prepare("UPDATE messages SET is_read=1 WHERE id=? AND receiver_id=?"); $stmt->bind_param('ii',$id,$_SESSION['user_id']); $stmt->execute();
  header("Location: inbox.php"); exit;
}

include __DIR__ . '/../includes/header.php';
$stmt = $conn->prepare("SELECT m.*, s.name as sender_name FROM messages m JOIN users s ON s.id = m.sender_id WHERE m.receiver_id=? ORDER BY m.sent_at DESC");
$stmt->bind_param('i', $_SESSION['user_id']); $stmt->execute(); $res = $stmt->get_result();
?>
<h3>Inbox</h3>
<table class="table">
  <thead><tr><th>From</th><th>Subject</th><th>Date</th><th>Action</th></tr></thead>
  <tbody>
    <?php while($m=$res->fetch_assoc()): ?>
    <tr class="<?= $m['is_read'] ? '' : 'table-warning' ?>">
      <td><?=htmlspecialchars($m['sender_name'])?></td>
      <td><?=htmlspecialchars($m['subject'])?><br><small><?=nl2br(htmlspecialchars($m['body']))?></small></td>
      <td><?=$m['sent_at']?></td>
      <td>
        <a class="btn btn-sm btn-success" href="message.php?reply=<?=$m['sender_id']?>">Reply</a>
        <a class="btn btn-sm btn-outline-danger" href="?delete=<?=$m['id']?>" onclick="return confirm('Delete?')">Delete</a>
        <?php if(!$m['is_read']): ?><a class="btn btn-sm btn-outline-secondary" href="?read=<?=$m['id']?>">Mark read</a><?php endif; ?>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php include __DIR__ . '/../includes/footer.php'; ?>

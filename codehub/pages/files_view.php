<?php
$stmt = $conn->prepare("SELECT * FROM files WHERE user_id=? ORDER BY upload_date DESC");
$stmt->bind_param('i', $_SESSION['user_id']); $stmt->execute(); $res = $stmt->get_result();
if($res->num_rows==0){ echo "<p>No files yet.</p>"; }
else {
  echo '<table class="table"><thead><tr><th>Name</th><th>Date</th><th>Action</th></tr></thead><tbody>';
  while($f=$res->fetch_assoc()){
    echo "<tr>";
    echo "<td>".htmlspecialchars($f['filename'])."</td>";
    echo "<td>".$f['upload_date']."</td>";
    echo "<td>
        <a class='btn btn-sm btn-outline-primary' href='/codehub/assets/uploads/".urlencode($f['stored_name'])."' target='_blank'>View</a>
        <a class='btn btn-sm btn-outline-danger' href='?delete={$f['id']}' onclick='return confirm(\"Delete?\")'>Delete</a>
      </td>";
    echo "</tr>";
  }
  echo '</tbody></table>';
}

// delete handler (simple GET)
if(isset($_GET['delete'])){
  $id = intval($_GET['delete']);
  $stmt = $conn->prepare("SELECT stored_name FROM files WHERE id=? AND user_id=?");
  $stmt->bind_param('ii',$id,$_SESSION['user_id']);
  $stmt->execute(); $r = $stmt->get_result()->fetch_assoc();
  if($r){
    @unlink(__DIR__ . '/../assets/uploads/' . $r['stored_name']);
    $stmt2 = $conn->prepare("DELETE FROM files WHERE id=?"); $stmt2->bind_param('i',$id); $stmt2->execute();
    set_flash("File deleted","success");
  } else set_flash("File not found","danger");
  header("Location: upload.php"); exit;
}
?>

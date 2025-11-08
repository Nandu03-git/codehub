<?php
require_once __DIR__ . '/../includes/config.php';
require_login();
include __DIR__ . '/../includes/header.php';

// Fetch recent uploads from all users (from files table)
$stmt = $conn->prepare("
  SELECT f.id, f.filename, f.stored_name, f.upload_date, u.name AS uploader_name
  FROM files f
  LEFT JOIN users u ON f.user_id = u.id
  ORDER BY f.upload_date DESC
  LIMIT 8
");
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-4">
  <div class="row">
    <!-- Main content -->
    <div class="col-md-8">
      <h3 class="fw-bold mb-3">Welcome, <?= htmlspecialchars($user['name']) ?> 👋</h3>
      <p class="text-muted">Here’s what people have been uploading recently.</p>

      <!-- Quick links -->
      <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="/codehub/pages/upload.php" class="btn btn-outline-primary">
          <i class="bi bi-upload"></i> Upload
        </a>
        <a href="/codehub/pages/compiler.php" class="btn btn-outline-success">
          <i class="bi bi-terminal"></i> Compiler
        </a>
        <a href="/codehub/pages/profile.php" class="btn btn-outline-secondary">
          <i class="bi bi-person-circle"></i> Profile
        </a>
      </div>

      <!-- Uploaded Files Section -->
      <h4 class="mt-4 mb-3">📂 Recently Uploaded Files</h4>

      <div class="row">
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-6 col-lg-4 mb-4">
              <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                  <h6 class="card-title text-primary mb-2">
                    <?= htmlspecialchars($row['filename']) ?>
                  </h6>
                  <p class="card-text text-muted mb-2">
                    <small>By <?= htmlspecialchars($row['uploader_name'] ?: 'Unknown') ?></small><br>
                    <small><?= date('d M Y, h:i A', strtotime($row['upload_date'])) ?></small>
                  </p>
                  <a href="/codehub/assets/uploads/<?= urlencode($row['stored_name']) ?>" 
   class="btn btn-sm btn-primary" target="_blank">
   <i class="bi bi-file-earmark-text"></i> View File
</a>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="text-muted">No files uploaded yet. Be the first to upload!</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5 class="card-title mb-3">👤 Your Info</h5>
          <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
          <a href="/codehub/pages/inbox.php" class="btn btn-outline-dark btn-sm">
            <i class="bi bi-envelope"></i> Open Inbox
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

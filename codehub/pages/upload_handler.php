<?php
require_once __DIR__ . '/../includes/config.php';
require_login();

if (!isset($_FILES['pdffile'])) {
    set_flash("No file uploaded", "danger");
    header("Location: upload.php");
    exit;
}

$file = $_FILES['pdffile'];

// Validate file type and extension
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed = ['pdf'];

if (!in_array($ext, $allowed) || $file['type'] !== 'application/pdf') {
    set_flash("Invalid file type. Only PDF files are allowed.", "danger");
    header("Location: upload.php");
    exit;
}

// Ensure uploads directory exists
$uploads_dir = __DIR__ . '/../assets/uploads';
if (!is_dir($uploads_dir)) {
    mkdir($uploads_dir, 0755, true);
}

// Generate unique stored filename
$stored = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
$target = $uploads_dir . '/' . $stored;

// Move the uploaded file
if (move_uploaded_file($file['tmp_name'], $target)) {
    $stmt = $conn->prepare("INSERT INTO files (user_id, filename, stored_name) VALUES (?, ?, ?)");
    $stmt->bind_param('iss', $_SESSION['user_id'], $file['name'], $stored);
    $stmt->execute();
    set_flash("PDF uploaded successfully.", "success");
} else {
    set_flash("Upload failed.", "danger");
}

header("Location: upload.php");
exit;

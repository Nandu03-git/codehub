<?php
require_once __DIR__ . '/../includes/config.php';
require_login();
include __DIR__ . '/../includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload PDF - CodeHub</title>
  <style>
    /* From Uiverse.io by Yaya12085 (Customized for Upload Box) */
    .upload-container {
      height: 320px;
      width: 320px;
      border-radius: 12px;
      box-shadow: 4px 4px 30px rgba(0, 0, 0, .2);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: space-between;
      padding: 15px;
      gap: 8px;
      background-color: rgba(0, 110, 255, 0.05);
      margin: 40px auto;
      transition: all 0.3s ease;
    }

    .upload-container:hover {
      transform: translateY(-3px);
      box-shadow: 4px 6px 35px rgba(0, 0, 0, 0.25);
    }

    .upload-header {
      flex: 1;
      width: 100%;
      border: 2px dashed royalblue;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      text-align: center;
      color: #111;
      background-color: rgba(255,255,255,0.1);
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .upload-header:hover {
      background-color: rgba(65, 105, 225, 0.1);
    }

    .upload-header svg {
      height: 80px;
      margin-bottom: 10px;
      fill: royalblue;
    }

    .upload-header p {
      font-size: 14px;
      color: #333;
    }

    .upload-footer {
      background-color: rgba(0, 110, 255, 0.075);
      width: 100%;
      height: 45px;
      padding: 8px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      border: none;
      color: royalblue;
      font-weight: bold;
      transition: all 0.3s ease;
    }

    .upload-footer:hover {
      background-color: royalblue;
      color: white;
    }

    #file {
      display: none;
    }
  </style>
</head>
<body>

<h3 style="text-align:center; color:#333;">Upload PDF File</h3>

<form action="upload_handler.php" method="post" enctype="multipart/form-data">
  <div class="upload-container">
    <label for="file" class="upload-header">
      <!-- SVG upload icon -->
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path d="M12 16V4m0 0l-5 5m5-5l5 5M4 20h16" stroke="royalblue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      <p>Click to select a PDF file</p>
    </label>
    <input type="file" name="pdffile" id="file" accept=".pdf" required>
    <button type="submit" class="upload-footer">
      Upload File
    </button>
  </div>
</form>

<hr style="margin:40px 0;">

<h4>Your Uploaded PDFs</h4>
<?php include 'files_view.php'; ?>

</body>
</html>

<?php include __DIR__ . '/../includes/footer.php'; ?>

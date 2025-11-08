<?php
require_once __DIR__ . '/../includes/config.php';
require_login();
include __DIR__ . '/../includes/header.php';
?>

<style>
  body {
    font-family: Arial, sans-serif;
    background: #f7f9fc;
    margin: 0;
    padding: 20px;
  }

  .container {
    background: white;
    max-width: 900px;
    margin: 40px auto;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
  }

  textarea {
    width: 100%;
    height: 120px;
    border-radius: 8px;
    border: 1px solid #ccc;
    padding: 10px;
    margin-bottom: 15px;
    font-family: monospace;
    font-size: 14px;
    resize: vertical;
  }

  select, button {
    padding: 8px 12px;
    border-radius: 6px;
    border: none;
    font-weight: bold;
    cursor: pointer;
  }

  #runBtn { background: #198754; color: white; }
  #clearBtn { background: #dc3545; color: white; }

  #outputBox {
    background: #0e1117;
    color: #00ff9d;
    padding: 15px;
    border-radius: 8px;
    margin-top: 15px;
    min-height: 150px;
    font-family: monospace;
    white-space: pre-wrap;
    overflow-y: auto;
    overflow-x: auto;
    max-height: 500px;
    transition: all 0.3s ease;
    box-sizing: border-box;
  }

  iframe {
    width: 100%;
    height: 400px;
    border: none;
    border-radius: 10px;
    background: #fff;
  }

  @media (max-width: 600px) {
    .container { padding: 15px; }
    textarea { height: 100px; }
    iframe { height: 300px; }
  }
</style>

<div class="container">
  <h2>Online Compiler</h2>

  <div style="margin-bottom: 10px;">
    <select id="language">
      <option value="python">Python</option>
      <option value="c">C</option>
      <option value="cpp">C++</option>
      <option value="java">Java</option>
      <option value="web">HTML / CSS / JS</option>
    </select>
    <button id="runBtn">Run Code</button>
    <button id="clearBtn">Clear</button>
  </div>

  <div id="codeSection">
    <label>Code</label>
    <textarea id="codeArea" placeholder="Write your code here..."></textarea>

    <div id="webEditors" style="display:none;">
      <label>HTML</label>
      <textarea id="htmlCode" placeholder="Write your HTML here..."></textarea>

      <label>CSS</label>
      <textarea id="cssCode" placeholder="Write your CSS here..."></textarea>

      <label>JavaScript</label>
      <textarea id="jsCode" placeholder="Write your JavaScript here..."></textarea>
    </div>
  </div>

  <h3>Output</h3>
  <div id="outputBox">
    <p>Your output will appear here...</p>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const runBtn = document.getElementById("runBtn");
    const clearBtn = document.getElementById("clearBtn");
    const outputBox = document.getElementById("outputBox");
    const langSelect = document.getElementById("language");
    const webEditors = document.getElementById("webEditors");
    const codeArea = document.getElementById("codeArea");

    // Change editor view based on language
    langSelect.addEventListener("change", () => {
      if (langSelect.value === "web") {
        codeArea.style.display = "none";
        webEditors.style.display = "block";
      } else {
        codeArea.style.display = "block";
        webEditors.style.display = "none";
      }
    });

    // Run Code
    runBtn.addEventListener("click", async () => {
      const lang = langSelect.value;

      // HTML/CSS/JS live preview
      if (lang === "web") {
        const htmlCode = document.getElementById("htmlCode").value;
        const cssCode = document.getElementById("cssCode").value;
        const jsCode = document.getElementById("jsCode").value;

        const combined = `
          <!DOCTYPE html>
          <html>
            <head><style>${cssCode}</style></head>
            <body>${htmlCode}<script>${jsCode}<\/script></body>
          </html>
        `;

        outputBox.innerHTML = `<iframe id="resultFrame"></iframe>`;
        const iframe = document.getElementById("resultFrame").contentWindow.document;
        iframe.open();
        iframe.write(combined);
        iframe.close();
        return;
      }

      // For compiled languages
      const code = codeArea.value.trim();
      if (!code) {
        alert("Please write some code first!");
        return;
      }

      outputBox.innerHTML = `<div style="color:yellow;">⏳ Running your ${lang} code...</div>`;

      try {
        const res = await fetch("run_code.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `language=${encodeURIComponent(lang)}&code=${encodeURIComponent(code)}`
        });
        const text = await res.text();
        outputBox.innerHTML = `<pre>${text}</pre>`;
      } catch (err) {
        outputBox.innerHTML = `<p style="color:red;">Error: ${err.message}</p>`;
      }
    });

    // Clear button
    clearBtn.addEventListener("click", () => {
      document.querySelectorAll("textarea").forEach(t => t.value = "");
      outputBox.innerHTML = `<p>Your output will appear here...</p>`;
    });
  });
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>

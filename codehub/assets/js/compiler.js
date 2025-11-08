document.addEventListener("DOMContentLoaded", () => {
  const runBtn = document.getElementById("runBtn");
  const clearBtn = document.getElementById("clearBtn");
  const outputBox = document.getElementById("outputBox");
  const languageSelect = document.getElementById("language");

  const codeArea = document.getElementById("codeArea");
  const webEditors = document.getElementById("webEditors");
  const htmlCode = document.getElementById("htmlCode");
  const cssCode = document.getElementById("cssCode");
  const jsCode = document.getElementById("jsCode");

  // 🔹 Switch between languages
  languageSelect.addEventListener("change", () => {
    if (languageSelect.value === "web") {
      codeArea.style.display = "none";
      webEditors.style.display = "block";
    } else {
      codeArea.style.display = "block";
      webEditors.style.display = "none";
    }
    outputBox.innerHTML = "Your output will appear here...";
  });

  // 🔹 Run button
  runBtn.addEventListener("click", async () => {
    const lang = languageSelect.value;

    // For HTML/CSS/JS
    if (lang === "web") {
      runWebCode();
      return;
    }

    const code = codeArea.value.trim();
    if (!code) {
      alert("Please write some code first!");
      return;
    }

    outputBox.innerHTML = `<div class="text-info">⏳ Running your ${lang} code...</div>`;

    try {
      const res = await fetch("run_code.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `language=${encodeURIComponent(lang)}&code=${encodeURIComponent(code)}`
      });
      const text = await res.text();
      outputBox.innerHTML = `<pre>${escapeHtml(text)}</pre>`;
    } catch (err) {
      outputBox.innerHTML = `<p class="text-danger">Error: ${err.message}</p>`;
    }
  });

  // 🔹 Clear button
  clearBtn.addEventListener("click", () => {
    codeArea.value = "";
    htmlCode.value = "";
    cssCode.value = "";
    jsCode.value = "";
    outputBox.innerHTML = "Your output will appear here...";
  });

  // 🔹 Run Web Code (HTML + CSS + JS)
  function runWebCode() {
    const html = htmlCode.value;
    const css = `<style>${cssCode.value}</style>`;
    const js = `<script>${jsCode.value}<\/script>`;
    const src = html + css + js;

    const iframe = document.createElement("iframe");
    iframe.style.width = "100%";
    iframe.style.height = "300px";
    iframe.style.border = "1px solid #ccc";
    iframe.setAttribute("sandbox", "allow-scripts allow-same-origin");
    iframe.srcdoc = src;

    outputBox.innerHTML = "";
    outputBox.appendChild(iframe);
  }

  // 🔹 Escape helper (for text output)
  function escapeHtml(text) {
    return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
  }
});

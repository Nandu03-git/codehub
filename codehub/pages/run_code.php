<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $language = $_POST['language'];
    $code = $_POST['code'];

    // Generate temporary file name
    $ext = match($language) {
        "python" => "py",
        "c" => "c",
        "cpp" => "cpp",
        "java" => "java",
        default => ""
    };

    if (!$ext) {
        echo "❌ Unsupported language!";
        exit;
    }

    $file = "temp.$ext";
    file_put_contents($file, $code);

    $output = "";

    switch ($language) {
        case "python":
            $output = shell_exec("python \"$file\" 2>&1");
            break;

        case "c":
            shell_exec("gcc \"$file\" -o temp_c.exe 2>&1");
            $output = shell_exec(".\\temp_c.exe 2>&1");
            break;

        case "cpp":
            shell_exec("g++ \"$file\" -o temp_cpp.exe 2>&1");
            $output = shell_exec(".\\temp_cpp.exe 2>&1");
            break;

        case "java":
    // Extract class name from user code
    if (preg_match('/public\s+class\s+([A-Za-z_][A-Za-z0-9_]*)/', $code, $matches)) {
        $classname = $matches[1];
        $file = $classname . ".java";
        file_put_contents($file, $code); // save code to the correct filename
    } else {
        echo "❌ Could not find public class name!";
        exit;
    }

    // Compile the Java file
    $compile = shell_exec("javac \"$file\" 2>&1");
    if ($compile) {
        $output = $compile; // show compilation errors
    } else {
        // Run the compiled Java class
        $output = shell_exec("java \"$classname\" 2>&1");
    }

    echo $output ?: "⚠️ No output or runtime error.";

    // Cleanup temporary files
    @unlink($file);
    @unlink($classname . ".class");
    break;


        default:
            $output = "❌ Language not supported.";
    }

    echo $output ?: "⚠️ No output or runtime error.";

    // Cleanup
    @unlink($file);
    @unlink("temp_c.exe");
    @unlink("temp_cpp.exe");
    @unlink(pathinfo($file, PATHINFO_FILENAME) . ".class");
}
?>

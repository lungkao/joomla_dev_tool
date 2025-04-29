
<?php
/**
 * สแกน unused files ใน com_puchchafaq
 * - Export ZIP พร้อม list.txt
 * - ข้าม index.html
 * - มีปุ่มลบไฟล์หลัง backup เสร็จ
 */

$basePath = __DIR__ . '/administrator/components/com_puchchafaq';
$zipFile = __DIR__ . '/unused_files_backup.zip';
$listFile = __DIR__ . '/unused_files.txt';
$extensions = ['php', 'xml', 'ini', 'css', 'js'];
$skipFiles = ['index.html'];
$unusedFiles = [];
$allFiles = [];
$usedFiles = [];

// 🧠 Helper: รวมไฟล์ทั้งหมดในโฟลเดอร์
function collectFiles($path, $extensions, $skipFiles) {
    $files = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    foreach ($iterator as $file) {
        if (in_array(strtolower($file->getExtension()), $extensions)) {
            if (!in_array(basename($file->getPathname()), $skipFiles)) {
                $files[] = str_replace($path . '/', '', $file->getPathname());
            }
        }
    }
    return $files;
}

// 🧠 Helper: หาว่าไฟล์ใดถูกอ้างถึงในโค้ดอื่น
function findUsedFiles($path, $allFiles) {
    $used = [];
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    foreach ($files as $file) {
        if (in_array($file->getExtension(), ['php', 'js'])) {
            $content = file_get_contents($file->getPathname());
            foreach ($allFiles as $f) {
                if (strpos($content, basename($f)) !== false) {
                    $used[$f] = true;
                }
            }
        }
    }
    return $used;
}

// 🔥 ถ้ามีการส่ง POST เพื่อลบไฟล์
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_all'])) {
    $deleted = 0;
    if (file_exists($listFile)) {
        $paths = file($listFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($paths as $relPath) {
            $relPath = trim($relPath);
            if ($relPath === '' || in_array(basename($relPath), ['index.html'])) {
                continue;
            }
            $fullPath = realpath($basePath . '/' . $relPath);
            if ($fullPath && strpos($fullPath, realpath($basePath)) === 0 && file_exists($fullPath)) {
                unlink($fullPath);
                $deleted++;
            }
        }
    }
    echo "<!DOCTYPE html><html><body><h1>🗑 ลบไฟล์ทั้งหมดแล้ว: $deleted ไฟล์</h1><a href='scan-unused-final.php'>🔙 กลับ</a></body></html>";
    exit;
}

// 🔍 สแกน
$allFiles = collectFiles($basePath, $extensions, $skipFiles);
$usedFiles = findUsedFiles($basePath, $allFiles);
foreach ($allFiles as $file) {
    if (!isset($usedFiles[$file])) {
        $unusedFiles[] = $file;
    }
}

// 📝 สร้างไฟล์ list.txt
$listContent = "Unused Files in com_puchchafaq (" . count($unusedFiles) . " files)\n\n";
$listContent .= implode("\n", $unusedFiles);
file_put_contents($listFile, $listContent);

// 🗜 Export ZIP
$zip = new ZipArchive();
if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
    $zip->addFile($listFile, 'unused_files.txt');
    foreach ($unusedFiles as $file) {
        $fullPath = $basePath . '/' . $file;
        if (file_exists($fullPath)) {
            $zip->addFile($fullPath, 'com_puchchafaq/' . $file);
        }
    }
    $zip->close();
    $status = "✅ Backup ZIP created with " . count($unusedFiles) . " files.";
} else {
    $status = "❌ Failed to create ZIP file.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ZIP Export and Delete Unused Files</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
<div class="container">
  <h1 class="mb-4">📦 Export and Delete Unused Files</h1>

  <p><?= $status ?></p>

  <?php if (file_exists($zipFile)): ?>
    <a class="btn btn-success mb-3" href="<?= basename($zipFile) ?>" download>⬇️ Download unused_files_backup.zip</a>
    <a class="btn btn-secondary mb-3" href="<?= basename($listFile) ?>" download>📄 Download unused_files.txt</a>

    <form method="post" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบไฟล์ทั้งหมดที่สำรองไว้แล้ว?');">
      <button type="submit" name="delete_all" value="1" class="btn btn-danger mt-3">🗑 ลบไฟล์ทั้งหมดที่สำรองไว้แล้ว</button>
    </form>
  <?php endif; ?>
</div>
</body>
</html>

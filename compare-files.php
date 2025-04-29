
<?php
/**
 * เปรียบเทียบเนื้อหาสองไฟล์ (file1.txt กับ file2.txt)
 * แสดงสิ่งที่เพิ่มมา / หายไป / เหมือนกัน
 */

// กำหนดชื่อไฟล์ที่จะเปรียบเทียบ
$file1Path = __DIR__ . '/file1.txt';
$file2Path = __DIR__ . '/file2.txt';

if (!file_exists($file1Path) || !file_exists($file2Path)) {
    die('❌ ไม่พบไฟล์ file1.txt หรือ file2.txt กรุณาอัปโหลดไฟล์ก่อน');
}

$file1 = file($file1Path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$file2 = file($file2Path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// เทียบข้อมูล
$onlyInFile1 = array_diff($file1, $file2);
$onlyInFile2 = array_diff($file2, $file1);
$common = array_intersect($file1, $file2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Compare Two Files</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
<div class="container">
  <h1 class="mb-4">📋 เปรียบเทียบไฟล์ file1.txt กับ file2.txt</h1>

  <div class="row">
    <div class="col-md-4">
      <h3 class="text-danger">➖ มีใน file1 แต่ไม่มีใน file2</h3>
      <?php if (empty($onlyInFile1)): ?>
        <div class="alert alert-success">✅ ไม่มีต่างกัน</div>
      <?php else: ?>
        <ul class="list-group mb-4">
          <?php foreach ($onlyInFile1 as $line): ?>
            <li class="list-group-item"><?= htmlspecialchars($line) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>

    <div class="col-md-4">
      <h3 class="text-success">➕ มีใน file2 แต่ไม่มีใน file1</h3>
      <?php if (empty($onlyInFile2)): ?>
        <div class="alert alert-success">✅ ไม่มีต่างกัน</div>
      <?php else: ?>
        <ul class="list-group mb-4">
          <?php foreach ($onlyInFile2 as $line): ?>
            <li class="list-group-item"><?= htmlspecialchars($line) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>

    <div class="col-md-4">
      <h3 class="text-primary">✅ มีเหมือนกันทั้งสองไฟล์</h3>
      <?php if (empty($common)): ?>
        <div class="alert alert-warning">⚠️ ไม่มีข้อมูลเหมือนกัน</div>
      <?php else: ?>
        <ul class="list-group mb-4">
          <?php foreach ($common as $line): ?>
            <li class="list-group-item"><?= htmlspecialchars($line) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>

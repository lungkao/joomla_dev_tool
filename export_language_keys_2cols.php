
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Language Keys Report (2 Columns)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
  <div class="container">
    <h1 class="mb-4">Language Keys Report (Site / Admin)</h1>
    <?php
    function findLanguageKeys($path) {
        if (!is_dir($path)) {
            return []; // ถ้า path ไม่เจอ คืน array ว่าง
        }
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        $pattern = '/COM_PUCHCHAFAQ_[A-Z0-9_]+/';
        $found = [];

        foreach ($files as $file) {
            if ($file->isFile()) {
                $ext = strtolower($file->getExtension());
                if (in_array($ext, ['php', 'xml', 'html'])) { // ❌ ข้าม .ini
                    $content = file_get_contents($file->getPathname());
                    if (preg_match_all($pattern, $content, $matches)) {
                        foreach ($matches[0] as $match) {
                            $found[] = [
                                'file' => str_replace('\\', '/', $file->getPathname()),
                                'key' => $match
                            ];
                        }
                    }
                }
            }
        }
        return $found;
    }

    $sitePath = __DIR__ . '/components/com_puchchafaq';
    $adminPath = __DIR__ . '/administrator/components/com_puchchafaq';

    $siteKeys = findLanguageKeys($sitePath);
    $adminKeys = findLanguageKeys($adminPath);

    function renderTable($id, $title, $data, $btnColor) {
        echo "<h2>$title</h2>";
        if (empty($data)) {
            echo "<div class='alert alert-warning'>No keys found.</div>";
            return;
        }
        echo "<button class='btn btn-$btnColor mb-3' onclick=\"exportKeys('$id')\">Export $title</button>";
        echo "<div class='table-responsive'>";
        echo "<table id='$id' class='table table-bordered table-striped'>";
        echo "<thead><tr><th>Language Key</th></tr></thead><tbody>";
        foreach ($data as $row) {
            echo "<tr><td>" . htmlspecialchars($row['key']) . "</td></tr>";
        }
        echo "</tbody></table></div>";
    }
    ?>

    <div class="row">
      <div class="col-md-6">
        <?php renderTable('siteTable', 'Site Language Keys', $siteKeys, 'success'); ?>
      </div>
      <div class="col-md-6">
        <?php renderTable('adminTable', 'Admin Language Keys', $adminKeys, 'primary'); ?>
      </div>
    </div>
  </div>

  <script>
  function exportKeys(tableId) {
    const table = document.getElementById(tableId);
    let textContent = "";
    for (let i = 1; i < table.rows.length; i++) {
      const key = table.rows[i].cells[0]?.innerText.trim();
      if (key) {
        textContent += key + "\n";
      }
    }
    const blob = new Blob([textContent], { type: "text/plain;charset=utf-8" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = (tableId === 'siteTable' ? "site_keys.txt" : "admin_keys.txt");
    link.click();
  }
  </script>
</body>
</html>

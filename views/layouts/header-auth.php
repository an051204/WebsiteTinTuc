<?php
$basePath = '/WebsiteTinTuc/public';
$settings = $settings ?? [];
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Xác thực'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <?php
    $cssFile = __DIR__ . '/../../public/css/style.css';
    $cssVer = file_exists($cssFile) ? filemtime($cssFile) : time();
    ?>
    <link rel="stylesheet" href="<?php echo $basePath; ?>/css/style.css?v=<?php echo $cssVer; ?>">
    <?php
    $frontPrimary = '#1a1a2e';
    if (!empty($settings['primary_color'])) { $frontPrimary = htmlspecialchars($settings['primary_color']); }
    $frontSecondary = '#c0392b';
    if (!empty($settings['secondary_color'])) { $frontSecondary = htmlspecialchars($settings['secondary_color']); }
    ?>
    <style>
    :root { --auth-primary: <?php echo $frontPrimary; ?>; --auth-secondary: <?php echo $frontSecondary; ?>; }
    *,*::before,*::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f8fafc; color: #111827; min-height: 100vh; -webkit-font-smoothing: antialiased; }
    ::selection { background: var(--auth-secondary); color: #fff; }
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: var(--auth-secondary); border-radius: 3px; }
    </style>
</head>
<body>

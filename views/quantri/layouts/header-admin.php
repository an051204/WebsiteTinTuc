<?php
$basePath = '/WebsiteTinTuc/public';
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
    <title><?php echo htmlspecialchars($pageTitle ?? 'Admin'); ?></title>
    <?php $cssFile = __DIR__ . '/../../../public/css/style.css';
    $cssVer = file_exists($cssFile) ? filemtime($cssFile) : time(); ?>
    <link rel="stylesheet" href="<?php echo $basePath; ?>/css/style.css?v=<?php echo $cssVer; ?>">
    <?php
    // export admin colors to CSS variables for footer and other components
    $adminPrimary = '#2c3e50';
    if (isset($settings) && !empty($settings['primary_color'])) {
        $adminPrimary = htmlspecialchars($settings['primary_color']);
    }
    $adminSecondary = '#3498db';
    if (isset($settings) && !empty($settings['secondary_color'])) {
        $adminSecondary = htmlspecialchars($settings['secondary_color']);
    }
    ?>
    <style>:root{ --primary-color: <?php echo $adminPrimary; ?>; --secondary-color: <?php echo $adminSecondary; ?>; }</style>
</head>

<body>
    <div class="admin-wrapper">
        <?php
        $adminPrimary = '#2c3e50';
        if (isset($settings) && !empty($settings['primary_color'])) {
            $adminPrimary = htmlspecialchars($settings['primary_color']);
        }
        ?>
        <header class="header-top" style="background: <?php echo $adminPrimary; ?>;">
            <div class="header-container">
                <?php
                $adminSiteTitle = $settings['site_title'] ?? 'Tin Tức';
                $adminSiteLogo = $settings['site_logo'] ?? '';
                ?>
                <a href="<?php echo $basePath; ?>/" class="logo site-brand">
                    <?php if (!empty($adminSiteLogo)): ?>
                        <img src="<?php echo htmlspecialchars($adminSiteLogo); ?>" class="site-logo" alt="Logo" onerror="this.style.display='none'">
                    <?php else: ?>
                        <span class="site-logo-fallback">📰</span>
                    <?php endif; ?>
                    <span class="site-logo-text"><?php echo htmlspecialchars($adminSiteTitle); ?></span>
                    <span class="admin-badge">ADMIN</span>
                </a>

                <div class="nav-links">
                    <a href="<?php echo $basePath; ?>/">← Về Trang Chủ</a>
                    <div class="user-menu">
                        <a href="<?php echo $basePath; ?>/ca-nhan/">Hồ Sơ</a>
                        <a href="<?php echo $basePath; ?>/dang-xuat/">Đăng Xuất</a>
                    </div>
                </div>
            </div>
        </header>

        <div class="admin-main">
            <aside class="admin-sidebar">
                <ul class="sidebar-menu">
                    <li class="menu-section">Chính</li>
                    <li>
                        <a href="<?php echo $basePath; ?>/quan-tri/"
                            class="<?php echo strpos($_SERVER['REQUEST_URI'], 'quan-tri/') !== false && strpos($_SERVER['REQUEST_URI'], 'bai-viet') === false && strpos($_SERVER['REQUEST_URI'], 'chuyenmuc') === false && strpos($_SERVER['REQUEST_URI'], 'the-tag') === false && strpos($_SERVER['REQUEST_URI'], 'binhluan') === false && strpos($_SERVER['REQUEST_URI'], 'tai-khoan') === false && strpos($_SERVER['REQUEST_URI'], 'caidat') === false ? 'active' : ''; ?>">📊
                            Tổng Quán</a>
                    </li>

                    <li class="menu-section">Nội Dung</li>
                    <li>
                        <a href="<?php echo $basePath; ?>/quan-tri/bai-viet/"
                            class="<?php echo strpos($_SERVER['REQUEST_URI'], 'bai-viet') !== false ? 'active' : ''; ?>">📰
                            Bài Viết</a>
                    </li>
                    <li>
                        <a href="<?php echo $basePath; ?>/quan-tri/chuyenmuc/"
                            class="<?php echo strpos($_SERVER['REQUEST_URI'], 'chuyenmuc') !== false ? 'active' : ''; ?>">📂
                            Chuyên Mục</a>
                    </li>
                    <li>
                        <a href="<?php echo $basePath; ?>/quan-tri/the-tag/"
                            class="<?php echo strpos($_SERVER['REQUEST_URI'], 'the-tag') !== false ? 'active' : ''; ?>">🏷️
                            Thẻ Tag</a>
                    </li>
                    <li>
                        <a href="<?php echo $basePath; ?>/quan-tri/binhluan/danh-sach/"
                            class="<?php echo strpos($_SERVER['REQUEST_URI'], 'binhluan') !== false ? 'active' : ''; ?>">💬
                            Bình Luận</a>
                    </li>

                    <li class="menu-section">Quản Lý</li>
                    <li>
                        <a href="<?php echo $basePath; ?>/quan-tri/tai-khoan/"
                            class="<?php echo strpos($_SERVER['REQUEST_URI'], 'tai-khoan') !== false ? 'active' : ''; ?>">👥
                            Tài Khoản</a>
                    </li>

                    <li class="menu-section">Cài Đặt</li>
                    <li>
                        <a href="<?php echo $basePath; ?>/quan-tri/caidat/quang-cao/"
                            class="<?php echo strpos($_SERVER['REQUEST_URI'], 'quang-cao') !== false ? 'active' : ''; ?>">📢
                            Quảng Cáo</a>
                    </li>
                    <li>
                        <a href="<?php echo $basePath; ?>/quan-tri/caidat/giao-dien/"
                            class="<?php echo strpos($_SERVER['REQUEST_URI'], 'giao-dien') !== false ? 'active' : ''; ?>">🎨
                            Giao Diện</a>
                    </li>
                </ul>
            </aside>

            <div class="admin-content">
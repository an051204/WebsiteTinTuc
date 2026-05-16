<?php
$basePath = '/WebsiteTinTuc/public';
$settings = $settings ?? [];
$siteTitle = trim($settings['site_title'] ?? 'Website Tin Tức');
$siteLogo = trim($settings['site_logo'] ?? '');
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
    <title><?php echo htmlspecialchars($pageTitle ?? 'Website Tin Tức'); ?></title>
    <?php $cssFile = __DIR__ . '/../../public/css/style.css';
    $cssVer = file_exists($cssFile) ? filemtime($cssFile) : time(); ?>
    <link rel="stylesheet" href="<?php echo $basePath; ?>/css/style.css?v=<?php echo $cssVer; ?>">
    <?php
    // expose runtime primary color to CSS variables so footer and other elements can use it
    $frontPrimary = '#2c3e50';
    if (!empty($settings['primary_color'])) {
        $frontPrimary = htmlspecialchars($settings['primary_color']);
    }
    $frontSecondary = '#3498db';
    if (!empty($settings['secondary_color'])) {
        $frontSecondary = htmlspecialchars($settings['secondary_color']);
    }
    ?>
    <style>:root{ --primary-color: <?php echo $frontPrimary; ?>; --secondary-color: <?php echo $frontSecondary; ?>; }</style>
</head>

<body>
    <?php
    $frontPrimary = '#2c3e50';
    if (!empty($settings['primary_color'])) {
        $frontPrimary = htmlspecialchars($settings['primary_color']);
    }
    ?>
    <header class="header-top" style="background: <?php echo $frontPrimary; ?>;">
        <div class="header-container">
            <a href="<?php echo $basePath; ?>/" class="logo site-brand">
                <?php if (!empty($siteLogo)): ?>
                <img src="<?php echo htmlspecialchars($siteLogo); ?>" alt="<?php echo htmlspecialchars($siteTitle); ?>"
                    class="site-logo" onerror="this.style.display='none'">
                <?php else: ?>
                <span class="site-logo-fallback">📰</span>
                <?php endif; ?>
                <span class="site-logo-text"><?php echo htmlspecialchars($siteTitle); ?></span>
            </a>

            <div class="search-wrapper">
                <form class="search-form" method="GET" action="<?php echo $basePath; ?>/tim-kiem">
                    <input type="text" name="q" placeholder="Tìm kiếm bài viết, thẻ tag..." id="headerSearchInput"
                        autocomplete="off">
                    <button type="submit">🔍</button>
                    <div class="search-suggestions" id="headerSuggestions"></div>
                </form>
            </div>

            <div class="nav-links">
                <a href="<?php echo $basePath; ?>/">Trang Chủ</a>
                <a href="<?php echo $basePath; ?>/danh-sach/">Chuyên Mục</a>
                <a href="<?php echo $basePath; ?>/tag/">Thẻ Tag</a>

                <?php if (!empty($_SESSION['user_id'])): ?>
                <?php
                    // Normalize avatar path
                    $userAvatar = $_SESSION['avatar'] ?? '';
                    $defaultAvatar = '/WebsiteTinTuc/public/assests/default-avatar.png';
                    
                    if (empty($userAvatar)) {
                        $userAvatar = $defaultAvatar;
                    } elseif (strpos($userAvatar, '/') !== 0) {
                        // If not absolute path, prepend base path
                        $userAvatar = '/WebsiteTinTuc/public/' . ltrim($userAvatar, '/');
                    }
                    
                    $userName = $_SESSION['full_name'] ?? 'Tài Khoản';
                    $userRoleId = $_SESSION['role_id'] ?? 3;
                    $roleName = match($userRoleId) {
                        1 => 'Quản trị viên',
                        2 => 'Biên tập viên',
                        default => 'Người dùng',
                    };
                    ?>
                <div class="user-dropdown-wrapper" id="userDropdown">
                    <button class="user-dropdown-toggle" onclick="toggleDropdown(event)">
                        <img src="<?php echo htmlspecialchars($userAvatar); ?>" alt="Avatar" class="user-avatar"
                            onerror="this.src='<?php echo htmlspecialchars($defaultAvatar); ?>'">
                        <span
                            class="user-dropdown-name"><?php echo htmlspecialchars(mb_substr($userName, 0, 15)); ?></span>
                        <span class="dropdown-arrow">▼</span>
                    </button>

                    <div class="user-dropdown-menu">
                        <div class="dropdown-user-info">
                            <img src="<?php echo htmlspecialchars($userAvatar); ?>" alt="Avatar" class="user-avatar-lg"
                                onerror="this.src='<?php echo htmlspecialchars($defaultAvatar); ?>'">
                            <div class="dropdown-user-details">
                                <div class="dropdown-user-fullname"><?php echo htmlspecialchars($userName); ?></div>
                                <div class="dropdown-user-role"><?php echo htmlspecialchars($roleName); ?></div>
                            </div>
                        </div>

                        <div class="dropdown-divider"></div>
                        <div class="dropdown-menu-items">
                            <a href="<?php echo $basePath; ?>/ca-nhan/">
                                <span class="dropdown-icon">👤</span> Trang cá nhân
                            </a>
                            <?php if ($userRoleId === 1): ?>
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo $basePath; ?>/quan-tri/">
                                <span class="dropdown-icon">📊</span> Bảng điều khiển
                            </a>
                            <a href="<?php echo $basePath; ?>/quan-tri/bai-viet/">
                                <span class="dropdown-icon">📝</span> Quản lý bài viết
                            </a>
                            <a href="<?php echo $basePath; ?>/quan-tri/chuyenmuc/">
                                <span class="dropdown-icon">📁</span> Quản lý chuyên mục
                            </a>
                            <a href="<?php echo $basePath; ?>/quan-tri/the-tag/">
                                <span class="dropdown-icon">🏷️</span> Quản lý thẻ tag
                            </a>
                            <a href="<?php echo $basePath; ?>/quan-tri/binhluan/danh-sach/">
                                <span class="dropdown-icon">💬</span> Quản lý bình luận
                            </a>
                            <a href="<?php echo $basePath; ?>/quan-tri/tai-khoan/">
                                <span class="dropdown-icon">👥</span> Quản lý tài khoản
                            </a>
                            <a href="<?php echo $basePath; ?>/quan-tri/caidat/giao-dien/">
                                <span class="dropdown-icon">🎨</span> Cài đặt giao diện
                            </a>
                            <a href="<?php echo $basePath; ?>/quan-tri/caidat/quang-cao/">
                                <span class="dropdown-icon">📢</span> Quản lý quảng cáo
                            </a>
                            <div class="dropdown-divider"></div>
                            <?php elseif ($userRoleId === 2): ?>
                            <a href="<?php echo $basePath; ?>/quan-tri/bai-viet/">
                                <span class="dropdown-icon">📝</span> Quản lý bài viết
                            </a>
                            <a href="<?php echo $basePath; ?>/quan-tri/the-tag/">
                                <span class="dropdown-icon">🏷️</span> Quản lý thẻ tag
                            </a>
                            <div class="dropdown-divider"></div>
                            <?php endif; ?>

                            <a href="<?php echo $basePath; ?>/dang-xuat/" class="dropdown-danger">
                                <span class="dropdown-icon">🚪</span> Đăng xuất
                            </a>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="guest-actions">
                    <a href="<?php echo $basePath; ?>/dang-nhap/" class="btn-login">🔐 Đăng Nhập</a>
                    <a href="<?php echo $basePath; ?>/dang-ky/" class="btn-register">📝 Đăng Ký</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <script>
    /* Toggle dropdown menu */
    function toggleDropdown(event) {
        event.stopPropagation();
        var wrapper = document.getElementById('userDropdown');
        if (wrapper) {
            wrapper.classList.toggle('open');
        }
    }

    /* Close dropdown khi click bên ngoài */
    document.addEventListener('click', function(event) {
        var dropdown = document.getElementById('userDropdown');
        if (dropdown && !dropdown.contains(event.target)) {
            dropdown.classList.remove('open');
        }
    });

    /* Search suggestions */
    var searchInput = document.getElementById('headerSearchInput');
    var suggestionsBox = document.getElementById('headerSuggestions');
    var debounceTimer;

    searchInput.addEventListener('input', function() {
        var query = this.value.trim();
        clearTimeout(debounceTimer);

        if (query.length < 1) {
            suggestionsBox.classList.remove('active');
            suggestionsBox.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(function() {
            fetch('<?php echo $basePath; ?>/api/tim-kiem/gop-y?q=' + encodeURIComponent(query))
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    suggestionsBox.innerHTML = '';

                    if (data.length === 0) {
                        suggestionsBox.innerHTML =
                            '<div class="suggestion-item suggestion-empty">Không tìm thấy kết quả</div>';
                        suggestionsBox.classList.add('active');
                        return;
                    }

                    data.forEach(function(item) {
                        var div = document.createElement('div');
                        div.className = 'suggestion-item';
                        div.innerHTML = '<div class="suggestion-title">' + item.title
                            .substring(0, 70) + '</div>';
                        div.onclick = function() {
                            window.location.href = item.url;
                        };
                        suggestionsBox.appendChild(div);
                    });

                    suggestionsBox.classList.add('active');
                })
                .catch(function(error) {
                    console.error('Lỗi:', error);
                });
        }, 250);
    });

    document.addEventListener('click', function(event) {
        if (event.target !== searchInput && !suggestionsBox.contains(event.target)) {
            suggestionsBox.classList.remove('active');
        }
    });
    </script>
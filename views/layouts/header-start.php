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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700;900&family=Inter:wght@300;400;500;600;700;800;900&family=Source+Serif+4:ital,opsz,wght@0,8..60,400;0,8..60,600;1,8..60,400&display=swap" rel="stylesheet">
    <?php $cssFile = __DIR__ . '/../../public/css/style.css';
    $cssVer = file_exists($cssFile) ? filemtime($cssFile) : time(); ?>
    <link rel="stylesheet" href="<?php echo $basePath; ?>/css/style.css?v=<?php echo $cssVer; ?>">
    <?php
    $frontPrimary = '#1a1a2e';
    if (!empty($settings['primary_color'])) {
        $frontPrimary = htmlspecialchars($settings['primary_color']);
    }
    $frontSecondary = '#c0392b';
    if (!empty($settings['secondary_color'])) {
        $frontSecondary = htmlspecialchars($settings['secondary_color']);
    }
    ?>
</head>

<body>
<style>:root{--news-navy:<?php echo $frontPrimary; ?>!important;--news-red:<?php echo $frontSecondary; ?>!important;--primary-color:<?php echo $frontPrimary; ?>!important;--secondary-color:<?php echo $frontSecondary; ?>!important;}</style>

    <!-- BREAKING NEWS BAR -->
    <div class="breaking-bar" role="marquee" aria-label="Tin nóng">
        <div class="breaking-bar__inner">
            <span class="breaking-bar__label">
                <span class="breaking-bar__dot"></span>
                NÓNG
            </span>
            <div class="breaking-bar__track">
                <div class="breaking-bar__content" id="breakingTicker">
                    <?php echo htmlspecialchars($siteTitle); ?> — Tin tức mới nhất, cập nhật liên tục 24/7 &nbsp;·&nbsp; Đọc báo nhanh, thông tin chính xác &nbsp;·&nbsp; Khám phá hàng nghìn bài viết chất lượng
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN HEADER -->
    <header class="site-header" id="siteHeader">
        <div class="site-header__inner">

            <!-- LOGO -->
            <a href="<?php echo $basePath; ?>/" class="site-header__logo" aria-label="Trang chủ">
                <?php if (!empty($siteLogo)): ?>
                <img src="<?php echo htmlspecialchars($siteLogo); ?>"
                     alt="<?php echo htmlspecialchars($siteTitle); ?>"
                     class="site-header__logo-img"
                     onerror="this.style.display='none'">
                <?php else: ?>
                <span class="site-header__logo-mark" aria-hidden="true">
                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                        <rect width="28" height="28" rx="6" fill="#c0392b"/>
                        <rect x="5" y="8" width="18" height="2.5" rx="1.25" fill="white"/>
                        <rect x="5" y="13" width="12" height="2.5" rx="1.25" fill="white"/>
                        <rect x="5" y="18" width="15" height="2.5" rx="1.25" fill="white"/>
                    </svg>
                </span>
                <?php endif; ?>
                <span class="site-header__logo-text"><?php echo htmlspecialchars($siteTitle); ?></span>
            </a>

            <!-- SEARCH -->
            <div class="site-header__search">
                <form class="search-bar" method="GET" action="<?php echo $basePath; ?>/tim-kiem" role="search">
                    <svg class="search-bar__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" name="q" id="headerSearchInput"
                           placeholder="Tìm kiếm bài viết..."
                           autocomplete="off"
                           aria-label="Tìm kiếm">
                    <button type="submit" aria-label="Tìm kiếm">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </button>
                    <div class="search-bar__suggestions" id="headerSuggestions" role="listbox"></div>
                </form>
            </div>

            <!-- NAV -->
            <nav class="site-header__nav" aria-label="Điều hướng chính">
                <a href="<?php echo $basePath; ?>/" class="site-header__nav-link">Trang Chủ</a>
                <a href="<?php echo $basePath; ?>/danh-sach/" class="site-header__nav-link">Chuyên Mục</a>
                <a href="<?php echo $basePath; ?>/tag/" class="site-header__nav-link">Thẻ Tag</a>
            </nav>

            <!-- USER AREA -->
            <div class="site-header__user">
                <?php if (!empty($_SESSION['user_id'])): ?>
                <?php
                    $userAvatar = $_SESSION['avatar'] ?? '';
                    $defaultAvatar = '/WebsiteTinTuc/public/assests/default-avatar.png';
                    if (empty($userAvatar)) {
                        $userAvatar = $defaultAvatar;
                    } elseif (strpos($userAvatar, '/') !== 0) {
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
                <div class="user-menu" id="userDropdown">
                    <button class="user-menu__toggle" onclick="toggleDropdown(event)" aria-expanded="false" aria-haspopup="true">
                        <img src="<?php echo htmlspecialchars($userAvatar); ?>"
                             alt="<?php echo htmlspecialchars($userName); ?>"
                             class="user-menu__avatar"
                             onerror="this.src='<?php echo htmlspecialchars($defaultAvatar); ?>'">
                        <span class="user-menu__name"><?php echo htmlspecialchars(mb_substr($userName, 0, 15)); ?></span>
                        <svg class="user-menu__chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </button>

                    <div class="user-menu__dropdown" role="menu">
                        <div class="user-menu__profile">
                            <img src="<?php echo htmlspecialchars($userAvatar); ?>"
                                 alt="<?php echo htmlspecialchars($userName); ?>"
                                 class="user-menu__profile-avatar"
                                 onerror="this.src='<?php echo htmlspecialchars($defaultAvatar); ?>'">
                            <div class="user-menu__profile-info">
                                <div class="user-menu__profile-name"><?php echo htmlspecialchars($userName); ?></div>
                                <div class="user-menu__profile-role"><?php echo htmlspecialchars($roleName); ?></div>
                            </div>
                        </div>

                        <div class="user-menu__divider"></div>

                        <div class="user-menu__items">
                            <a href="<?php echo $basePath; ?>/ca-nhan/" class="user-menu__item" role="menuitem">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                Trang cá nhân
                            </a>

                            <?php if ($userRoleId === 1): ?>
                            <div class="user-menu__divider"></div>
                            <a href="<?php echo $basePath; ?>/quan-tri/" class="user-menu__item" role="menuitem">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                                Bảng điều khiển
                            </a>
                            <a href="<?php echo $basePath; ?>/quan-tri/bai-viet/" class="user-menu__item" role="menuitem">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                Quản lý bài viết
                            </a>
                            <a href="<?php echo $basePath; ?>/quan-tri/chuyenmuc/" class="user-menu__item" role="menuitem">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                                Quản lý chuyên mục
                            </a>
                            <a href="<?php echo $basePath; ?>/quan-tri/the-tag/" class="user-menu__item" role="menuitem">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                Quản lý thẻ tag
                            </a>
                            <a href="<?php echo $basePath; ?>/quan-tri/binhluan/danh-sach/" class="user-menu__item" role="menuitem">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                Quản lý bình luận
                            </a>
                            <a href="<?php echo $basePath; ?>/quan-tri/tai-khoan/" class="user-menu__item" role="menuitem">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                Quản lý tài khoản
                            </a>
                            <a href="<?php echo $basePath; ?>/quan-tri/caidat/giao-dien/" class="user-menu__item" role="menuitem">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41M21 12h-2M19.07 19.07l-1.41-1.41M12 21v-2M4.93 19.07l1.41-1.41M3 12h2M4.93 4.93l1.41 1.41"/></svg>
                                Cài đặt giao diện
                            </a>
                            <a href="<?php echo $basePath; ?>/quan-tri/caidat/quang-cao/" class="user-menu__item" role="menuitem">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                                Quản lý quảng cáo
                            </a>
                            <div class="user-menu__divider"></div>
                            <?php elseif ($userRoleId === 2): ?>
                            <div class="user-menu__divider"></div>
                            <a href="<?php echo $basePath; ?>/quan-tri/bai-viet/" class="user-menu__item" role="menuitem">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                Quản lý bài viết
                            </a>
                            <a href="<?php echo $basePath; ?>/quan-tri/the-tag/" class="user-menu__item" role="menuitem">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                Quản lý thẻ tag
                            </a>
                            <div class="user-menu__divider"></div>
                            <?php endif; ?>

                            <a href="<?php echo $basePath; ?>/dang-xuat/" class="user-menu__item user-menu__item--danger" role="menuitem">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                Đăng xuất
                            </a>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="header-auth">
                    <a href="<?php echo $basePath; ?>/dang-nhap/" class="header-auth__login">Đăng nhập</a>
                    <a href="<?php echo $basePath; ?>/dang-ky/" class="header-auth__register">Đăng ký</a>
                </div>
                <?php endif; ?>
            </div>

            <!-- HAMBURGER (mobile) -->
            <button class="site-header__hamburger" id="navToggle" aria-label="Mở menu" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>

        <!-- MOBILE NAV -->
        <div class="site-header__mobile-nav" id="mobileNav" aria-hidden="true">
            <a href="<?php echo $basePath; ?>/">Trang Chủ</a>
            <a href="<?php echo $basePath; ?>/danh-sach/">Chuyên Mục</a>
            <a href="<?php echo $basePath; ?>/tag/">Thẻ Tag</a>
            <?php if (empty($_SESSION['user_id'])): ?>
            <a href="<?php echo $basePath; ?>/dang-nhap/" class="mobile-login">Đăng nhập</a>
            <a href="<?php echo $basePath; ?>/dang-ky/" class="mobile-register">Đăng ký</a>
            <?php endif; ?>
        </div>
    </header>

    <script>
    /* ── Dropdown ── */
    function toggleDropdown(event) {
        event.stopPropagation();
        var wrapper = document.getElementById('userDropdown');
        if (!wrapper) return;
        var isOpen = wrapper.classList.toggle('open');
        wrapper.querySelector('.user-menu__toggle').setAttribute('aria-expanded', isOpen);
    }
    document.addEventListener('click', function(e) {
        var d = document.getElementById('userDropdown');
        if (d && !d.contains(e.target)) {
            d.classList.remove('open');
            var toggle = d.querySelector('.user-menu__toggle');
            if (toggle) toggle.setAttribute('aria-expanded', 'false');
        }
    });

    /* ── Mobile nav ── */
    var navToggle = document.getElementById('navToggle');
    var mobileNav = document.getElementById('mobileNav');
    if (navToggle && mobileNav) {
        navToggle.addEventListener('click', function() {
            var isOpen = navToggle.classList.toggle('open');
            mobileNav.classList.toggle('open');
            navToggle.setAttribute('aria-expanded', isOpen);
            mobileNav.setAttribute('aria-hidden', !isOpen);
        });
    }

    /* ── Sticky header shadow on scroll ── */
    window.addEventListener('scroll', function() {
        var h = document.getElementById('siteHeader');
        if (h) h.classList.toggle('scrolled', window.scrollY > 8);
    }, { passive: true });

    /* ── Search suggestions ── */
    var searchInput = document.getElementById('headerSearchInput');
    var suggestionsBox = document.getElementById('headerSuggestions');
    var debounceTimer;
    if (searchInput && suggestionsBox) {
        searchInput.addEventListener('input', function() {
            var query = this.value.trim();
            clearTimeout(debounceTimer);
            if (query.length < 1) { suggestionsBox.classList.remove('active'); suggestionsBox.innerHTML = ''; return; }
            debounceTimer = setTimeout(function() {
                fetch('<?php echo $basePath; ?>/api/tim-kiem/gop-y?q=' + encodeURIComponent(query))
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        suggestionsBox.innerHTML = '';
                        if (!data.length) {
                            suggestionsBox.innerHTML = '<div class="search-suggestion search-suggestion--empty">Không tìm thấy kết quả</div>';
                        } else {
                            data.forEach(function(item) {
                                var div = document.createElement('div');
                                div.className = 'search-suggestion';
                                div.setAttribute('role', 'option');
                                div.innerHTML = '<span class="search-suggestion__title">' + item.title.substring(0, 72) + '</span>';
                                div.onclick = function() { window.location.href = item.url; };
                                suggestionsBox.appendChild(div);
                            });
                        }
                        suggestionsBox.classList.add('active');
                    }).catch(function() {});
            }, 250);
        });
        document.addEventListener('click', function(e) {
            if (e.target !== searchInput && !suggestionsBox.contains(e.target)) {
                suggestionsBox.classList.remove('active');
            }
        });
    }
    </script>
<?php
header('Content-Type: text/html; charset=utf-8');
$basePath = '/WebsiteTinTuc/public';
$pageTitle = 'Quên mật khẩu - Website Tin Tức';
$settings = $settings ?? [];
$siteTitle = trim($settings['site_title'] ?? 'Website Tin Tức');
include dirname(__FILE__) . '/../layouts/header-auth.php';
?>

<div class="auth-split">
    <div class="auth-panel auth-panel--left">
        <div class="auth-panel__inner">
            <a href="<?php echo $basePath; ?>/" class="auth-brand">
                <div class="auth-brand__icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6z"/></svg>
                </div>
                <span><?php echo htmlspecialchars($siteTitle); ?></span>
            </a>

            <div class="auth-panel__content">
                <h2 class="auth-panel__heading">Khôi phục mật khẩu</h2>
                <p class="auth-panel__sub">Nhập email đã đăng ký, chúng tôi sẽ gửi liên kết đặt lại mật khẩu cho bạn.</p>
            </div>

            <div class="auth-panel__features">
                <div class="auth-feature">
                    <div class="auth-feature__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <span>Liên kết có hiệu lực trong 1 giờ</span>
                </div>
                <div class="auth-feature">
                    <div class="auth-feature__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <span>Mật khẩu mới an toàn</span>
                </div>
            </div>
        </div>
        <div class="auth-panel__decoration">
            <div class="auth-deco auth-deco--1"></div>
            <div class="auth-deco auth-deco--2"></div>
            <div class="auth-deco auth-deco--3"></div>
        </div>
    </div>

    <div class="auth-panel auth-panel--right">
        <div class="auth-form-wrap">
            <div class="auth-form-header">
                <h1 class="auth-form-title">Quên mật khẩu</h1>
                <p class="auth-form-desc">Nhập email tài khoản của bạn</p>
            </div>

            <?php if (!empty($_SESSION['error'])): ?>
            <div class="auth-alert auth-alert--error">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['success'])): ?>
            <div class="auth-alert auth-alert--success">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo $basePath; ?>/quen-mat-khau/" class="auth-form" novalidate>
                <div class="form-floating">
                    <input type="email" id="email" name="email" placeholder=" " required autofocus>
                    <label for="email">Địa chỉ email</label>
                </div>

                <button type="submit" class="btn-auth btn-auth--primary">
                    <span>Gửi liên kết đặt lại</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                </button>
            </form>

            <div class="auth-divider"><span>hoặc</span></div>

            <div class="auth-footer-text">
                <span>Nhớ mật khẩu rồi?</span>
                <a href="<?php echo $basePath; ?>/dang-nhap/" class="auth-link auth-link--primary">Đăng nhập ngay</a>
            </div>
        </div>
    </div>
</div>

<?php include dirname(__FILE__) . '/../layouts/footer-auth.php'; ?>

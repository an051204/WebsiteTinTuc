<?php
header('Content-Type: text/html; charset=utf-8');
$basePath = '/WebsiteTinTuc/public';
$pageTitle = 'Đăng nhập - Website Tin Tức';
$settings = $settings ?? [];
$siteTitle = trim($settings['site_title'] ?? 'Website Tin Tức');
include dirname(__FILE__) . '/../layouts/header-auth.php';
?>

<div class="auth-split">
    <!-- Left Panel: Branding -->
    <div class="auth-panel auth-panel--left">
        <div class="auth-panel__inner">
            <a href="<?php echo $basePath; ?>/" class="auth-brand">
                <div class="auth-brand__icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6z"/></svg>
                </div>
                <span><?php echo htmlspecialchars($siteTitle); ?></span>
            </a>

            <div class="auth-panel__content">
                <h2 class="auth-panel__heading">Chào mừng bạn quay trở lại</h2>
                <p class="auth-panel__sub">Đăng nhập để đọc tin tức, bình luận và lưu bài viết yêu thích.</p>
            </div>

            <div class="auth-panel__features">
                <div class="auth-feature">
                    <div class="auth-feature__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <span>Tài khoản cá nhân</span>
                </div>
                <div class="auth-feature">
                    <div class="auth-feature__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                    </div>
                    <span>Lưu bài viết yêu thích</span>
                </div>
                <div class="auth-feature">
                    <div class="auth-feature__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </div>
                    <span>Bình luận & tương tác</span>
                </div>
            </div>
        </div>
        <div class="auth-panel__decoration">
            <div class="auth-deco auth-deco--1"></div>
            <div class="auth-deco auth-deco--2"></div>
            <div class="auth-deco auth-deco--3"></div>
        </div>
    </div>

    <!-- Right Panel: Form -->
    <div class="auth-panel auth-panel--right">
        <div class="auth-form-wrap">
            <div class="auth-form-header">
                <h1 class="auth-form-title">Đăng nhập</h1>
                <p class="auth-form-desc">Nhập thông tin tài khoản của bạn</p>
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

            <form method="POST" action="<?php echo $basePath; ?>/dang-nhap/" class="auth-form" id="loginForm" novalidate>
                <div class="form-floating">
                    <input type="email" id="email" name="email" placeholder=" " value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required autofocus>
                    <label for="email">Địa chỉ email</label>
                </div>

                <div class="form-floating">
                    <input type="password" id="password" name="password" placeholder=" " required>
                    <label for="password">Mật khẩu</label>
                    <button type="button" class="password-toggle" onclick="togglePassword('password')" tabindex="-1" aria-label="Hiện mật khẩu">
                        <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>

                <div class="form-row form-row--between">
                    <label class="form-check">
                        <input type="checkbox" name="remember">
                        <span class="form-check__box"></span>
                        <span>Ghi nhớ đăng nhập</span>
                    </label>
                    <a href="<?php echo $basePath; ?>/quen-mat-khau/" class="auth-link auth-link--forgot">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="btn-auth btn-auth--primary">
                    <span>Đăng nhập</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
            </form>

            <div class="auth-divider">
                <span>hoặc</span>
            </div>

            <div class="auth-footer-text">
                <span>Chưa có tài khoản?</span>
                <a href="<?php echo $basePath; ?>/dang-ky/" class="auth-link auth-link--primary">Đăng ký ngay</a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(id) {
    var input = document.getElementById(id);
    var btn = input.parentElement.querySelector('.password-toggle');
    var eyeOpen = btn.querySelector('.eye-open');
    var eyeClosed = btn.querySelector('.eye-closed');
    if (input.type === 'password') {
        input.type = 'text';
        eyeOpen.style.display = 'none';
        eyeClosed.style.display = 'block';
    } else {
        input.type = 'password';
        eyeOpen.style.display = 'block';
        eyeClosed.style.display = 'none';
    }
}
</script>

<?php include dirname(__FILE__) . '/../layouts/footer-auth.php'; ?>

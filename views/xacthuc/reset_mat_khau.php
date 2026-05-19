<?php
header('Content-Type: text/html; charset=utf-8');
$token = $token ?? '';
$basePath = '/WebsiteTinTuc/public';
$pageTitle = 'Đặt lại mật khẩu - Website Tin Tức';
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
                <h2 class="auth-panel__heading">Đặt mật khẩu mới</h2>
                <p class="auth-panel__sub">Tạo mật khẩu mới cho tài khoản của bạn. Hãy chọn mật khẩu mạnh để bảo vệ tài khoản.</p>
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
                <h1 class="auth-form-title">Đặt lại mật khẩu</h1>
                <p class="auth-form-desc">Nhập mật khẩu mới cho tài khoản</p>
            </div>

            <?php if (!empty($_SESSION['error'])): ?>
            <div class="auth-alert auth-alert--error">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo $basePath; ?>/reset-mat-khau/" class="auth-form" novalidate>
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                <div class="form-floating">
                    <input type="password" id="password" name="password" placeholder=" " required minlength="8"
                           oninput="checkPasswordStrength(this.value)">
                    <label for="password">Mật khẩu mới</label>
                    <button type="button" class="password-toggle" onclick="togglePassword('password')" tabindex="-1" aria-label="Hiện mật khẩu">
                        <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                    <div class="password-strength">
                        <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                        <span class="strength-label" id="strengthLabel"></span>
                    </div>
                </div>

                <div class="form-floating">
                    <input type="password" id="password_confirm" name="password_confirm" placeholder=" " required>
                    <label for="password_confirm">Xác nhận mật khẩu</label>
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirm')" tabindex="-1" aria-label="Hiện mật khẩu">
                        <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>

                <button type="submit" class="btn-auth btn-auth--primary">
                    <span>Đặt lại mật khẩu</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </button>
            </form>

            <div class="auth-divider"><span>hoặc</span></div>

            <div class="auth-footer-text">
                <span>Quay lại?</span>
                <a href="<?php echo $basePath; ?>/dang-nhap/" class="auth-link auth-link--primary">Đăng nhập</a>
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

function checkPasswordStrength(pwd) {
    var fill = document.getElementById('strengthFill');
    var label = document.getElementById('strengthLabel');
    if (!fill || !label) return;
    var score = 0;
    if (pwd.length >= 8) score++;
    if (pwd.match(/[A-Z]/)) score++;
    if (pwd.match(/[a-z]/)) score++;
    if (pwd.match(/[0-9]/)) score++;
    if (pwd.match(/[^A-Za-z0-9]/)) score++;
    var pct = (score / 5) * 100;
    fill.style.width = pct + '%';
    fill.className = 'strength-fill';
    if (score <= 1) { fill.classList.add('strength-weak'); label.textContent = 'Yếu'; label.className = 'strength-label strength-weak'; }
    else if (score <= 3) { fill.classList.add('strength-medium'); label.textContent = 'Trung bình'; label.className = 'strength-label strength-medium'; }
    else { fill.classList.add('strength-strong'); label.textContent = 'Mạnh'; label.className = 'strength-label strength-strong'; }
}
</script>

<?php include dirname(__FILE__) . '/../layouts/footer-auth.php'; ?>

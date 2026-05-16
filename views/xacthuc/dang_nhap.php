<?php
header('Content-Type: text/html; charset=utf-8');

$basePath = '/WebsiteTinTuc/public';
$pageTitle = 'Đăng nhập - Website Tin Tức';
include dirname(__FILE__) . '/../layouts/header-start.php';
?>
    <div class="auth-container">
        <div class="auth-header">
            <h1>🔐 Đăng nhập</h1>
            <p>Vui lòng nhập thông tin tài khoản của bạn</p>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-error">✗ <?php echo htmlspecialchars($_SESSION['error']); ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success">✓ <?php echo htmlspecialchars($_SESSION['success']); ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form method="POST" action="<?php echo $basePath; ?>/dang-nhap/">
            <div class="form-group">
                <label for="email">📧 Email</label>
                <input type="email" id="email" name="email" placeholder="your.email@example.com" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">🔑 Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
            </div>

            <button type="submit" class="btn-login">🔐 Đăng nhập</button>
        </form>

        <div class="auth-footer">
            <p>Chưa có tài khoản? <a href="<?php echo $basePath; ?>/dang-ky/" class="auth-link">Đăng ký ngay</a></p>
            <p><a href="<?php echo $basePath; ?>/quen-mat-khau/" class="auth-link">Quên mật khẩu?</a></p>
        </div>
    </div>

<?php include dirname(__FILE__) . '/../layouts/footer.php'; ?>

<?php
header('Content-Type: text/html; charset=utf-8');

$basePath = '/WebsiteTinTuc/public';
$pageTitle = 'Quên mật khẩu - Website Tin Tức';
include dirname(__FILE__) . '/../layouts/header-start.php';
?>
    <div class="auth-container">
        <div class="auth-header">
            <h1>🔑 Quên mật khẩu?</h1>
            <p>Nhập email của bạn và chúng tôi sẽ gửi cho bạn một liên kết để đặt lại mật khẩu</p>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-error">✗ <?php echo htmlspecialchars($_SESSION['error']); ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success">✓ <?php echo htmlspecialchars($_SESSION['success']); ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="info-box">
            <strong>💡 Mẹo:</strong> Liên kết đặt lại mật khẩu sẽ hết hạn sau 1 giờ vì lý do bảo mật.
        </div>

        <form method="POST" action="<?php echo $basePath; ?>/quen-mat-khau/">
            <div class="form-group">
                <label for="email">📧 Email</label>
                <input type="email" id="email" name="email" placeholder="Nhập email tài khoản của bạn" required autofocus>
            </div>

            <button type="submit" class="btn-submit">📬 Gửi liên kết đặt lại</button>
        </form>

        <div class="auth-footer">
            <p><a href="<?php echo $basePath; ?>/dang-nhap/" class="auth-link">← Quay lại đăng nhập</a></p>
            <p>Chưa có tài khoản? <a href="<?php echo $basePath; ?>/dang-ky/" class="auth-link">Đăng ký ngay</a></p>
        </div>
    </div>

<?php include dirname(__FILE__) . '/../layouts/footer.php'; ?>

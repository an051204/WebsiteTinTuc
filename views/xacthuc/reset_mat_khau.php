<?php
header('Content-Type: text/html; charset=utf-8');

$token = $token ?? '';
$basePath = '/WebsiteTinTuc/public';
$pageTitle = 'Đặt lại mật khẩu - Website Tin Tức';
include dirname(__FILE__) . '/../layouts/header-start.php';
?>
    <div class="auth-container">
        <div class="auth-header">
            <h1>🔑 Đặt lại mật khẩu</h1>
            <p>Vui lòng nhập mật khẩu mới của bạn</p>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-error">✗ <?php echo htmlspecialchars($_SESSION['error']); ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" action="<?php echo $basePath; ?>/reset-mat-khau/">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <div class="form-group">
                <label for="password">🔑 Mật khẩu mới</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu mới" required autofocus>
            </div>

            <div class="password-requirements">
                <strong>Yêu cầu mật khẩu:</strong>
                <ul>
                    <li>✓ Ít nhất 8 ký tự</li>
                    <li>✓ Chứa chữ hoa (A-Z)</li>
                    <li>✓ Chứa chữ thường (a-z)</li>
                    <li>✓ Chứa số (0-9)</li>
                    <li>✓ Chứa ký tự đặc biệt (!@#$%^&*...)</li>
                </ul>
            </div>

            <div class="form-group">
                <label for="password_confirm">🔑 Xác nhận mật khẩu</label>
                <input type="password" id="password_confirm" name="password_confirm" placeholder="Nhập lại mật khẩu" required>
            </div>

            <button type="submit" class="btn-submit">💾 Đặt lại mật khẩu</button>
        </form>

        <div class="auth-footer">
            <p><a href="<?php echo $basePath; ?>/dang-nhap/" class="auth-link">← Quay lại đăng nhập</a></p>
        </div>
    </div>

<?php include dirname(__FILE__) . '/../layouts/footer.php'; ?>

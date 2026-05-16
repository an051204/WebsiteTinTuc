<?php
header('Content-Type: text/html; charset=utf-8');

$basePath = '/WebsiteTinTuc/public';
$pageTitle = 'Đăng ký - Website Tin Tức';
include dirname(__FILE__) . '/../layouts/header-start.php';
?>
    <div class="auth-container">
        <div class="auth-header">
            <h1>📝 Đăng ký</h1>
            <p>Tạo tài khoản mới để bắt đầu</p>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-error">✗ <?php echo htmlspecialchars($_SESSION['error']); ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" action="<?php echo $basePath; ?>/dang-ky/">
            <div class="form-group">
                <label for="full_name">👤 Tên đầy đủ</label>
                <input type="text" id="full_name" name="full_name" placeholder="Nhập tên của bạn" required autofocus>
            </div>

            <div class="form-group">
                <label for="email">📧 Email</label>
                <input type="email" id="email" name="email" placeholder="your.email@example.com" required>
            </div>

            <div class="form-group">
                <label for="password">🔑 Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
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

            <button type="submit" class="btn-register">📝 Đăng ký</button>
        </form>

        <div class="auth-footer">
            <p>Đã có tài khoản? <a href="<?php echo $basePath; ?>/dang-nhap/" class="auth-link">Đăng nhập ngay</a></p>
        </div>
    </div>

<?php include dirname(__FILE__) . '/../layouts/footer.php'; ?>

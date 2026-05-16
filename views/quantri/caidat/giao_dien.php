<?php
header('Content-Type: text/html; charset=utf-8');
$basePath = '/WebsiteTinTuc/public';
$pageTitle = 'Quản Lý Giao Diện - Admin';
$settings = $settings ?? [];

include dirname(__DIR__) . '/layouts/header-admin.php';

$siteTitle = $settings['site_title'] ?? 'Website Tin Tức';
$siteLogo = $settings['site_logo'] ?? '';
$primaryColor = $settings['primary_color'] ?? '#2c3e50';
$secondaryColor = $settings['secondary_color'] ?? '#3498db';
$showFeatured = ($settings['show_featured'] ?? '1') === '1';
$showLatest = ($settings['show_latest'] ?? '1') === '1';
$showMostViewed = ($settings['show_most_viewed'] ?? '1') === '1';
$showCategories = ($settings['show_categories'] ?? '1') === '1';
$showAds = ($settings['show_ads'] ?? '1') === '1';
?>

<style>
    .admin-container {
        padding: 20px;
        max-width: 900px;
    }

    .page-header {
        margin-bottom: 25px;
    }

    .page-header h1 {
        margin: 0 0 5px 0;
        font-size: 26px;
        color: #2c3e50;
    }

    .page-header p {
        margin: 0;
        color: #7f8c8d;
        font-size: 14px;
    }

    .settings-section {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 16px 24px;
        font-size: 16px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-body {
        padding: 24px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #2d3748;
        font-size: 14px;
    }

    .form-group input[type="text"],
    .form-group input[type="url"] {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s;
        box-sizing: border-box;
    }

    .form-group input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    }

    .form-group .hint {
        font-size: 12px;
        color: #718096;
        margin-top: 4px;
    }

    .color-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .color-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .color-group input[type="color"] {
        width: 50px;
        height: 40px;
        border: 2px solid #ddd;
        border-radius: 6px;
        cursor: pointer;
        padding: 2px;
    }

    .color-group input[type="text"] {
        flex: 1;
    }

    .color-preview {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 2px solid #ddd;
        flex-shrink: 0;
    }

    .toggle-list {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .toggle-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 0;
        border-bottom: 1px solid #edf2f7;
    }

    .toggle-item:last-child {
        border-bottom: none;
    }

    .toggle-info {
        flex: 1;
    }

    .toggle-label {
        font-weight: 600;
        color: #2d3748;
        font-size: 14px;
        margin-bottom: 2px;
    }

    .toggle-desc {
        font-size: 12px;
        color: #718096;
    }

    .toggle-switch {
        position: relative;
        width: 52px;
        height: 28px;
        flex-shrink: 0;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e0;
        transition: 0.3s;
        border-radius: 28px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    .toggle-switch input:checked + .toggle-slider {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(24px);
    }

    .upload-area {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background: #fafafa;
        cursor: pointer;
        transition: all 0.3s;
    }

    .upload-area:hover {
        border-color: #667eea;
        background: #f8f9ff;
    }

    .upload-area input[type="file"] {
        display: none;
    }

    .preview-logo {
        max-width: 200px;
        max-height: 80px;
        margin-top: 10px;
        border-radius: 6px;
    }

    .form-footer {
        padding: 18px 24px;
        background: #f7fafc;
        border-top: 1px solid #edf2f7;
        display: flex;
        justify-content: flex-end;
    }

    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 14px 32px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102,126,234,0.3);
    }

    .message {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 6px;
        font-weight: 500;
    }

    .message-success {
        background-color: #c6f6d5;
        color: #22543d;
        border: 1px solid #9ae6b4;
    }

    .message-error {
        background-color: #fed7d7;
        color: #742a2a;
        border: 1px solid #fc8181;
    }

    .live-preview {
        background: #f7fafc;
        border: 1px solid #edf2f7;
        border-radius: 8px;
        padding: 16px;
        margin-top: 16px;
    }

    .live-preview-header {
        font-size: 12px;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .preview-bar {
        padding: 12px 16px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: white;
        font-weight: 700;
        font-size: 16px;
    }

    @media (max-width: 768px) {
        .color-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="admin-container">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="message message-success">
            ✅ <?php echo htmlspecialchars($_SESSION['success']); ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="message message-error">
            ❌ <?php echo htmlspecialchars($_SESSION['error']); ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="page-header">
        <h1>🎨 Quản Lý Giao Diện</h1>
        <p>Tùy chỉnh giao diện website: logo, tiêu đề, màu sắc và các khối nội dung</p>
    </div>

    <form method="POST" action="<?php echo $basePath; ?>/quan-tri/caidat/giao-dien/" enctype="multipart/form-data">

        <div class="settings-section">
            <div class="section-header">
                🏷️ Thông Tin Website
            </div>
            <div class="section-body">
                <div class="form-group">
                    <label>Tiêu đề Website</label>
                    <input type="text" name="site_title" 
                           value="<?php echo htmlspecialchars($siteTitle); ?>" 
                           placeholder="Nhập tiêu đề website"
                           id="siteTitleInput">
                    <div class="hint">Tiêu đề hiển thị trên header và tab trình duyệt</div>
                </div>

                <div class="form-group">
                    <label>Logo Website</label>
                    <div class="upload-area" onclick="document.getElementById('logoFile').click()">
                        <div style="font-size: 28px; margin-bottom: 8px;">🖼️</div>
                        <div style="font-size: 14px; color: #4a5568; font-weight: 500;">Nhấn để chọn logo</div>
                        <div style="font-size: 12px; color: #a0aec0; margin-top: 4px;">PNG, JPG, SVG - Tối đa 2MB</div>
                        <input type="file" id="logoFile" name="logo_file" accept="image/*" onchange="previewLogo(this)">
                    </div>
                    <div id="logoPreview">
                        <?php if (!empty($siteLogo)): ?>
                            <img src="<?php echo htmlspecialchars($siteLogo); ?>" class="preview-logo" alt="Logo">
                        <?php endif; ?>
                    </div>
                    <div class="hint" style="margin-top: 8px;">Hoặc nhập URL logo:</div>
                    <input type="text" name="site_logo" 
                           value="<?php echo htmlspecialchars($siteLogo); ?>" 
                           placeholder="https://example.com/logo.png"
                           style="margin-top: 4px;">
                </div>
            </div>
        </div>

        <div class="settings-section">
            <div class="section-header">
                🎨 Màu Sắc
            </div>
            <div class="section-body">
                <div class="color-row">
                    <div class="form-group">
                        <label>Màu chính (Primary)</label>
                        <div class="color-group">
                            <input type="color" id="primaryColorPicker" 
                                   value="<?php echo htmlspecialchars($primaryColor); ?>"
                                   onchange="document.getElementById('primaryColorText').value = this.value; updatePreview();">
                            <input type="text" id="primaryColorText" name="primary_color" 
                                   value="<?php echo htmlspecialchars($primaryColor); ?>"
                                   onchange="document.getElementById('primaryColorPicker').value = this.value; updatePreview();">
                        </div>
                        <div class="hint">Dùng cho header, sidebar, tiêu đề</div>
                    </div>
                    <div class="form-group">
                        <label>Màu phụ (Secondary)</label>
                        <div class="color-group">
                            <input type="color" id="secondaryColorPicker" 
                                   value="<?php echo htmlspecialchars($secondaryColor); ?>"
                                   onchange="document.getElementById('secondaryColorText').value = this.value; updatePreview();">
                            <input type="text" id="secondaryColorText" name="secondary_color" 
                                   value="<?php echo htmlspecialchars($secondaryColor); ?>"
                                   onchange="document.getElementById('secondaryColorPicker').value = this.value; updatePreview();">
                        </div>
                        <div class="hint">Dùng cho nút bấm, liên kết, nhấn mạnh</div>
                    </div>
                </div>

                <div class="live-preview">
                    <div class="live-preview-header">Xem trước Header</div>
                    <div class="preview-bar" id="previewBar" 
                         style="background: <?php echo htmlspecialchars($primaryColor); ?>;">
                        📰 <span id="previewTitle"><?php echo htmlspecialchars($siteTitle); ?></span>
                        <span style="margin-left: auto; font-size: 13px; font-weight: 400;">Trang Chủ | Chuyên Mục | Đăng Nhập</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="settings-section">
            <div class="section-header">
                🔧 Bật/Tắt Khối Nội Dung
            </div>
            <div class="section-body">
                <div class="toggle-list">
                    <div class="toggle-item">
                        <div class="toggle-info">
                            <div class="toggle-label">🔥 Tin Nổi Bật (Slide)</div>
                            <div class="toggle-desc">Hiển thị slider tin nổi bật ở đầu trang chủ</div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="show_featured" value="1" <?php echo $showFeatured ? 'checked' : ''; ?>>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="toggle-item">
                        <div class="toggle-info">
                            <div class="toggle-label">⏰ Tin Mới Nhất</div>
                            <div class="toggle-desc">Hiển thị khối tin mới nhất trên trang chủ</div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="show_latest" value="1" <?php echo $showLatest ? 'checked' : ''; ?>>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="toggle-item">
                        <div class="toggle-info">
                            <div class="toggle-label">👀 Xem Nhiều Nhất</div>
                            <div class="toggle-desc">Hiển thị khối bài viết xem nhiều nhất trên trang chủ</div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="show_most_viewed" value="1" <?php echo $showMostViewed ? 'checked' : ''; ?>>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="toggle-item">
                        <div class="toggle-info">
                            <div class="toggle-label">📚 Tin Theo Chuyên Mục</div>
                            <div class="toggle-desc">Hiển thị các khối bài viết theo chuyên mục trên trang chủ</div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="show_categories" value="1" <?php echo $showCategories ? 'checked' : ''; ?>>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="toggle-item">
                        <div class="toggle-info">
                            <div class="toggle-label">📢 Quảng Cáo</div>
                            <div class="toggle-desc">Hiển thị banner quảng cáo trên trang chủ</div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="show_ads" value="1" <?php echo $showAds ? 'checked' : ''; ?>>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-footer">
                <button type="submit" class="btn-save">💾 Lưu Thay Đổi</button>
            </div>
        </div>
    </form>
</div>

<script>
function previewLogo(input) {
    const preview = document.getElementById('logoPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" class="preview-logo" alt="Logo Preview">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function updatePreview() {
    const primaryColor = document.getElementById('primaryColorText').value;
    const previewBar = document.getElementById('previewBar');
    previewBar.style.background = primaryColor;
}

document.getElementById('siteTitleInput').addEventListener('input', function() {
    document.getElementById('previewTitle').textContent = this.value || 'Website Tin Tức';
});
</script>

<?php include dirname(__DIR__) . '/layouts/footer-admin.php'; ?>

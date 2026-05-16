<?php
header('Content-Type: text/html; charset=utf-8');
$basePath = '/WebsiteTinTuc/public';
$isEdit = $isEdit ?? false;
$quangCao = $quangCao ?? [];
$pageTitle = ($isEdit ? 'Sửa' : 'Thêm') . ' Quảng Cáo - Admin';

include dirname(__DIR__) . '/layouts/header-admin.php';
?>

<style>
    .admin-container {
        padding: 20px;
        max-width: 800px;
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

    .form-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 18px 24px;
        font-size: 16px;
        font-weight: 600;
    }

    .form-body {
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

    .form-group label .required {
        color: #e74c3c;
    }

    .form-group input[type="text"],
    .form-group input[type="url"],
    .form-group input[type="date"],
    .form-group select {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s;
        box-sizing: border-box;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    }

    .form-group .hint {
        font-size: 12px;
        color: #718096;
        margin-top: 4px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .upload-area {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background: #fafafa;
        transition: all 0.3s;
        cursor: pointer;
    }

    .upload-area:hover {
        border-color: #667eea;
        background: #f8f9ff;
    }

    .upload-area input[type="file"] {
        display: none;
    }

    .upload-area .upload-icon {
        font-size: 32px;
        margin-bottom: 8px;
    }

    .upload-area .upload-text {
        font-size: 14px;
        color: #4a5568;
        font-weight: 500;
    }

    .upload-area .upload-hint {
        font-size: 12px;
        color: #a0aec0;
        margin-top: 4px;
    }

    .preview-img {
        max-width: 300px;
        max-height: 150px;
        object-fit: cover;
        border-radius: 6px;
        margin-top: 10px;
        border: 1px solid #eee;
    }

    .form-footer {
        padding: 18px 24px;
        background: #f7fafc;
        border-top: 1px solid #edf2f7;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(102,126,234,0.3);
    }

    .btn-back {
        background: #edf2f7;
        color: #4a5568;
        border: none;
        padding: 12px 28px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-back:hover {
        background: #e2e8f0;
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

    @media (max-width: 768px) {
        .form-row {
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
        <h1><?php echo $isEdit ? '✏️ Sửa Quảng Cáo' : '➕ Thêm Quảng Cáo'; ?></h1>
        <p><?php echo $isEdit ? 'Cập nhật thông tin banner quảng cáo' : 'Tạo banner quảng cáo mới'; ?></p>
    </div>

    <form method="POST"
          action="<?php echo $basePath; ?>/quan-tri/caidat/quang-cao/<?php echo $isEdit ? 'sua/?id=' . ($quangCao['id'] ?? 0) : 'them/'; ?>"
          enctype="multipart/form-data">

        <div class="form-card">
            <div class="form-header">
                📢 Thông Tin Quảng Cáo
            </div>
            <div class="form-body">
                <div class="form-group">
                    <label>Tiêu đề <span class="required">*</span></label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($quangCao['title'] ?? ''); ?>" 
                           placeholder="Nhập tiêu đề quảng cáo" required>
                </div>

                <div class="form-group">
                    <label>Ảnh banner <span class="required">*</span></label>
                    <div class="upload-area" onclick="document.getElementById('imageFile').click()">
                        <div class="upload-icon">📷</div>
                        <div class="upload-text">Nhấn để chọn ảnh</div>
                        <div class="upload-hint">JPG, PNG, GIF, WEBP - Tối đa 5MB</div>
                        <input type="file" id="imageFile" name="image_file" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <div id="imagePreview">
                        <?php if (!empty($quangCao['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($quangCao['image_url']); ?>" class="preview-img" alt="Preview">
                        <?php endif; ?>
                    </div>
                    <div class="hint" style="margin-top: 8px;">Hoặc nhập URL ảnh trực tiếp:</div>
                    <input type="text" name="image_url" value="<?php echo htmlspecialchars($quangCao['image_url'] ?? ''); ?>" 
                           placeholder="https://example.com/banner.jpg" style="margin-top: 4px;">
                </div>

                <div class="form-group">
                    <label>Link liên kết <span class="required">*</span></label>
                    <input type="url" name="link_url" value="<?php echo htmlspecialchars($quangCao['link_url'] ?? ''); ?>" 
                           placeholder="https://example.com" required>
                    <div class="hint">URL sẽ mở khi người dùng nhấn vào banner</div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Vị trí hiển thị</label>
                        <select name="position">
                            <option value="home_top" <?php echo ($quangCao['position'] ?? '') === 'home_top' ? 'selected' : ''; ?>>🏠 Trang chủ (trên cùng)</option>
                            <option value="home_middle" <?php echo ($quangCao['position'] ?? '') === 'home_middle' ? 'selected' : ''; ?>>🏠 Trang chủ (giữa nội dung)</option>
                            <option value="sidebar" <?php echo ($quangCao['position'] ?? '') === 'sidebar' ? 'selected' : ''; ?>>📐 Sidebar</option>
                            <option value="article_bottom" <?php echo ($quangCao['position'] ?? '') === 'article_bottom' ? 'selected' : ''; ?>>📰 Cuối bài viết</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select name="status">
                            <option value="active" <?php echo ($quangCao['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>✅ Hoạt động</option>
                            <option value="inactive" <?php echo ($quangCao['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>⛔ Tắt</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Ngày bắt đầu</label>
                        <input type="date" name="start_date" 
                               value="<?php echo htmlspecialchars($quangCao['start_date'] ?? ''); ?>">
                        <div class="hint">Để trống = hiển thị ngay</div>
                    </div>
                    <div class="form-group">
                        <label>Ngày kết thúc</label>
                        <input type="date" name="end_date" 
                               value="<?php echo htmlspecialchars($quangCao['end_date'] ?? ''); ?>">
                        <div class="hint">Để trống = hiển thị vĩnh viễn</div>
                    </div>
                </div>
            </div>
            <div class="form-footer">
                <a href="<?php echo $basePath; ?>/quan-tri/caidat/quang-cao/" class="btn-back">← Quay lại</a>
                <button type="submit" class="btn-save">💾 <?php echo $isEdit ? 'Cập Nhật' : 'Thêm Mới'; ?></button>
            </div>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" class="preview-img" alt="Preview">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include dirname(__DIR__) . '/layouts/footer-admin.php'; ?>

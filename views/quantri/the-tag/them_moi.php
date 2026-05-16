<?php
$tag = $tag ?? [];
$isEdit = $isEdit ?? false;
$basePath = $basePath ?? '/WebsiteTinTuc/public';

header('Content-Type: text/html; charset=utf-8');
$pageTitle = ($isEdit ? 'Sửa' : 'Thêm') . ' Thẻ Tag - Admin';

include dirname(__FILE__) . '/../layouts/header-admin.php';
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        color: #333;
    }

    .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 5px;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .form-container {
        background: white;
        padding: 30px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .form-container h1 {
        margin-top: 0;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    input[type="text"] {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-family: Arial, sans-serif;
        font-size: 14px;
        box-sizing: border-box;
    }

    input[type="text"]:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 5px rgba(102, 126, 234, 0.2);
    }

    .slug-preview {
        margin-top: 5px;
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 4px;
        border: 1px solid #eee;
        font-size: 13px;
        color: #666;
    }

    .help-text {
        font-size: 12px;
        color: #999;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        flex: 1;
        text-align: center;
    }

    .btn-submit {
        background-color: #667eea;
        color: white;
    }

    .btn-submit:hover {
        background-color: #5568d3;
    }

    .btn-cancel {
        background-color: #6c757d;
        color: white;
    }

    .btn-cancel:hover {
        background-color: #5a6268;
    }
</style>

<div class="container">
    <div class="form-container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                ✓ <?php echo htmlspecialchars($_SESSION['success']); ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                ✗ <?php echo htmlspecialchars($_SESSION['error']); ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <h1><?php echo $isEdit ? '✏️ Sửa Thẻ Tag' : '✚ Thêm Thẻ Tag'; ?></h1>

        <form method="POST" action="<?php echo $isEdit ? $basePath . '/quan-tri/the-tag/sua/?id=' . $tag['id'] : $basePath . '/quan-tri/the-tag/them/'; ?>">
            <div class="form-group">
                <label for="name">Tên Tag *</label>
                <input type="text" id="name" name="name" required 
                       value="<?php echo htmlspecialchars($tag['name'] ?? ''); ?>"
                       placeholder="VD: Công Nghệ, PHP, JavaScript..."
                       onkeyup="updateSlugPreview()">
                <div class="help-text">Tên tag phải từ 2-50 ký tự</div>
            </div>

            <div class="form-group">
                <label>Slug (Auto)</label>
                <div class="slug-preview" id="slugPreview">
                    <?php echo htmlspecialchars($tag['slug'] ?? 'slug-se-duoc-tao-tu-dong'); ?>
                </div>
                <div class="help-text">Slug tự động được tạo từ tên tag (dùng cho URL)</div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-submit">💾 <?php echo $isEdit ? 'Cập Nhật' : 'Thêm'; ?></button>
                <a href="<?php echo $basePath; ?>/quan-tri/the-tag/" class="btn btn-cancel">← Quay Lại</a>
            </div>
        </form>
    </div>
</div>

<script>
function updateSlugPreview() {
    const nameInput = document.getElementById('name');
    const slugPreview = document.getElementById('slugPreview');
    
    let name = nameInput.value.toLowerCase().trim();
    // Loại bỏ các ký tự không phải chữ, số, dấu cách, gạch ngang
    name = name.replace(/[^a-z0-9\s-]/g, '');
    // Thay nhiều dấu cách/gạch ngang bằng một dấu gạch ngang
    name = name.replace(/[\s-]+/g, '-');
    // Xóa dấu gạch ngang ở đầu/cuối
    name = name.replace(/^-+|-+$/g, '');
    
    if (name === '') {
        slugPreview.textContent = 'slug-se-duoc-tao-tu-dong';
    } else {
        slugPreview.textContent = name;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', updateSlugPreview);
</script>


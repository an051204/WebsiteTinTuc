<?php
$category = $category ?? [];
$allCategories = $allCategories ?? [];
$isEdit = !empty($category);
$basePath = '/WebsiteTinTuc/public';

header('Content-Type: text/html; charset=utf-8');
$pageTitle = ($isEdit ? 'Sửa' : 'Thêm') . ' Chuyên Mục - Admin';

include dirname(__FILE__) . '/../layouts/header-admin.php';
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        color: #333;
    }

    .container {
        max-width: 800px;
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

    input[type="text"],
    textarea,
    select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-family: Arial, sans-serif;
        font-size: 14px;
        box-sizing: border-box;
    }

    input[type="text"]:focus,
    textarea:focus,
    select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 5px rgba(102, 126, 234, 0.2);
    }

    textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .checkbox-group input[type="checkbox"] {
        width: auto;
        margin: 0;
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

    .help-text {
        font-size: 12px;
        color: #999;
        margin-top: 5px;
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

        <h1><?php echo $isEdit ? '✏️ Sửa Chuyên Mục' : '✚ Thêm Chuyên Mục'; ?></h1>

        <form method="POST" action="<?php echo $isEdit ? '/WebsiteTinTuc/public/quan-tri/chuyenmuc/sua/?id=' . $category['id'] : '/WebsiteTinTuc/public/quan-tri/chuyenmuc/them/'; ?>">
            <div class="form-group">
                <label for="name">Tên Chuyên Mục *</label>
                <input type="text" id="name" name="name" required 
                       value="<?php echo htmlspecialchars($category['name'] ?? ''); ?>"
                       placeholder="Nhập tên chuyên mục (tối thiểu 3 ký tự)">
                <div class="help-text">Tên chuyên mục phải ít nhất 3 ký tự</div>
            </div>

            <div class="form-group">
                <label for="parent_id">Chuyên Mục Cha (Để trống nếu là chuyên mục chính)</label>
                <select id="parent_id" name="parent_id">
                    <option value="">-- Chuyên Mục Chính --</option>
                    <?php foreach ($allCategories as $cat): ?>
                        <?php 
                            // Không hiển thị chính nó nếu đang sửa
                            if ($isEdit && $cat['id'] == $category['id']) continue;
                            // Không hiển thị nếu nó là con của chuyên mục hiện tại (tránh vòng lặp)
                            if ($isEdit && $cat['parent_id'] == $category['id']) continue;
                        ?>
                        <option value="<?php echo $cat['id']; ?>" 
                                <?php echo (!empty($category['parent_id']) && $cat['id'] == $category['parent_id']) ? 'selected' : ''; ?>>
                            <?php echo str_repeat('└─ ', ($cat['parent_id'] ? 1 : 0)) . htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="help-text">Chọn chuyên mục cha để tạo chuyên mục con</div>
            </div>

            <div class="form-group">
                <label for="description">Mô Tả</label>
                <textarea id="description" name="description" placeholder="Nhập mô tả chuyên mục (tùy chọn)"><?php echo htmlspecialchars($category['description'] ?? ''); ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="status">Trạng Thái *</label>
                    <select id="status" name="status" required>
                        <option value="active" <?php echo (!isset($category['status']) || $category['status'] === 'active') ? 'selected' : ''; ?>>Công Khai</option>
                        <option value="hidden" <?php echo (isset($category['status']) && $category['status'] === 'hidden') ? 'selected' : ''; ?>>Ẩn</option>
                    </select>
                    <div class="help-text">Công khai: hiển thị trên website, Ẩn: chỉ admin thấy</div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-submit">
                    <?php echo $isEdit ? '💾 Cập Nhật' : '✚ Tạo Chuyên Mục'; ?>
                </button>
                <a href="<?php echo $basePath; ?>/quan-tri/chuyenmuc/" class="btn btn-cancel">← Hủy</a>
            </div>
        </form>
    </div>
</div>

<?php include dirname(__FILE__) . '/../layouts/footer-admin.php'; ?>

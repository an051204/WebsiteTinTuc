<?php
$badWords = $badWords ?? [];
$basePath = $basePath ?? '/WebsiteTinTuc/public';

header('Content-Type: text/html; charset=utf-8');
$pageTitle = 'Quản Lý Từ Khóa Xấu - Admin';

include dirname(__FILE__) . '/../layouts/header-admin.php';
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        color: #333;
    }

    .container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
    }

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .header-section h1 {
        margin: 0;
    }

    .header-section a {
        background-color: #667eea;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
    }

    .header-section a:hover {
        background-color: #5568d3;
    }

    .content-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-container,
    .list-container {
        background: white;
        padding: 30px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .form-container h2,
    .list-container h2 {
        margin-top: 0;
        margin-bottom: 20px;
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

    .form-actions {
        display: flex;
        gap: 10px;
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
        flex: 1;
    }

    .btn-submit:hover {
        background-color: #5568d3;
    }

    .btn-reset {
        background-color: #6c757d;
        color: white;
    }

    .btn-reset:hover {
        background-color: #5a6268;
    }

    .help-text {
        font-size: 12px;
        color: #999;
        margin-top: 5px;
    }

    .words-list {
        max-height: 500px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
    }

    .word-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px;
        border: 1px solid #eee;
        border-radius: 4px;
        margin-bottom: 8px;
        background-color: #f9f9f9;
    }

    .word-item:hover {
        background-color: #f5f5f5;
    }

    .word-text {
        font-weight: 500;
        color: #333;
        flex: 1;
    }

    .btn-delete {
        background-color: #dc3545;
        color: white;
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: background-color 0.2s;
    }

    .btn-delete:hover {
        background-color: #c82333;
    }

    .no-words {
        text-align: center;
        padding: 30px 10px;
        color: #999;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .content-section {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container">
    <a href="<?php echo $basePath; ?>/quan-tri/binhluan/danh-sach/" class="back-link">← Quay lại Bình Luận</a>

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

    <div class="header-section">
        <h1>🚫 Quản Lý Từ Khóa Xấu</h1>
    </div>

    <div class="content-section">
        <!-- Form Thêm Từ Khóa -->
        <div class="form-container">
            <h2>➕ Thêm Từ Khóa Xấu</h2>

            <form id="addWordForm" method="POST" action="<?php echo $basePath; ?>/quan-tri/binhluan/them-tu-khoa/">
                <div class="form-group">
                    <label for="word">Từ Khóa / Cụm Từ</label>
                    <input type="text" id="word" name="word" required 
                           placeholder="Nhập từ khóa xấu (tối thiểu 2 ký tự)"
                           autocomplete="off">
                    <div class="help-text">Các bình luận chứa từ khóa này sẽ được đánh dấu là spam</div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-submit">✚ Thêm</button>
                    <button type="reset" class="btn btn-reset">Xóa</button>
                </div>
            </form>

            <div style="margin-top: 20px; padding: 15px; background-color: #f0f8ff; border-radius: 4px; font-size: 13px; color: #333;">
                <strong>💡 Lưu ý:</strong>
                <ul style="margin: 5px 0; padding-left: 20px;">
                    <li>Từ khóa không phân biệt chữ hoa/thường</li>
                    <li>Có thể thêm cụm từ (VD: "từ xấu 123")</li>
                    <li>Kiểm tra tồn tại trước khi thêm</li>
                </ul>
            </div>
        </div>

        <!-- Danh Sách Từ Khóa -->
        <div class="list-container">
            <h2>📋 Danh Sách Từ Khóa (<?php echo count($badWords); ?>)</h2>

            <?php if (empty($badWords)): ?>
                <div class="no-words">
                    <p>Chưa có từ khóa xấu nào</p>
                </div>
            <?php else: ?>
                <div class="words-list">
                    <?php foreach ($badWords as $word): ?>
                        <div class="word-item">
                            <span class="word-text"><?php echo htmlspecialchars($word['word']); ?></span>
                            <button type="button" class="btn-delete" onclick="deleteWord(<?php echo $word['id']; ?>)">
                                🗑 Xóa
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Xử lý form thêm từ khóa
document.getElementById('addWordForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const word = document.getElementById('word').value.trim();

    if (!word || word.length < 2) {
        alert('Từ khóa phải ít nhất 2 ký tự');
        return;
    }

    const formData = new FormData();
    formData.append('word', word);

    fetch('<?php echo $basePath; ?>/quan-tri/binhluan/them-tu-khoa/', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('word').value = '';
            location.reload();
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Lỗi khi thêm từ khóa!');
    });
});

function deleteWord(wordId) {
    if (!confirm('Bạn chắc chắn muốn xóa từ khóa này?')) {
        return;
    }

    const formData = new FormData();
    formData.append('id', wordId);

    fetch('<?php echo $basePath; ?>/quan-tri/binhluan/xoa-tu-khoa/', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Lỗi khi xóa từ khóa!');
    });
}
</script>


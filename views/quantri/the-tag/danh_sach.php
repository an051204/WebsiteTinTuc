<?php
$tags = $tags ?? [];
$page = $page ?? 1;
$totalPages = $totalPages ?? 1;
$search = $search ?? '';
$total = $total ?? 0;
$basePath = $basePath ?? '/WebsiteTinTuc/public';

header('Content-Type: text/html; charset=utf-8');
$pageTitle = 'Quản Lý Thẻ Tag - Admin';

include dirname(__FILE__) . '/../layouts/header-admin.php';
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        color: #333;
    }

    .container {
        max-width: 1000px;
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
        border-radius: 10px;
        display: inline-block;
        margin-left: 14px; /* breathing space from tag-count */
        box-shadow: 0 6px 18px rgba(102,126,234,0.12);
    }

    .header-section a:hover {
        background-color: #5568d3;
    }

    .filters {
        background: white;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .filter-row {
        display: flex;
        gap: 10px;
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
    }

    .filter-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        font-size: 14px;
    }

    .filter-group input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        box-sizing: border-box;
    }

    .filter-group input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 5px rgba(102, 126, 234, 0.2);
    }

    .btn-search {
        background-color: #667eea;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
    }

    .btn-search:hover {
        background-color: #5568d3;
    }

    .btn-reset {
        background-color: #6c757d;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
    }

    .btn-reset:hover {
        background-color: #5a6268;
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

    .table-container {
        background: white;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: #333;
    }

    tbody tr {
        border-bottom: 1px solid #dee2e6;
        transition: background-color 0.2s;
    }

    tbody tr:hover {
        background-color: #f8f9fa;
    }

    td {
        padding: 15px;
    }

    .actions {
        display: flex;
        gap: 8px;
    }

    .btn-small {
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.2s;
    }

    .btn-edit {
        background-color: #ffc107;
        color: #333;
    }

    .btn-edit:hover {
        background-color: #e0a800;
    }

    .btn-delete {
        background-color: #dc3545;
        color: white;
    }

    .btn-delete:hover {
        background-color: #c82333;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 5px;
        margin-top: 20px;
        padding: 20px;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-decoration: none;
        color: #667eea;
    }

    .pagination a:hover {
        background-color: #f5f5f5;
    }

    .pagination .current {
        background-color: #667eea;
        color: white;
        border-color: #667eea;
    }

    .no-data {
        text-align: center;
        padding: 40px 20px;
        color: #999;
    }

    .tag-count {
        display: inline-block;
        background-color: #667eea;
        color: white;
        padding: 6px 10px;
        border-radius: 10px;
        font-size: 13px;
        margin-left: 10px;
        vertical-align: middle;
        box-shadow: 0 6px 18px rgba(102,126,234,0.12);
    }
</style>

<div class="container">
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
        <h1>🏷️ Quản Lý Thẻ Tag <span class="tag-count"><?php echo $total; ?></span></h1>
        <a href="<?php echo $basePath; ?>/quan-tri/the-tag/them/">➕ Thêm Tag Mới</a>
    </div>

    <!-- Bộ Lọc -->
    <div class="filters">
        <form method="GET" action="<?php echo $basePath; ?>/quan-tri/the-tag/">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="search">Tìm kiếm:</label>
                    <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Tìm kiếm theo tên hoặc slug...">
                </div>

                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn-search">🔍 Tìm</button>
                    <a href="<?php echo $basePath; ?>/quan-tri/the-tag/" class="btn-reset">↻ Xóa</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Bảng Tag -->
    <div class="table-container">
        <?php if (empty($tags)): ?>
            <div class="no-data">
                <p>Không có tag nào</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Tên Tag</th>
                        <th>Slug</th>
                        <th style="width: 150px;">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tags as $tag): ?>
                        <tr>
                            <td><?php echo $tag['id']; ?></td>
                            <td><?php echo htmlspecialchars($tag['name']); ?></td>
                            <td><code><?php echo htmlspecialchars($tag['slug']); ?></code></td>
                            <td>
                                <div class="actions">
                                    <a href="<?php echo $basePath; ?>/quan-tri/the-tag/sua/?id=<?php echo $tag['id']; ?>" class="btn-small btn-edit">✏️ Sửa</a>
                                    <button class="btn-small btn-delete" onclick="deleteTag(<?php echo $tag['id']; ?>)">🗑 Xóa</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Phân Trang -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="<?php echo $basePath; ?>/quan-tri/the-tag/?page=1<?php if ($search) echo '&search=' . urlencode($search); ?>">« Đầu</a>
                        <a href="<?php echo $basePath; ?>/quan-tri/the-tag/?page=<?php echo $page - 1; ?><?php if ($search) echo '&search=' . urlencode($search); ?>">‹ Trước</a>
                    <?php endif; ?>

                    <?php
                        $startPage = max(1, $page - 2);
                        $endPage = min($totalPages, $page + 2);

                        if ($startPage > 1): ?>
                            <span>...</span>
                        <?php endif; ?>

                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <?php if ($i === $page): ?>
                                <span class="current"><?php echo $i; ?></span>
                            <?php else: ?>
                                <a href="<?php echo $basePath; ?>/quan-tri/the-tag/?page=<?php echo $i; ?><?php if ($search) echo '&search=' . urlencode($search); ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($endPage < $totalPages): ?>
                            <span>...</span>
                        <?php endif; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="<?php echo $basePath; ?>/quan-tri/the-tag/?page=<?php echo $page + 1; ?><?php if ($search) echo '&search=' . urlencode($search); ?>">Sau ›</a>
                        <a href="<?php echo $basePath; ?>/quan-tri/the-tag/?page=<?php echo $totalPages; ?><?php if ($search) echo '&search=' . urlencode($search); ?>">Cuối »</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function deleteTag(tagId) {
    if (!confirm('Bạn chắc chắn muốn xóa tag này? Sẽ xóa liên kết từ tất cả bài viết!')) {
        return;
    }

    const formData = new FormData();
    formData.append('id', tagId);

    fetch('<?php echo $basePath; ?>/quan-tri/the-tag/xoa/', {
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
        alert('Lỗi khi xóa tag!');
    });
}
</script>


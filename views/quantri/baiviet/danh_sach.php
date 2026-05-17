<?php
$articles = $articles ?? [];
$categories = $categories ?? [];
$page = $page ?? 1;
$totalPages = $totalPages ?? 0;
$search = $search ?? '';
$status = $status ?? '';

header('Content-Type: text/html; charset=utf-8');
$basePath = '/WebsiteTinTuc/public';
$pageTitle = 'Quản lý Bài Viết - Admin';

include dirname(__FILE__) . '/../layouts/header-admin.php';
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        color: #333;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .header {
        background: white;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .header h1 {
        margin: 0 0 15px 0;
        font-size: 24px;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
    }

    .btn-primary {
        background-color: #667eea;
        color: white;
    }

    .btn-primary:hover {
        background-color: #5568d3;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .btn-warning {
        background-color: #ffc107;
        color: black;
    }

    .btn-warning:hover {
        background-color: #e0a800;
    }

    .search-box {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        width: 100%;
        align-items: center;
    }

    .search-box input {
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        flex: 2;
        font-size: 16px;
        height: 45px;
        box-sizing: border-box;
    }

    .search-box select {
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        height: 45px;
        flex: 1;
    }

    .search-box button {
        padding: 12px 25px;
        background-color: #667eea;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        height: 45px;
        white-space: nowrap;
    }

    .search-box button:hover {
        background-color: #5568d3;
    }

    .table-container {
        background: white;
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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

    td {
        padding: 15px;
        border-bottom: 1px solid #dee2e6;
    }

    tbody tr:hover {
        background-color: #f8f9fa;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 3px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .status-draft {
        background-color: #ccc;
        color: white;
    }

    .status-pending {
        background-color: #ff9800;
        color: white;
    }

    .status-published {
        background-color: #4caf50;
        color: white;
    }

    .featured-badge {
        background-color: #2196f3;
        color: white;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 11px;
    }

    .actions {
        display: flex;
        gap: 5px;
    }

    .action-btn {
        padding: 5px 10px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 12px;
        text-decoration: none;
        display: inline-block;
    }

    .edit-btn {
        background-color: #667eea;
        color: white;
    }

    .delete-btn {
        background-color: #dc3545;
        color: white;
    }

    .approve-btn {
        background-color: #28a745;
        color: white;
    }

    .approve-btn:hover {
        background-color: #218838;
    }

    .delete-btn:hover {
        background-color: #c82333;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 5px;
        margin-top: 20px;
        align-items: center;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 3px;
        text-decoration: none;
        color: #667eea;
    }

    .pagination a:hover {
        background-color: #667eea;
        color: white;
    }

    .pagination .active {
        background-color: #667eea;
        color: white;
        border-color: #667eea;
    }

    .empty-message {
        text-align: center;
        padding: 40px;
        color: #999;
    }

    .message {
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 5px;
    }

    .message-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .message-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>

<div class="container">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="message message-success">
            <?php echo htmlspecialchars($_SESSION['success']); ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="message message-error">
            <?php echo htmlspecialchars($_SESSION['error']); ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="header">
        <h1>Quản lý Bài Viết</h1>
        
        <div class="action-buttons">
            <a href="<?php echo $basePath; ?>/quan-tri/bai-viet/them/" class="btn btn-primary">+ Thêm Bài Viết</a>
        </div>

        <form method="GET" class="search-box">
            <input type="text" name="search" placeholder="Tìm kiếm theo tiêu đề hoặc tác giả..." value="<?php echo htmlspecialchars($search); ?>">
            <select name="status">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="draft" <?php echo $status === 'draft' ? 'selected' : ''; ?>>Nháp</option>
                <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Chờ duyệt</option>
                <option value="published" <?php echo $status === 'published' ? 'selected' : ''; ?>>Đã đăng</option>
            </select>
            <button type="submit">Tìm kiếm</button>
        </form>
    </div>

    <?php if (empty($articles)): ?>
        <div class="table-container">
            <div class="empty-message">
                <p>Không có bài viết nào</p>
            </div>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">ID</th>
                        <th style="width: 8%;">Ảnh</th>
                        <th style="width: 30%;">Tiêu đề</th>
                        <th style="width: 15%;">Chuyên mục</th>
                        <th style="width: 12%;">Tác giả</th>
                        <th style="width: 10%;">Trạng thái</th>
                        <th style="width: 8%;">Lượt xem</th>
                        <th style="width: 10%;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($article['id']); ?></td>
                            <td>
                                <?php if (!empty($article['thumbnail'])): ?>
                                    <img src="<?php echo htmlspecialchars($article['thumbnail']); ?>" alt="Thumbnail" style="width: 64px; height: 64px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd;">
                                <?php else: ?>
                                    <span style="color: #999; font-size: 12px;">Không có</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($article['title']); ?></strong>
                                <?php if ($article['is_featured']): ?>
                                    <span class="featured-badge">Nổi bật</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($article['category_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($article['author_name'] ?? 'N/A'); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo htmlspecialchars($article['status']); ?>">
                                    <?php
                                    $statusText = [
                                        'draft' => 'Nháp',
                                        'pending' => 'Chờ duyệt',
                                        'published' => 'Đã đăng'
                                    ];
                                    echo $statusText[$article['status']] ?? $article['status'];
                                    ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($article['views_count'] ?? 0); ?></td>
                            <td>
                                <div class="actions">
                                    <?php if ($article['status'] === 'pending'): ?>
                                        <form method="POST" action="<?php echo $basePath; ?>/quan-tri/bai-viet/cap-nhat-trang-thai/" style="display: inline;">
                                            <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                                            <input type="hidden" name="status" value="published">
                                            <button type="submit" class="action-btn approve-btn" onclick="return confirm('Duyệt bài viết này?');">✓ Duyệt</button>
                                        </form>
                                    <?php endif; ?>
                                    <a href="<?php echo $basePath; ?>/quan-tri/bai-viet/sua/<?php echo $article['id']; ?>/" class="action-btn edit-btn">Sửa</a>
                                    <form method="POST" action="<?php echo $basePath; ?>/quan-tri/bai-viet/xoa/" style="display: inline;">
                                        <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                                        <button type="submit" class="action-btn delete-btn" onclick="return confirm('Xóa bài viết này? Hành động không thể hoàn tác!');">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=1&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>">Đầu</a>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>">← Trước</a>
                <?php endif; ?>

                <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                    <?php if ($i === $page): ?>
                        <span class="active"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>">Sau →</a>
                    <a href="?page=<?php echo $totalPages; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>">Cuối</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include dirname(__FILE__) . '/../layouts/footer-admin.php'; ?>

<?php
$comments = $comments ?? [];
$page = $page ?? 1;
$totalPages = $totalPages ?? 1;
$search = $search ?? '';
$status = $status ?? '';
$stats = $stats ?? [];
$basePath = $basePath ?? '/WebsiteTinTuc/public';

header('Content-Type: text/html; charset=utf-8');
$pageTitle = 'Quản Lý Bình Luận - Admin';

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

    .stats-section {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 15px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .stat-card h3 {
        margin: 0 0 10px 0;
        font-size: 14px;
        color: #999;
    }

    .stat-card .number {
        font-size: 28px;
        font-weight: bold;
        color: #667eea;
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
        gap: 15px;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        font-size: 14px;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        box-sizing: border-box;
    }

    .filter-group input:focus,
    .filter-group select:focus {
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
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    thead {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    th {
        padding: 8px 10px;
        text-align: left;
        font-weight: 600;
        color: #333;
        white-space: nowrap;
        border-right: 1px solid #dee2e6;
        font-size: 12px;
    }

    th:last-child {
        border-right: none;
    }

    tbody tr {
        border-bottom: 1px solid #dee2e6;
        transition: background-color 0.2s;
    }

    tbody tr:hover {
        background-color: #f8f9fa;
    }

    td {
        padding: 8px 10px;
        vertical-align: middle;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-approved {
        background-color: #d4edda;
        color: #155724;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-hidden {
        background-color: #e2e3e5;
        color: #383d41;
    }

    .status-spam {
        background-color: #f8d7da;
        color: #721c24;
    }

    .actions {
        display: flex;
        gap: 6px;
        flex-wrap: nowrap;
        align-items: center;
        justify-content: flex-start;
    }

    .btn-small {
        padding: 4px 8px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.2s;
        white-space: nowrap;
    }

    .btn-approve {
        background-color: #28a745;
        color: white;
    }

    .btn-approve:hover {
        background-color: #218838;
    }

    .btn-hide {
        background-color: #ffc107;
        color: #333;
    }

    .btn-hide:hover {
        background-color: #e0a800;
    }

    .btn-spam {
        background-color: #dc3545;
        color: white;
    }

    .btn-spam:hover {
        background-color: #c82333;
    }

    .btn-delete {
        background-color: #6c757d;
        color: white;
    }

    .btn-delete:hover {
        background-color: #5a6268;
    }

    .content-preview {
        max-width: 180px;
        white-space: normal;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    /* Sticky action column on right */
    td:last-child,
    th:last-child {
        position: sticky;
        right: 0;
        z-index: 5;
        background: white;
    }

    thead th:last-child {
        background-color: #f8f9fa;
    }

    tbody tr:hover td:last-child {
        background-color: #f8f9fa;
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

    @media (max-width: 768px) {
        .stats-section {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-row {
            flex-direction: column;
        }

        .filter-group {
            width: 100%;
        }

        table {
            font-size: 12px;
        }

        td, th {
            padding: 10px;
        }
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
        <h1>💬 Quản Lý Bình Luận</h1>
        <a href="<?php echo $basePath; ?>/quan-tri/binhluan/quan-ly-tu-khoa/">🚫 Quản Lý Từ Khóa Xấu</a>
    </div>

    <!-- Thống Kê -->
    <div class="stats-section">
        <div class="stat-card">
            <h3>Tất Cả</h3>
            <div class="number"><?php echo $stats['total'] ?? 0; ?></div>
        </div>
        <div class="stat-card">
            <h3>Duyệt</h3>
            <div class="number" style="color: #28a745;"><?php echo $stats['approved'] ?? 0; ?></div>
        </div>
        <div class="stat-card">
            <h3>Chờ Duyệt</h3>
            <div class="number" style="color: #ffc107;"><?php echo $stats['pending'] ?? 0; ?></div>
        </div>
        <div class="stat-card">
            <h3>Ẩn</h3>
            <div class="number" style="color: #6c757d;"><?php echo $stats['hidden'] ?? 0; ?></div>
        </div>
        <div class="stat-card">
            <h3>Spam</h3>
            <div class="number" style="color: #dc3545;"><?php echo $stats['spam'] ?? 0; ?></div>
        </div>
    </div>

    <!-- Bộ Lọc -->
    <div class="filters">
        <form method="GET" action="<?php echo $basePath; ?>/quan-tri/binhluan/danh-sach/">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="search">Tìm kiếm (Người dùng, Bài viết, Nội dung):</label>
                    <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Nhập từ khóa...">
                </div>

                <div class="filter-group">
                    <label for="status">Trạng thái:</label>
                    <select id="status" name="status">
                        <option value="">-- Tất Cả --</option>
                        <option value="approved" <?php echo $status === 'approved' ? 'selected' : ''; ?>>Duyệt</option>
                        <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Chờ Duyệt</option>
                        <option value="hidden" <?php echo $status === 'hidden' ? 'selected' : ''; ?>>Ẩn</option>
                        <option value="spam" <?php echo $status === 'spam' ? 'selected' : ''; ?>>Spam</option>
                    </select>
                </div>

                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn-search">🔍 Tìm</button>
                    <a href="<?php echo $basePath; ?>/quan-tri/binhluan/danh-sach/" class="btn-reset">↻ Xóa</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Bảng Bình Luận -->
    <div class="table-container">
        <?php if (empty($comments)): ?>
            <div class="no-data">
                <p>Không có bình luận nào</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th style="width: 35px;">ID</th>
                        <th style="width: 100px;">Người Dùng</th>
                        <th style="width: 180px;">Nội Dung</th>
                        <th style="width: 100px;">Bài Viết</th>
                        <th style="width: 70px;">Trạng Thái</th>
                        <th style="width: 85px;">Ngày</th>
                        <th style="width: 280px;">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comments as $comment): ?>
                        <tr>
                            <td><?php echo $comment['id']; ?></td>
                            <td><?php echo htmlspecialchars(substr($comment['full_name'] ?? 'N/A', 0, 18)); ?></td>
                            <td>
                                <div class="content-preview">
                                    <?php echo htmlspecialchars(substr($comment['content'], 0, 60)); ?>
                                    <?php if (strlen($comment['content']) > 60): ?>...<?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <a href="<?php echo $basePath; ?>/<?php echo htmlspecialchars($comment['slug']); ?>/" style="color: #667eea; text-decoration: none; font-size: 12px;">
                                    <?php echo htmlspecialchars(substr($comment['article_title'], 0, 23)); ?>
                                </a>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo $comment['status']; ?>" style="font-size: 11px;">
                                    <?php 
                                        $statusText = [
                                            'approved' => 'Duyệt',
                                            'pending' => 'Chờ',
                                            'hidden' => 'Ẩn',
                                            'spam' => 'Spam'
                                        ];
                                        echo $statusText[$comment['status']] ?? $comment['status'];
                                    ?>
                                </span>
                            </td>
                            <td><span style="font-size: 11px;"><?php echo date('d/m H:i', strtotime($comment['created_at'])); ?></span></td>
                            <td>
                                <div class="actions">
                                    <?php if ($comment['status'] !== 'approved'): ?>
                                        <button class="btn-small btn-approve" onclick="updateStatus(<?php echo $comment['id']; ?>, 'approved')" title="Duyệt">✓ D</button>
                                    <?php endif; ?>

                                    <?php if ($comment['status'] !== 'hidden'): ?>
                                        <button class="btn-small btn-hide" onclick="updateStatus(<?php echo $comment['id']; ?>, 'hidden')" title="Ẩn">👁 Ẩn</button>
                                    <?php endif; ?>

                                    <?php if ($comment['status'] !== 'spam'): ?>
                                        <button class="btn-small btn-spam" onclick="updateStatus(<?php echo $comment['id']; ?>, 'spam')" title="Spam">⚠ S</button>
                                    <?php endif; ?>

                                    <button class="btn-small btn-delete" onclick="deleteComment(<?php echo $comment['id']; ?>)" title="Xóa">🗑 X</button>
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
                        <a href="<?php echo $basePath; ?>/quan-tri/binhluan/danh-sach/?page=1<?php if ($search) echo '&search=' . urlencode($search); if ($status) echo '&status=' . urlencode($status); ?>">« Đầu</a>
                        <a href="<?php echo $basePath; ?>/quan-tri/binhluan/danh-sach/?page=<?php echo $page - 1; ?><?php if ($search) echo '&search=' . urlencode($search); if ($status) echo '&status=' . urlencode($status); ?>">‹ Trước</a>
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
                                <a href="<?php echo $basePath; ?>/quan-tri/binhluan/danh-sach/?page=<?php echo $i; ?><?php if ($search) echo '&search=' . urlencode($search); if ($status) echo '&status=' . urlencode($status); ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($endPage < $totalPages): ?>
                            <span>...</span>
                        <?php endif; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="<?php echo $basePath; ?>/quan-tri/binhluan/danh-sach/?page=<?php echo $page + 1; ?><?php if ($search) echo '&search=' . urlencode($search); if ($status) echo '&status=' . urlencode($status); ?>">Sau ›</a>
                        <a href="<?php echo $basePath; ?>/quan-tri/binhluan/danh-sach/?page=<?php echo $totalPages; ?><?php if ($search) echo '&search=' . urlencode($search); if ($status) echo '&status=' . urlencode($status); ?>">Cuối »</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function updateStatus(commentId, status) {
    if (!confirm('Bạn chắc chắn muốn cập nhật trạng thái bình luận này?')) {
        return;
    }

    const formData = new FormData();
    formData.append('id', commentId);
    formData.append('status', status);

    fetch('<?php echo $basePath; ?>/quan-tri/binhluan/cap-nhat-trang-thai/', {
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
        alert('Lỗi khi cập nhật trạng thái!');
    });
}

function deleteComment(commentId) {
    if (!confirm('Bạn chắc chắn muốn xóa bình luận này? Không thể khôi phục!')) {
        return;
    }

    const formData = new FormData();
    formData.append('id', commentId);

    fetch('<?php echo $basePath; ?>/quan-tri/binhluan/xoa/', {
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
        alert('Lỗi khi xóa bình luận!');
    });
}
</script>


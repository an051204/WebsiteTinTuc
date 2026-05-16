<?php
$categories = $categories ?? [];
$page = $page ?? 1;
$totalPages = $totalPages ?? 1;
$search = $search ?? '';
$basePath = '/WebsiteTinTuc/public';

header('Content-Type: text/html; charset=utf-8');
$pageTitle = 'Quản Lý Chuyên Mục - Admin';

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

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .header-section h1 {
        margin: 0;
        font-size: 28px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        font-weight: 600;
    }

    .btn-primary {
        background-color: #667eea;
        color: white;
    }

    .btn-primary:hover {
        background-color: #5568d3;
    }

    .search-box {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .search-box input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .search-box button {
        padding: 10px 20px;
        background-color: #667eea;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .search-box button:hover {
        background-color: #5568d3;
    }

    .table-container {
        background: white;
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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

    tr:hover {
        background-color: #f9f9f9;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 3px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-active {
        background-color: #d4edda;
        color: #155724;
    }

    .status-hidden {
        background-color: #f8d7da;
        color: #721c24;
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
        background-color: #ffc107;
        color: #333;
    }

    .edit-btn:hover {
        background-color: #e0a800;
    }

    .delete-btn {
        background-color: #dc3545;
        color: white;
    }

    .delete-btn:hover {
        background-color: #c82333;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 5px;
        margin-top: 20px;
    }

    .pagination a, .pagination span {
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
        padding: 40px 20px;
        color: #999;
    }

    .category-level {
        color: #999;
        font-size: 12px;
    }

    .sort-controls {
        display: flex;
        gap: 5px;
        align-items: center;
    }

    .sort-btn {
        padding: 3px 8px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 11px;
        font-weight: 600;
    }

    .sort-btn:hover {
        background-color: #218838;
    }

    .sort-btn:disabled {
        background-color: #cccccc;
        cursor: not-allowed;
    }

    .draggable-row {
        cursor: move;
        transition: background-color 0.2s;
    }

    .draggable-row.dragging {
        opacity: 0.6;
        background-color: #f0f0f0 !important;
    }

    .draggable-row.drag-over {
        background-color: #e8f4f8 !important;
        border-top: 3px solid #667eea;
    }

    .sort-notice {
        background-color: #e7f3ff;
        color: #004085;
        border: 1px solid #b8daff;
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        font-size: 13px;
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
        <h1>📁 Quản Lý Chuyên Mục</h1>
        <a href="<?php echo $basePath; ?>/quan-tri/chuyenmuc/them/" class="btn btn-primary">✚ Thêm Chuyên Mục</a>
    </div>

    <div class="search-box">
        <form method="GET" style="display: flex; gap: 10px; width: 100%;">
            <input type="text" name="search" placeholder="Tìm kiếm theo tên chuyên mục..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Tìm kiếm</button>
        </form>
    </div>

    <div class="sort-notice" id="sortNotice" style="display: none;">
        ℹ️ Bạn đã thay đổi thứ tự. <button type="button" onclick="saveSortOrder()" style="background:#667eea; color:white; border:none; padding:5px 15px; border-radius:3px; cursor:pointer; font-weight:600;">Lưu Thay Đổi</button>
    </div>

    <?php if (empty($categories)): ?>
        <div class="table-container">
            <div class="empty-message">
                <p>📭 Không có chuyên mục nào</p>
            </div>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table id="categoriesTable">
                <thead>
                    <tr>
                        <th style="width: 5%;">ID</th>
                        <th style="width: 30%;">Tên Chuyên Mục</th>
                        <th style="width: 20%;">Parent</th>
                        <th style="width: 10%;">Trạng Thái</th>
                        <th style="width: 10%;">Thứ Tự</th>
                        <th style="width: 20%;">Hành Động</th>
                    </tr>
                </thead>
                <tbody id="categoriesTableBody">
                    <?php foreach ($categories as $index => $category): ?>
                        <tr class="draggable-row" draggable="true" data-category-id="<?php echo htmlspecialchars($category['id']); ?>">
                            <td>
                                <span style="cursor: grab; color: #999;">⋮⋮</span>
                                <?php echo htmlspecialchars($category['id']); ?>
                            </td>
                            <td>
                                <?php 
                                    if (!empty($category['parent_id'])) {
                                        echo '<span class="category-level">└─ </span>';
                                    }
                                    echo htmlspecialchars($category['name']); 
                                ?>
                            </td>
                            <td>
                                <?php 
                                    if (!empty($category['parent_id'])) {
                                        $parentName = 'N/A';
                                        foreach ($allCategories ?? [] as $cat) {
                                            if ($cat['id'] == $category['parent_id']) {
                                                $parentName = $cat['name'];
                                                break;
                                            }
                                        }
                                        echo htmlspecialchars($parentName);
                                    } else {
                                        echo '—';
                                    }
                                ?>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo htmlspecialchars($category['status']); ?>">
                                    <?php echo $category['status'] === 'active' ? 'Công Khai' : 'Ẩn'; ?>
                                </span>
                            </td>
                            <td>
                                <span id="sortOrder_<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['sort_order']); ?></span>
                            </td>
                            <td>
                                <div class="actions" style="gap: 8px;">
                                    <div class="sort-controls">
                                        <button type="button" class="sort-btn" onclick="moveUp(this)" title="Di chuyển lên">▲</button>
                                        <button type="button" class="sort-btn" onclick="moveDown(this)" title="Di chuyển xuống">▼</button>
                                    </div>
                                    <a href="<?php echo $basePath; ?>/quan-tri/chuyenmuc/sua/?id=<?php echo $category['id']; ?>" class="action-btn edit-btn">Sửa</a>
                                    <form method="POST" action="<?php echo $basePath; ?>/quan-tri/chuyenmuc/xoa/" style="display: inline;">
                                        <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                                        <button type="submit" class="action-btn delete-btn" onclick="return confirm('Xóa chuyên mục này? Lưu ý: không thể xóa nếu có bài viết hoặc chuyên mục con!');">Xóa</button>
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
                    <a href="?page=1&search=<?php echo urlencode($search); ?>">« Đầu</a>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">‹ Trước</a>
                <?php endif; ?>

                <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                    <?php if ($i == $page): ?>
                        <span class="active"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Sau ›</a>
                    <a href="?page=<?php echo $totalPages; ?>&search=<?php echo urlencode($search); ?>">Cuối »</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include dirname(__FILE__) . '/../layouts/footer-admin.php'; ?>

<script>
let draggedElement = null;
let originalOrder = [];

// Lưu thứ tự gốc khi trang load
document.addEventListener('DOMContentLoaded', function() {
    saveOriginalOrder();
    setupDragListeners();
});

function saveOriginalOrder() {
    originalOrder = Array.from(document.querySelectorAll('#categoriesTableBody tr')).map(row => row.dataset.categoryId);
}

function setupDragListeners() {
    const rows = document.querySelectorAll('.draggable-row');
    
    rows.forEach(row => {
        row.addEventListener('dragstart', handleDragStart);
        row.addEventListener('dragend', handleDragEnd);
        row.addEventListener('dragover', handleDragOver);
        row.addEventListener('drop', handleDrop);
        row.addEventListener('dragenter', handleDragEnter);
        row.addEventListener('dragleave', handleDragLeave);
    });
}

function handleDragStart(e) {
    draggedElement = this;
    this.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.innerHTML);
    showSortNotice();
}

function handleDragEnd(e) {
    this.classList.remove('dragging');
    document.querySelectorAll('.draggable-row').forEach(row => {
        row.classList.remove('drag-over');
    });
}

function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    return false;
}

function handleDragEnter(e) {
    if (this !== draggedElement) {
        this.classList.add('drag-over');
    }
}

function handleDragLeave(e) {
    this.classList.remove('drag-over');
}

function handleDrop(e) {
    e.preventDefault();
    e.stopPropagation();
    
    if (this !== draggedElement) {
        // Hoán đổi vị trí
        const tbody = document.getElementById('categoriesTableBody');
        const allRows = Array.from(tbody.querySelectorAll('tr'));
        const draggedIndex = allRows.indexOf(draggedElement);
        const targetIndex = allRows.indexOf(this);
        
        if (draggedIndex < targetIndex) {
            this.parentNode.insertBefore(draggedElement, this.nextSibling);
        } else {
            this.parentNode.insertBefore(draggedElement, this);
        }
        
        updateSortNumbers();
    }
    
    this.classList.remove('drag-over');
    return false;
}

function moveUp(button) {
    const row = button.closest('tr');
    const prevRow = row.previousElementSibling;
    
    if (prevRow && prevRow.classList.contains('draggable-row')) {
        row.parentNode.insertBefore(row, prevRow);
        updateSortNumbers();
        showSortNotice();
    }
}

function moveDown(button) {
    const row = button.closest('tr');
    const nextRow = row.nextElementSibling;
    
    if (nextRow && nextRow.classList.contains('draggable-row')) {
        row.parentNode.insertBefore(nextRow, row);
        updateSortNumbers();
        showSortNotice();
    }
}

function updateSortNumbers() {
    const rows = document.querySelectorAll('#categoriesTableBody tr');
    rows.forEach((row, index) => {
        const categoryId = row.dataset.categoryId;
        document.getElementById(`sortOrder_${categoryId}`).textContent = index;
    });
}

function showSortNotice() {
    document.getElementById('sortNotice').style.display = 'block';
}

function saveSortOrder() {
    const rows = document.querySelectorAll('#categoriesTableBody tr');
    const orders = Array.from(rows).map(row => row.dataset.categoryId);
    
    const formData = new FormData();
    orders.forEach((id, index) => {
        formData.append(`orders[${index}]`, id);
    });
    
    fetch('<?php echo $basePath; ?>/quan-tri/chuyenmuc/cap-nhat-thu-tu/', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('sortNotice').style.display = 'none';
            // Hiển thị thông báo thành công
            alert('✓ Thứ tự đã được cập nhật thành công!');
            // Reload trang để refresh
            setTimeout(() => location.reload(), 500);
        } else {
            alert('✗ Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        alert('✗ Lỗi kết nối: ' + error.message);
    });
}
</script>

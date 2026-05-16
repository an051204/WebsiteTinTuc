<?php
header('Content-Type: text/html; charset=utf-8');
$basePath = '/WebsiteTinTuc/public';
$pageTitle = 'Quản Lý Quảng Cáo - Admin';

$danhSach = $danhSach ?? [];
$page = $page ?? 1;
$totalPages = $totalPages ?? 0;
$search = $search ?? '';
$total = $total ?? 0;

include dirname(__DIR__) . '/layouts/header-admin.php';

$positionLabels = [
    'home_top' => '🏠 Trang chủ (trên)',
    'home_middle' => '🏠 Trang chủ (giữa)',
    'sidebar' => '📐 Sidebar',
    'article_bottom' => '📰 Cuối bài viết',
];
?>

<style>
    .admin-container {
        padding: 20px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-header h1 {
        margin: 0;
        font-size: 26px;
        color: #2c3e50;
    }

    .page-header .actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .search-bar {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
    }

    .search-bar input {
        flex: 1;
        max-width: 350px;
        padding: 10px 14px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }

    .search-bar input:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
    }

    .search-bar button {
        padding: 10px 20px;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.2s;
    }

    .search-bar button:hover {
        background: #5568d3;
    }

    .ad-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .ad-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .ad-table th {
        padding: 14px 16px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .ad-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #edf2f7;
        font-size: 14px;
        vertical-align: middle;
    }

    .ad-table tr:hover {
        background: #f8f9ff;
    }

    .ad-table tr:last-child td {
        border-bottom: none;
    }

    .ad-thumb {
        width: 80px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #eee;
    }

    .ad-thumb-placeholder {
        width: 80px;
        height: 50px;
        background: #f0f0f0;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .position-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        background: #e8f0fe;
        color: #1967d2;
    }

    .status-active {
        background: #c6f6d5;
        color: #22543d;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-inactive {
        background: #fed7d7;
        color: #742a2a;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .table-actions {
        display: flex;
        gap: 6px;
    }

    .table-actions a,
    .table-actions button {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
    }

    .btn-edit {
        background: #edf2f7;
        color: #4a5568;
    }

    .btn-edit:hover {
        background: #e2e8f0;
    }

    .btn-toggle {
        background: #fefcbf;
        color: #744210;
    }

    .btn-toggle:hover {
        background: #fef08a;
    }

    .btn-delete {
        background: #fed7d7;
        color: #742a2a;
    }

    .btn-delete:hover {
        background: #feb2b2;
    }

    .add-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
    }

    .add-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(102,126,234,0.3);
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 6px;
        margin-top: 25px;
    }

    .pagination a,
    .pagination span {
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .pagination a {
        background: white;
        color: #667eea;
        border: 1px solid #ddd;
    }

    .pagination a:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .pagination .current {
        background: #667eea;
        color: white;
        border: 1px solid #667eea;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #718096;
    }

    .empty-state .icon {
        font-size: 48px;
        margin-bottom: 15px;
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

    .stat-bar {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .stat-item {
        background: white;
        padding: 12px 18px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        font-size: 13px;
        color: #4a5568;
    }

    .stat-item strong {
        color: #667eea;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .ad-table {
            display: block;
            overflow-x: auto;
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
        <h1>📢 Quản Lý Quảng Cáo</h1>
        <div class="actions">
            <a href="<?php echo $basePath; ?>/quan-tri/caidat/quang-cao/them/" class="add-btn">➕ Thêm Quảng Cáo</a>
        </div>
    </div>

    <div class="stat-bar">
        <div class="stat-item">Tổng: <strong><?php echo $total; ?></strong> quảng cáo</div>
    </div>

    <form class="search-bar" method="GET" action="<?php echo $basePath; ?>/quan-tri/caidat/quang-cao/">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Tìm theo tiêu đề...">
        <button type="submit">🔍 Tìm</button>
    </form>

    <?php if (!empty($danhSach)): ?>
    <table class="ad-table">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tiêu đề</th>
                <th>Vị trí</th>
                <th>Trạng thái</th>
                <th>Thời gian</th>
                <th>Clicks</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($danhSach as $ad): ?>
            <tr id="ad-row-<?php echo $ad['id']; ?>">
                <td>
                    <?php if (!empty($ad['image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($ad['image_url']); ?>" class="ad-thumb" alt="Banner">
                    <?php else: ?>
                        <div class="ad-thumb-placeholder">🖼️</div>
                    <?php endif; ?>
                </td>
                <td>
                    <strong><?php echo htmlspecialchars($ad['title'] ?? ''); ?></strong>
                    <br><small style="color:#718096;"><?php echo htmlspecialchars(substr($ad['link_url'] ?? '', 0, 40)); ?></small>
                </td>
                <td>
                    <span class="position-badge">
                        <?php echo $positionLabels[$ad['position']] ?? $ad['position']; ?>
                    </span>
                </td>
                <td>
                    <span class="status-<?php echo $ad['status']; ?>">
                        <?php echo $ad['status'] === 'active' ? '✅ Hoạt động' : '⛔ Tắt'; ?>
                    </span>
                </td>
                <td style="font-size: 12px; color: #718096;">
                    <?php if (!empty($ad['start_date'])): ?>
                        <?php echo date('d/m/Y', strtotime($ad['start_date'])); ?>
                    <?php else: ?>
                        --
                    <?php endif; ?>
                    →
                    <?php if (!empty($ad['end_date'])): ?>
                        <?php echo date('d/m/Y', strtotime($ad['end_date'])); ?>
                    <?php else: ?>
                        --
                    <?php endif; ?>
                </td>
                <td style="text-align: center; font-weight: 600;"><?php echo $ad['clicks_count'] ?? 0; ?></td>
                <td>
                    <div class="table-actions">
                        <a href="<?php echo $basePath; ?>/quan-tri/caidat/quang-cao/sua/?id=<?php echo $ad['id']; ?>" class="btn-edit">✏️ Sửa</a>
                        <button class="btn-toggle" onclick="toggleAdStatus(<?php echo $ad['id']; ?>, '<?php echo $ad['status'] === 'active' ? 'inactive' : 'active'; ?>')">
                            <?php echo $ad['status'] === 'active' ? '⛔ Tắt' : '✅ Bật'; ?>
                        </button>
                        <button class="btn-delete" onclick="deleteAd(<?php echo $ad['id']; ?>)">🗑️ Xóa</button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php if ($i == $page): ?>
                <span class="current"><?php echo $i; ?></span>
            <?php else: ?>
                <a href="<?php echo $basePath; ?>/quan-tri/caidat/quang-cao/?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <div class="empty-state">
        <div class="icon">📢</div>
        <p>Chưa có quảng cáo nào</p>
        <a href="<?php echo $basePath; ?>/quan-tri/caidat/quang-cao/them/" class="add-btn" style="margin-top: 15px;">➕ Thêm quảng cáo đầu tiên</a>
    </div>
    <?php endif; ?>
</div>

<script>
function toggleAdStatus(id, newStatus) {
    if (!confirm('Bạn có chắc muốn ' + (newStatus === 'active' ? 'bật' : 'tắt') + ' quảng cáo này?')) return;

    fetch('<?php echo $basePath; ?>/quan-tri/caidat/quang-cao/cap-nhat-trang-thai/', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + id + '&status=' + newStatus
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Lỗi: ' + (data.message || 'Không xác định'));
        }
    })
    .catch(() => alert('Lỗi kết nối'));
}

function deleteAd(id) {
    if (!confirm('Bạn có chắc muốn xóa quảng cáo này?')) return;

    fetch('<?php echo $basePath; ?>/quan-tri/caidat/quang-cao/xoa/', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + id
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Lỗi: ' + (data.message || 'Không xác định'));
        }
    })
    .catch(() => alert('Lỗi kết nối'));
}
</script>

<?php include dirname(__DIR__) . '/layouts/footer-admin.php'; ?>

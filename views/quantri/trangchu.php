<?php
header('Content-Type: text/html; charset=utf-8');
$basePath = '/WebsiteTinTuc/public';
$pageTitle = 'Tổng Quan - Admin';

include dirname(__FILE__) . '/layouts/header-admin.php';

$totalArticles = $totalArticles ?? 0;
$publishedArticles = $publishedArticles ?? 0;
$pendingArticles = $pendingArticles ?? 0;
$draftArticles = $draftArticles ?? 0;
$totalUsers = $totalUsers ?? 0;
$totalComments = $totalComments ?? 0;
$totalCategories = $totalCategories ?? 0;
$totalTags = $totalTags ?? 0;
$recentArticles = $recentArticles ?? [];
$featuredArticles = $featuredArticles ?? [];
$topViewedArticles = $topViewedArticles ?? [];
?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f7fa;
        color: #333;
    }

    .admin-container {
        padding: 20px;
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-header h1 {
        margin: 0;
        font-size: 28px;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .page-header p {
        margin: 0;
        color: #7f8c8d;
        font-size: 14px;
    }

    /* Statistics Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid #667eea;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .stat-card.articles {
        border-left-color: #667eea;
    }

    .stat-card.published {
        border-left-color: #48bb78;
    }

    .stat-card.pending {
        border-left-color: #f6ad55;
    }

    .stat-card.draft {
        border-left-color: #cbd5e0;
    }

    .stat-card.users {
        border-left-color: #9f7aea;
    }

    .stat-card.comments {
        border-left-color: #38b2ac;
    }

    .stat-card.categories {
        border-left-color: #ed8936;
    }

    .stat-card.tags {
        border-left-color: #ee5a6f;
    }

    .stat-label {
        font-size: 12px;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: bold;
        color: #2d3748;
        margin-bottom: 10px;
    }

    .stat-action {
        font-size: 12px;
    }

    .stat-action a {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
    }

    .stat-action a:hover {
        text-decoration: underline;
    }

    /* Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }

    .panel {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .panel-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .panel-header h2 {
        margin: 0;
        font-size: 18px;
    }

    .panel-header a {
        color: white;
        text-decoration: none;
        font-size: 12px;
        background: rgba(255,255,255,0.2);
        padding: 5px 10px;
        border-radius: 3px;
        transition: background 0.2s;
    }

    .panel-header a:hover {
        background: rgba(255,255,255,0.3);
    }

    .panel-body {
        padding: 20px;
    }

    .article-item {
        padding: 15px;
        border-bottom: 1px solid #edf2f7;
        display: flex;
        justify-content: space-between;
        align-items: start;
        gap: 10px;
    }

    .article-item:last-child {
        border-bottom: none;
    }

    .article-info {
        flex: 1;
    }

    .article-title {
        font-weight: 600;
        color: #2d3748;
        font-size: 14px;
        margin-bottom: 5px;
        line-height: 1.4;
    }

    .article-meta {
        font-size: 12px;
        color: #718096;
        display: flex;
        gap: 10px;
    }

    .article-status {
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-published {
        background: #c6f6d5;
        color: #22543d;
    }

    .status-pending {
        background: #fed7aa;
        color: #7c2d12;
    }

    .status-draft {
        background: #e2e8f0;
        color: #2d3748;
    }

    .status-rejected {
        background: #fed7d7;
        color: #742a2a;
    }

    .article-views {
        font-weight: 600;
        color: #667eea;
        min-width: 50px;
        text-align: right;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #718096;
    }

    .empty-state svg {
        width: 60px;
        height: 60px;
        opacity: 0.3;
        margin-bottom: 15px;
    }

    .empty-text {
        margin-bottom: 15px;
    }

    .create-btn {
        display: inline-block;
        background: #667eea;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: background 0.2s;
    }

    .create-btn:hover {
        background: #5568d3;
    }

    .quick-actions {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .quick-actions h3 {
        margin-top: 0;
        margin-bottom: 15px;
        color: #2d3748;
        font-size: 16px;
    }

    .action-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 10px;
    }

    .action-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        font-weight: 600;
        font-size: 14px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
    }

    .action-btn.secondary {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    }

    .action-btn.secondary:hover {
        box-shadow: 0 4px 8px rgba(72, 187, 120, 0.3);
    }

    .action-btn.danger {
        background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
    }

    .action-btn.danger:hover {
        box-shadow: 0 4px 8px rgba(245, 101, 101, 0.3);
    }

    @media (max-width: 768px) {
        .content-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }

        .page-header h1 {
            font-size: 24px;
        }
    }

    .message {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
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
        <h1>📊 Tổng Quan Admin</h1>
        <p>Chào mừng đến trang quản trị. Dưới đây là những thống kê và công cụ quản lý của bạn.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card articles">
            <div class="stat-label">📰 Tổng Bài Viết</div>
            <div class="stat-value"><?php echo $totalArticles; ?></div>
            <div class="stat-action">
                <a href="<?php echo $basePath; ?>/quan-tri/bai-viet/">Quản lý bài viết →</a>
            </div>
        </div>

        <div class="stat-card published">
            <div class="stat-label">✅ Đã Đăng</div>
            <div class="stat-value"><?php echo $publishedArticles; ?></div>
            <div class="stat-action">
                <a href="<?php echo $basePath; ?>/quan-tri/bai-viet/?status=published">Xem chi tiết →</a>
            </div>
        </div>

        <div class="stat-card pending">
            <div class="stat-label">⏳ Chờ Duyệt</div>
            <div class="stat-value"><?php echo $pendingArticles; ?></div>
            <div class="stat-action">
                <a href="<?php echo $basePath; ?>/quan-tri/bai-viet/?status=pending">Duyệt ngay →</a>
            </div>
        </div>

        <div class="stat-card draft">
            <div class="stat-label">📝 Nháp</div>
            <div class="stat-value"><?php echo $draftArticles; ?></div>
            <div class="stat-action">
                <a href="<?php echo $basePath; ?>/quan-tri/bai-viet/?status=draft">Xem nháp →</a>
            </div>
        </div>

        <div class="stat-card users">
            <div class="stat-label">👥 Người Dùng</div>
            <div class="stat-value"><?php echo $totalUsers; ?></div>
            <div class="stat-action">
                <a href="<?php echo $basePath; ?>/quan-tri/tai-khoan/">Quản lý tài khoản →</a>
            </div>
        </div>

        <div class="stat-card comments">
            <div class="stat-label">💬 Bình Luận</div>
            <div class="stat-value"><?php echo $totalComments; ?></div>
            <div class="stat-action">
                <a href="<?php echo $basePath; ?>/quan-tri/">Xem bình luận →</a>
            </div>
        </div>

        <div class="stat-card categories">
            <div class="stat-label">📂 Chuyên Mục</div>
            <div class="stat-value"><?php echo $totalCategories; ?></div>
            <div class="stat-action">
                <a href="<?php echo $basePath; ?>/quan-tri/">Quản lý chuyên mục →</a>
            </div>
        </div>

        <div class="stat-card tags">
            <div class="stat-label">🏷️ Thẻ Tags</div>
            <div class="stat-value"><?php echo $totalTags; ?></div>
            <div class="stat-action">
                <a href="<?php echo $basePath; ?>/quan-tri/">Quản lý tags →</a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3>⚡ Thao Tác Nhanh</h3>
        <div class="action-buttons">
            <a href="<?php echo $basePath; ?>/quan-tri/bai-viet/them/" class="action-btn">
                ✚ Tạo Bài Viết
            </a>
            <a href="<?php echo $basePath; ?>/quan-tri/bai-viet/?status=pending" class="action-btn secondary">
                ⏳ Duyệt Bài (<?php echo $pendingArticles; ?>)
            </a>
            <a href="<?php echo $basePath; ?>/quan-tri/tai-khoan/" class="action-btn secondary">
                👥 Quản Lý Người Dùng
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Recent Articles -->
        <div class="panel">
            <div class="panel-header">
                <h2>📰 Bài Viết Gần Đây</h2>
                <a href="<?php echo $basePath; ?>/quan-tri/bai-viet/">Xem tất cả</a>
            </div>
            <div class="panel-body">
                <?php if (!empty($recentArticles)): ?>
                    <?php foreach ($recentArticles as $article): ?>
                        <div class="article-item">
                            <div style="width: 54px; height: 54px; flex-shrink: 0; border-radius: 8px; overflow: hidden; background: #edf2f7; border: 1px solid #e2e8f0; margin-right: 12px; display: flex; align-items: center; justify-content: center;">
                                <?php if (!empty($article['thumbnail'])): ?>
                                    <img src="<?php echo htmlspecialchars($article['thumbnail']); ?>" alt="Thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <span style="font-size: 11px; color: #a0aec0;">No image</span>
                                <?php endif; ?>
                            </div>
                            <div class="article-info">
                                <div class="article-title">
                                    <a href="<?php echo $basePath; ?>/quan-tri/bai-viet/sua/<?php echo $article['id']; ?>/" 
                                       style="color: #2d3748; text-decoration: none;">
                                        <?php echo htmlspecialchars(substr($article['title'], 0, 50)); ?>
                                        <?php if (strlen($article['title']) > 50): ?>...<?php endif; ?>
                                    </a>
                                </div>
                                <div class="article-meta">
                                    <span class="article-status status-<?php echo $article['status']; ?>">
                                        <?php 
                                        $statusLabels = [
                                            'published' => '✅ Đã đăng',
                                            'pending' => '⏳ Chờ duyệt',
                                            'draft' => '📝 Nháp',
                                            'rejected' => '❌ Từ chối'
                                        ];
                                        echo $statusLabels[$article['status']] ?? $article['status'];
                                        ?>
                                    </span>
                                    <span><?php echo $article['author_name'] ?? 'Unknown'; ?></span>
                                </div>
                            </div>
                            <div class="article-views">
                                👁️ <?php echo $article['views_count']; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-text">Chưa có bài viết nào</div>
                        <a href="<?php echo $basePath; ?>/quan-tri/bai-viet/them/" class="create-btn">Tạo bài viết đầu tiên</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div>
            <!-- Featured Articles -->
            <div class="panel" style="margin-bottom: 20px;">
                <div class="panel-header">
                    <h2>⭐ Bài Nổi Bật</h2>
                </div>
                <div class="panel-body">
                    <?php if (!empty($featuredArticles)): ?>
                        <?php foreach ($featuredArticles as $article): ?>
                            <div class="article-item" style="padding: 10px 0; border-bottom: 1px solid #edf2f7;">
                                <div style="width: 44px; height: 44px; flex-shrink: 0; border-radius: 6px; overflow: hidden; background: #edf2f7; border: 1px solid #e2e8f0; margin-right: 10px; display: flex; align-items: center; justify-content: center;">
                                    <?php if (!empty($article['thumbnail'])): ?>
                                        <img src="<?php echo htmlspecialchars($article['thumbnail']); ?>" alt="Thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else: ?>
                                        <span style="font-size: 10px; color: #a0aec0;">No image</span>
                                    <?php endif; ?>
                                </div>
                                <div style="flex: 1;">
                                    <div class="article-title" style="margin-bottom: 3px;">
                                        <a href="<?php echo $basePath; ?>/quan-tri/bai-viet/sua/<?php echo $article['id']; ?>/" 
                                           style="color: #2d3748; text-decoration: none; font-size: 13px;">
                                            <?php echo htmlspecialchars(substr($article['title'], 0, 40)); ?>
                                        </a>
                                    </div>
                                    <div style="font-size: 12px; color: #718096;">
                                        👁️ <?php echo $article['views_count']; ?> lượt xem
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="text-align: center; padding: 20px; color: #718096; font-size: 13px;">
                            Chưa có bài nổi bật
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Top Viewed Articles -->
            <div class="panel">
                <div class="panel-header">
                    <h2>🔥 Xu Hướng Tuần</h2>
                </div>
                <div class="panel-body">
                    <?php if (!empty($topViewedArticles)): ?>
                        <?php foreach ($topViewedArticles as $article): ?>
                            <div class="article-item" style="padding: 10px 0; border-bottom: 1px solid #edf2f7;">
                                <div style="flex: 1;">
                                    <div class="article-title" style="margin-bottom: 3px;">
                                        <a href="<?php echo $basePath; ?>/quan-tri/bai-viet/sua/<?php echo $article['id']; ?>/" 
                                           style="color: #2d3748; text-decoration: none; font-size: 13px;">
                                            <?php echo htmlspecialchars(substr($article['title'], 0, 40)); ?>
                                        </a>
                                    </div>
                                    <div style="font-size: 12px; color: #718096;">
                                        👁️ <?php echo $article['views_count']; ?> lượt xem
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="text-align: center; padding: 20px; color: #718096; font-size: 13px;">
                            Không có dữ liệu
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include dirname(__FILE__) . '/layouts/footer-admin.php'; ?>

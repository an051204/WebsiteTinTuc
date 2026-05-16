<?php
header('Content-Type: text/html; charset=utf-8');

$chuyenMuc = $chuyenMuc ?? [];
$articles = $articles ?? [];
$filter = $filter ?? 'newest';
$page = $page ?? 1;
$totalPages = $totalPages ?? 0;
$totalArticles = $totalArticles ?? 0;

$basePath = '/WebsiteTinTuc/public';
$pageTitle = htmlspecialchars($chuyenMuc['name'] ?? '') . ' - Website Tin Tức';
include dirname(__FILE__) . '/../layouts/header-start.php';
?>

<div class="page-hero">
    <div class="container">
        <h1><?php echo htmlspecialchars($chuyenMuc['name'] ?? 'Không xác định'); ?></h1>
        <p>Tổng cộng: <?php echo htmlspecialchars($totalArticles); ?> bài viết</p>
    </div>
</div>

<div class="container">
    <div class="page-title">
        <h1><?php echo htmlspecialchars($chuyenMuc['name'] ?? 'Không xác định'); ?></h1>
        <p>Tổng cộng: <?php echo htmlspecialchars($totalArticles); ?> bài viết</p>
    </div>

    <div class="filters">
        <h3>📌 Sắp xếp theo:</h3>
        <select class="filter-select" id="filterSelect" onchange="changeFilter(this.value)">
            <option value="newest" <?php echo $filter === 'newest' ? 'selected' : ''; ?>>⏰ Mới nhất</option>
            <option value="most_viewed" <?php echo $filter === 'most_viewed' ? 'selected' : ''; ?>>👀 Xem nhiều nhất</option>
            <option value="most_commented" <?php echo $filter === 'most_commented' ? 'selected' : ''; ?>>💬 Bình luận nhiều nhất</option>
        </select>
    </div>

    <script>
        function changeFilter(filterValue) {
            const slug = '<?php echo htmlspecialchars($chuyenMuc['slug'] ?? ''); ?>';
            window.location.href = '<?php echo $basePath; ?>/danh-sach/' + slug + '?filter=' + filterValue + '&page=1';
        }
    </script>

    <div class="articles-list">
        <?php if (!empty($articles)): ?>
            <?php foreach ($articles as $article): ?>
                <div class="article-item">
                    <div class="article-thumbnail">
                        <?php if (!empty($article['thumbnail'])): ?>
                            <img src="<?php echo htmlspecialchars($article['thumbnail']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                        <?php else: ?>
                            <?php echo htmlspecialchars(substr($article['title'], 0, 15)); ?>...
                        <?php endif; ?>
                    </div>
                    <div class="article-content">
                        <h3>
                            <a href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($article['slug']); ?>">
                                <?php echo htmlspecialchars($article['title']); ?>
                            </a>
                        </h3>
                        <p class="article-excerpt">
                            <?php echo htmlspecialchars(substr(strip_tags($article['content']), 0, 150)); ?>...
                        </p>
                        <div class="article-meta">
                            <span class="meta-item">📅 <?php echo date('d/m/Y H:i', strtotime($article['created_at'])); ?></span>
                            <span class="meta-item">👁 <?php echo htmlspecialchars($article['views_count']); ?> lượt xem</span>
                            <span class="meta-item">💬 <?php echo htmlspecialchars($article['comments_count']); ?> bình luận</span>
                        </div>
                        <?php if (!empty($article['tags'])): ?>
                            <div class="article-tags">
                                <?php foreach ($article['tags'] as $tag): ?>
                                    <a href="<?php echo $basePath; ?>/tag/<?php echo htmlspecialchars($tag['slug']); ?>" class="article-tag">
                                        <?php echo htmlspecialchars($tag['name']); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-articles">
                <p>Chuyên mục này chưa có bài viết nào.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="<?php echo $basePath; ?>/danh-sach/<?php echo htmlspecialchars($chuyenMuc['slug'] ?? ''); ?>?filter=<?php echo htmlspecialchars($filter); ?>&amp;page=1">« Đầu tiên</a>
                <a href="<?php echo $basePath; ?>/danh-sach/<?php echo htmlspecialchars($chuyenMuc['slug'] ?? ''); ?>?filter=<?php echo htmlspecialchars($filter); ?>&amp;page=<?php echo $page - 1; ?>">‹ Trước</a>
            <?php else: ?>
                <span class="disabled">« Đầu tiên</span>
                <span class="disabled">‹ Trước</span>
            <?php endif; ?>

            <?php
            $startPage = max(1, $page - 2);
            $endPage = min($totalPages, $page + 2);

            if ($startPage > 1) {
                echo '<span class="disabled">...</span>';
            }

            for ($i = $startPage; $i <= $endPage; $i++) {
                if ($i == $page) {
                    echo '<span class="active">' . htmlspecialchars($i) . '</span>';
                } else {
                    echo '<a href="' . $basePath . '/danh-sach/' . htmlspecialchars($chuyenMuc['slug'] ?? '') . '?filter=' . htmlspecialchars($filter) . '&amp;page=' . htmlspecialchars($i) . '">' . htmlspecialchars($i) . '</a>';
                }
            }

            if ($endPage < $totalPages) {
                echo '<span class="disabled">...</span>';
            }
            ?>

            <?php if ($page < $totalPages): ?>
                <a href="<?php echo $basePath; ?>/danh-sach/<?php echo htmlspecialchars($chuyenMuc['slug'] ?? ''); ?>?filter=<?php echo htmlspecialchars($filter); ?>&amp;page=<?php echo $page + 1; ?>">Sau ›</a>
                <a href="<?php echo $basePath; ?>/danh-sach/<?php echo htmlspecialchars($chuyenMuc['slug'] ?? ''); ?>?filter=<?php echo htmlspecialchars($filter); ?>&amp;page=<?php echo $totalPages; ?>">Cuối cùng »</a>
            <?php else: ?>
                <span class="disabled">Sau ›</span>
                <span class="disabled">Cuối cùng »</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php include dirname(__FILE__) . '/../layouts/footer.php'; ?>

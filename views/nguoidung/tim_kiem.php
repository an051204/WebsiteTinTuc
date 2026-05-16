<?php
header('Content-Type: text/html; charset=utf-8');

$keyword = $keyword ?? '';
$articles = $articles ?? [];
$page = $page ?? 1;
$totalPages = $totalPages ?? 0;
$totalArticles = $totalArticles ?? 0;

$basePath = '/WebsiteTinTuc/public';
$pageTitle = 'Tìm kiếm: "' . htmlspecialchars($keyword) . '" - Website Tin Tức';
include dirname(__FILE__) . '/../layouts/header-start.php';
?>

<div class="page-hero">
    <div class="container">
        <h1>🔍 Kết quả tìm kiếm</h1>
        <p><?php echo !empty($keyword) ? 'Từ khóa: ' . htmlspecialchars($keyword) : 'Nhập từ khóa để tìm nội dung bạn quan tâm'; ?></p>
    </div>
</div>

<div class="container">
    <a href="<?php echo $basePath; ?>/" class="back-link">← Quay lại Trang chủ</a>

    <?php if (!empty($keyword)): ?>
        <div class="search-results-header">
            <h2>Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($keyword); ?>"</h2>
            <p class="search-results-info">Tìm thấy <?php echo htmlspecialchars($totalArticles); ?> kết quả</p>
        </div>

        <?php if (!empty($articles)): ?>
            <div class="articles-list">
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
                                <span class="meta-item">✍️ <?php echo htmlspecialchars($article['author_name'] ?? 'Tác giả ẩn danh'); ?></span>
                                <span class="meta-item">📁 <a href="<?php echo $basePath; ?>/danh-sach/<?php echo htmlspecialchars($article['category_slug']); ?>"><?php echo htmlspecialchars($article['category_name']); ?></a></span>
                                <span class="meta-item">👁 <?php echo htmlspecialchars($article['views_count']); ?> lượt xem</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="<?php echo $basePath; ?>/tim-kiem?q=<?php echo urlencode($keyword); ?>&page=1">« Đầu tiên</a>
                        <a href="<?php echo $basePath; ?>/tim-kiem?q=<?php echo urlencode($keyword); ?>&page=<?php echo $page - 1; ?>">‹ Trước</a>
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
                            echo '<a href="' . $basePath . '/tim-kiem?q=' . urlencode($keyword) . '&page=' . htmlspecialchars($i) . '">' . htmlspecialchars($i) . '</a>';
                        }
                    }

                    if ($endPage < $totalPages) {
                        echo '<span class="disabled">...</span>';
                    }
                    ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="<?php echo $basePath; ?>/tim-kiem?q=<?php echo urlencode($keyword); ?>&page=<?php echo $page + 1; ?>">Sau ›</a>
                        <a href="<?php echo $basePath; ?>/tim-kiem?q=<?php echo urlencode($keyword); ?>&page=<?php echo $totalPages; ?>">Cuối cùng »</a>
                    <?php else: ?>
                        <span class="disabled">Sau ›</span>
                        <span class="disabled">Cuối cùng »</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="no-results">
                <h3>😞 Không tìm thấy kết quả</h3>
                <p>Thử tìm kiếm với từ khóa khác hoặc xem các bài viết trong danh mục.</p>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="no-results">
            <h3>🔍 Nhập từ khóa để tìm kiếm</h3>
            <p>Bạn có thể tìm kiếm theo tiêu đề, nội dung hoặc tác giả bài viết.</p>
        </div>
    <?php endif; ?>
</div>

<?php include dirname(__FILE__) . '/../layouts/footer.php'; ?>

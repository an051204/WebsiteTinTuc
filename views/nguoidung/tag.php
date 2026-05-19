<?php
header('Content-Type: text/html; charset=utf-8');

$tag = $tag ?? [];
$articles = $articles ?? [];
$page = $page ?? 1;
$totalPages = $totalPages ?? 0;
$totalArticles = $totalArticles ?? 0;

$basePath = '/WebsiteTinTuc/public';
$pageTitle = htmlspecialchars($tag['name'] ?? '') . ' - Website Tin Tức';
include dirname(__FILE__) . '/../layouts/header-start.php';
?>

<div class="page-hero page-hero--catalog">
    <div class="container">
        <span class="hero-eyebrow">Thẻ tag</span>
        <h1>#<?php echo htmlspecialchars($tag['name'] ?? 'Không xác định'); ?></h1>
        <p>Tất cả bài viết được gắn thẻ <strong>“<?php echo htmlspecialchars($tag['name'] ?? ''); ?>”</strong> tổng hợp tại đây.</p>
        <div class="hero-stats">
            <div class="hero-stat">
                <strong><?php echo htmlspecialchars($totalArticles); ?></strong>
                <span>Bài viết</span>
            </div>
            <?php if ($totalPages > 1): ?>
            <div class="hero-stat">
                <strong>Trang <?php echo (int)$page; ?>/<?php echo (int)$totalPages; ?></strong>
                <span>Phân trang</span>
            </div>
            <?php endif; ?>
            <div class="hero-stat">
                <strong>
                    <a href="<?php echo $basePath; ?>/tag/" style="color:#fff;text-decoration:none;">Khám phá thêm →</a>
                </strong>
                <span>Tất cả thẻ tag</span>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="articles-list">
        <?php if (!empty($articles)): ?>
            <?php foreach ($articles as $article): ?>
                <div class="article-item">
                    <div class="article-thumbnail">
                        <span class="card-badge card-badge--accent">#<?php echo htmlspecialchars($tag['name'] ?? ''); ?></span>
                        <?php if (!empty($article['thumbnail'])): ?>
                            <img src="<?php echo htmlspecialchars($article['thumbnail']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                        <?php else: ?>
                            <span style="color:#94a3b8;font-weight:600;padding:18px;text-align:center;"><?php echo htmlspecialchars(substr($article['title'], 0, 20)); ?>...</span>
                        <?php endif; ?>
                    </div>
                    <div class="article-content">
                        <h3>
                            <a href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($article['slug']); ?>">
                                <?php echo htmlspecialchars($article['title']); ?>
                            </a>
                        </h3>
                        <p class="article-excerpt">
                            <?php echo htmlspecialchars(substr(strip_tags($article['content']), 0, 170)); ?>...
                        </p>
                        <div class="article-meta">
                            <span class="meta-item"><?php echo date('d/m/Y H:i', strtotime($article['created_at'])); ?></span>
                            <span class="meta-item"><?php echo htmlspecialchars($article['views_count']); ?> lượt xem</span>
                            <span class="meta-item"><?php echo htmlspecialchars($article['comments_count']); ?> bình luận</span>
                        </div>
                        <?php if (!empty($article['tags'])): ?>
                            <div class="article-tags">
                                <?php foreach ($article['tags'] as $t): ?>
                                    <a href="<?php echo $basePath; ?>/tag/<?php echo htmlspecialchars($t['slug']); ?>" class="article-tag">
                                        <?php echo htmlspecialchars($t['name']); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-articles no-content">
                <p>Thẻ tag này chưa có bài viết nào.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="<?php echo $basePath; ?>/tag/<?php echo htmlspecialchars($tag['slug'] ?? ''); ?>?page=1">«</a>
                <a href="<?php echo $basePath; ?>/tag/<?php echo htmlspecialchars($tag['slug'] ?? ''); ?>?page=<?php echo $page - 1; ?>">‹</a>
            <?php else: ?>
                <span class="disabled">«</span>
                <span class="disabled">‹</span>
            <?php endif; ?>

            <?php
            $startPage = max(1, $page - 2);
            $endPage = min($totalPages, $page + 2);

            if ($startPage > 1) {
                echo '<span class="disabled">…</span>';
            }

            for ($i = $startPage; $i <= $endPage; $i++) {
                if ($i == $page) {
                    echo '<span class="active">' . htmlspecialchars($i) . '</span>';
                } else {
                    echo '<a href="' . $basePath . '/tag/' . htmlspecialchars($tag['slug'] ?? '') . '?page=' . htmlspecialchars($i) . '">' . htmlspecialchars($i) . '</a>';
                }
            }

            if ($endPage < $totalPages) {
                echo '<span class="disabled">…</span>';
            }
            ?>

            <?php if ($page < $totalPages): ?>
                <a href="<?php echo $basePath; ?>/tag/<?php echo htmlspecialchars($tag['slug'] ?? ''); ?>?page=<?php echo $page + 1; ?>">›</a>
                <a href="<?php echo $basePath; ?>/tag/<?php echo htmlspecialchars($tag['slug'] ?? ''); ?>?page=<?php echo $totalPages; ?>">»</a>
            <?php else: ?>
                <span class="disabled">›</span>
                <span class="disabled">»</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php include dirname(__FILE__) . '/../layouts/footer.php'; ?>

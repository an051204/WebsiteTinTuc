<?php
header('Content-Type: text/html; charset=utf-8');

$tags = $tags ?? [];
$basePath = '/WebsiteTinTuc/public';
$pageTitle = 'Danh Sách Thẻ Tag';
include dirname(__FILE__) . '/../layouts/header-start.php';

$totalArticles = 0;
foreach ($tags as $t) {
    $totalArticles += (int)($t['article_count'] ?? 0);
}
?>

<div class="page-hero page-hero--catalog">
    <div class="container">
        <span class="hero-eyebrow">Thẻ tag</span>
        <h1>Danh Sách Thẻ Tag</h1>
        <p>Khám phá nhanh các thẻ tag để tìm chính xác nội dung bạn đang quan tâm – mỗi thẻ là một góc nhìn mới.</p>
        <?php if (!empty($tags)): ?>
        <div class="hero-stats">
            <div class="hero-stat">
                <strong><?php echo count($tags); ?></strong>
                <span>Thẻ tag</span>
            </div>
            <div class="hero-stat">
                <strong><?php echo $totalArticles; ?></strong>
                <span>Bài viết liên kết</span>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="container">
<div class="tags-container">
    <div class="catalog-summary stats-card">
        <div>
            <h2>Chọn thẻ nhanh</h2>
            <p>Danh sách thẻ được tối ưu để quét nhanh và đi thẳng vào nội dung cần xem.</p>
        </div>
        <div class="catalog-summary__count">
            <strong><?php echo count($tags); ?></strong>
            <span>tag</span>
        </div>
    </div>

    <?php if (!empty($tags)): ?>
        <div class="tags-grid">
            <?php foreach ($tags as $tag): ?>
                <a href="<?php echo $basePath; ?>/tag/<?php echo htmlspecialchars($tag['slug']); ?>/" class="tag-card">
                    <div class="tag-card__top">
                        <div class="tag-name"><?php echo htmlspecialchars($tag['name']); ?></div>
                        <div class="tag-count">
                            <strong><?php echo $tag['article_count']; ?></strong>
                        </div>
                    </div>
                    <div class="tag-card__label">bài viết liên quan</div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-message">
            <div class="empty-message-icon"></div>
            <p>Hiện tại chưa có thẻ tag nào</p>
        </div>
    <?php endif; ?>
</div>
</div>

<?php include dirname(__FILE__) . '/../layouts/footer.php'; ?>

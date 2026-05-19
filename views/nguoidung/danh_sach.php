<?php
header('Content-Type: text/html; charset=utf-8');

$categories = $categories ?? [];
$basePath = '/WebsiteTinTuc/public';
$pageTitle = 'Danh Mục Tin Tức - Website Tin Tức';
include dirname(__FILE__) . '/../layouts/header-start.php';
?>

<div class="page-hero page-hero--catalog">
    <div class="container">
        <span class="hero-eyebrow">Chuyên mục</span>
        <h1>Danh Mục Tin Tức</h1>
        <p>Khám phá toàn bộ chuyên mục tin tức, cập nhật liên tục mỗi ngày.</p>
    </div>
</div>

<div class="container">
    <?php if (!empty($categories)): ?>
        <div class="catalog-summary stats-card">
            <div>
                <h2>Tất cả chuyên mục</h2>
                <p>Chọn chuyên mục bạn quan tâm để xem danh sách bài viết.</p>
            </div>
            <div class="catalog-summary__count">
                <strong><?php echo count($categories); ?></strong>
                <span>chuyên mục</span>
            </div>
        </div>

        <div class="categories-grid">
            <?php foreach ($categories as $i => $category): ?>
                <a href="<?php echo $basePath; ?>/danh-sach/<?php echo htmlspecialchars($category['slug']); ?>" class="category-card">
                    <div class="category-card__top">
                        <div class="category-name"><?php echo htmlspecialchars($category['name']); ?></div>
                    </div>
                    <?php if (!empty($category['description'])): ?>
                        <p class="category-desc"><?php echo htmlspecialchars(substr($category['description'], 0, 120)); ?></p>
                    <?php else: ?>
                        <p class="category-desc">Tin tức mới nhất trong chuyên mục <?php echo htmlspecialchars($category['name']); ?>.</p>
                    <?php endif; ?>
                    <span class="category-link read-more">Xem chuyên mục</span>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-content">
            <p>Hiện chưa có chuyên mục nào.</p>
        </div>
    <?php endif; ?>
</div>

<?php include dirname(__FILE__) . '/../layouts/footer.php'; ?>

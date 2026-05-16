<?php
header('Content-Type: text/html; charset=utf-8');

$categories = $categories ?? [];
$basePath = '/WebsiteTinTuc/public';
$pageTitle = 'Danh Mục Tin Tức - Website Tin Tức';
include dirname(__FILE__) . '/../layouts/header-start.php';
?>

<div class="page-hero">
    <div class="container">
        <h1>📚 Danh Mục Tin Tức</h1>
        <p>Khám phá các chuyên mục tin tức hàng ngày</p>
    </div>
</div>

<div class="container">
    <?php if (!empty($categories)): ?>
        <div class="catalog-summary stats-card">
            <div>
                <h2>Chọn nhanh chuyên mục</h2>
                <p>Danh sách được sắp theo dạng lưới gọn, dễ quét và dễ vào nội dung.</p>
            </div>
            <div class="catalog-summary__count">
                <strong><?php echo count($categories); ?></strong>
                <span>chuyên mục</span>
            </div>
        </div>

        <div class="categories-grid">
            <?php foreach ($categories as $category): ?>
                <a href="<?php echo $basePath; ?>/danh-sach/<?php echo htmlspecialchars($category['slug']); ?>" class="category-card">
                    <div class="category-card__top">
                        <div class="category-icon">📑</div>
                        <div class="category-name"><?php echo htmlspecialchars($category['name']); ?></div>
                    </div>
                    <?php if (!empty($category['description'])): ?>
                        <div class="category-desc"><?php echo htmlspecialchars(substr($category['description'], 0, 110)); ?></div>
                    <?php endif; ?>
                    <div class="category-link">Xem chuyên mục</div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-content">
            <p>❌ Hiện chưa có chuyên mục nào.</p>
        </div>
    <?php endif; ?>
</div>

<?php include dirname(__FILE__) . '/../layouts/footer.php'; ?>

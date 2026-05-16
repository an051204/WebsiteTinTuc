<?php
header('Content-Type: text/html; charset=utf-8');

$slide = $slide ?? [];
$tinMoiNhat = $tinMoiNhat ?? [];
$tinXemNhieu = $tinXemNhieu ?? [];
$chuyenMuc = $chuyenMuc ?? [];
$quangCaoTop = $quangCaoTop ?? [];
$quangCaoMiddle = $quangCaoMiddle ?? [];
$settings = $settings ?? [];

$showFeatured = ($settings['show_featured'] ?? '1') === '1';
$showLatest = ($settings['show_latest'] ?? '1') === '1';
$showMostViewed = ($settings['show_most_viewed'] ?? '1') === '1';
$showCategories = ($settings['show_categories'] ?? '1') === '1';
$showAds = ($settings['show_ads'] ?? '1') === '1';
$siteTitle = $settings['site_title'] ?? 'Website Tin Tức';
$featuredCategoryLimit = 3;
$topAd = !empty($quangCaoTop) ? $quangCaoTop[0] : null;
$middleAd = !empty($quangCaoMiddle) ? $quangCaoMiddle[0] : null;
$featuredCategories = array_slice($chuyenMuc, 0, $featuredCategoryLimit);

$basePath = '/WebsiteTinTuc/public';
$pageTitle = 'Trang Chủ - ' . $siteTitle;
include dirname(__FILE__) . '/../layouts/header-start.php';
?>
<div class="page-hero">
    <div class="container">
        <h1><?php echo htmlspecialchars($siteTitle); ?></h1>
        <p>Nhịp tin hôm nay được chọn lọc theo từng chuyên mục, bài nổi bật và các bài được đọc nhiều nhất.</p>
    </div>
</div>

<div class="container">

    <?php if ($showAds && !empty($topAd)): ?>
    <div class="ad-section">
        <p class="text-muted">QUẢNG CÁO</p>
        <div class="ad-banner ad-banner--compact">
            <a href="<?php echo htmlspecialchars($topAd['link_url'] ?? ''); ?>" target="_blank"
                rel="noopener noreferrer">
                <?php if (!empty($topAd['image_url'])): ?>
                <img src="<?php echo htmlspecialchars($topAd['image_url']); ?>"
                    alt="<?php echo htmlspecialchars($topAd['title'] ?? ''); ?>">
                <?php else: ?>
                <?php echo htmlspecialchars($topAd['title'] ?? ''); ?>
                <?php endif; ?>
            </a>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($showFeatured && !empty($slide)): ?>
    <div class="content-panel">
        <div class="section-header">
            <h2>🔥 Tin Nổi Bật</h2>
        </div>
        <div class="article-card article-card--featured article-card--compact">
            <div class="article-img article-img--featured">
                <?php if (!empty($slide[0]['thumbnail'])): ?>
                <img src="<?php echo htmlspecialchars($slide[0]['thumbnail']); ?>"
                    alt="<?php echo htmlspecialchars($slide[0]['title'] ?? ''); ?>">
                <?php else: ?>
                <span>📰 Tin nổi bật</span>
                <?php endif; ?>
            </div>
            <div class="article-body article-body--compact">
                <h3><a
                        href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($slide[0]['slug'] ?? ''); ?>"><?php echo htmlspecialchars($slide[0]['title'] ?? 'Không xác định'); ?></a>
                </h3>
                <div class="article-meta">
                    <span>📅
                        <?php echo !empty($slide[0]['created_at']) ? date('d/m/Y', strtotime($slide[0]['created_at'])) : 'N/A'; ?></span>
                    <span>👁 <?php echo htmlspecialchars($slide[0]['views_count'] ?? 0); ?> lượt xem</span>
                </div>
                <p class="article-summary"><?php echo htmlspecialchars(substr($slide[0]['content'] ?? '', 0, 95)); ?>...
                </p>
                <a href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($slide[0]['slug'] ?? ''); ?>"
                    class="read-more">Xem chi tiết →</a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($showLatest || $showMostViewed): ?>
    <div class="section-head">
        <h2 class="section-head__title">Bản tin hôm nay</h2>
        <span class="chip">Cập nhật liên tục</span>
    </div>
    <div class="article-grid">
        <?php if ($showLatest && !empty($tinMoiNhat)): ?>
        <?php foreach ($tinMoiNhat as $tin): ?>
        <article class="article-card">
            <div class="article-img">
                <?php if (!empty($tin['thumbnail'])): ?>
                <img src="<?php echo htmlspecialchars($tin['thumbnail']); ?>"
                    alt="<?php echo htmlspecialchars($tin['title'] ?? ''); ?>">
                <?php else: ?>
                <span>📰</span>
                <?php endif; ?>
            </div>
            <div class="article-body">
                <h3><a
                        href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($tin['slug'] ?? ''); ?>"><?php echo htmlspecialchars($tin['title'] ?? ''); ?></a>
                </h3>
                <div class="article-meta">
                    <span>📅
                        <?php echo !empty($tin['created_at']) ? date('d/m/Y', strtotime($tin['created_at'])) : 'N/A'; ?></span>
                    <span>👁 <?php echo htmlspecialchars($tin['views_count'] ?? 0); ?></span>
                </div>
                <p class="article-summary">
                    <?php echo htmlspecialchars(substr($tin['summary'] ?? $tin['content'] ?? '', 0, 110)); ?>...</p>
                <a href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($tin['slug'] ?? ''); ?>"
                    class="read-more">Đọc tiếp →</a>
            </div>
        </article>
        <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($showMostViewed && !empty($tinXemNhieu)): ?>
        <?php foreach ($tinXemNhieu as $tin): ?>
        <article class="article-card">
            <div class="article-img">
                <?php if (!empty($tin['thumbnail'])): ?>
                <img src="<?php echo htmlspecialchars($tin['thumbnail']); ?>"
                    alt="<?php echo htmlspecialchars($tin['title'] ?? ''); ?>">
                <?php else: ?>
                <span>📈</span>
                <?php endif; ?>
            </div>
            <div class="article-body">
                <h3><a
                        href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($tin['slug'] ?? ''); ?>"><?php echo htmlspecialchars($tin['title'] ?? ''); ?></a>
                </h3>
                <div class="article-meta">
                    <span>📅
                        <?php echo !empty($tin['created_at']) ? date('d/m/Y', strtotime($tin['created_at'])) : 'N/A'; ?></span>
                    <span>👁 <?php echo htmlspecialchars($tin['views_count'] ?? 0); ?> lượt xem</span>
                </div>
                <p class="article-summary">
                    <?php echo htmlspecialchars(substr($tin['summary'] ?? $tin['content'] ?? '', 0, 110)); ?>...</p>
                <a href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($tin['slug'] ?? ''); ?>"
                    class="read-more">Đọc tiếp →</a>
            </div>
        </article>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if ($showAds && !empty($middleAd)): ?>
    <div class="ad-section">
        <p class="text-muted">QUẢNG CÁO</p>
        <div class="ad-banner ad-banner--compact">
            <a href="<?php echo htmlspecialchars($middleAd['link_url'] ?? ''); ?>" target="_blank"
                rel="noopener noreferrer">
                <?php if (!empty($middleAd['image_url'])): ?>
                <img src="<?php echo htmlspecialchars($middleAd['image_url']); ?>"
                    alt="<?php echo htmlspecialchars($middleAd['title'] ?? ''); ?>">
                <?php else: ?>
                <?php echo htmlspecialchars($middleAd['title'] ?? ''); ?>
                <?php endif; ?>
            </a>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($showCategories && !empty($featuredCategories)): ?>
    <div class="section-head">
        <h2 class="section-head__title">📚 Chuyên mục tiêu biểu</h2>
        <a href="<?php echo $basePath; ?>/danh-sach/" class="btn btn-primary btn-sm">Tất cả chuyên mục →</a>
    </div>

    <div class="featured-category-grid">
        <?php foreach ($featuredCategories as $cat): ?>
        <?php $featuredArticle = !empty($cat['articles']) ? $cat['articles'][0] : null; ?>
        <article class="featured-category-card">
            <div class="featured-category-card__header">
                <div>
                    <h3><?php echo htmlspecialchars($cat['name'] ?? ''); ?></h3>
                    <?php if (!empty($cat['description'])): ?>
                    <p><?php echo htmlspecialchars(substr($cat['description'], 0, 90)); ?></p>
                    <?php endif; ?>
                </div>
                <a href="<?php echo $basePath; ?>/danh-sach/<?php echo htmlspecialchars($cat['slug'] ?? ''); ?>"
                    class="category-link">Xem chuyên mục</a>
            </div>

            <?php if (!empty($featuredArticle)): ?>
            <a class="featured-category-card__article"
                href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($featuredArticle['slug'] ?? ''); ?>">
                <div class="featured-category-card__thumb">
                    <?php if (!empty($featuredArticle['thumbnail'])): ?>
                    <img src="<?php echo htmlspecialchars($featuredArticle['thumbnail']); ?>"
                        alt="<?php echo htmlspecialchars($featuredArticle['title'] ?? ''); ?>">
                    <?php else: ?>
                    <span>📰</span>
                    <?php endif; ?>
                </div>
                <div class="featured-category-card__body">
                    <h4><?php echo htmlspecialchars($featuredArticle['title'] ?? ''); ?></h4>
                    <div class="article-meta">
                        <span>📅
                            <?php echo !empty($featuredArticle['created_at']) ? date('d/m/Y', strtotime($featuredArticle['created_at'])) : 'N/A'; ?></span>
                        <span>👁 <?php echo htmlspecialchars($featuredArticle['views_count'] ?? 0); ?></span>
                    </div>
                    <p><?php echo htmlspecialchars(substr($featuredArticle['summary'] ?? $featuredArticle['content'] ?? '', 0, 90)); ?>...
                    </p>
                </div>
            </a>
            <?php else: ?>
            <div class="no-content featured-category-card__empty">Chưa có bài viết tiêu biểu.</div>
            <?php endif; ?>
        </article>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php include dirname(__FILE__) . '/../layouts/footer.php'; ?>
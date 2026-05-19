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
$topAd = !empty($quangCaoTop) ? $quangCaoTop[0] : null;
$middleAd = !empty($quangCaoMiddle) ? $quangCaoMiddle[0] : null;
$featuredCategories = array_slice($chuyenMuc, 0, 4);
$basePath = '/WebsiteTinTuc/public';
$pageTitle = 'Trang Chủ - ' . $siteTitle;
include dirname(__FILE__) . '/../layouts/header-start.php';
?>
<style>
/* ===== HOMEPAGE VIP LAYOUT ===== */
.hp-wrapper { background: var(--news-bg); min-height: 100vh; position: relative; }

/* TOP AD */
.hp-ad-top { background: var(--white); padding: var(--spacing-xl) 0; text-align: center; border-bottom: 1px solid var(--news-border); box-shadow: var(--shadow-soft); }
.hp-ad-top img { max-height: 90px; border-radius: var(--border-radius-md); box-shadow: var(--shadow-medium); transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
.hp-ad-top img:hover { transform: scale(1.02) translateY(-2px); }

/* MAIN GRID */
.hp-main { max-width: 1280px; margin: 0 auto; padding: var(--spacing-2xl) var(--spacing-lg); display: grid; grid-template-columns: 1fr 360px; gap: var(--spacing-2xl); }
@media(max-width:1024px){ .hp-main{ grid-template-columns: 1fr; } .hp-sidebar{ display: none; } }

/* SECTION TITLE */
.sec-title { display: flex; align-items: center; gap: 16px; margin: 0 0 var(--spacing-xl); padding-bottom: 16px; border-bottom: 2px solid var(--news-border); position: relative; }
.sec-title::after { content: ''; position: absolute; left: 0; bottom: -2px; width: 80px; height: 3px; background: var(--news-red); border-radius: 2px; }
.sec-title h2 { font-family: var(--font-heading); font-size: 1.8rem; font-weight: 900; color: var(--news-navy); margin: 0; letter-spacing: -0.01em; }
.sec-title a { margin-left: auto; font-size: 12px; font-weight: 800; color: var(--news-red); text-decoration: none; text-transform: uppercase; letter-spacing: 0.1em; transition: all 0.25s ease; display: inline-flex; align-items: center; gap: 4px; }
.sec-title a:hover { color: var(--news-red-dark); transform: translateX(3px); }

/* FEATURED HERO - big card + 4 small */
.hp-featured { margin-bottom: var(--spacing-2xl); }
.featured-hero-grid { display: grid; grid-template-columns: 1.35fr 1fr; gap: var(--spacing-xl); align-items: stretch; }
@media(max-width:800px){ .featured-hero-grid{ grid-template-columns: 1fr; } }

.hero-big { position: relative; border-radius: var(--border-radius-lg); overflow: hidden; background: #000; box-shadow: var(--shadow-medium); display: block; transition: all 0.3s ease; }
.hero-big-img-wrap { position: relative; width: 100%; aspect-ratio: 16/9; overflow: hidden; background: #1a1a2e; }
.hero-big-img-wrap img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.88; transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94), opacity 0.6s; }
.hero-big:hover img { transform: scale(1.06); opacity: 1; }
.hero-big-overlay { position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,0.05) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.95) 100%); pointer-events: none; }
.hero-big-body { position: absolute; bottom: 0; left: 0; right: 0; padding: var(--spacing-xl) var(--spacing-xl) var(--spacing-xl); color: #fff; z-index: 2; }
.hero-big-cat { display: inline-block; background: var(--news-red); color: #fff; font-size: 11px; font-weight: 900; letter-spacing: 0.15em; text-transform: uppercase; padding: 6px 16px; border-radius: var(--border-radius-sm); margin-bottom: 16px; }
.hero-big-title { font-family: var(--font-heading); font-size: 1.8rem; font-weight: 900; line-height: 1.25; margin: 0 0 14px; color: #fff; text-shadow: 0 4px 12px rgba(0,0,0,0.6); }
.hero-big-meta { font-size: 13px; font-family: var(--font-body); color: rgba(255,255,255,0.9); display: flex; align-items: center; gap: 14px; font-weight: 600; }

.featured-sub-list { display: flex; flex-direction: column; gap: var(--spacing-lg); }
.fsub-card { display: grid; grid-template-columns: 160px 1fr; gap: 16px; background: #fff; border-radius: var(--border-radius-md); border: 1px solid transparent; overflow: hidden; transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94); box-shadow: var(--shadow-soft); }
.fsub-card:hover { box-shadow: var(--shadow-medium); transform: translateY(-4px); border-color: var(--news-border); }
.fsub-img { position: relative; width: 100%; aspect-ratio: 4/3; overflow: hidden; background: #f0f0f0; display: block; }
.fsub-img img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
.fsub-card:hover .fsub-img img { transform: scale(1.1); }
.fsub-body { padding: 14px 14px 14px 0; display: flex; flex-direction: column; justify-content: center; min-width: 0; }
.fsub-cat { font-size: 10px; font-weight: 900; color: var(--news-red); text-transform: uppercase; letter-spacing: 0.12em; margin-bottom: 6px; display: block; }
.fsub-title { font-family: var(--font-heading); font-size: 0.95rem; font-weight: 800; color: var(--news-navy); line-height: 1.4; text-decoration: none; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: auto; transition: color 0.25s; }
.fsub-card:hover .fsub-title { color: var(--news-red); }
.fsub-meta { font-size: 11px; color: var(--news-text-light); font-weight: 600; margin-top: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* LATEST NEWS - 3 column grid */
.hp-latest { margin-bottom: var(--spacing-2xl); }
.latest-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--spacing-xl); }
@media(max-width:800px){ .latest-grid{ grid-template-columns: repeat(2, 1fr); } }
@media(max-width:500px){ .latest-grid{ grid-template-columns: 1fr; } }

.news-card { background: #fff; border-radius: var(--border-radius-md); border: 1px solid transparent; overflow: hidden; display: flex; flex-direction: column; transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94); box-shadow: var(--shadow-soft); }
.news-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-medium); }
.news-card-img { aspect-ratio: 16/10; overflow: hidden; background: #f0f0f0; position: relative; }
.news-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
.news-card:hover .news-card-img img { transform: scale(1.1); }
.news-card-badge { position: absolute; top: 12px; left: 12px; background: rgba(17, 24, 39, 0.92); backdrop-filter: blur(6px); color: #fff; font-size: 10px; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; padding: 5px 11px; border-radius: var(--border-radius-sm); }
.news-card-body { padding: var(--spacing-lg); display: flex; flex-direction: column; flex: 1; }
.news-card-title { font-family: var(--font-heading); font-size: 1.2rem; font-weight: 800; color: var(--news-navy); line-height: 1.45; text-decoration: none; margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; transition: color 0.25s; }
.news-card:hover .news-card-title { color: var(--news-red); }
.news-card-excerpt { font-size: 0.95rem; color: var(--news-text-muted); line-height: 1.65; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 16px; font-weight: 400; font-family: var(--font-reading); }
.news-card-meta { font-size: 12px; color: var(--news-text-light); margin-top: auto; display: flex; justify-content: space-between; align-items: center; font-weight: 600; border-top: 1px solid var(--news-border); padding-top: 14px; }
.news-card-readmore { font-size: 11px; font-weight: 800; color: var(--news-red); text-transform: uppercase; letter-spacing: 0.08em; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: all 0.2s; }
.news-card-readmore::after { content: '→'; font-size: 13px; transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
.news-card:hover .news-card-readmore::after { transform: translateX(4px); }

/* TRENDING SIDEBAR */
.hp-sidebar { display: flex; flex-direction: column; gap: var(--spacing-2xl); }
.sidebar-box { background: #fff; border-radius: var(--border-radius-lg); overflow: hidden; box-shadow: var(--shadow-soft); border: 1px solid transparent; }
.sidebar-box-title { background: var(--news-navy); color: #fff; font-family: var(--font-heading); font-size: 1.15rem; font-weight: 900; padding: 18px 22px; display: flex; align-items: center; gap: 12px; letter-spacing: 0.05em; text-transform: uppercase; }
.sidebar-box-title::before { content: ''; width: 4px; height: 24px; background: var(--news-red); border-radius: 2px; }
.trending-list { padding: 8px 0; counter-reset: trending-counter; }
.trending-item { display: flex; align-items: flex-start; gap: 16px; padding: 16px 20px; border-bottom: 1px solid var(--news-border); transition: all 0.2s ease; text-decoration: none; }
.trending-item:last-child { border-bottom: none; }
.trending-item:hover { background: #f9fafb; }
.trending-item::before { counter-increment: trending-counter; content: "0" counter(trending-counter); font-family: var(--font-heading); font-size: 1.6rem; font-weight: 900; color: var(--news-border); line-height: 1; transition: all 0.3s; margin-top: 2px; }
.trending-item:nth-child(1)::before { color: var(--news-red); }
.trending-item:nth-child(2)::before { color: var(--news-gold); }
.trending-item:nth-child(3)::before { color: var(--news-blue); }
.trending-item:nth-child(4)::before { color: var(--news-text-light); }
.trending-content { display: flex; flex-direction: column; flex: 1; }
.trending-title { font-family: var(--font-heading); font-size: 1.02rem; font-weight: 800; color: var(--news-navy); line-height: 1.45; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 8px; transition: color 0.25s; }
.trending-item:hover .trending-title { color: var(--news-red); }
.trending-meta { font-size: 11px; color: var(--news-text-light); font-weight: 600; font-family: var(--font-body); }

/* CATEGORIES SECTION */
.hp-categories { margin-bottom: var(--spacing-2xl); }
.cat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: var(--spacing-xl); }
@media(max-width:1024px){ .cat-grid{ grid-template-columns: repeat(3, 1fr); } }
@media(max-width:800px){ .cat-grid{ grid-template-columns: repeat(2, 1fr); } }
@media(max-width:500px){ .cat-grid{ grid-template-columns: 1fr; } }

.cat-card { background: #fff; border: 1px solid transparent; border-radius: var(--border-radius-md); overflow: hidden; transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94); box-shadow: var(--shadow-soft); }
.cat-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-medium); }
.cat-card-header { padding: 18px 20px 14px; border-bottom: 1px solid var(--news-border); display: flex; align-items: center; justify-content: space-between; background: #f9fafb; }
.cat-card-name { font-family: var(--font-heading); font-size: 1.15rem; font-weight: 900; color: var(--news-navy); text-decoration: none; border-left: 4px solid var(--news-red); padding-left: 12px; letter-spacing: -0.01em; }
.cat-card-name:hover { color: var(--news-red); }
.cat-card-link { font-size: 10px; color: var(--news-text-muted); text-decoration: none; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; transition: color 0.2s; }
.cat-card-link:hover { color: var(--news-red); }
.cat-article { display: grid; grid-template-columns: 90px 1fr; gap: 0; border-bottom: 1px solid var(--news-border); text-decoration: none; transition: all 0.2s; }
.cat-article:last-child { border-bottom: none; }
.cat-article:hover { background: #f9fafb; }
.cat-article-img { height: 80px; overflow: hidden; background: #f0f0f0; }
.cat-article-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
.cat-article:hover .cat-article-img img { transform: scale(1.08); }
.cat-article-body { padding: 13px 16px; display: flex; align-items: center; }
.cat-article-title { font-family: var(--font-heading); font-size: 0.9rem; font-weight: 700; color: var(--news-navy); line-height: 1.45; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; transition: color 0.2s; }
.cat-article:hover .cat-article-title { color: var(--news-red); }

/* MIDDLE AD */
.hp-ad-mid { text-align: center; margin: 0 0 var(--spacing-2xl); padding: var(--spacing-xl); background: #fff; border: 1px solid transparent; border-radius: var(--border-radius-md); display: block; text-decoration: none; transition: all 0.3s ease; box-shadow: var(--shadow-soft); }
.hp-ad-mid:hover { box-shadow: var(--shadow-medium); }
.hp-ad-mid img { max-height: 110px; border-radius: 8px; box-shadow: var(--shadow-soft); transition: all 0.3s ease; }
.hp-ad-mid:hover img { transform: scale(1.02); }
.hp-ad-label { font-size: 11px; font-weight: 800; letter-spacing: 0.15em; text-transform: uppercase; color: var(--news-text-muted); margin-bottom: 10px; display: block; }

/* NO IMAGE PLACEHOLDER */
.no-img-placeholder { width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:1rem; font-weight: 600; color:var(--news-text-light); background: #e5e7eb; }
</style>

<div class="hp-wrapper">

<?php if ($showAds && !empty($topAd)): ?>
<div class="hp-ad-top">
    <div class="hp-ad-label">Quảng cáo</div>
    <a href="<?php echo htmlspecialchars($topAd['link_url'] ?? '#'); ?>" target="_blank" rel="noopener">
        <?php if (!empty($topAd['image_url'])): ?>
        <img src="<?php echo htmlspecialchars($topAd['image_url']); ?>" alt="<?php echo htmlspecialchars($topAd['title'] ?? ''); ?>">
        <?php else: ?><?php echo htmlspecialchars($topAd['title'] ?? ''); ?><?php endif; ?>
    </a>
</div>
<?php endif; ?>

<div class="hp-main">
    <!-- LEFT CONTENT -->
    <div class="hp-content">

        <?php if ($showFeatured && !empty($slide)): ?>
        <div class="hp-featured">
            <div class="sec-title">
                <h2>Tin Nổi Bật</h2>
            </div>
            <div class="featured-hero-grid">
                <!-- BIG HERO -->
                <?php $main = $slide[0]; ?>
                <a class="hero-big" href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($main['slug'] ?? ''); ?>">
                    <?php if (!empty($main['thumbnail'])): ?>
                    <div class="hero-big-img-wrap">
                        <img src="<?php echo htmlspecialchars($main['thumbnail']); ?>" alt="<?php echo htmlspecialchars($main['title'] ?? ''); ?>">
                    </div>
                    <?php else: ?><div class="hero-big-img-wrap" style="display:flex;align-items:center;justify-content:center;"><span style="color:#9ca3af;font-size:1rem;font-weight:600;">Không có ảnh</span></div><?php endif; ?>
                    <div class="hero-big-overlay"></div>
                    <div class="hero-big-body">
                        <span class="hero-big-cat">Nổi bật</span>
                        <span class="hero-big-title"><?php echo htmlspecialchars($main['title'] ?? ''); ?></span>
                        <div class="hero-big-meta">
                            <?php echo !empty($main['created_at']) ? date('d/m/Y', strtotime($main['created_at'])) : ''; ?>
                            &nbsp;·&nbsp; <?php echo $main['views_count'] ?? 0; ?> lượt xem
                        </div>
                    </div>
                </a>
                <!-- SUB LIST -->
                <div class="featured-sub-list">
                    <?php foreach(array_slice($slide, 1, 4) as $art): ?>
                    <div class="fsub-card">
                        <div class="fsub-img">
                            <?php if (!empty($art['thumbnail'])): ?>
                            <img src="<?php echo htmlspecialchars($art['thumbnail']); ?>" alt="<?php echo htmlspecialchars($art['title'] ?? ''); ?>">
                            <?php else: ?><div class="no-img-placeholder"></div><?php endif; ?>
                        </div>
                        <div class="fsub-body">
                            <span class="fsub-cat">Nổi bật</span>
                            <a class="fsub-title" href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($art['slug'] ?? ''); ?>"><?php echo htmlspecialchars($art['title'] ?? ''); ?></a>
                            <div class="fsub-meta"><?php echo !empty($art['created_at']) ? date('d/m/Y', strtotime($art['created_at'])) : ''; ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($showLatest && !empty($tinMoiNhat)): ?>
        <div class="hp-latest">
            <div class="sec-title">
                <h2>Tin Mới Nhất</h2>
                <a href="<?php echo $basePath; ?>/danh-sach/">Xem tất cả →</a>
            </div>
            <div class="latest-grid">
                <?php foreach(array_slice($tinMoiNhat, 0, 6) as $tin): ?>
                <div class="news-card">
                    <div class="news-card-img">
                        <span class="news-card-badge">Mới</span>
                        <?php if (!empty($tin['thumbnail'])): ?>
                        <img src="<?php echo htmlspecialchars($tin['thumbnail']); ?>" alt="<?php echo htmlspecialchars($tin['title'] ?? ''); ?>">
                        <?php else: ?><div class="no-img-placeholder"></div><?php endif; ?>
                    </div>
                    <div class="news-card-body">
                        <a class="news-card-title" href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($tin['slug'] ?? ''); ?>"><?php echo htmlspecialchars($tin['title'] ?? ''); ?></a>
                        <p class="news-card-excerpt"><?php echo htmlspecialchars(substr(strip_tags($tin['summary'] ?? $tin['content'] ?? ''), 0, 100)); ?></p>
                        <div class="news-card-meta">
                            <span><?php echo !empty($tin['created_at']) ? date('d/m/Y', strtotime($tin['created_at'])) : ''; ?></span>
                            <span><?php echo $tin['views_count'] ?? 0; ?></span>
                        </div>
                        <a class="news-card-readmore" href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($tin['slug'] ?? ''); ?>">Đọc tiếp »</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($showAds && !empty($middleAd)): ?>
        <div class="hp-ad-mid">
            <div class="hp-ad-label">Quảng cáo</div>
            <a href="<?php echo htmlspecialchars($middleAd['link_url'] ?? '#'); ?>" target="_blank" rel="noopener">
                <?php if (!empty($middleAd['image_url'])): ?>
                <img src="<?php echo htmlspecialchars($middleAd['image_url']); ?>" alt="">
                <?php else: ?><?php echo htmlspecialchars($middleAd['title'] ?? ''); ?><?php endif; ?>
            </a>
        </div>
        <?php endif; ?>

        <?php if ($showCategories && !empty($featuredCategories)): ?>
        <div class="hp-categories">
            <div class="sec-title">
                <h2>Theo Chuyên Mục</h2>
                <a href="<?php echo $basePath; ?>/danh-sach/">Tất cả →</a>
            </div>
            <div class="cat-grid">
                <?php foreach($featuredCategories as $cat): ?>
                <div class="cat-card">
                    <div class="cat-card-header">
                        <a class="cat-card-name" href="<?php echo $basePath; ?>/danh-sach/<?php echo htmlspecialchars($cat['slug'] ?? ''); ?>"><?php echo htmlspecialchars($cat['name'] ?? ''); ?></a>
                        <a class="cat-card-link" href="<?php echo $basePath; ?>/danh-sach/<?php echo htmlspecialchars($cat['slug'] ?? ''); ?>">Xem →</a>
                    </div>
                    <?php foreach(array_slice($cat['articles'] ?? [], 0, 3) as $art): ?>
                    <a class="cat-article" href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($art['slug'] ?? ''); ?>">
                        <div class="cat-article-img">
                            <?php if (!empty($art['thumbnail'])): ?><img src="<?php echo htmlspecialchars($art['thumbnail']); ?>" alt="">
                            <?php else: ?><div class="no-img-placeholder" style="height:58px;font-size:.9rem;">📰</div><?php endif; ?>
                        </div>
                        <div class="cat-article-body"><span class="cat-article-title"><?php echo htmlspecialchars($art['title'] ?? ''); ?></span></div>
                    </a>
                    <?php endforeach; ?>
                    <?php if (empty($cat['articles'])): ?><div style="padding:16px;color:#aaa;font-size:.82rem;text-align:center;">Chưa có bài viết</div><?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div><!-- /hp-content -->

    <!-- RIGHT SIDEBAR -->
    <aside class="hp-sidebar">

        <?php if ($showMostViewed && !empty($tinXemNhieu)): ?>
        <div class="sidebar-box">
            <div class="sidebar-box-title"><span class="dot"></span> Đọc Nhiều Nhất</div>
            <div class="trending-list">
                <?php foreach(array_slice($tinXemNhieu, 0, 8) as $idx => $tin): ?>
                <div class="trending-item">
                    <span class="trending-num"><?php echo $idx + 1; ?></span>
                    <div>
                        <a class="trending-title" href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($tin['slug'] ?? ''); ?>"><?php echo htmlspecialchars($tin['title'] ?? ''); ?></a>
                        <div class="trending-meta"><?php echo $tin['views_count'] ?? 0; ?> · <?php echo !empty($tin['created_at']) ? date('d/m', strtotime($tin['created_at'])) : ''; ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($chuyenMuc)): ?>
        <div class="sidebar-box">
            <div class="sidebar-box-title"><span class="dot"></span> Chuyên Mục</div>
            <div style="padding:8px 0;">
                <?php foreach(array_slice($chuyenMuc, 0, 10) as $cat): ?>
                <a href="<?php echo $basePath; ?>/danh-sach/<?php echo htmlspecialchars($cat['slug'] ?? ''); ?>" style="display:flex;align-items:center;justify-content:space-between;padding:9px 16px;border-bottom:1px solid #f0f0ec;text-decoration:none;color:#0d1b2a;font-size:.85rem;font-weight:600;transition:background .15s;">
                    <span><?php echo htmlspecialchars($cat['name'] ?? ''); ?></span>
                    <span style="font-size:10px;color:#aaa;">→</span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </aside>
</div><!-- /hp-main -->
</div><!-- /hp-wrapper -->

<?php include dirname(__FILE__) . '/../layouts/footer.php'; ?>
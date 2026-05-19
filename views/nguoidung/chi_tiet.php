<?php
header('Content-Type: text/html; charset=utf-8');

$baiViet = $baiViet ?? [];
$chuyenMuc = $chuyenMuc ?? [];
$binhLuanList = $binhLuanList ?? [];
$baiLienQuan = $baiLienQuan ?? [];
$baiXemNhieu = $baiXemNhieu ?? [];
$userId = $userId ?? null;
$daThich = $daThich ?? false;
$daLuu = $daLuu ?? false;
$tags = $tags ?? [];

$basePath = '/WebsiteTinTuc/public';
$currentUrl = $_SERVER['REQUEST_URI'];
$pageTitle = htmlspecialchars($baiViet['title'] ?? 'Bài viết') . ' - Website Tin Tức';
include dirname(__FILE__) . '/../layouts/header-start.php';

// Cấu hình hiển thị quảng cáo
$showAds = ($settings['show_ads'] ?? '1') === '1';
$sidebarAd = !empty($quangCaoSidebar) ? $quangCaoSidebar[0] : null;
$articleBottomAd = !empty($quangCaoArticleBottom) ? $quangCaoArticleBottom[0] : null;

$plainContent = strip_tags($baiViet['content'] ?? '');
$wordCount = max(1, count(preg_split('/\s+/u', trim($plainContent))));
$readingMinutes = max(1, (int) ceil($wordCount / 200));

$authorName = $baiViet['author_name'] ?? 'Tác giả ẩn danh';
$authorInitial = strtoupper(substr($authorName, 0, 1));
$publishDate = !empty($baiViet['created_at']) ? date('d/m/Y', strtotime($baiViet['created_at'])) : '';
?>
<!-- Reading Progress -->
<div class="reading-progress-bar" id="readingProgressBar">
    <div class="reading-progress-fill" id="readingProgressFill"></div>
</div>

<!-- Breadcrumb -->
<div class="detail-breadcrumb">
    <div class="container">
        <nav class="breadcrumb-nav">
            <a href="<?php echo $basePath; ?>/">Trang chủ</a>
            <span class="breadcrumb-sep">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
            <?php if (!empty($baiViet['category_slug'])): ?>
            <a href="<?php echo $basePath; ?>/danh-sach/<?php echo htmlspecialchars($baiViet['category_slug']); ?>">
                <?php echo htmlspecialchars($baiViet['category_name'] ?? 'Chuyên mục'); ?>
            </a>
            <span class="breadcrumb-sep">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
            <?php endif; ?>
            <span class="breadcrumb-current"><?php echo htmlspecialchars(mb_substr($baiViet['title'] ?? '', 0, 50)); ?><?php echo (mb_strlen($baiViet['title'] ?? '') > 50) ? '...' : ''; ?></span>
        </nav>
    </div>
</div>

<!-- Article Header -->
<header class="article-header">
    <div class="container">
        <?php if (!empty($baiViet['category_slug'])): ?>
        <a class="article-category" href="<?php echo $basePath; ?>/danh-sach/<?php echo htmlspecialchars($baiViet['category_slug']); ?>">
            <?php echo htmlspecialchars($baiViet['category_name'] ?? 'Chuyên mục'); ?>
        </a>
        <?php endif; ?>

        <h1 class="article-title"><?php echo htmlspecialchars($baiViet['title'] ?? 'Không xác định'); ?></h1>

        <?php if (!empty($baiViet['summary'])): ?>
        <p class="article-excerpt"><?php echo htmlspecialchars($baiViet['summary']); ?></p>
        <?php endif; ?>

        <div class="article-meta">
            <div class="meta-author">
                <div class="author-avatar"><?php echo $authorInitial; ?></div>
                <div class="author-info">
                    <span class="author-name"><?php echo htmlspecialchars($authorName); ?></span>
                    <span class="author-date"><?php echo $publishDate; ?></span>
                </div>
            </div>

            <div class="meta-stats">
                <span class="meta-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <?php echo $readingMinutes; ?> phút đọc
                </span>
                <span class="meta-divider"></span>
                <span class="meta-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <?php echo number_format((int)($baiViet['views_count'] ?? 0)); ?> lượt xem
                </span>
                <span class="meta-divider"></span>
                <span class="meta-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <?php echo count($binhLuanList); ?> bình luận
                </span>
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<div class="container article-container">
    <!-- Alerts -->
    <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-error">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
    </div>
    <?php endif; ?>

    <div class="article-layout">
        <!-- Main Article -->
        <article class="article-main">
            <div class="article-body">
                <div class="article-content">
                    <?php echo $baiViet['content'] ?? '<p>Không có nội dung</p>'; ?>
                </div>

                <!-- Tags -->
                <?php if (!empty($tags)): ?>
                <div class="article-tags">
                    <span class="tags-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        Tags:
                    </span>
                    <?php foreach ($tags as $tag): ?>
                    <a href="<?php echo $basePath; ?>/tag/<?php echo htmlspecialchars($tag['slug']); ?>" class="tag-chip">
                        #<?php echo htmlspecialchars($tag['name']); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Share & Actions -->
                <div class="article-actions">
                    <div class="actions-share">
                        <span class="share-text">Chia sẻ:</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://localhost' . $currentUrl); ?>"
                            target="_blank" rel="noopener noreferrer" class="share-btn fb">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://localhost' . $currentUrl); ?>&text=<?php echo urlencode($baiViet['title']); ?>"
                            target="_blank" rel="noopener noreferrer" class="share-btn tw">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>
                        </a>
                        <button class="share-btn copy" type="button" onclick="copyLink('<?php echo addslashes($currentUrl); ?>')" title="Sao chép liên kết">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        </button>
                    </div>

                    <div class="actions-interact">
                        <?php if ($userId): ?>
                        <form method="POST" class="interact-form">
                            <input type="hidden" name="action" value="like">
                            <button type="submit" class="interact-btn <?php echo $daThich ? 'liked' : ''; ?>">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="<?php echo $daThich ? 'currentColor' : 'none'; ?>" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                <span><?php echo $daThich ? 'Đã thích' : 'Thích'; ?></span>
                            </button>
                        </form>
                        <form method="POST" class="interact-form">
                            <input type="hidden" name="action" value="save">
                            <button type="submit" class="interact-btn <?php echo $daLuu ? 'saved' : ''; ?>">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="<?php echo $daLuu ? 'currentColor' : 'none'; ?>" stroke="currentColor" stroke-width="2"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                                <span><?php echo $daLuu ? 'Đã lưu' : 'Lưu'; ?></span>
                            </button>
                        </form>
                        <?php else: ?>
                        <a href="<?php echo $basePath; ?>/dang-nhap" class="login-link">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Đăng nhập để tương tác
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Comments -->
            <section class="article-comments">
                <div class="comments-header">
                    <h2 class="comments-title">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        Bình luận
                        <?php if (count($binhLuanList) > 0): ?>
                        <span class="comments-badge"><?php echo count($binhLuanList); ?></span>
                        <?php endif; ?>
                    </h2>
                </div>

                <?php if ($userId): ?>
                <div class="comment-compose">
                    <div class="compose-avatar">
                        <?php echo strtoupper(substr($_SESSION['user_full_name'] ?? 'U', 0, 1)); ?>
                    </div>
                    <form method="POST" class="compose-form">
                        <textarea name="content" required placeholder="Viết bình luận của bạn..." rows="3"></textarea>
                        <input type="hidden" name="action" value="comment">
                        <div class="compose-actions">
                            <button type="submit" class="btn-send">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                Gửi bình luận
                            </button>
                        </div>
                    </form>
                </div>
                <?php else: ?>
                <div class="comment-please-login">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                    <a href="<?php echo $basePath; ?>/dang-nhap">Đăng nhập</a> để bình luận
                </div>
                <?php endif; ?>

                <?php if (!empty($binhLuanList)): ?>
                <div class="comments-list" id="commentsList">
                    <?php foreach ($binhLuanList as $comment): ?>
                    <div class="comment-card">
                        <div class="comment-avatar-lg">
                            <?php echo strtoupper(substr($comment['full_name'] ?? 'A', 0, 1)); ?>
                        </div>
                        <div class="comment-info">
                            <div class="comment-meta">
                                <strong class="comment-name"><?php echo htmlspecialchars($comment['full_name'] ?? 'Ẩn danh'); ?></strong>
                                <time class="comment-time"><?php echo !empty($comment['created_at']) ? date('d/m/Y H:i', strtotime($comment['created_at'])) : ''; ?></time>
                            </div>
                            <div class="comment-text">
                                <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="no-comments-yet">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <p>Chưa có bình luận nào</p>
                    <span>Hãy là người đầu tiên bình luận bài viết này!</span>
                </div>
                <?php endif; ?>
            </section>
        </article>

        <!-- Sidebar -->
        <aside class="article-sidebar">
            <?php if (!empty($baiXemNhieu)): ?>
            <div class="sidebar-widget">
                <div class="widget-header">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 6l-9.5 9.5-5-5L1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                    <h3>Tin xem nhiều</h3>
                </div>
                <div class="widget-body">
                    <?php foreach ($baiXemNhieu as $index => $item): ?>
                    <a class="widget-post" href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($item['slug']); ?>">
                        <span class="post-number"><?php echo $index + 1; ?></span>
                        <?php if (!empty($item['thumbnail'])): ?>
                        <div class="post-thumb">
                            <img src="<?php echo htmlspecialchars($item['thumbnail']); ?>"
                                alt="<?php echo htmlspecialchars($item['title']); ?>" loading="lazy">
                        </div>
                        <?php endif; ?>
                        <div class="post-details">
                            <span class="post-title"><?php echo htmlspecialchars($item['title']); ?></span>
                            <span class="post-views">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                <?php echo number_format((int)($item['views_count'] ?? 0)); ?>
                            </span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($baiLienQuan)): ?>
            <div class="sidebar-widget">
                <div class="widget-header">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <h3>Bài viết liên quan</h3>
                </div>
                <div class="widget-body">
                    <?php foreach ($baiLienQuan as $related): ?>
                    <a class="widget-post" href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($related['slug']); ?>">
                        <?php if (!empty($related['thumbnail'])): ?>
                        <div class="post-thumb">
                            <img src="<?php echo htmlspecialchars($related['thumbnail']); ?>"
                                alt="<?php echo htmlspecialchars($related['title']); ?>" loading="lazy">
                        </div>
                        <?php endif; ?>
                        <div class="post-details">
                            <span class="post-title"><?php echo htmlspecialchars($related['title']); ?></span>
                            <span class="post-views">
                                <?php echo !empty($related['created_at']) ? date('d/m/Y', strtotime($related['created_at'])) : ''; ?>
                            </span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

                <?php if ($showAds && !empty($sidebarAd)): ?>
                <div class="sidebar-widget">
                    <div class="widget-header">
                        <h3>Quảng cáo</h3>
                    </div>
                    <div class="widget-body">
                        <a href="<?php echo htmlspecialchars($sidebarAd['link_url'] ?? '#'); ?>" target="_blank" rel="noopener">
                            <?php if (!empty($sidebarAd['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($sidebarAd['image_url']); ?>" alt="<?php echo htmlspecialchars($sidebarAd['title'] ?? ''); ?>" style="width:100%;height:auto;border-radius:8px;">
                            <?php else: ?>
                            <?php echo htmlspecialchars($sidebarAd['title'] ?? 'Quảng cáo'); ?>
                            <?php endif; ?>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
        </aside>
    </div>

        <?php if ($showAds && !empty($articleBottomAd)): ?>
        <div class="article-ad-bottom" style="text-align:center;margin:18px 0;">
            <div class="hp-ad-label">Quảng cáo</div>
            <a href="<?php echo htmlspecialchars($articleBottomAd['link_url'] ?? '#'); ?>" target="_blank" rel="noopener">
                <?php if (!empty($articleBottomAd['image_url'])): ?>
                <img src="<?php echo htmlspecialchars($articleBottomAd['image_url']); ?>" alt="<?php echo htmlspecialchars($articleBottomAd['title'] ?? ''); ?>" style="max-width:100%;max-height:140px;border-radius:10px;">
                <?php else: ?>
                <?php echo htmlspecialchars($articleBottomAd['title'] ?? 'Quảng cáo'); ?>
                <?php endif; ?>
            </a>
        </div>
        <?php endif; ?>

        <!-- Bottom Nav -->
    <div class="article-bottom-nav">
        <?php if (!empty($chuyenMuc) && !empty($chuyenMuc['slug'])): ?>
        <a href="<?php echo $basePath; ?>/danh-sach/<?php echo htmlspecialchars($chuyenMuc['slug']); ?>" class="bottom-nav-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            <span>Quay về <?php echo htmlspecialchars($chuyenMuc['name'] ?? 'Chuyên mục'); ?></span>
        </a>
        <?php endif; ?>
        <a href="<?php echo $basePath; ?>/" class="bottom-nav-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span>Trang chủ</span>
        </a>
    </div>
</div>

<script>
// Fix inline background styles from editor content
(function() {
    var content = document.querySelector('.article-content');
    if (content) {
        var elements = content.querySelectorAll('[style*="background"], [style*="background-color"]');
        elements.forEach(function(el) {
            el.style.background = '';
            el.style.backgroundColor = '';
        });
        // Also fix direct inline background on the content wrapper
        content.style.background = '';
        content.style.backgroundColor = '';
    }
})();
</script>

<script>
// Reading progress
window.addEventListener('scroll', function() {
    var el = document.getElementById('readingProgressFill');
    if (el) {
        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        var scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        el.style.width = (scrollTop / scrollHeight * 100) + '%';
    }
});

// Copy link
function copyLink(url) {
    var fullUrl = window.location.origin + url;
    navigator.clipboard.writeText(fullUrl).then(function() {
        var btn = document.querySelector('.share-btn.copy');
        if (btn) {
            btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>';
            btn.classList.add('copied');
            setTimeout(function() {
                btn.classList.remove('copied');
                btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>';
            }, 2000);
        }
    });
}
</script>

<?php include dirname(__FILE__) . '/../layouts/footer.php'; ?>

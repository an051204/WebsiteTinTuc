<?php
header('Content-Type: text/html; charset=utf-8');

$baiViet = $baiViet ?? [];
$chuyenMuc = $chuyenMuc ?? [];
$binhLuanList = $binhLuanList ?? [];
$baiLienQuan = $baiLienQuan ?? [];
$userId = $userId ?? null;
$daThich = $daThich ?? false;
$daLuu = $daLuu ?? false;
$tags = $tags ?? [];

$basePath = '/WebsiteTinTuc/public';
$currentUrl = $_SERVER['REQUEST_URI'];
$pageTitle = htmlspecialchars($baiViet['title'] ?? 'Bài viết') . ' - Website Tin Tức';
include dirname(__FILE__) . '/../layouts/header-start.php';
?>
<div class="container detail-shell">
    <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">✓ <?php echo htmlspecialchars($_SESSION['success']); ?></div>
    <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-error">✗ <?php echo htmlspecialchars($_SESSION['error']); ?></div>
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <article class="detail-hero">
        <h1><?php echo htmlspecialchars($baiViet['title'] ?? 'Không xác định'); ?></h1>
        <div class="detail-meta">
            <span>📅
                <?php echo !empty($baiViet['created_at']) ? date('d/m/Y H:i', strtotime($baiViet['created_at'])) : 'Chưa xác định'; ?></span>
            <span>✍️ <?php echo htmlspecialchars($baiViet['author_name'] ?? 'Tác giả ẩn danh'); ?></span>
            <span>📁 <a
                    href="<?php echo $basePath; ?>/danh-sach/<?php echo htmlspecialchars($baiViet['category_slug'] ?? ''); ?>"><?php echo htmlspecialchars($baiViet['category_name'] ?? 'Không xác định'); ?></a></span>
            <span>👁 <?php echo htmlspecialchars($baiViet['views_count'] ?? 0); ?> lượt xem</span>
            <span>💬 <?php echo htmlspecialchars($baiViet['comments_count'] ?? 0); ?> bình luận</span>
        </div>
    </article>

    <div class="content-panel detail-body">
        <?php if (!empty($baiViet['thumbnail'])): ?>
        <div class="detail-media">
            <img src="<?php echo htmlspecialchars($baiViet['thumbnail']); ?>"
                alt="<?php echo htmlspecialchars($baiViet['title'] ?? ''); ?>">
        </div>
        <?php endif; ?>

        <div class="detail-content">
            <?php echo $baiViet['content'] ?? '<p>Không có nội dung</p>'; ?>
        </div>

        <?php if (!empty($tags)): ?>
        <div class="detail-tags">
            <?php foreach ($tags as $tag): ?>
            <a
                href="<?php echo $basePath; ?>/tag/<?php echo htmlspecialchars($tag['slug']); ?>"><?php echo htmlspecialchars($tag['name']); ?></a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="share-bar">
            <strong>📤 Chia sẻ:</strong>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://localhost' . $currentUrl); ?>"
                target="_blank" rel="noopener noreferrer" class="share-btn share-facebook">👍 Facebook</a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://localhost' . $currentUrl); ?>&text=<?php echo urlencode($baiViet['title']); ?>"
                target="_blank" rel="noopener noreferrer" class="share-btn share-twitter">🐦 Twitter</a>
            <button class="share-btn share-copy" type="button"
                onclick="copyToClipboard('<?php echo addslashes($currentUrl); ?>')">📋 Sao chép liên kết</button>
        </div>

        <?php if ($userId): ?>
        <div class="action-bar">
            <form method="POST">
                <input type="hidden" name="action" value="like">
                <button type="submit"
                    class="action-button <?php echo $daThich ? 'active' : ''; ?>"><?php echo $daThich ? '❤️ Đã thích' : '🤍 Thích'; ?></button>
            </form>
            <form method="POST">
                <input type="hidden" name="action" value="save">
                <button type="submit"
                    class="action-button <?php echo $daLuu ? 'active' : ''; ?>"><?php echo $daLuu ? '💾 Đã lưu' : '💾 Lưu'; ?></button>
            </form>
        </div>
        <?php else: ?>
        <div class="login-notice">⚠️ Vui lòng <a href="<?php echo $basePath; ?>/dang-nhap">đăng nhập</a> để thích hoặc
            bình luận bài viết.</div>
        <?php endif; ?>

        <section class="comments-section">
            <div class="section-head">
                <h2 class="section-head__title">💬 Bình luận (<?php echo count($binhLuanList); ?>)</h2>
            </div>

            <?php if ($userId): ?>
            <div class="comment-form">
                <form method="POST">
                    <div class="form-group">
                        <label for="comment_content">Bình luận của bạn:</label>
                        <textarea id="comment_content" name="content" required
                            placeholder="Nhập bình luận..."></textarea>
                    </div>
                    <input type="hidden" name="action" value="comment">
                    <button type="submit" class="btn btn-primary">Gửi bình luận</button>
                </form>
            </div>
            <?php else: ?>
            <div class="login-notice">⚠️ Vui lòng <a href="<?php echo $basePath; ?>/dang-nhap">đăng nhập</a> để bình
                luận.</div>
            <?php endif; ?>

            <?php if (!empty($binhLuanList)): ?>
            <div class="comments-list comments-list--collapsed" id="commentsList">
                <?php foreach ($binhLuanList as $comment): ?>
                <div class="comment-item">
                    <div class="comment-author"><?php echo htmlspecialchars($comment['full_name'] ?? 'Ẩn danh'); ?>
                    </div>
                    <div class="comment-time">
                        <?php echo !empty($comment['created_at']) ? date('d/m/Y H:i', strtotime($comment['created_at'])) : 'Chưa xác định'; ?>
                    </div>
                    <div class="comment-content"><?php echo htmlspecialchars($comment['content']); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($binhLuanList) > 3): ?>
            <button class="load-more-comments-btn" id="loadMoreBtn" onclick="toggleComments()">
                📄 Xem thêm bình luận (<?php echo count($binhLuanList) - 3; ?>)
            </button>
            <?php endif; ?>
            <?php else: ?>
            <div class="no-comments">Chưa có bình luận nào. Hãy là người đầu tiên bình luận! 😊</div>
            <?php endif; ?>
        </section>

        <?php if (!empty($baiLienQuan)): ?>
        <section class="related-section">
            <div class="section-head">
                <h2 class="section-head__title">📚 Bài viết liên quan</h2>
            </div>
            <div class="related-articles">
                <?php foreach ($baiLienQuan as $related): ?>
                <a class="related-article"
                    href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($related['slug']); ?>">
                    <div class="related-article-img">
                        <?php if (!empty($related['thumbnail'])): ?>
                        <img src="<?php echo htmlspecialchars($related['thumbnail']); ?>"
                            alt="<?php echo htmlspecialchars($related['title']); ?>">
                        <?php else: ?>
                        <span>📰</span>
                        <?php endif; ?>
                    </div>
                    <div class="related-article-body">
                        <div class="related-article-title"><?php echo htmlspecialchars($related['title']); ?></div>
                        <div class="related-article-date">👁 <?php echo htmlspecialchars($related['views_count']); ?>
                            lượt xem</div>
                        <div class="related-article-date">📅
                            <?php echo !empty($related['created_at']) ? date('d/m/Y', strtotime($related['created_at'])) : 'N/A'; ?>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>

    <div class="navigation">
        <?php if (!empty($chuyenMuc) && !empty($chuyenMuc['slug'])): ?>
        <a href="<?php echo $basePath; ?>/danh-sach/<?php echo htmlspecialchars($chuyenMuc['slug']); ?>"
            class="nav-btn">
            <span class="label">← Quay lại</span>
            <span><?php echo htmlspecialchars($chuyenMuc['name'] ?? 'Chuyên mục'); ?></span>
        </a>
        <?php endif; ?>
        <a href="<?php echo $basePath; ?>/" class="nav-btn">
            <span class="label">← Quay lại</span>
            <span>Trang chủ</span>
        </a>
    </div>
</div>

<script>
function copyToClipboard(url) {
    navigator.clipboard.writeText(url).then(() => {
        alert('Đã sao chép liên kết!');
    });
}
</script>

<?php include dirname(__FILE__) . '/../layouts/footer.php'; ?>
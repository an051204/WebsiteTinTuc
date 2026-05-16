<?php
header('Content-Type: text/html; charset=utf-8');

$user = $user ?? [];
$tab = $tab ?? 'thong-tin';
$articles = $articles ?? [];
$comments = $comments ?? [];
$page = $page ?? 1;
$totalPages = $totalPages ?? 0;
$totalLikes = $totalLikes ?? 0;
$totalArticles = $totalArticles ?? 0;
$totalComments = $totalComments ?? 0;

$basePath = '/WebsiteTinTuc/public';
$pageTitle = 'Tài khoản cá nhân - ' . htmlspecialchars($user['full_name'] ?? '');
include dirname(__FILE__) . '/../layouts/header-start.php';
?>
<div class="container">
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <img id="avatarDisplay" src="<?php echo htmlspecialchars($user['avatar'] ?? '/WebsiteTinTuc/public/assests/default-avatar.png'); ?>" alt="Avatar" class="avatar-img" onerror="this.src='/WebsiteTinTuc/public/assests/default-avatar.png'">
                <button type="button" class="avatar-upload-btn" onclick="document.getElementById('avatarFileInput').click()">📷 Thay đổi</button>
            </div>

            <div class="profile-info">
                <h2><?php echo htmlspecialchars($user['full_name'] ?? ''); ?></h2>
                <p>📧 <?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
                <p>📅 Tham gia: <?php echo !empty($user['created_at']) ? date('d/m/Y', strtotime($user['created_at'])) : 'N/A'; ?></p>

                <div class="profile-stats">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo htmlspecialchars($totalLikes); ?></div>
                        <div class="stat-label">Bài thích</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?php echo htmlspecialchars($totalArticles); ?></div>
                        <div class="stat-label">Bài lưu</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?php echo htmlspecialchars($totalComments); ?></div>
                        <div class="stat-label">Bình luận</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-tabs">
            <a href="?tab=thong-tin" class="profile-tab <?php echo $tab === 'thong-tin' ? 'active' : ''; ?>">ℹ️ Thông tin</a>
            <a href="?tab=thich" class="profile-tab <?php echo $tab === 'thich' ? 'active' : ''; ?>">👍 Bài thích</a>
            <a href="?tab=luu" class="profile-tab <?php echo $tab === 'luu' ? 'active' : ''; ?>">💾 Bài lưu</a>
            <a href="?tab=binh-luan" class="profile-tab <?php echo $tab === 'binh-luan' ? 'active' : ''; ?>">💬 Bình luận</a>
        </div>

        <div class="profile-content">
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success">✓ <?php echo htmlspecialchars($_SESSION['success']); ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-error">✗ <?php echo htmlspecialchars($_SESSION['error']); ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if ($tab === 'thong-tin'): ?>
                <div class="section-head">
                    <h3 class="section-head__title">Cập nhật thông tin cá nhân</h3>
                </div>

                <div class="avatar-upload-form">
                    <h4>📷 Cập nhật Avatar</h4>
                    <p class="text-muted">Chọn file ảnh (JPEG, PNG, GIF, WebP) tối đa 5MB</p>
                    <input type="file" id="avatarFileInput" class="avatar-file-input" accept="image/jpeg,image/png,image/gif,image/webp">
                    <div id="avatarUploadStatus" class="flash-message" hidden></div>
                </div>

                <form method="POST" action="<?php echo $basePath; ?>/ca-nhan/cap-nhat">
                    <div class="form-group">
                        <label for="full_name">Tên đầy đủ:</label>
                        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary">💾 Cập nhật</button>
                </form>

            <?php elseif ($tab === 'thich'): ?>
                <div class="section-head">
                    <h3 class="section-head__title">Các bài viết bạn đã thích (<?php echo $totalLikes; ?>)</h3>
                </div>

                <?php if (!empty($articles)): ?>
                    <div class="article-grid">
                        <?php foreach ($articles as $article): ?>
                            <article class="article-card">
                                <div class="article-img">
                                    <?php if (!empty($article['thumbnail'])): ?>
                                        <img src="<?php echo htmlspecialchars($article['thumbnail']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                                    <?php else: ?>
                                        <span>📰</span>
                                    <?php endif; ?>
                                </div>
                                <div class="article-body">
                                    <h3>
                                        <a href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($article['slug']); ?>">
                                            <?php echo htmlspecialchars($article['title']); ?>
                                        </a>
                                    </h3>
                                    <div class="article-meta">
                                        <span>📅 <?php echo !empty($article['created_at']) ? date('d/m/Y', strtotime($article['created_at'])) : 'N/A'; ?></span>
                                        <span>👁 <?php echo htmlspecialchars($article['views_count']); ?></span>
                                        <span>💬 <?php echo htmlspecialchars($article['comments_count']); ?></span>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($totalPages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?tab=thich&page=1">« Đầu</a>
                                <a href="?tab=thich&page=<?php echo $page - 1; ?>">‹ Trước</a>
                            <?php else: ?>
                                <span class="disabled">« Đầu</span>
                                <span class="disabled">‹ Trước</span>
                            <?php endif; ?>

                            <?php
                            $start = max(1, $page - 2);
                            $end = min($totalPages, $page + 2);
                            for ($i = $start; $i <= $end; $i++) {
                                if ($i == $page) {
                                    echo '<span class="active">' . $i . '</span>';
                                } else {
                                    echo '<a href="?tab=thich&page=' . $i . '">' . $i . '</a>';
                                }
                            }
                            ?>

                            <?php if ($page < $totalPages): ?>
                                <a href="?tab=thich&page=<?php echo $page + 1; ?>">Sau ›</a>
                                <a href="?tab=thich&page=<?php echo $totalPages; ?>">Cuối »</a>
                            <?php else: ?>
                                <span class="disabled">Sau ›</span>
                                <span class="disabled">Cuối »</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="no-content">👍 Bạn chưa thích bài viết nào. Hãy khám phá các bài viết tuyệt vời!</div>
                <?php endif; ?>

            <?php elseif ($tab === 'luu'): ?>
                <div class="section-head">
                    <h3 class="section-head__title">Các bài viết bạn đã lưu (<?php echo $totalArticles; ?>)</h3>
                </div>

                <?php if (!empty($articles)): ?>
                    <div class="article-grid">
                        <?php foreach ($articles as $article): ?>
                            <article class="article-card">
                                <div class="article-img">
                                    <?php if (!empty($article['thumbnail'])): ?>
                                        <img src="<?php echo htmlspecialchars($article['thumbnail']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                                    <?php else: ?>
                                        <span>📰</span>
                                    <?php endif; ?>
                                </div>
                                <div class="article-body">
                                    <h3>
                                        <a href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($article['slug']); ?>">
                                            <?php echo htmlspecialchars($article['title']); ?>
                                        </a>
                                    </h3>
                                    <div class="article-meta">
                                        <span>📅 <?php echo !empty($article['created_at']) ? date('d/m/Y', strtotime($article['created_at'])) : 'N/A'; ?></span>
                                        <span>👁 <?php echo htmlspecialchars($article['views_count']); ?></span>
                                        <span>💬 <?php echo htmlspecialchars($article['comments_count']); ?></span>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($totalPages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?tab=luu&page=1">« Đầu</a>
                                <a href="?tab=luu&page=<?php echo $page - 1; ?>">‹ Trước</a>
                            <?php else: ?>
                                <span class="disabled">« Đầu</span>
                                <span class="disabled">‹ Trước</span>
                            <?php endif; ?>

                            <?php
                            $start = max(1, $page - 2);
                            $end = min($totalPages, $page + 2);
                            for ($i = $start; $i <= $end; $i++) {
                                if ($i == $page) {
                                    echo '<span class="active">' . $i . '</span>';
                                } else {
                                    echo '<a href="?tab=luu&page=' . $i . '">' . $i . '</a>';
                                }
                            }
                            ?>

                            <?php if ($page < $totalPages): ?>
                                <a href="?tab=luu&page=<?php echo $page + 1; ?>">Sau ›</a>
                                <a href="?tab=luu&page=<?php echo $totalPages; ?>">Cuối »</a>
                            <?php else: ?>
                                <span class="disabled">Sau ›</span>
                                <span class="disabled">Cuối »</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="no-content">💾 Bạn chưa lưu bài viết nào. Hãy lưu các bài viết yêu thích!</div>
                <?php endif; ?>

            <?php elseif ($tab === 'binh-luan'): ?>
                <div class="section-head">
                    <h3 class="section-head__title">Các bình luận bạn đã đăng (<?php echo $totalComments; ?>)</h3>
                </div>

                <?php if (!empty($comments)): ?>
                    <div class="comments-list">
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment-item">
                                <div class="comment-content"><?php echo htmlspecialchars($comment['content']); ?></div>
                                <div class="comment-meta">
                                    📅 <?php echo !empty($comment['created_at']) ? date('d/m/Y H:i', strtotime($comment['created_at'])) : 'N/A'; ?>
                                    <span class="comment-status <?php echo htmlspecialchars($comment['status']); ?>"><?php echo htmlspecialchars($comment['status']); ?></span>
                                    <a href="<?php echo $basePath; ?>/tin-tuc/<?php echo htmlspecialchars($comment['slug']); ?>#comments">Xem bài viết →</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($totalPages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?tab=binh-luan&page=1">« Đầu</a>
                                <a href="?tab=binh-luan&page=<?php echo $page - 1; ?>">‹ Trước</a>
                            <?php else: ?>
                                <span class="disabled">« Đầu</span>
                                <span class="disabled">‹ Trước</span>
                            <?php endif; ?>

                            <?php
                            $start = max(1, $page - 2);
                            $end = min($totalPages, $page + 2);
                            for ($i = $start; $i <= $end; $i++) {
                                if ($i == $page) {
                                    echo '<span class="active">' . $i . '</span>';
                                } else {
                                    echo '<a href="?tab=binh-luan&page=' . $i . '">' . $i . '</a>';
                                }
                            }
                            ?>

                            <?php if ($page < $totalPages): ?>
                                <a href="?tab=binh-luan&page=<?php echo $page + 1; ?>">Sau ›</a>
                                <a href="?tab=binh-luan&page=<?php echo $totalPages; ?>">Cuối »</a>
                            <?php else: ?>
                                <span class="disabled">Sau ›</span>
                                <span class="disabled">Cuối »</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="no-content">💬 Bạn chưa bình luận bài viết nào. Hãy chia sẻ ý kiến của bạn!</div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.getElementById('avatarFileInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const statusDiv = document.getElementById('avatarUploadStatus');
    statusDiv.hidden = false;
    statusDiv.className = 'flash-message alert-warning';
    statusDiv.textContent = '⏳ Đang tải lên...';

    const formData = new FormData();
    formData.append('avatar', file);

    fetch('<?php echo $basePath; ?>/ca-nhan/upload-avatar/', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            throw new Error(data.error || 'Lỗi tải lên');
        }

        statusDiv.className = 'flash-message alert-success';
        statusDiv.textContent = '✓ ' + data.message;
        document.getElementById('avatarDisplay').src = data.avatar_url;
        setTimeout(() => {
            statusDiv.hidden = true;
        }, 3000);
    })
    .catch(error => {
        statusDiv.className = 'flash-message alert-error';
        statusDiv.textContent = '✗ ' + (error.message || 'Lỗi tải lên');
    });

    e.target.value = '';
});
</script>

<?php include dirname(__FILE__) . '/../layouts/footer.php'; ?>

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
            <div class="profile-avatar" id="profileAvatarBlock" style="position:relative;">
                <img id="avatarDisplay" src="<?php echo htmlspecialchars($user['avatar'] ?? '/WebsiteTinTuc/public/assests/default-avatar.png'); ?>" alt="Avatar" class="avatar-img" onerror="this.src='/WebsiteTinTuc/public/assests/default-avatar.png'" style="width:120px;height:120px;border-radius:8px;object-fit:cover;">
                <div class="avatar-overlay" id="avatarOverlay" style="position:absolute;inset:auto 0 8px 0;display:flex;justify-content:center;gap:8px;">
                    <button type="button" id="avatarChooseBtn" class="avatar-upload-btn">📷 Chọn ảnh</button>
                    <button type="button" id="avatarSaveBtn" class="avatar-upload-btn" style="display:none;background:#27ae60;color:#fff;">✓ Lưu</button>
                    <button type="button" id="avatarCancelBtn" class="avatar-upload-btn" style="display:none;background:#e74c3c;color:#fff;">✕ Hủy</button>
                </div>
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
                    <div style="display:flex;gap:12px;align-items:center;">
                        <div style="width:72px;height:72px;border:1px dashed #ddd;border-radius:6px;display:flex;align-items:center;justify-content:center;overflow:hidden;background:#fafafa;">
                            <img id="avatarPreviewInline" src="<?php echo htmlspecialchars($user['avatar'] ?? '/WebsiteTinTuc/public/assests/default-avatar.png'); ?>" alt="Preview" style="width:100%;height:100%;object-fit:cover;" onerror="this.src='/WebsiteTinTuc/public/assests/default-avatar.png'">
                        </div>
                        <div>
                            <div style="display:flex;gap:8px;margin-bottom:8px;">
                                <button type="button" id="avatarChooseBtnInline" class="avatar-upload-btn">📁 Chọn ảnh</button>
                                <button type="button" id="avatarSaveBtnInline" class="avatar-upload-btn" style="display:none;background:#27ae60;color:#fff;">✓ Lưu</button>
                                <button type="button" id="avatarCancelBtnInline" class="avatar-upload-btn" style="display:none;background:#e74c3c;color:#fff;">✕ Hủy</button>
                            </div>
                            <div class="help-text">Bạn có thể chọn ảnh mới và nhấn Lưu để cập nhật avatar.</div>
                        </div>
                    </div>
                    <input type="file" id="avatarFileInput" class="avatar-file-input" accept="image/jpeg,image/png,image/gif,image/webp" style="display:none">
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
(() => {
    const fileInput = document.getElementById('avatarFileInput');
    const chooseBtn = document.getElementById('avatarChooseBtn');
    const chooseBtnInline = document.getElementById('avatarChooseBtnInline');
    const saveBtn = document.getElementById('avatarSaveBtn');
    const saveBtnInline = document.getElementById('avatarSaveBtnInline');
    const cancelBtn = document.getElementById('avatarCancelBtn');
    const cancelBtnInline = document.getElementById('avatarCancelBtnInline');
    const avatarImg = document.getElementById('avatarDisplay');
    const avatarPreviewInline = document.getElementById('avatarPreviewInline');
    const statusDiv = document.getElementById('avatarUploadStatus');

    let pendingFile = null;
    let originalSrc = avatarImg.src;

    function resetSelection() {
        pendingFile = null;
        if (fileInput) fileInput.value = '';
        if (saveBtn) saveBtn.style.display = 'none';
        if (saveBtnInline) saveBtnInline.style.display = 'none';
        if (cancelBtn) cancelBtn.style.display = 'none';
        if (cancelBtnInline) cancelBtnInline.style.display = 'none';
        if (avatarImg) avatarImg.src = originalSrc;
        if (avatarPreviewInline) avatarPreviewInline.src = originalSrc;
    }

    if (chooseBtn) {
        chooseBtn.addEventListener('click', function() { if (fileInput) fileInput.click(); });
    }
    if (chooseBtnInline) {
        chooseBtnInline.addEventListener('click', function() { if (fileInput) fileInput.click(); });
    }

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // validate on client
        const allowed = ['image/jpeg','image/png','image/gif','image/webp'];
        if (!allowed.includes(file.type)) {
            statusDiv.hidden = false;
            statusDiv.className = 'flash-message alert-error';
            statusDiv.textContent = '✗ Định dạng không hợp lệ';
            fileInput.value = '';
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            statusDiv.hidden = false;
            statusDiv.className = 'flash-message alert-error';
            statusDiv.textContent = '✗ Kích thước quá 5MB';
            fileInput.value = '';
            return;
        }

        // show preview
        const reader = new FileReader();
        reader.onload = function(ev) {
            if (avatarImg) avatarImg.src = ev.target.result;
            if (avatarPreviewInline) avatarPreviewInline.src = ev.target.result;
            originalSrc = originalSrc || (avatarImg ? avatarImg.src : ev.target.result);
            if (saveBtn) saveBtn.style.display = 'inline-block';
            if (saveBtnInline) saveBtnInline.style.display = 'inline-block';
            if (cancelBtn) cancelBtn.style.display = 'inline-block';
            if (cancelBtnInline) cancelBtnInline.style.display = 'inline-block';
            statusDiv.hidden = true;
        };
        reader.readAsDataURL(file);
        pendingFile = file;
    });

    if (cancelBtn) cancelBtn.addEventListener('click', function() { resetSelection(); });
    if (cancelBtnInline) cancelBtnInline.addEventListener('click', function() { resetSelection(); });

    function doSave() {
        if (!pendingFile) return;
        statusDiv.hidden = false;
        statusDiv.className = 'flash-message alert-warning';
        statusDiv.textContent = '⏳ Đang tải lên...';

        const formData = new FormData();
        formData.append('avatar', pendingFile);

        fetch('<?php echo $basePath; ?>/ca-nhan/upload-avatar/', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) throw new Error(data.error || 'Lỗi tải lên');

            statusDiv.className = 'flash-message alert-success';
            statusDiv.textContent = '✓ ' + data.message;
            if (avatarImg) avatarImg.src = data.avatar_url;
            originalSrc = data.avatar_url;
            setTimeout(() => { statusDiv.hidden = true; }, 2500);
            resetSelection();
        })
        .catch(err => {
            statusDiv.className = 'flash-message alert-error';
            statusDiv.textContent = '✗ ' + (err.message || 'Lỗi tải lên');
        });
    }

    if (saveBtn) saveBtn.addEventListener('click', doSave);
    if (saveBtnInline) saveBtnInline.addEventListener('click', doSave);

    // accessibility: allow keyboard to trigger choose
    if (chooseBtn) {
        chooseBtn.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                if (fileInput) fileInput.click();
            }
        });
    }
    if (chooseBtnInline) {
        chooseBtnInline.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                if (fileInput) fileInput.click();
            }
        });
    }
})();
</script>

<?php include dirname(__FILE__) . '/../layouts/footer.php'; ?>

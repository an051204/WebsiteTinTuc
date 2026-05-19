<?php
$article = $article ?? [];
$categories = $categories ?? [];
$tags = $tags ?? [];
$selectedTagIds = $selectedTagIds ?? [];
$isEdit = $isEdit ?? false;

header('Content-Type: text/html; charset=utf-8');
$basePath = '/WebsiteTinTuc/public';
$pageTitle = ($isEdit ? 'Chỉnh sửa' : 'Thêm') . ' Bài Viết - Admin';

include dirname(__FILE__) . '/../layouts/header-admin.php';
?>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/lang/summernote-vi-VN.min.js"></script>

<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    color: #333;
}

.container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
}

.form-container {
    background: white;
    padding: 30px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.form-container h1 {
    margin-top: 0;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

input[type="text"],
input[type="datetime-local"],
input[type="number"],
select,
textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    box-sizing: border-box;
}

input[type="text"]:focus,
input[type="datetime-local"]:focus,
input[type="number"]:focus,
select:focus,
textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 5px rgba(102, 126, 234, 0.2);
}

textarea {
    resize: vertical;
    min-height: 300px;
    font-family: monospace;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-row-3 {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 20px;
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

input[type="checkbox"] {
    width: auto;
    margin: 0;
    cursor: pointer;
}

.tag-selection {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    padding: 15px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.tag-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.tag-item input[type="checkbox"] {
    width: auto;
}

.tag-item label {
    margin-bottom: 0;
    cursor: pointer;
    font-weight: normal;
    color: #666;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #ddd;
}

.btn {
    padding: 12px 30px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
}

.btn-submit {
    background-color: #667eea;
    color: white;
}

.btn-submit:hover {
    background-color: #5568d3;
}

.btn-cancel {
    background-color: #ccc;
    color: #333;
    text-decoration: none;
    display: inline-block;
}

.btn-cancel:hover {
    background-color: #bbb;
}

.message {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
}

.message-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.help-text {
    font-size: 12px;
    color: #999;
    margin-top: 5px;
}

.info-box {
    background-color: #e3f2fd;
    border-left: 4px solid #2196f3;
    padding: 15px;
    margin-top: 20px;
    border-radius: 3px;
    font-size: 13px;
}

.summernote {
    max-height: 400px;
}

.note-editor.note-frame {
    border: 1px solid #ddd;
    border-radius: 5px;
}

/* Fix Summernote modal z-index and dialog issues */
.note-dialog {
    z-index: 99999 !important;
}

.note-modal-backdrop {
    z-index: 99998 !important;
    background: rgba(0, 0, 0, 0.5);
    pointer-events: auto !important;
    cursor: pointer;
}

/* Figure / figcaption styles for inline image captions in editor */
.editor-figure {
    display: block;
    margin: 12px 0;
    text-align: center;
}

.editor-figure img {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 0 auto;
}

.editor-figcaption {
    display: block;
    width: 100%;
    text-align: center;
    font-size: 13px;
    color: #666;
    margin-top: 6px;
    outline: none;
    min-height: 18px;
}

.editor-figcaption:empty:before {
    content: attr(data-placeholder);
    color: #999;
}

/* Allow interaction with modals */
.note-modal-backdrop.note-processing {
    z-index: 99998 !important;
}

.empty-tags {
    padding: 15px;
    text-align: center;
    color: #999;
}
</style>

<div class="container">
    <?php if (isset($_SESSION['error'])): ?>
    <div class="message message-error">
        <?php echo htmlspecialchars($_SESSION['error']); ?>
    </div>
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="form-container">
        <h1><?php echo $isEdit ? 'Chỉnh sửa Bài Viết' : 'Thêm Bài Viết Mới'; ?></h1>

        <form method="POST" id="articleForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="title">Tiêu đề *</label>
                    <input type="text" id="title" name="title" required
                        value="<?php echo htmlspecialchars($article['title'] ?? ''); ?>"
                        placeholder="Nhập tiêu đề bài viết">
                    <div class="help-text">Tiêu đề phải ít nhất 5 ký tự</div>
                </div>

                <div class="form-group">
                    <label for="category_id">Chuyên mục *</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">-- Chọn chuyên mục --</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"
                            <?php echo (isset($article['category_id']) && $article['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="summary">Tóm tắt</label>
                <textarea id="summary" name="summary" rows="3"
                    placeholder="Viết tóm tắt ngắn gọn cho bài viết (không bắt buộc)"><?php echo htmlspecialchars($article['summary'] ?? ''); ?></textarea>
                <div class="help-text">Sẽ hiển thị trong danh sách bài viết</div>
            </div>

            <div class="form-group">
                <label for="content">Nội dung *</label>
                <textarea id="content" name="content"
                    class="summernote"><?php echo htmlspecialchars($article['content'] ?? ''); ?></textarea>
                <div class="help-text">Sử dụng thanh công cụ bên dưới để định dạng nội dung</div>
            </div>

            <div class="form-group">
                <label for="thumbnail_file">🖼️ Hình đại diện</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <input type="file" id="thumbnail_file" name="thumbnail_file" accept="image/*"
                            style="width: 100%;">
                        <div class="help-text" style="margin-top: 8px;">Chọn ảnh từ máy tính (JPG, PNG, GIF, WebP)</div>
                        <button type="button" id="upload_thumbnail_btn" class="btn btn-submit"
                            style="margin-top: 10px; width: 100%;">↑ Tải lên</button>
                    </div>
                    <div>
                        <div id="thumbnail_preview"
                            style="width: 100%; height: 200px; border: 2px dashed #ddd; border-radius: 5px; display: flex; align-items: center; justify-content: center; background-color: #fafafa; overflow: hidden;">
                            <?php if (isset($article['thumbnail']) && $article['thumbnail']): ?>
                            <img src="<?php echo htmlspecialchars($article['thumbnail']); ?>" alt="Preview"
                                style="max-width: 100%; max-height: 100%; object-fit: cover;">
                            <?php else: ?>
                            <span style="color: #999; text-align: center; padding: 20px;">📷 Xem trước hình ảnh</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="thumbnail" name="thumbnail"
                    value="<?php echo htmlspecialchars($article['thumbnail'] ?? ''); ?>">
            </div>

            <?php if (!empty($tags)): ?>
            <div class="form-group">
                <label>Gắn Thẻ Tags</label>
                <div class="tag-selection">
                    <?php foreach ($tags as $tag): ?>
                    <div class="tag-item">
                        <input type="checkbox" id="tag_<?php echo $tag['id']; ?>" name="tag_ids[]"
                            value="<?php echo $tag['id']; ?>"
                            <?php echo in_array($tag['id'], $selectedTagIds) ? 'checked' : ''; ?>>
                        <label for="tag_<?php echo $tag['id']; ?>"><?php echo htmlspecialchars($tag['name']); ?></label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="form-group">
                <label>Gắn Thẻ Tags</label>
                <div class="empty-tags">
                    <p>Chưa có thẻ tag nào. Vui lòng tạo tag trước.</p>
                </div>
            </div>
            <?php endif; ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="status">Trạng thái *</label>
                    <select id="status" name="status" required>
                        <option value="draft"
                            <?php echo (isset($article['status']) && $article['status'] === 'draft') ? 'selected' : ''; ?>>
                            Nháp (Chỉ bạn thấy)
                        </option>
                        <option value="pending"
                            <?php echo (isset($article['status']) && $article['status'] === 'pending') ? 'selected' : ''; ?>>
                            Chờ duyệt (Gửi cho Admin)
                        </option>
                        <option value="published"
                            <?php echo (isset($article['status']) && $article['status'] === 'published') ? 'selected' : ''; ?>>
                            Đã đăng (Công khai)
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="published_at">⏰ Thời gian đăng (Để trống = Đăng ngay)</label>
                    <input type="datetime-local" id="published_at" name="published_at"
                        value="<?php echo (isset($article['published_at']) && $article['published_at']) ? substr($article['published_at'], 0, 16) : ''; ?>">
                    <small style="color: #666; display: block; margin-top: 5px;">
                        • Nếu để trống: bài sẽ được đăng lên ngay lập tức<br>
                        • Nếu chọn thời gian trong tương lai: bài sẽ chờ tới giờ mới đăng
                    </small>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="checkbox-group" style="padding-top: 8px;">
                        <input type="checkbox" id="is_featured" name="is_featured"
                            <?php echo (isset($article['is_featured']) && $article['is_featured']) ? 'checked' : ''; ?>>
                        <label for="is_featured" style="margin-bottom: 0;">⭐ Đánh dấu là Nổi bật</label>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-submit">
                    <?php echo $isEdit ? '💾 Cập nhật' : '✚ Tạo Bài Viết'; ?>
                </button>
                <a href="<?php echo $basePath; ?>/quan-tri/bai-viet/" class="btn btn-cancel">← Hủy</a>
            </div>
        </form>
    </div>

    <?php if ($isEdit): ?>
    <div class="info-box">
        <strong>📊 Thông tin bài viết:</strong><br>
        ID: <?php echo htmlspecialchars($article['id'] ?? 'N/A'); ?> |
        Tạo: <?php echo htmlspecialchars($article['created_at'] ?? 'N/A'); ?> |
        Sửa: <?php echo htmlspecialchars($article['updated_at'] ?? 'N/A'); ?> |
        Lượt xem: <?php echo htmlspecialchars($article['views_count'] ?? 0); ?> |
        Bình luận: <?php echo htmlspecialchars($article['comments_count'] ?? 0); ?>
    </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    var thumbnailUploading = false;

    $('#content').summernote({
        lang: 'vi-VN',
        height: 400,
        dialogsInBody: true,
        dialogsFade: true,
        minHeight: 300,
        maxHeight: 500,
        toolbar: [
            ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        callbacks: {
            onImageUpload: function(files) {
                uploadImageToSummernote(files[0]);
            },
            onError: function(e) {
                console.error('Summernote error:', e);
                alert('Lỗi: ' + e.message);
                // Close stuck modals
                $('.note-dialog').remove();
                $('.note-modal-backdrop').remove();
            }
        }
    });

    // Handle ESC key to close stuck modals
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' || e.keyCode === 27) {
            var dialogs = $('.note-dialog');
            var backdrops = $('.note-modal-backdrop');
            if (dialogs.length > 0 || backdrops.length > 0) {
                dialogs.remove();
                backdrops.remove();
                e.preventDefault();
            }
        }
    });

    // Allow clicking on backdrop to close stuck modals
    $(document).on('click', '.note-modal-backdrop', function() {
        $('.note-dialog').remove();
        $('.note-modal-backdrop').remove();
    });

    function getUploadErrorMessage(xhr, fallbackText) {
        if (xhr && xhr.responseText) {
            try {
                var obj = JSON.parse(xhr.responseText);
                if (obj && obj.error) {
                    return obj.error;
                }
            } catch (e) {
                return xhr.responseText;
            }
        }

        if (xhr && xhr.status === 413) {
            return 'File quá lớn (vượt giới hạn server)';
        }

        return fallbackText || 'Không thể tải ảnh lên';
    }

    function uploadImageToSummernote(file) {
        if (!file.type.startsWith('image/')) {
            alert('Vui lòng chọn file ảnh');
            return;
        }

        var data = new FormData();
        data.append('file', file);

        $.ajax({
            url: '<?php echo $basePath; ?>/quan-tri/upload/',
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    var response_obj = typeof response === 'string' ? JSON.parse(response) :
                        response;
                    if (response_obj.success && response_obj.url) {
                        // Insert a figure with editable figcaption so the editor can add a caption
                        var figureHtml = '<figure class="editor-figure">' +
                            '<img src="' + response_obj.url + '" alt="">' +
                            '<figcaption contenteditable="true" class="editor-figcaption" data-placeholder="Nhập chú thích..."></figcaption>' +
                            '</figure>';

                        try {
                            $('#content').summernote('pasteHTML', figureHtml);

                            // Focus the newly inserted figcaption and place caret at the end
                            setTimeout(function() {
                                var $editable = $('.note-editable');
                                var $figcap = $editable.find('figure.editor-figure').last()
                                    .find('.editor-figcaption');
                                if ($figcap.length) {
                                    var el = $figcap.get(0);
                                    el.focus();
                                    try {
                                        var range = document.createRange();
                                        range.selectNodeContents(el);
                                        range.collapse(false);
                                        var sel = window.getSelection();
                                        sel.removeAllRanges();
                                        sel.addRange(range);
                                    } catch (e) {
                                        // ignore selection errors
                                    }
                                }
                            }, 120);

                        } catch (e) {
                            // fallback to default insertImage if pasteHTML fails
                            console.warn('pasteHTML failed, falling back to insertImage', e);
                            $('#content').summernote('insertImage', response_obj.url, '');
                        }

                        // Immediately close any open dialogs after successful insert
                        setTimeout(function() {
                            $('.note-dialog').remove();
                            $('.note-modal-backdrop').remove();
                        }, 150);
                    } else {
                        alert('Lỗi: ' + (response_obj.error || 'Không thể tải ảnh'));
                        // Close modal on error
                        $('.note-dialog').remove();
                        $('.note-modal-backdrop').remove();
                    }
                } catch (e) {
                    console.error('Parse error:', e, 'Response:', response);
                    alert('Lỗi: ' + e.message);
                    // Close modal on error
                    $('.note-dialog').remove();
                    $('.note-modal-backdrop').remove();
                }
            },
            error: function(xhr, status, error) {
                console.error('Upload error:', status, error, xhr.responseText);
                alert('❌ ' + getUploadErrorMessage(xhr, error));
                // Close modal on error
                $('.note-dialog').remove();
                $('.note-modal-backdrop').remove();
            },
            complete: function() {
                // Ensure modals are closed after upload attempt
                setTimeout(function() {
                    var dialogs = $('.note-dialog');
                    var backdrops = $('.note-modal-backdrop');
                    if (dialogs.length > 0 || backdrops.length > 0) {
                        dialogs.remove();
                        backdrops.remove();
                    }
                }, 500);
            }
        });
    }

    function uploadThumbnailFile(file) {
        if (!file.type.startsWith('image/')) {
            alert('Vui lòng chọn file ảnh');
            return;
        }

        var btn = $('#upload_thumbnail_btn');
        var originalText = btn.text();
        thumbnailUploading = true;
        btn.prop('disabled', true).text('⏳ Đang tải lên...');

        var data = new FormData();
        data.append('file', file);

        $.ajax({
            url: '<?php echo $basePath; ?>/quan-tri/upload/',
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    var response_obj = typeof response === 'string' ? JSON.parse(response) :
                        response;
                    if (response_obj.success && response_obj.url) {
                        $('#thumbnail').val(response_obj.url);
                        $('#thumbnail_preview').html('<img src="' + response_obj.url +
                            '" style="max-width: 100%; max-height: 100%; object-fit: cover;">');
                    } else {
                        alert('Lỗi: ' + (response_obj.error || 'Không thể tải ảnh'));
                    }
                } catch (e) {
                    console.error('Parse error:', e, 'Response:', response);
                    alert('Lỗi: ' + e.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Upload error:', status, error, xhr.responseText);
                alert('❌ ' + getUploadErrorMessage(xhr, error));
            },
            complete: function() {
                thumbnailUploading = false;
                btn.prop('disabled', false).text(originalText);
            }
        });
    }

    $('#thumbnail_file').on('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            if (!file.type.startsWith('image/')) {
                alert('Vui lòng chọn file ảnh');
                $(this).val('');
                return;
            }

            var reader = new FileReader();
            reader.onload = function(event) {
                $('#thumbnail_preview').html('<img src="' + event.target.result +
                    '" style="max-width: 100%; max-height: 100%; object-fit: cover;">');
            };
            reader.readAsDataURL(file);

            uploadThumbnailFile(file);
        }
    });

    $('#upload_thumbnail_btn').on('click', function() {
        var file = $('#thumbnail_file')[0].files[0];
        if (!file) {
            alert('Vui lòng chọn ảnh trước');
            return;
        }

        uploadThumbnailFile(file);
    });

    $('#articleForm').on('submit', function(e) {
        var title = $('#title').val().trim();
        var categoryId = $('#category_id').val();
        var content = $('#content').summernote('code').trim();
        var thumbnailFile = $('#thumbnail_file')[0].files[0];
        var thumbnailUrl = $('#thumbnail').val().trim();
        var status = $('#status').val();

        var isValid = true;
        var errorMsg = '';

        if (!title) {
            errorMsg += '- Tiêu đề không được để trống\n';
            isValid = false;
        }

        if (!categoryId) {
            errorMsg += '- Chuyên mục không được để trống\n';
            isValid = false;
        }

        if (!content || content.length < 20) {
            errorMsg += '- Nội dung phải ít nhất 20 ký tự\n';
            isValid = false;
            $('#content').summernote('focus');
        }

        if (thumbnailUploading) {
            e.preventDefault();
            alert('Ảnh đại diện đang được tải lên, vui lòng chờ hoàn tất rồi hãy đăng bài.');
            return false;
        }

        if (thumbnailFile && !thumbnailUrl) {
            e.preventDefault();
            alert(
                'Ảnh đại diện chưa được tải lên. Vui lòng đợi hoặc bấm "Tải lên" trước khi đăng bài.'
            );
            return false;
        }

        if (!isValid) {
            e.preventDefault();
            alert('❌ Vui lòng sửa các lỗi sau:\n\n' + errorMsg);
            return false;
        }

        var publishedAt = $('#published_at').val();
        if (publishedAt) {
            var selectedTime = new Date(publishedAt);
            var now = new Date();
            if (selectedTime < now) {
                e.preventDefault();
                alert('❌ Thời gian đăng phải sau thời gian hiện tại');
                return false;
            }
        }
    });

    // Mutation observer + focus handler to clean up stray backdrops and ensure dialog focus
    try {
        var modalObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(m) {
                m.addedNodes.forEach(function(node) {
                    if (node && node.nodeType === 1 && node.classList && node.classList
                        .contains('note-modal-backdrop')) {
                        // if no dialog present shortly after backdrop added, remove it
                        setTimeout(function() {
                            if ($('.note-dialog').length === 0) {
                                $('.note-modal-backdrop').remove();
                            }
                        }, 120);
                    }
                });
            });
        });

        modalObserver.observe(document.body, {
            childList: true,
            subtree: false
        });
    } catch (e) {
        console.warn('Modal observer not available:', e);
    }

    // When the window regains focus (after native file picker), clear any stray backdrops
    $(window).on('focus', function() {
        setTimeout(function() {
            if ($('.note-modal-backdrop').length > 0 && $('.note-dialog').length === 0) {
                $('.note-modal-backdrop').remove();
            }
            // ensure existing dialogs are focused and above header
            $('.note-dialog').each(function() {
                $(this).css('z-index', 100001);
                try {
                    $(this).attr('tabindex', -1).focus();
                } catch (e) {}
            });
        }, 100);
    });
});
</script>

<?php include dirname(__FILE__) . '/../layouts/footer-admin.php'; ?>
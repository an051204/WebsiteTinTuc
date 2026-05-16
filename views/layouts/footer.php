    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-grid">
                <div class="footer-column">
                    <h4>Về Website</h4>
                    <p>Website Tin Tức cung cấp thông tin và tin tức mới nhất, cập nhật liên tục cho bạn.</p>
                </div>

                <div class="footer-column">
                    <h4>Danh Mục</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo $basePath; ?>/danh-sach/">Tất Cả Danh Mục</a></li>
                        <li><a href="<?php echo $basePath; ?>/">Trang Chủ</a></li>
                        <li><a href="<?php echo $basePath; ?>/ca-nhan/">Tài Khoản</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h4>Hỗ Trợ</h4>
                    <ul class="footer-links">
                        <li><a href="#">Liên Hệ</a></li>
                        <li><a href="#">Chính Sách Bảo Mật</a></li>
                        <li><a href="#">Điều Khoản Dịch Vụ</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h4>Theo Dõi</h4>
                    <ul class="footer-links">
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Twitter</a></li>
                        <li><a href="#">Instagram</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2026 Website Tin Tức. Tất cả quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <script>
    function toggleComments() {
        const commentsList = document.getElementById('commentsList');
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        
        if (commentsList.classList.contains('comments-list--collapsed')) {
            commentsList.classList.remove('comments-list--collapsed');
            loadMoreBtn.textContent = '📄 Ẩn bình luận';
            loadMoreBtn.classList.add('hide-after');
            setTimeout(() => {
                loadMoreBtn.classList.add('hide');
            }, 300);
        } else {
            commentsList.classList.add('comments-list--collapsed');
            loadMoreBtn.classList.remove('hide');
            loadMoreBtn.classList.remove('hide-after');
        }
    }

    function toggleUserDropdown(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('userDropdown');
        if (dropdown) {
            dropdown.classList.toggle('active');
        }
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('userDropdown');
        if (dropdown && !dropdown.contains(event.target)) {
            dropdown.classList.remove('active');
        }
    });
    </script>

</body>

</html>

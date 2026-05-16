-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 15, 2026 lúc 05:21 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `websitetintuc`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `advertisements`
--

CREATE TABLE `advertisements` (
  `id` int(11) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `link_url` varchar(255) NOT NULL,
  `position` enum('home_top','home_middle','sidebar','article_bottom') NOT NULL,
  `clicks_count` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `advertisements`
--

INSERT INTO `advertisements` (`id`, `title`, `image_url`, `link_url`, `position`, `clicks_count`, `status`, `start_date`, `end_date`, `created_at`) VALUES
(1, 'Banner Test - Trang chu', 'https://picsum.photos/728/90', 'https://example.com', 'home_middle', 0, 'active', '2026-01-01', '2027-12-31', '2026-05-12 10:40:19'),
(2, 'Banner Sidebar', 'https://picsum.photos/728/90', 'https://example.com', 'sidebar', 0, 'active', '2026-01-01', '2027-12-31', '2026-05-12 10:40:19'),
(3, 'Khuyen mai Hot 50%', 'https://picsum.photos/728/90', 'https://example.com', 'article_bottom', 0, 'active', NULL, NULL, '2026-05-12 10:49:07'),
(4, 'Test Upload Test Upload Anh', '/WebsiteTinTuc/public/uploads/ad_6a042404ca55e.png', 'https://example.com/test', 'home_top', 0, 'active', NULL, NULL, '2026-05-12 11:10:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `articles`
--

CREATE TABLE `articles` (
  `id` bigint(20) NOT NULL,
  `author_id` bigint(20) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `summary` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `status` enum('draft','pending','published','rejected') DEFAULT 'draft',
  `is_featured` tinyint(1) DEFAULT 0,
  `views_count` int(11) DEFAULT 0,
  `shares_count` int(11) DEFAULT 0,
  `comments_count` int(11) DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `articles`
--

INSERT INTO `articles` (`id`, `author_id`, `category_id`, `title`, `slug`, `summary`, `content`, `thumbnail`, `meta_title`, `meta_description`, `status`, `is_featured`, `views_count`, `shares_count`, `comments_count`, `published_at`, `created_at`, `updated_at`) VALUES
(3, 9, 2, 'Chỉ số lạm phát tháng 5 ghi nhận mức tăng 2.3% so với cùng kỳ năm ngoái', 'chi-so-lam-phat-thang-5', NULL, '<p>Đây là nội dung chi tiết của bài viết. Bài viết chứa các thông tin quan trọng và hữu ích cho độc giả.</p><p>Chúng tôi cam kết cung cấp thông tin chính xác, cập nhật và có giá trị để phục vụ nhu cầu của bạn.</p><p>Hãy tiếp tục theo dõi để cập nhật thông tin mới nhất!</p>', NULL, NULL, NULL, 'published', 0, 429, 0, 3, '2026-05-11 18:53:05', '2026-05-11 16:53:05', '2026-05-12 11:30:49'),
(4, 10, 2, 'Các ngân hàng lớn công bố kết quả kinh doanh quý II vượt kỳ vọng', 'cac-ngan-hang-lon-cong-bo-ket-qua-q2', NULL, '<p>Đây là nội dung chi tiết của bài viết. Bài viết chứa các thông tin quan trọng và hữu ích cho độc giả.</p><p>Chúng tôi cam kết cung cấp thông tin chính xác, cập nhật và có giá trị để phục vụ nhu cầu của bạn.</p><p>Hãy tiếp tục theo dõi để cập nhật thông tin mới nhất!</p>', NULL, NULL, NULL, 'published', 0, 467, 0, 2, '2026-05-11 18:53:05', '2026-05-11 16:53:05', '2026-05-12 11:31:28'),
(5, 10, 6, 'Thị trường bất động sản khôi phục sau 6 tháng căng thẳng', 'thi-truong-bat-dong-san-khoi-phuc', NULL, '<p>Đây là nội dung chi tiết của bài viết. Bài viết chứa các thông tin quan trọng và hữu ích cho độc giả.</p><p>Chúng tôi cam kết cung cấp thông tin chính xác, cập nhật và có giá trị để phục vụ nhu cầu của bạn.</p><p>Hãy tiếp tục theo dõi để cập nhật thông tin mới nhất!</p>', NULL, NULL, NULL, 'published', 0, 96, 0, 5, '2026-05-11 18:53:05', '2026-05-11 16:53:05', '2026-05-11 16:53:05'),
(11, 9, 7, 'Đội bóng quốc gia giành chiến thắng 3-1 trong trận giao hữu tại nước ngoài', 'doi-bong-quoc-gia-chien-thang-3-1', NULL, '<p>Đây là nội dung chi tiết của bài viết. Bài viết chứa các thông tin quan trọng và hữu ích cho độc giả.</p><p>Chúng tôi cam kết cung cấp thông tin chính xác, cập nhật và có giá trị để phục vụ nhu cầu của bạn.</p><p>Hãy tiếp tục theo dõi để cập nhật thông tin mới nhất!</p>', NULL, NULL, NULL, 'published', 0, 343, 0, 5, '2026-05-11 18:53:05', '2026-05-11 16:53:05', '2026-05-11 16:53:05'),
(15, 10, 2, '[NHÁP] Xu hướng mới trong ngành công nghiệp tài chính', 'xu-huong-moi-tai-chinh', NULL, '<p>Đây là nội dung chi tiết của bài viết. Bài viết chứa các thông tin quan trọng và hữu ích cho độc giả.</p><p>Chúng tôi cam kết cung cấp thông tin chính xác, cập nhật và có giá trị để phục vụ nhu cầu của bạn.</p><p>Hãy tiếp tục theo dõi để cập nhật thông tin mới nhất!</p>', NULL, NULL, NULL, 'draft', 0, 0, 0, 0, NULL, '2026-05-11 16:53:05', '2026-05-11 16:53:05'),
(19, 9, 1, 'Test Cron Article', 'cron-test-6a020e3a51853', NULL, 'This article will auto-publish in 2 minutes', NULL, NULL, NULL, 'published', 0, 2, 0, 0, '2026-05-12 00:15:30', '2026-05-11 17:13:30', '2026-05-14 17:44:38'),
(20, 9, 1, 'Past Cron Test', 'past-cron-6a020e5703735', NULL, 'Published 5 minutes ago', NULL, NULL, NULL, 'published', 0, 0, 0, 0, '2026-05-12 00:08:59', '2026-05-11 17:13:59', '2026-05-11 17:14:07'),
(23, 9, 13, 'Tét tét tét tét', 'tt-tt-tt-tt', 'Tét', '<p>Tét tét tét tét tét tét</p>', '', NULL, NULL, 'published', 1, 6, 0, 1, NULL, '2026-05-12 03:01:42', '2026-05-15 04:10:50'),
(24, 9, 16, 'TÉT ẢNH', 'tt-nh', 'QWQEQE', '<p>ÁDASDSADSADASDASDASDSADs<img src=\"/WebsiteTinTuc/public/uploads/article_1778578886_a34a90a5.png\" style=\"width: 871px;\" data-filename=\"alt text\"></p>', '/WebsiteTinTuc/public/uploads/article_1778578893_9b912c16.png', NULL, NULL, 'published', 0, 3, 0, 0, NULL, '2026-05-12 09:41:39', '2026-05-14 17:39:54'),
(25, 11, 2, 'Bài viết test từ user thường', 'bi-vit-test-t-user-thng', '', 'Đây là bài viết test từ một user thường trên website tin tức. Bài viết này được tạo để kiểm tra tính năng cho phép user đăng bài viết mới với trạng thái chờ duyệt.', '', NULL, NULL, 'pending', 0, 0, 0, 0, NULL, '2026-05-13 07:42:28', '2026-05-13 07:42:28'),
(26, 10, 16, 'QWRQWRWQRQW', 'qwrqwrwqrqw', 'ADSADASD', '<p>SAFSAFASFSFSAFASFS<img src=\"/WebsiteTinTuc/public/uploads/article_1778660642_ea669d94.png\" style=\"width: 878.4px;\" data-filename=\"alt text\"></p>', '', NULL, NULL, 'published', 0, 2, 0, 0, NULL, '2026-05-13 08:24:19', '2026-05-14 17:44:18'),
(27, 11, 16, 'EWEWEW', 'ewewew', 'EWEWEWEWEW', '<p>asdasdsadasdsaddddddddddddddddd<img src=\"/WebsiteTinTuc/public/uploads/article_1778680405_a67b949c.png\" style=\"width: 871px;\" data-filename=\"alt text\"><br></p>', '', NULL, NULL, 'published', 0, 3, 0, 0, NULL, '2026-05-13 13:53:49', '2026-05-14 17:44:19'),
(28, 11, 16, 'QWQWQWQWQ', 'qwqwqwqwq', 'ƯQWQWQWQWQW', '<p>ƯQWQWQWQWQWQWQWQWQWQ<img src=\"/WebsiteTinTuc/public/uploads/article_1778680830_4955424b.png\" style=\"width: 871px;\" data-filename=\"alt text\"></p>', '', NULL, NULL, 'pending', 0, 0, 0, 0, NULL, '2026-05-13 14:00:36', '2026-05-13 14:00:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `article_likes`
--

CREATE TABLE `article_likes` (
  `user_id` bigint(20) NOT NULL,
  `article_id` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `article_likes`
--

INSERT INTO `article_likes` (`user_id`, `article_id`, `created_at`) VALUES
(9, 3, '2026-05-11 16:53:05'),
(9, 4, '2026-05-11 16:53:05'),
(9, 5, '2026-05-11 16:53:05'),
(9, 11, '2026-05-11 16:53:05'),
(10, 3, '2026-05-11 16:53:05'),
(10, 5, '2026-05-11 16:53:05'),
(10, 11, '2026-05-11 16:53:05'),
(11, 3, '2026-05-11 16:53:05'),
(11, 4, '2026-05-11 16:53:05'),
(11, 5, '2026-05-11 16:53:05'),
(11, 11, '2026-05-11 16:53:05'),
(11, 23, '2026-05-13 08:01:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `article_saves`
--

CREATE TABLE `article_saves` (
  `user_id` bigint(20) NOT NULL,
  `article_id` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `article_saves`
--

INSERT INTO `article_saves` (`user_id`, `article_id`, `created_at`) VALUES
(10, 4, '2026-05-11 16:53:05'),
(10, 5, '2026-05-11 16:53:05'),
(11, 3, '2026-05-11 16:53:05'),
(11, 4, '2026-05-11 16:53:05'),
(11, 5, '2026-05-11 16:53:05'),
(11, 11, '2026-05-11 16:53:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `article_tag`
--

CREATE TABLE `article_tag` (
  `article_id` bigint(20) NOT NULL,
  `tag_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `article_tag`
--

INSERT INTO `article_tag` (`article_id`, `tag_id`) VALUES
(3, 6),
(3, 9),
(4, 7),
(4, 8),
(5, 3),
(5, 6),
(5, 9),
(11, 7),
(15, 2),
(15, 3),
(23, 3),
(26, 3),
(26, 11),
(27, 3),
(27, 11),
(28, 3),
(28, 11);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bad_words`
--

CREATE TABLE `bad_words` (
  `id` int(11) NOT NULL,
  `word` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bad_words`
--

INSERT INTO `bad_words` (`id`, `word`) VALUES
(2, 'cac'),
(1, 'cc'),
(4, 'du ma'),
(3, 'lon');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` enum('active','hidden') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `name`, `slug`, `description`, `sort_order`, `status`, `created_at`) VALUES
(1, NULL, 'Thể Thao', 'the-thao', NULL, 11, 'active', '2026-05-09 13:57:35'),
(2, NULL, 'Công Nghệ', 'cong-nghe', NULL, 12, 'active', '2026-05-09 13:57:35'),
(6, 1, 'Điện Tử', 'in-t', '', 13, 'active', '2026-05-11 10:32:48'),
(7, NULL, 'Chính Trị', 'chnh-tr', '', 3, 'active', '2026-05-11 12:36:33'),
(8, NULL, 'Thế Giới', 'the-gioi', NULL, 9, 'active', '2026-05-11 16:53:05'),
(9, NULL, 'Kinh Tế', 'kinh-te', NULL, 6, 'active', '2026-05-11 16:53:05'),
(10, NULL, 'Pháp Luật', 'phap-luat', NULL, 8, 'active', '2026-05-11 16:53:05'),
(11, NULL, 'Giáo Dục', 'giao-duc', NULL, 5, 'active', '2026-05-11 16:53:05'),
(12, NULL, 'Y Tế', 'y-te', NULL, 10, 'active', '2026-05-11 16:53:05'),
(13, NULL, 'Bất Động Sản', 'bat-dong-san', NULL, 1, 'active', '2026-05-11 16:53:05'),
(14, NULL, 'Bóng Đá', 'bong-da', NULL, 2, 'active', '2026-05-11 16:53:05'),
(15, NULL, 'Giải Trí', 'giai-tri', NULL, 4, 'active', '2026-05-11 16:53:05'),
(16, NULL, 'L Binh TV', 'l-binh-tv', '', 0, 'active', '2026-05-12 03:11:17'),
(17, 16, 'L Hieu TV', 'l-hieu-tv', '', 7, 'active', '2026-05-12 03:12:16');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) NOT NULL,
  `article_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `parent_id` bigint(20) DEFAULT NULL,
  `content` text NOT NULL,
  `status` enum('approved','pending','hidden','spam') DEFAULT 'approved',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `comments`
--

INSERT INTO `comments` (`id`, `article_id`, `user_id`, `parent_id`, `content`, `status`, `created_at`, `updated_at`) VALUES
(9, 3, 11, NULL, 'Bài viết rất hữu ích, cảm ơn tác giả!', 'approved', '2026-05-11 16:53:05', '2026-05-11 16:53:05'),
(10, 3, 11, NULL, 'Mình hoàn toàn đồng ý với quan điểm này.', 'approved', '2026-05-11 16:53:05', '2026-05-11 16:53:05'),
(11, 3, 11, NULL, 'Bài viết rất hữu ích, cảm ơn tác giả!', 'approved', '2026-05-11 16:53:05', '2026-05-11 16:53:05'),
(12, 4, 11, NULL, 'Mình hoàn toàn đồng ý với quan điểm này.', 'approved', '2026-05-11 16:53:05', '2026-05-11 16:53:05'),
(13, 4, 11, NULL, 'Đây là tình hình mà nhiều người quan tâm.', 'approved', '2026-05-11 16:53:05', '2026-05-11 16:53:05'),
(14, 5, 11, NULL, 'Cảm ơn đã cung cấp thông tin hữu ích này.', 'approved', '2026-05-11 16:53:05', '2026-05-11 16:53:05'),
(15, 5, 11, NULL, 'Cảm ơn đã cung cấp thông tin hữu ích này.', 'approved', '2026-05-11 16:53:05', '2026-05-11 16:53:05'),
(16, 5, 11, NULL, 'Mong chờ bài viết tiếp theo về chủ đề này.', 'approved', '2026-05-11 16:53:05', '2026-05-11 16:53:05'),
(17, 5, 11, NULL, 'Có thể giải thích thêm về vấn đề này không?', 'approved', '2026-05-11 16:53:05', '2026-05-11 16:53:05'),
(18, 5, 11, NULL, 'Đây là tình hình mà nhiều người quan tâm.', 'approved', '2026-05-11 16:53:05', '2026-05-11 16:53:05'),
(38, 11, 11, NULL, 'Bài viết rất hữu ích, cảm ơn tác giả!', 'approved', '2026-05-11 16:53:05', '2026-05-11 16:53:05'),
(39, 11, 11, NULL, 'Nội dung rất hấp dẫn, tôi sẽ chia sẻ.', 'approved', '2026-05-11 16:53:05', '2026-05-11 16:53:05'),
(40, 11, 11, NULL, 'Nội dung rất hấp dẫn, tôi sẽ chia sẻ.', 'approved', '2026-05-11 16:53:05', '2026-05-11 16:53:05'),
(41, 11, 11, NULL, 'Cần thêm các dẫn chứng cụ thể hơn.', 'approved', '2026-05-11 16:53:05', '2026-05-11 16:53:05'),
(42, 11, 11, NULL, 'Đây là tình hình mà nhiều người quan tâm.', 'approved', '2026-05-11 16:53:05', '2026-05-11 16:53:05'),
(48, 23, 9, NULL, 'lol', 'approved', '2026-05-12 03:15:26', '2026-05-15 04:10:50');

--
-- Bẫy `comments`
--
DELIMITER $$
CREATE TRIGGER `after_comment_delete` AFTER DELETE ON `comments` FOR EACH ROW BEGIN
    IF OLD.status = 'approved' THEN
        UPDATE articles SET comments_count = comments_count - 1 WHERE id = OLD.article_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_comment_insert` AFTER INSERT ON `comments` FOR EACH ROW BEGIN
    IF NEW.status = 'approved' THEN
        UPDATE articles SET comments_count = comments_count + 1 WHERE id = NEW.article_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_comment_update` AFTER UPDATE ON `comments` FOR EACH ROW BEGIN
    IF NEW.status = 'approved' AND OLD.status != 'approved' THEN
        UPDATE articles SET comments_count = comments_count + 1 WHERE id = NEW.article_id;
    ELSEIF NEW.status != 'approved' AND OLD.status = 'approved' THEN
        UPDATE articles SET comments_count = comments_count - 1 WHERE id = NEW.article_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'editor', 'Editor'),
(3, 'user', 'Normal User');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `description`) VALUES
(1, 'site_title', 'Website Tin Tuc', 'Tieu de website'),
(2, 'site_logo', '/WebsiteTinTuc/public/uploads/ad_6a069b4a79dc4.png', 'URL logo website'),
(3, 'primary_color', '#003366', 'Mau chinh'),
(4, 'secondary_color', '#11cfe8', 'Mau phu'),
(5, 'show_featured', '1', 'Hien thi tin noi bat'),
(6, 'show_latest', '1', 'Hien thi tin moi nhat'),
(7, 'show_most_viewed', '1', 'Hien thi xem nhieu nhat'),
(8, 'show_categories', '1', 'Hien thi tin theo chuyen muc'),
(9, 'show_ads', '1', 'Hien thi quang cao');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tags`
--

CREATE TABLE `tags` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`) VALUES
(1, 'Tin Nóng', 'tin-nóng'),
(2, 'Xu Hướng', 'xu-hướng'),
(3, 'Độc Quyền', 'Độc-quyền'),
(4, 'Phỏng Vấn', 'phỏng-vấn'),
(5, 'Dự Báo', 'dự-báo'),
(6, 'Phân Tích', 'phân-tích'),
(7, 'Số Liệu', 'số-liệu'),
(8, 'Khảo Sát', 'khảo-sát'),
(9, 'Ý Kiến', 'Ý-kiến'),
(10, 'Sự Kiện', 'sự-kiện'),
(11, 'LBINHTV', 'lbinhtv');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT 3,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` enum('active','locked') DEFAULT 'active',
  `reset_password_token` varchar(255) DEFAULT NULL,
  `reset_token_expire` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `role_id`, `full_name`, `email`, `password_hash`, `avatar`, `status`, `reset_password_token`, `reset_token_expire`, `created_at`, `updated_at`) VALUES
(9, 1, 'Test Admin', 'admin.test@example.com', '$2y$10$RkVKsDNInhCYqBCu6vepyuh3r3DU8BgVdljD5VsFw/iQLB8qEXYrK', '/WebsiteTinTuc/public/uploads/avatar_9_1778658591.png', 'active', NULL, NULL, '2026-05-11 04:17:23', '2026-05-13 07:49:51'),
(10, 2, 'Test Editor', 'editor.test@example.com', '$2y$10$Il6qCf.tuOavIm7l6lVLDu1cdZdGKHQBssP2vqU03FWlr/Vmf6One', '/WebsiteTinTuc/public/uploads/avatar_10_1778660099.png', 'active', NULL, NULL, '2026-05-11 04:17:23', '2026-05-13 08:14:59'),
(11, 3, 'Test User', 'user.test@example.com', '$2y$10$cE5APbSAQzPV/vSfb3OXjel9UhQmPDx8KIAE9W5TNj1doNJQN7bJe', '/WebsiteTinTuc/public/uploads/avatar_11_1778658683.png', 'active', NULL, NULL, '2026-05-11 04:17:23', '2026-05-13 07:51:23');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `advertisements`
--
ALTER TABLE `advertisements`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `category_id` (`category_id`);
ALTER TABLE `articles` ADD FULLTEXT KEY `idx_search` (`title`,`content`);

--
-- Chỉ mục cho bảng `article_likes`
--
ALTER TABLE `article_likes`
  ADD PRIMARY KEY (`user_id`,`article_id`),
  ADD KEY `article_id` (`article_id`);

--
-- Chỉ mục cho bảng `article_saves`
--
ALTER TABLE `article_saves`
  ADD PRIMARY KEY (`user_id`,`article_id`),
  ADD KEY `article_id` (`article_id`);

--
-- Chỉ mục cho bảng `article_tag`
--
ALTER TABLE `article_tag`
  ADD PRIMARY KEY (`article_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Chỉ mục cho bảng `bad_words`
--
ALTER TABLE `bad_words`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `word` (`word`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Chỉ mục cho bảng `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Chỉ mục cho bảng `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `advertisements`
--
ALTER TABLE `advertisements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `articles`
--
ALTER TABLE `articles`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT cho bảng `bad_words`
--
ALTER TABLE `bad_words`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `articles_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `fk_articles_category_new` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `fk_articles_user_new` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `article_likes`
--
ALTER TABLE `article_likes`
  ADD CONSTRAINT `article_likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `article_likes_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `article_saves`
--
ALTER TABLE `article_saves`
  ADD CONSTRAINT `article_saves_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `article_saves_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `article_tag`
--
ALTER TABLE `article_tag`
  ADD CONSTRAINT `article_tag_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `article_tag_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

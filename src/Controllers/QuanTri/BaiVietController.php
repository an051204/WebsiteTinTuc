<?php
namespace App\Controllers\QuanTri;

use App\Core\Controller;
use App\Models\BaiVietModel;
use App\Models\ChuyenMucModel;
use App\Models\TheTagModel;

class BaiVietController extends Controller
{
    public function danhSach(): void
    {
        $this->requireAdmin();

        $page = (int)($_GET['page'] ?? 1);
        $search = trim($_GET['search'] ?? '');
        $status = $_GET['status'] ?? '';
        $limit = 10;

        if ($page < 1) {
            $page = 1;
        }

        $offset = ($page - 1) * $limit;

        $baiVietModel = new BaiVietModel();
        $chuyenMucModel = new ChuyenMucModel();

        $total = $baiVietModel->demDanhSachBaiVietAdmin($search, $status);
        $articles = $baiVietModel->layDanhSachBaiVietAdmin($search, $status, $limit, $offset);
        $categories = $chuyenMucModel->layChuyenMucActive();

        $totalPages = $total > 0 ? (int)ceil($total / $limit) : 0;

        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
        }

        $data = [
            'articles' => $articles,
            'categories' => $categories,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalArticles' => $total,
            'search' => $search,
            'status' => $status,
        ];

        $this->renderView('quantri/baiviet/danh_sach', $data);
    }

    public function them(): void
    {
        $this->requireAdmin();

        $chuyenMucModel = new ChuyenMucModel();
        $theTagModel = new TheTagModel();
        $categories = $chuyenMucModel->layChuyenMucActive();
        $tags = $theTagModel->layTatCaTags();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $data = [
                'categories' => $categories,
                'tags' => $tags,
                'isEdit' => false,
            ];
            $this->renderView('quantri/baiviet/them_moi', $data);
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $content = $_POST['content'] ?? '';
        $summary = trim($_POST['summary'] ?? '');
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $status = $_POST['status'] ?? 'draft';
        $thumbnail = $_POST['thumbnail'] ?? '';
        $publishedAt = !empty($_POST['published_at']) ? $_POST['published_at'] : null;
        $tagIds = isset($_POST['tag_ids']) ? (array)$_POST['tag_ids'] : [];

        if (strlen($title) < 5) {
            $_SESSION['error'] = 'Tiêu đề phải ít nhất 5 ký tự';
            header('Location: /WebsiteTinTuc/public/quan-tri/bai-viet/them/');
            exit;
        }

        if ($categoryId < 1) {
            $_SESSION['error'] = 'Vui lòng chọn chuyên mục';
            header('Location: /WebsiteTinTuc/public/quan-tri/bai-viet/them/');
            exit;
        }

        if (strlen($content) < 20) {
            $_SESSION['error'] = 'Nội dung phải ít nhất 20 ký tự';
            header('Location: /WebsiteTinTuc/public/quan-tri/bai-viet/them/');
            exit;
        }

        if (!in_array($status, ['draft', 'pending', 'published', 'rejected'])) {
            $status = 'draft';
        }

        if (!empty($publishedAt)) {
            try {
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $now = new \DateTime('now', new \DateTimeZone('Asia/Ho_Chi_Minh'));
                $publishDate = new \DateTime($publishedAt, new \DateTimeZone('Asia/Ho_Chi_Minh'));
                
                $publishedAt = $publishDate->format('Y-m-d H:i:s');
                
                if ($publishDate > $now) {
                    $status = 'pending';
                } elseif ($publishDate <= $now && $status !== 'draft') {
                    $status = 'published';
                }
            } catch (\Exception $e) {
                $publishedAt = null;
                $status = 'draft';
            }
        }
        
        $baiVietModel = new BaiVietModel();
        $userId = $_SESSION['user_id'];

        $articleId = $baiVietModel->themBaiViet($userId, $categoryId, $title, $content, $summary, 
                                                $thumbnail, $isFeatured, $status, $publishedAt);

        if ($articleId) {
            if (!empty($tagIds)) {
                $theTagModel->lienKetTagVoiBaiViet($articleId, $tagIds);
            }

            $_SESSION['success'] = 'Bài viết đã được tạo thành công';
            header("Location: /WebsiteTinTuc/public/quan-tri/bai-viet/sua/{$articleId}/");
            exit;
        } else {
            $_SESSION['error'] = 'Lỗi tạo bài viết. Vui lòng thử lại';
            header('Location: /WebsiteTinTuc/public/quan-tri/bai-viet/them/');
            exit;
        }
    }

    public function sua(): void
    {
        $this->requireAdmin();

        $articleId = (int)$this->getRouteParam('id', 0);

        if ($articleId < 1) {
            http_response_code(404);
            echo "<h1>Lỗi 404 - Bài viết không tồn tại</h1>";
            return;
        }

        $baiVietModel = new BaiVietModel();
        $chuyenMucModel = new ChuyenMucModel();
        $theTagModel = new TheTagModel();
        $article = $baiVietModel->layChiTietBaiVietAdmin($articleId);

        if (!$article) {
            http_response_code(404);
            echo "<h1>Lỗi 404 - Bài viết không tồn tại</h1>";
            return;
        }

        $oldThumbnail = $article['thumbnail'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $categories = $chuyenMucModel->layChuyenMucActive();
            $tags = $theTagModel->layTatCaTags();
            $articleTags = $theTagModel->layTagsTheoBaiViet($articleId);
            
            $selectedTagIds = [];
            foreach ($articleTags as $tag) {
                $selectedTagIds[] = $tag['id'];
            }

            $data = [
                'article' => $article,
                'categories' => $categories,
                'tags' => $tags,
                'selectedTagIds' => $selectedTagIds,
                'isEdit' => true,
            ];
            $this->renderView('quantri/baiviet/them_moi', $data);
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $content = $_POST['content'] ?? '';
        $summary = trim($_POST['summary'] ?? '');
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $status = $_POST['status'] ?? 'draft';
        $thumbnail = $_POST['thumbnail'] ?? '';
        $publishedAt = !empty($_POST['published_at']) ? $_POST['published_at'] : null;
        $tagIds = isset($_POST['tag_ids']) ? (array)$_POST['tag_ids'] : [];

        if (strlen($title) < 5) {
            $_SESSION['error'] = 'Tiêu đề phải ít nhất 5 ký tự';
            header("Location: /WebsiteTinTuc/public/quan-tri/bai-viet/sua/{$articleId}/");
            exit;
        }

        if ($categoryId < 1) {
            $_SESSION['error'] = 'Vui lòng chọn chuyên mục';
            header("Location: /WebsiteTinTuc/public/quan-tri/bai-viet/sua/{$articleId}/");
            exit;
        }

        if (!in_array($status, ['draft', 'pending', 'published', 'rejected'])) {
            $status = 'draft';
        }

        if (!empty($publishedAt)) {
            try {
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $now = new \DateTime('now', new \DateTimeZone('Asia/Ho_Chi_Minh'));
                $publishDate = new \DateTime($publishedAt, new \DateTimeZone('Asia/Ho_Chi_Minh'));
                
                $publishedAt = $publishDate->format('Y-m-d H:i:s');
                
                if ($publishDate > $now) {
                    $status = 'pending';
                } elseif ($publishDate <= $now && $status !== 'draft') {
                    $status = 'published';
                }
            } catch (\Exception $e) {
                $publishedAt = null;
                $status = 'draft';
            }
        }

        if ($baiVietModel->capNhatBaiViet($articleId, $categoryId, $title, $content, $summary, 
                                          $thumbnail, $isFeatured, $status, $publishedAt)) {
            if (!empty($oldThumbnail) && $oldThumbnail !== $thumbnail) {
                $this->xoaFileLocalTuUrl($oldThumbnail);
            }

            if (!empty($tagIds)) {
                $theTagModel->lienKetTagVoiBaiViet($articleId, $tagIds);
            }

            $_SESSION['success'] = 'Bài viết đã được cập nhật thành công';
            header("Location: /WebsiteTinTuc/public/quan-tri/bai-viet/sua/{$articleId}/");
            exit;
        } else {
            $_SESSION['error'] = 'Lỗi cập nhật bài viết. Vui lòng thử lại';
            header("Location: /WebsiteTinTuc/public/quan-tri/bai-viet/sua/{$articleId}/");
            exit;
        }
    }

    public function xoa(): void
    {
        $this->requireAdmin();

        $articleId = (int)($_POST['article_id'] ?? 0);

        if ($articleId < 1) {
            $_SESSION['error'] = 'ID bài viết không hợp lệ';
            header('Location: /WebsiteTinTuc/public/quan-tri/bai-viet/');
            exit;
        }

        $baiVietModel = new BaiVietModel();
        $article = $baiVietModel->layChiTietBaiVietAdmin($articleId);

        if (!$article) {
            $_SESSION['error'] = 'Bài viết không tồn tại';
            header('Location: /WebsiteTinTuc/public/quan-tri/bai-viet/');
            exit;
        }

        if ($baiVietModel->xoaBaiViet($articleId)) {
            if (!empty($article['thumbnail'])) {
                $this->xoaFileLocalTuUrl($article['thumbnail']);
            }

            $_SESSION['success'] = 'Bài viết đã được xóa thành công';
        } else {
            $_SESSION['error'] = 'Lỗi xóa bài viết. Vui lòng thử lại';
        }

        header('Location: /WebsiteTinTuc/public/quan-tri/bai-viet/');
        exit;
    }

    private function xoaFileLocalTuUrl(?string $url): void
    {
        if (empty($url)) {
            return;
        }

        $path = parse_url($url, PHP_URL_PATH);
        if (!$path) {
            return;
        }

        $isLocalUpload = strpos($path, '/WebsiteTinTuc/public/uploads/') !== false || strpos($path, '/uploads/') !== false;
        if (!$isLocalUpload) {
            return;
        }

        $fileName = basename($path);
        if (empty($fileName)) {
            return;
        }

        $filePath = dirname(__DIR__, 3) . '/public/uploads/' . $fileName;
        if (is_file($filePath)) {
            @unlink($filePath);
        }
    }

    public function capNhatTrangThai(): void
    {
        $this->requireAdmin();

        $articleId = (int)($_POST['article_id'] ?? 0);
        $newStatus = trim($_POST['status'] ?? '');

        if ($articleId < 1 || !in_array($newStatus, ['draft', 'pending', 'published', 'rejected'])) {
            $_SESSION['error'] = 'Dữ liệu không hợp lệ';
            header('Location: /WebsiteTinTuc/public/quan-tri/bai-viet/');
            exit;
        }

        $baiVietModel = new BaiVietModel();
        $article = $baiVietModel->layChiTietBaiVietAdmin($articleId);

        if (!$article) {
            $_SESSION['error'] = 'Bài viết không tồn tại';
            header('Location: /WebsiteTinTuc/public/quan-tri/bai-viet/');
            exit;
        }

        if ($baiVietModel->capNhatTrangThai($articleId, $newStatus)) {
            $_SESSION['success'] = 'Trạng thái đã được cập nhật thành công';
        } else {
            $_SESSION['error'] = 'Lỗi cập nhật trạng thái. Vui lòng thử lại';
        }

        header('Location: /WebsiteTinTuc/public/quan-tri/bai-viet/');
        exit;
    }
}


<?php
namespace App\Controllers\NguoiDung;

use App\Core\Controller;
use App\Core\Session;
use App\Models\BaiVietModel;
use App\Models\BinhLuanModel;
use App\Models\ChuyenMucModel;
use App\Models\TuongTacModel;
use App\Models\TheTagModel;

class TinTucController extends Controller
{
    public function chiTiet(): void
    {
        $slug = $this->getRouteParam('slug');

        if (empty($slug)) {
            http_response_code(404);
            echo "<h1>Lỗi 404 - Bài viết không tồn tại</h1>";
            return;
        }

        $baiVietModel = new BaiVietModel();
        $chuyenMucModel = new ChuyenMucModel();
        $binhLuanModel = new BinhLuanModel();
        $tuongTacModel = new TuongTacModel();
        $theTagModel = new TheTagModel();

        $baiViet = $baiVietModel->layChiTietBaiVietTheoSlug($slug);

        if (!$baiViet) {
            http_response_code(404);
            echo "<h1>Lỗi 404 - Bài viết không tồn tại</h1>";
            return;
        }

        $articleId = $baiViet['id'];
        $userId = $_SESSION['user_id'] ?? null;

        if (!isset($_SESSION['viewed_articles'])) {
            $_SESSION['viewed_articles'] = [];
        }

        if (!in_array($articleId, $_SESSION['viewed_articles'])) {
            $baiVietModel->tangLuotXem($articleId);
            $_SESSION['viewed_articles'][] = $articleId;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$userId) {
                header('Location: /WebsiteTinTuc/public/dang-nhap');
                exit;
            }

            if (isset($_POST['action']) && $_POST['action'] === 'comment') {
                $content = trim($_POST['content'] ?? '');

                // Kiểm tra content không được trống
                if (empty($content)) {
                    $_SESSION['error'] = 'Bình luận không được để trống';
                    header("Location: /WebsiteTinTuc/public/tin-tuc/{$slug}");
                    exit;
                }

                // Kiểm tra spam
                if (!$binhLuanModel->kiemTraCommentSpam($userId, $articleId)) {
                    $_SESSION['error'] = 'Vui lòng chờ ít nhất 10 giây trước khi bình luận lại';
                    header("Location: /WebsiteTinTuc/public/tin-tuc/{$slug}");
                    exit;
                }

                // Kiểm tra từ khóa xấu
                $kiemTra = $binhLuanModel->kiemTraTuKhoa($content);
                if (!$kiemTra) {
                    $_SESSION['error'] = 'Bình luận chứa các từ không được phép';
                    header("Location: /WebsiteTinTuc/public/tin-tuc/{$slug}");
                    exit;
                }

                // Thêm bình luận
                if ($binhLuanModel->themBinhLuan($articleId, $userId, $content)) {
                    $_SESSION['success'] = 'Bình luận đã được thêm thành công';
                } else {
                    $_SESSION['error'] = 'Lỗi khi thêm bình luận';
                }
                
                header("Location: /WebsiteTinTuc/public/tin-tuc/{$slug}");
                exit;
            } elseif (isset($_POST['action']) && $_POST['action'] === 'like') {
                $tuongTacModel->thichBaiViet($articleId, $userId);
                header("Location: /WebsiteTinTuc/public/tin-tuc/{$slug}");
                exit;
            } elseif (isset($_POST['action']) && $_POST['action'] === 'save') {
                $alreadySaved = $tuongTacModel->kiemTraDaLuu($articleId, $userId);
                if ($tuongTacModel->luuBaiViet($articleId, $userId)) {
                    $_SESSION['success'] = $alreadySaved ? 'Bỏ lưu thành công' : 'Đã lưu bài viết';
                } else {
                    $_SESSION['error'] = 'Lỗi khi lưu bài viết';
                }
                header("Location: /WebsiteTinTuc/public/tin-tuc/{$slug}");
                exit;
            }
        }

        $chuyenMuc = $chuyenMucModel->layChiTietTheoId($baiViet['category_id']);
        if (!$chuyenMuc) {
            $chuyenMuc = [];
        }

        $binhLuanList = $binhLuanModel->layBinhLuanTheoBaiViet($articleId);
        $baiLienQuan = $baiVietModel->layBaiLienQuan($baiViet['category_id'], $articleId);
        $tags = $theTagModel->layTagsTheoBaiViet($articleId);

        $daThich = false;
        $daLuu = false;
        if ($userId) {
            $daThich = $tuongTacModel->kiemTraDaThich($articleId, $userId);
            $daLuu = $tuongTacModel->kiemTraDaLuu($articleId, $userId);
        }

        $data = [
            'baiViet' => $baiViet,
            'chuyenMuc' => $chuyenMuc,
            'binhLuanList' => $binhLuanList,
            'baiLienQuan' => $baiLienQuan,
            'tags' => $tags,
            'userId' => $userId,
            'daThich' => $daThich,
            'daLuu' => $daLuu,
        ];

        $this->renderView('nguoidung/chi_tiet', $data);
    }
}

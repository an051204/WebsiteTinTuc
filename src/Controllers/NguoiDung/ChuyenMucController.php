<?php
namespace App\Controllers\NguoiDung;

use App\Core\Controller;
use App\Models\BaiVietModel;
use App\Models\ChuyenMucModel;
use App\Models\TheTagModel;

class ChuyenMucController extends Controller
{
    public function index(): void
    {
        // Ngăn browser cache
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        $slug = $this->getRouteParam('slug');
        $filter = $_GET['filter'] ?? 'newest';
        $page = (int)($_GET['page'] ?? 1);
        $limit = 10;

        if (empty($slug)) {
            http_response_code(404);
            echo "<h1>Lỗi 404 - Chuyên mục không tồn tại</h1>";
            return;
        }

        $chuyenMucModel = new ChuyenMucModel();
        $baiVietModel = new BaiVietModel();
        $theTagModel = new TheTagModel();

        $chuyenMuc = $chuyenMucModel->layChiTietTheoSlug($slug);

        if (!$chuyenMuc) {
            http_response_code(404);
            echo "<h1>Lỗi 404 - Chuyên mục không tồn tại</h1>";
            return;
        }

        if ($page < 1) {
            $page = 1;
        }

        $offset = ($page - 1) * $limit;
        $totalArticles = $baiVietModel->demTongTinTheoChuyenMuc($chuyenMuc['id']);
        $totalPages = (int)ceil($totalArticles / $limit);

        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
        }

        $articles = $baiVietModel->layTinTheoChuyenMucPhanTrang($chuyenMuc['id'], $filter, $limit, $offset);

        // Fetch tags for each article
        foreach ($articles as &$article) {
            $article['tags'] = $theTagModel->layTagsTheoBaiViet($article['id']);
        }
        unset($article);

        $data = [
            'chuyenMuc' => $chuyenMuc,
            'articles' => $articles,
            'filter' => $filter,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalArticles' => $totalArticles,
        ];

        $this->renderView('nguoidung/chuyenmuc', $data);
    }
}

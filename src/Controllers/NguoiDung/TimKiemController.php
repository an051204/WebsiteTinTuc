<?php
namespace App\Controllers\NguoiDung;

use App\Core\Controller;
use App\Models\BaiVietModel;

class TimKiemController extends Controller
{
    public function index(): void
    {
        $keyword = trim($_GET['q'] ?? '');
        $page = (int)($_GET['page'] ?? 1);
        $limit = 10;

        if (mb_strlen($keyword) < 1) {
            $keyword = '';
        }

        if ($page < 1) {
            $page = 1;
        }

        $offset = ($page - 1) * $limit;
        $baiVietModel = new BaiVietModel();

        $total = 0;
        $articles = [];

        if (!empty($keyword)) {
            $total = $baiVietModel->demTimKiemTheoTacGia($keyword);
            $articles = $baiVietModel->timKiemTheoTacGia($keyword, $limit, $offset);
        }

        $totalPages = $total > 0 ? (int)ceil($total / $limit) : 0;

        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
            $articles = $baiVietModel->timKiemTheoTacGia($keyword, $limit, $offset);
        }

        $data = [
            'keyword' => $keyword,
            'articles' => $articles,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalArticles' => $total,
        ];

        $this->renderView('nguoidung/tim_kiem', $data);
    }
}

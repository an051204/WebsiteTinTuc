<?php
namespace App\Controllers\QuanTri;

use App\Core\Controller;
use App\Models\BaiVietModel;
use App\Models\TaiKhoanModel;
use App\Models\BinhLuanModel;
use App\Models\ChuyenMucModel;
use App\Models\TheTagModel;

class TongQuanController extends Controller
{
    public function trangChu(): void
    {
        $this->requireAdmin();

        $baiVietModel = new BaiVietModel();
        $taiKhoanModel = new TaiKhoanModel();
        $binhLuanModel = new BinhLuanModel();
        $chuyenMucModel = new ChuyenMucModel();
        $theTagModel = new TheTagModel();

        // Lấy thống kê bài viết
        $totalArticles = $baiVietModel->demDanhSachBaiVietAdmin('', '');
        $publishedArticles = $baiVietModel->demDanhSachBaiVietAdmin('', 'published');
        $pendingArticles = $baiVietModel->demDanhSachBaiVietAdmin('', 'pending');
        $draftArticles = $baiVietModel->demDanhSachBaiVietAdmin('', 'draft');

        // Bài viết mới nhất (5 bài gần đây)
        $recentArticles = $baiVietModel->layDanhSachBaiVietAdmin('', '', 5, 0);

        // Thống kê khác
        $totalUsers = $taiKhoanModel->demTatCaTaiKhoan();
        $totalComments = $binhLuanModel->demTatCaBinhLuan() ?? 0;
        $totalCategories = $chuyenMucModel->demChuyenMuc();
        $totalTags = $theTagModel->demTatCaTags();

        // Bài viết nổi bật
        $featuredArticles = $baiVietModel->layBaiVietNoiBat(5);

        // Bài viết được xem nhiều nhất (7 ngày gần đây)
        $topViewedArticles = $baiVietModel->layBaiVietXemNhieuTuan(5);

        $data = [
            'totalArticles' => $totalArticles,
            'publishedArticles' => $publishedArticles,
            'pendingArticles' => $pendingArticles,
            'draftArticles' => $draftArticles,
            'totalUsers' => $totalUsers,
            'totalComments' => $totalComments,
            'totalCategories' => $totalCategories,
            'totalTags' => $totalTags,
            'recentArticles' => $recentArticles,
            'featuredArticles' => $featuredArticles,
            'topViewedArticles' => $topViewedArticles,
        ];

        $this->renderView('quantri/trangchu', $data);
    }
}

<?php
namespace App\Controllers\NguoiDung;

use App\Core\Controller;
use App\Models\BaiVietModel;
use App\Models\ChuyenMucModel;
use App\Models\QuangCaoModel;
use App\Models\CaiDatModel;

class TrangChuController extends Controller
{
    public function index(): void
    {
        /* Ngăn browser cache trang chủ để luôn hiển thị dữ liệu mới */
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() - 86400) . ' GMT');

        $baiVietModel = new BaiVietModel();
        $chuyenMucModel = new ChuyenMucModel();
        $quangCaoModel = new QuangCaoModel();
        $caiDatModel = new CaiDatModel();

        $settings = $caiDatModel->layTatCaCaiDat();

        $slide = $baiVietModel->layTinSlide() ?? [];
        $tinMoiNhat = $baiVietModel->layTinMoiNhat(8) ?? [];
        $tinXemNhieu = $baiVietModel->layTinXemNhieu(8) ?? [];
        $chuyenMuc = $chuyenMucModel->layChuyenMucActive() ?? [];
        $quangCaoTop = $quangCaoModel->layQuangCaoTheoViTri('home_top') ?? [];
        $quangCaoMiddle = $quangCaoModel->layQuangCaoTheoViTri('home_middle') ?? [];

        if (!empty($chuyenMuc)) {
            foreach ($chuyenMuc as &$cat) {
                $cat['articles'] = $baiVietModel->layTinTheoChuyenMuc($cat['id'], 6) ?? [];
            }
        }

        $data = [
            'slide' => $slide,
            'tinMoiNhat' => $tinMoiNhat,
            'tinXemNhieu' => $tinXemNhieu,
            'chuyenMuc' => $chuyenMuc,
            'quangCaoTop' => $quangCaoTop,
            'quangCaoMiddle' => $quangCaoMiddle,
            'settings' => $settings,
        ];

        $this->renderView('nguoidung/trangchu', $data);
    }
}

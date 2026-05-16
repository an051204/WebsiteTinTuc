<?php
namespace App\Controllers\NguoiDung;

use App\Core\Controller;
use App\Models\ChuyenMucModel;

class DanhSachController extends Controller
{
    public function index(): void
    {
        // Ngăn browser cache
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        $chuyenMucModel = new ChuyenMucModel();
        $categories = $chuyenMucModel->layChuyenMucActive();

        $data = [
            'categories' => $categories,
        ];

        $this->renderView('nguoidung/danh_sach', $data);
    }
}

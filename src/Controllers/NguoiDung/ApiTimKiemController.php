<?php
namespace App\Controllers\NguoiDung;

use App\Core\Controller;
use App\Models\BaiVietModel;

class ApiTimKiemController extends Controller
{
    public function gopY(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $keyword = trim($_GET['q'] ?? '');

        if (mb_strlen($keyword) < 1) {
            echo json_encode([]);
            return;
        }

        $baiVietModel = new BaiVietModel();
        $ketQua = $baiVietModel->timKiemGopY($keyword, 10);

        $dulieu = [];
        foreach ($ketQua as $bai) {
            $dulieu[] = [
                'id' => $bai['id'],
                'title' => htmlspecialchars($bai['title']),
                'slug' => htmlspecialchars($bai['slug']),
                'url' => '/WebsiteTinTuc/public/tin-tuc/' . htmlspecialchars($bai['slug'])
            ];
        }

        echo json_encode($dulieu);
    }
}

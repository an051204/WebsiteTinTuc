<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once dirname(__DIR__) . '/vendor/autoload.php';

loadEnv(__DIR__ . '/../.env');

function loadEnv($filePath)
{
    if (!file_exists($filePath)) {
        throw new Exception("File .env not found at: {$filePath}");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            $_ENV[$key] = $value;
        }
    }
}

$router = new \App\Core\DieuHuong();

$router->get('/', [\App\Controllers\NguoiDung\TrangChuController::class, 'index']);
$router->get('/danh-sach/', [\App\Controllers\NguoiDung\DanhSachController::class, 'index']);
$router->get('/danh-sach/{slug}/', [\App\Controllers\NguoiDung\ChuyenMucController::class, 'index']);
$router->get('/tag/', [\App\Controllers\NguoiDung\TagController::class, 'listAll']);
$router->get('/tag/{slug}/', [\App\Controllers\NguoiDung\TagController::class, 'index']);
$router->get('/tin-tuc/{slug}/', [\App\Controllers\NguoiDung\TinTucController::class, 'chiTiet']);
$router->post('/tin-tuc/{slug}/', [\App\Controllers\NguoiDung\TinTucController::class, 'chiTiet']);
$router->get('/tim-kiem/', [\App\Controllers\NguoiDung\TimKiemController::class, 'index']);
$router->get('/api/tim-kiem/gop-y/', [\App\Controllers\NguoiDung\ApiTimKiemController::class, 'gopY']);
$router->get('/ca-nhan/', [\App\Controllers\NguoiDung\TrangCaNhanController::class, 'index']);
$router->post('/ca-nhan/cap-nhat/', [\App\Controllers\NguoiDung\TrangCaNhanController::class, 'capNhatThongTin']);
$router->post('/ca-nhan/upload-avatar/', [\App\Controllers\NguoiDung\TrangCaNhanController::class, 'uploadAvatar']);

$router->get('/dang-ky/', [\App\Controllers\XacThuc\XacThucController::class, 'dang_ky']);
$router->post('/dang-ky/', [\App\Controllers\XacThuc\XacThucController::class, 'dang_ky']);
$router->get('/dang-nhap/', [\App\Controllers\XacThuc\XacThucController::class, 'dang_nhap']);
$router->post('/dang-nhap/', [\App\Controllers\XacThuc\XacThucController::class, 'dang_nhap']);
$router->get('/quen-mat-khau/', [\App\Controllers\XacThuc\XacThucController::class, 'quen_mat_khau']);
$router->post('/quen-mat-khau/', [\App\Controllers\XacThuc\XacThucController::class, 'quen_mat_khau']);
$router->get('/reset-mat-khau/', [\App\Controllers\XacThuc\XacThucController::class, 'reset_mat_khau']);
$router->post('/reset-mat-khau/', [\App\Controllers\XacThuc\XacThucController::class, 'reset_mat_khau']);
$router->post('/ca-nhan/cap-nhat-mat-khau/', [\App\Controllers\XacThuc\XacThucController::class, 'cap_nhat_mat_khau']);
$router->get('/dang-xuat/', [\App\Controllers\XacThuc\XacThucController::class, 'dang_xuat']);

$router->get('/quan-tri/tai-khoan/', [\App\Controllers\QuanTri\TaiKhoanController::class, 'danhSach']);
$router->post('/quan-tri/tai-khoan/', [\App\Controllers\QuanTri\TaiKhoanController::class, 'danhSach']);
$router->post('/quan-tri/tai-khoan/cap-nhat-role/', [\App\Controllers\QuanTri\TaiKhoanController::class, 'capNhatRole']);
$router->post('/quan-tri/tai-khoan/cap-nhat-status/', [\App\Controllers\QuanTri\TaiKhoanController::class, 'capNhatStatus']);
$router->post('/quan-tri/tai-khoan/xoa/', [\App\Controllers\QuanTri\TaiKhoanController::class, 'xoa']);

$router->post('/quan-tri/upload/', [\App\Controllers\QuanTri\UploadController::class, 'upload']);

$router->get('/quan-tri/bai-viet/', [\App\Controllers\QuanTri\BaiVietController::class, 'danhSach']);
$router->get('/quan-tri/bai-viet/them/', [\App\Controllers\QuanTri\BaiVietController::class, 'them']);
$router->post('/quan-tri/bai-viet/them/', [\App\Controllers\QuanTri\BaiVietController::class, 'them']);
$router->get('/quan-tri/bai-viet/sua/{id}/', [\App\Controllers\QuanTri\BaiVietController::class, 'sua']);
$router->post('/quan-tri/bai-viet/sua/{id}/', [\App\Controllers\QuanTri\BaiVietController::class, 'sua']);
$router->post('/quan-tri/bai-viet/xoa/', [\App\Controllers\QuanTri\BaiVietController::class, 'xoa']);
$router->post('/quan-tri/bai-viet/cap-nhat-trang-thai/', [\App\Controllers\QuanTri\BaiVietController::class, 'capNhatTrangThai']);

// Quản lý chuyên mục
$router->get('/quan-tri/chuyenmuc/', [\App\Controllers\QuanTri\ChuyenMucController::class, 'danhSach']);
$router->get('/quan-tri/chuyenmuc/them/', [\App\Controllers\QuanTri\ChuyenMucController::class, 'them']);
$router->post('/quan-tri/chuyenmuc/them/', [\App\Controllers\QuanTri\ChuyenMucController::class, 'them']);
$router->get('/quan-tri/chuyenmuc/sua/', [\App\Controllers\QuanTri\ChuyenMucController::class, 'sua']);
$router->post('/quan-tri/chuyenmuc/sua/', [\App\Controllers\QuanTri\ChuyenMucController::class, 'sua']);
$router->post('/quan-tri/chuyenmuc/xoa/', [\App\Controllers\QuanTri\ChuyenMucController::class, 'xoa']);
$router->post('/quan-tri/chuyenmuc/cap-nhat-thu-tu/', [\App\Controllers\QuanTri\ChuyenMucController::class, 'capNhatThuTu']);

// Quản lý bình luận
$router->get('/quan-tri/binhluan/danh-sach/', [\App\Controllers\QuanTri\BinhLuanController::class, 'danhSach']);
$router->post('/quan-tri/binhluan/cap-nhat-trang-thai/', [\App\Controllers\QuanTri\BinhLuanController::class, 'capNhatTrangThai']);
$router->post('/quan-tri/binhluan/xoa/', [\App\Controllers\QuanTri\BinhLuanController::class, 'xoa']);
$router->get('/quan-tri/binhluan/quan-ly-tu-khoa/', [\App\Controllers\QuanTri\BinhLuanController::class, 'quanLyTuKhoa']);
$router->post('/quan-tri/binhluan/them-tu-khoa/', [\App\Controllers\QuanTri\BinhLuanController::class, 'themTuKhoa']);
$router->post('/quan-tri/binhluan/xoa-tu-khoa/', [\App\Controllers\QuanTri\BinhLuanController::class, 'xoaTuKhoa']);

// Quản lý thẻ tag
$router->get('/quan-tri/the-tag/', [\App\Controllers\QuanTri\TheTagController::class, 'danhSach']);
$router->get('/quan-tri/the-tag/them/', [\App\Controllers\QuanTri\TheTagController::class, 'them']);
$router->post('/quan-tri/the-tag/them/', [\App\Controllers\QuanTri\TheTagController::class, 'them']);
$router->get('/quan-tri/the-tag/sua/', [\App\Controllers\QuanTri\TheTagController::class, 'sua']);
$router->post('/quan-tri/the-tag/sua/', [\App\Controllers\QuanTri\TheTagController::class, 'sua']);
$router->post('/quan-tri/the-tag/xoa/', [\App\Controllers\QuanTri\TheTagController::class, 'xoa']);

// Quản lý quảng cáo
$router->get('/quan-tri/caidat/quang-cao/', [\App\Controllers\QuanTri\CaiDatController::class, 'quangCao']);
$router->get('/quan-tri/caidat/quang-cao/them/', [\App\Controllers\QuanTri\CaiDatController::class, 'themQuangCao']);
$router->post('/quan-tri/caidat/quang-cao/them/', [\App\Controllers\QuanTri\CaiDatController::class, 'themQuangCao']);
$router->get('/quan-tri/caidat/quang-cao/sua/', [\App\Controllers\QuanTri\CaiDatController::class, 'suaQuangCao']);
$router->post('/quan-tri/caidat/quang-cao/sua/', [\App\Controllers\QuanTri\CaiDatController::class, 'suaQuangCao']);
$router->post('/quan-tri/caidat/quang-cao/xoa/', [\App\Controllers\QuanTri\CaiDatController::class, 'xoaQuangCao']);
$router->post('/quan-tri/caidat/quang-cao/cap-nhat-trang-thai/', [\App\Controllers\QuanTri\CaiDatController::class, 'capNhatTrangThaiQuangCao']);

// Quản lý giao diện
$router->get('/quan-tri/caidat/giao-dien/', [\App\Controllers\QuanTri\CaiDatController::class, 'giaoDien']);
$router->post('/quan-tri/caidat/giao-dien/', [\App\Controllers\QuanTri\CaiDatController::class, 'giaoDien']);

// Admin Dashboard
$router->get('/quan-tri/', [\App\Controllers\QuanTri\TongQuanController::class, 'trangChu']);
$router->get('/quan-tri/trang-chu/', [\App\Controllers\QuanTri\TongQuanController::class, 'trangChu']);

$router->route();
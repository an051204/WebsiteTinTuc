<?php
namespace App\Controllers\QuanTri;

use App\Core\Controller;
use App\Models\QuangCaoModel;
use App\Models\CaiDatModel;

class CaiDatController extends Controller
{
    /**
     * Danh sách quảng cáo
     */
    public function quangCao(): void
    {
        $this->requireAdmin();
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        $quangCaoModel = new QuangCaoModel();

        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $danhSach = $quangCaoModel->layDanhSachAdmin($search, $limit, $offset);
        $total = $quangCaoModel->demQuangCao($search);
        $totalPages = ceil($total / $limit);

        $data = [
            'danhSach' => $danhSach,
            'page' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'total' => $total,
            'basePath' => '/WebsiteTinTuc/public'
        ];

        $this->renderView('quantri/caidat/quang_cao', $data);
    }

    /**
     * Thêm quảng cáo mới
     */
    public function themQuangCao(): void
    {
        $this->requireAdmin();
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $imageUrl = isset($_POST['image_url']) ? trim($_POST['image_url']) : '';
            $linkUrl = isset($_POST['link_url']) ? trim($_POST['link_url']) : '';
            $position = isset($_POST['position']) ? trim($_POST['position']) : 'sidebar';
            $status = isset($_POST['status']) ? trim($_POST['status']) : 'active';
            $startDate = isset($_POST['start_date']) ? trim($_POST['start_date']) : null;
            $endDate = isset($_POST['end_date']) ? trim($_POST['end_date']) : null;

            /* Xử lý upload ảnh nếu có */
            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->xuLyUploadAnh($_FILES['image_file']);
                if ($uploadResult['success']) {
                    $imageUrl = $uploadResult['url'];
                } else {
                    $_SESSION['error'] = $uploadResult['message'];
                    header('Location: /WebsiteTinTuc/public/quan-tri/caidat/quang-cao/them/');
                    exit;
                }
            }

            if (empty($title)) {
                $_SESSION['error'] = 'Tiêu đề không được để trống';
                header('Location: /WebsiteTinTuc/public/quan-tri/caidat/quang-cao/them/');
                exit;
            }

            if (empty($imageUrl)) {
                $_SESSION['error'] = 'Vui lòng upload ảnh hoặc nhập URL ảnh';
                header('Location: /WebsiteTinTuc/public/quan-tri/caidat/quang-cao/them/');
                exit;
            }

            if (empty($linkUrl)) {
                $_SESSION['error'] = 'Link liên kết không được để trống';
                header('Location: /WebsiteTinTuc/public/quan-tri/caidat/quang-cao/them/');
                exit;
            }

            $quangCaoModel = new QuangCaoModel();
            $startDateVal = !empty($startDate) ? $startDate : null;
            $endDateVal = !empty($endDate) ? $endDate : null;

            $id = $quangCaoModel->themQuangCao([
                'title' => $title,
                'image_url' => $imageUrl,
                'link_url' => $linkUrl,
                'position' => $position,
                'status' => $status,
                'start_date' => $startDateVal,
                'end_date' => $endDateVal,
            ]);

            if ($id) {
                $_SESSION['success'] = 'Thêm quảng cáo thành công!';
                header('Location: /WebsiteTinTuc/public/quan-tri/caidat/quang-cao/');
                exit;
            } else {
                $_SESSION['error'] = 'Lỗi khi thêm quảng cáo';
                header('Location: /WebsiteTinTuc/public/quan-tri/caidat/quang-cao/them/');
                exit;
            }
        }

        $data = [
            'quangCao' => [],
            'isEdit' => false,
            'basePath' => '/WebsiteTinTuc/public'
        ];

        $this->renderView('quantri/caidat/them_quang_cao', $data);
    }

    /**
     * Sửa quảng cáo
     */
    public function suaQuangCao(): void
    {
        $this->requireAdmin();
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (empty($id)) {
            $_SESSION['error'] = 'ID quảng cáo không hợp lệ';
            header('Location: /WebsiteTinTuc/public/quan-tri/caidat/quang-cao/');
            exit;
        }

        $quangCaoModel = new QuangCaoModel();
        $quangCao = $quangCaoModel->layChiTiet($id);

        if (empty($quangCao)) {
            $_SESSION['error'] = 'Quảng cáo không tồn tại';
            header('Location: /WebsiteTinTuc/public/quan-tri/caidat/quang-cao/');
            exit;
        }

        $oldImageUrl = $quangCao['image_url'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $imageUrl = isset($_POST['image_url']) ? trim($_POST['image_url']) : $quangCao['image_url'];
            $linkUrl = isset($_POST['link_url']) ? trim($_POST['link_url']) : '';
            $position = isset($_POST['position']) ? trim($_POST['position']) : 'sidebar';
            $status = isset($_POST['status']) ? trim($_POST['status']) : 'active';
            $startDate = isset($_POST['start_date']) ? trim($_POST['start_date']) : null;
            $endDate = isset($_POST['end_date']) ? trim($_POST['end_date']) : null;

            /* Xử lý upload ảnh mới nếu có */
            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->xuLyUploadAnh($_FILES['image_file']);
                if ($uploadResult['success']) {
                    $imageUrl = $uploadResult['url'];
                } else {
                    $_SESSION['error'] = $uploadResult['message'];
                    header("Location: /WebsiteTinTuc/public/quan-tri/caidat/quang-cao/sua/?id={$id}");
                    exit;
                }
            }

            if (empty($title)) {
                $_SESSION['error'] = 'Tiêu đề không được để trống';
                header("Location: /WebsiteTinTuc/public/quan-tri/caidat/quang-cao/sua/?id={$id}");
                exit;
            }

            if (empty($linkUrl)) {
                $_SESSION['error'] = 'Link liên kết không được để trống';
                header("Location: /WebsiteTinTuc/public/quan-tri/caidat/quang-cao/sua/?id={$id}");
                exit;
            }

            $startDateVal = !empty($startDate) ? $startDate : null;
            $endDateVal = !empty($endDate) ? $endDate : null;

            $result = $quangCaoModel->capNhatQuangCao($id, [
                'title' => $title,
                'image_url' => $imageUrl,
                'link_url' => $linkUrl,
                'position' => $position,
                'status' => $status,
                'start_date' => $startDateVal,
                'end_date' => $endDateVal,
            ]);

            if ($result) {
                if (!empty($oldImageUrl) && $oldImageUrl !== $imageUrl) {
                    $this->xoaFileLocalTuUrl($oldImageUrl);
                }

                $_SESSION['success'] = 'Cập nhật quảng cáo thành công!';
                header('Location: /WebsiteTinTuc/public/quan-tri/caidat/quang-cao/');
                exit;
            } else {
                $_SESSION['error'] = 'Lỗi khi cập nhật quảng cáo';
                header("Location: /WebsiteTinTuc/public/quan-tri/caidat/quang-cao/sua/?id={$id}");
                exit;
            }
        }

        $data = [
            'quangCao' => $quangCao,
            'isEdit' => true,
            'basePath' => '/WebsiteTinTuc/public'
        ];

        $this->renderView('quantri/caidat/them_quang_cao', $data);
    }

    /**
     * Xóa quảng cáo (POST + JSON response)
     */
    public function xoaQuangCao(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing ad ID']);
            return;
        }

        $quangCaoModel = new QuangCaoModel();
        $quangCao = $quangCaoModel->layChiTiet($id);

        if (empty($quangCao)) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Ad not found']);
            return;
        }

        if ($quangCaoModel->xoaQuangCao($id)) {
            if (!empty($quangCao['image_url'])) {
                $this->xoaFileLocalTuUrl($quangCao['image_url']);
            }

            $_SESSION['success'] = 'Xóa quảng cáo thành công!';
            echo json_encode(['success' => true, 'message' => 'Ad deleted successfully']);
        } else {
            $_SESSION['error'] = 'Lỗi khi xóa quảng cáo';
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to delete ad']);
        }
    }

    /**
     * Cập nhật trạng thái quảng cáo (POST + JSON)
     */
    public function capNhatTrangThaiQuangCao(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $status = isset($_POST['status']) ? trim($_POST['status']) : '';

        if (empty($id) || !in_array($status, ['active', 'inactive'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
            return;
        }

        $quangCaoModel = new QuangCaoModel();
        if ($quangCaoModel->capNhatTrangThai($id, $status)) {
            $_SESSION['success'] = 'Cập nhật trạng thái thành công!';
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update status']);
        }
    }

    /**
     * Trang quản lý giao diện (logo, tiêu đề, màu sắc, bật/tắt khối)
     */
    public function giaoDien(): void
    {
        $this->requireAdmin();
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        $caiDatModel = new CaiDatModel();
        $oldSiteLogo = $caiDatModel->layGiaTri('site_logo', '');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settings = [
                'site_title' => isset($_POST['site_title']) ? trim($_POST['site_title']) : 'Website Tin Tức',
                'site_logo' => isset($_POST['site_logo']) ? trim($_POST['site_logo']) : '',
                'primary_color' => isset($_POST['primary_color']) ? trim($_POST['primary_color']) : '#2c3e50',
                'secondary_color' => isset($_POST['secondary_color']) ? trim($_POST['secondary_color']) : '#3498db',
                'show_featured' => isset($_POST['show_featured']) ? '1' : '0',
                'show_latest' => isset($_POST['show_latest']) ? '1' : '0',
                'show_most_viewed' => isset($_POST['show_most_viewed']) ? '1' : '0',
                'show_categories' => isset($_POST['show_categories']) ? '1' : '0',
                'show_ads' => isset($_POST['show_ads']) ? '1' : '0',
            ];

            /* Xử lý upload logo nếu có */
            if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->xuLyUploadAnh($_FILES['logo_file']);
                if ($uploadResult['success']) {
                    $settings['site_logo'] = $uploadResult['url'];
                } else {
                    $_SESSION['error'] = 'Upload logo thất bại: ' . $uploadResult['message'];
                    header('Location: /WebsiteTinTuc/public/quan-tri/caidat/giao-dien/');
                    exit;
                }
            }

            if ($caiDatModel->capNhatNhieuCaiDat($settings)) {
                if (!empty($oldSiteLogo) && $oldSiteLogo !== $settings['site_logo']) {
                    $this->xoaFileLocalTuUrl($oldSiteLogo);
                }

                $_SESSION['success'] = 'Cập nhật giao diện thành công!';
            } else {
                $_SESSION['error'] = 'Lỗi khi cập nhật giao diện';
            }
            header('Location: /WebsiteTinTuc/public/quan-tri/caidat/giao-dien/');
            exit;
        }

        $settings = $caiDatModel->layTatCaCaiDat();

        $data = [
            'settings' => $settings,
            'basePath' => '/WebsiteTinTuc/public'
        ];

        $this->renderView('quantri/caidat/giao_dien', $data);
    }

    /**
     * Xử lý upload ảnh (dùng chung cho quảng cáo và logo)
     */
    private function xuLyUploadAnh(array $file): array
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024;

        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Chỉ chấp nhận file ảnh (JPG, PNG, GIF, WEBP)'];
        }

        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'File ảnh tối đa 5MB'];
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('ad_') . '.' . strtolower($ext);
        $uploadDir = dirname(__DIR__, 3) . '/public/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $destination = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return [
                'success' => true,
                'url' => '/WebsiteTinTuc/public/uploads/' . $filename
            ];
        }

        return ['success' => false, 'message' => 'Lỗi khi upload file'];
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
}
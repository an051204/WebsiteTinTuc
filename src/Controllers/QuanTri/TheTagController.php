<?php
namespace App\Controllers\QuanTri;

use App\Core\Controller;
use App\Core\Session;
use App\Models\TheTagModel;

class TheTagController extends Controller
{
    public function danhSach(): void
    {
        // Kiểm tra quyền admin
        $this->requireAdmin();

        // Ngăn browser cache
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        $theTagModel = new TheTagModel();

        // Lấy parameter từ URL
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Lấy danh sách tag
        $tags = $theTagModel->layDanhSachTagAdmin($search, $limit, $offset);
        $total = $theTagModel->demDanhSachTagAdmin($search);
        $totalPages = ceil($total / $limit);

        $data = [
            'tags' => $tags,
            'page' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'total' => $total,
            'basePath' => '/WebsiteTinTuc/public'
        ];

        $this->renderView('quantri/the-tag/danh_sach', $data);
    }

    public function them(): void
    {
        $this->requireAdmin();

        // Ngăn browser cache
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        $theTagModel = new TheTagModel();
        $tag = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';

            // Validate
            if (empty($name)) {
                $_SESSION['error'] = 'Tên tag không được để trống';
                header('Location: /WebsiteTinTuc/public/quan-tri/the-tag/them/');
                exit;
            }

            if (strlen($name) < 2) {
                $_SESSION['error'] = 'Tên tag phải ít nhất 2 ký tự';
                header('Location: /WebsiteTinTuc/public/quan-tri/the-tag/them/');
                exit;
            }

            if (strlen($name) > 50) {
                $_SESSION['error'] = 'Tên tag tối đa 50 ký tự';
                header('Location: /WebsiteTinTuc/public/quan-tri/the-tag/them/');
                exit;
            }

            // Kiểm tra tên tồn tại
            if ($theTagModel->kiemTraTenTagTonTai($name)) {
                $_SESSION['error'] = 'Tag này đã tồn tại';
                header('Location: /WebsiteTinTuc/public/quan-tri/the-tag/them/');
                exit;
            }

            // Thêm tag
            $tagId = $theTagModel->themTag($name);
            if ($tagId) {
                $_SESSION['success'] = 'Thêm tag thành công!';
                header('Location: /WebsiteTinTuc/public/quan-tri/the-tag/');
                exit;
            } else {
                $_SESSION['error'] = 'Lỗi khi thêm tag';
                header('Location: /WebsiteTinTuc/public/quan-tri/the-tag/them/');
                exit;
            }
        }

        $data = [
            'tag' => $tag,
            'basePath' => '/WebsiteTinTuc/public'
        ];

        $this->renderView('quantri/the-tag/them_moi', $data);
    }

    public function sua(): void
    {
        $this->requireAdmin();

        // Ngăn browser cache
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        $theTagModel = new TheTagModel();

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if (empty($id)) {
            http_response_code(404);
            echo "<h1>Lỗi 404 - Tag không tồn tại</h1>";
            return;
        }

        $tag = $theTagModel->layChiTietTag($id);

        if (empty($tag)) {
            http_response_code(404);
            echo "<h1>Lỗi 404 - Tag không tồn tại</h1>";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';

            // Validate
            if (empty($name)) {
                $_SESSION['error'] = 'Tên tag không được để trống';
                header("Location: /WebsiteTinTuc/public/quan-tri/the-tag/sua/?id={$id}");
                exit;
            }

            if (strlen($name) < 2) {
                $_SESSION['error'] = 'Tên tag phải ít nhất 2 ký tự';
                header("Location: /WebsiteTinTuc/public/quan-tri/the-tag/sua/?id={$id}");
                exit;
            }

            if (strlen($name) > 50) {
                $_SESSION['error'] = 'Tên tag tối đa 50 ký tự';
                header("Location: /WebsiteTinTuc/public/quan-tri/the-tag/sua/?id={$id}");
                exit;
            }

            // Kiểm tra tên tồn tại (ngoại trừ chính nó)
            if ($theTagModel->kiemTraTenTagTonTai($name, $id)) {
                $_SESSION['error'] = 'Tag này đã tồn tại';
                header("Location: /WebsiteTinTuc/public/quan-tri/the-tag/sua/?id={$id}");
                exit;
            }

            // Cập nhật tag
            if ($theTagModel->capNhatTag($id, $name)) {
                $_SESSION['success'] = 'Cập nhật tag thành công!';
                header('Location: /WebsiteTinTuc/public/quan-tri/the-tag/');
                exit;
            } else {
                $_SESSION['error'] = 'Lỗi khi cập nhật tag';
                header("Location: /WebsiteTinTuc/public/quan-tri/the-tag/sua/?id={$id}");
                exit;
            }
        }

        $data = [
            'tag' => $tag,
            'isEdit' => true,
            'basePath' => '/WebsiteTinTuc/public'
        ];

        $this->renderView('quantri/the-tag/them_moi', $data);
    }

    public function xoa(): void
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
            echo json_encode(['success' => false, 'message' => 'Missing tag ID']);
            return;
        }

        $theTagModel = new TheTagModel();

        // Kiểm tra tag tồn tại
        $tag = $theTagModel->layChiTietTag($id);
        if (empty($tag)) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Tag not found']);
            return;
        }

        if ($theTagModel->xoaTag($id)) {
            $_SESSION['success'] = 'Xóa tag thành công!';
            echo json_encode(['success' => true, 'message' => 'Tag deleted successfully']);
        } else {
            $_SESSION['error'] = 'Lỗi khi xóa tag';
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to delete tag']);
        }
    }
}

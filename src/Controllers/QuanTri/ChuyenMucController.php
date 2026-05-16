<?php
namespace App\Controllers\QuanTri;

use App\Core\Controller;
use App\Models\ChuyenMucModel;

class ChuyenMucController extends Controller
{
    // Danh sách chuyên mục
    public function danhSach(): void
    {
        $this->requireAdmin();

        $search = trim($_GET['search'] ?? '');
        $page = (int)($_GET['page'] ?? 1);
        $page = max(1, $page);

        $limit = 20;
        $offset = ($page - 1) * $limit;

        $chuyenMucModel = new ChuyenMucModel();

        $categories = $chuyenMucModel->layDanhSachChuyenMuc($search, $limit, $offset);
        $total = $chuyenMucModel->demDanhSachChuyenMuc($search);
        $totalPages = ceil($total / $limit);

        $allCategories = $chuyenMucModel->layTatCaChuyenMuc();

        $data = [
            'categories' => $categories,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'search' => $search,
            'allCategories' => $allCategories,
        ];

        $this->renderView('quantri/chuyenmuc/danh_sach', $data);
    }

    // Form thêm chuyên mục
    public function them(): void
    {
        $this->requireAdmin();

        $chuyenMucModel = new ChuyenMucModel();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $allCategories = $chuyenMucModel->layTatCaChuyenMuc();
            $data = ['allCategories' => $allCategories];
            $this->renderView('quantri/chuyenmuc/them_moi', $data);
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $parentId = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
        $status = $_POST['status'] ?? 'active';

        if (strlen($name) < 3) {
            $_SESSION['error'] = 'Tên chuyên mục phải ít nhất 3 ký tự';
            header('Location: /WebsiteTinTuc/public/quan-tri/chuyenmuc/them/');
            exit;
        }

        // Kiểm tra tên không bị trùng
        if ($chuyenMucModel->kiemTraTenTonTai($name)) {
            $_SESSION['error'] = 'Tên chuyên mục đã tồn tại';
            header('Location: /WebsiteTinTuc/public/quan-tri/chuyenmuc/them/');
            exit;
        }

        // Không cho phép chuyên mục là parent của chính nó
        if (!in_array($status, ['active', 'hidden'])) {
            $status = 'active';
        }

        if ($chuyenMucModel->themChuyenMuc($name, $description, $parentId, $status)) {
            $_SESSION['success'] = 'Chuyên mục đã được tạo thành công';
            header('Location: /WebsiteTinTuc/public/quan-tri/chuyenmuc/');
            exit;
        } else {
            $_SESSION['error'] = 'Lỗi tạo chuyên mục. Vui lòng thử lại';
            header('Location: /WebsiteTinTuc/public/quan-tri/chuyenmuc/them/');
            exit;
        }
    }

    // Form sửa chuyên mục
    public function sua(): void
    {
        $this->requireAdmin();

        $categoryId = (int)($_GET['id'] ?? 0);

        if ($categoryId < 1) {
            $_SESSION['error'] = 'ID chuyên mục không hợp lệ';
            header('Location: /WebsiteTinTuc/public/quan-tri/chuyenmuc/');
            exit;
        }

        $chuyenMucModel = new ChuyenMucModel();
        $category = $chuyenMucModel->layChiTietChuyenMucAdmin($categoryId);

        if (!$category) {
            $_SESSION['error'] = 'Chuyên mục không tồn tại';
            header('Location: /WebsiteTinTuc/public/quan-tri/chuyenmuc/');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $allCategories = $chuyenMucModel->layTatCaChuyenMuc();
            $data = [
                'category' => $category,
                'allCategories' => $allCategories,
            ];
            $this->renderView('quantri/chuyenmuc/them_moi', $data);
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $parentId = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
        $status = $_POST['status'] ?? 'active';

        if (strlen($name) < 3) {
            $_SESSION['error'] = 'Tên chuyên mục phải ít nhất 3 ký tự';
            header("Location: /WebsiteTinTuc/public/quan-tri/chuyenmuc/sua/?id={$categoryId}");
            exit;
        }

        // Kiểm tra tên không bị trùng với chuyên mục khác
        if ($chuyenMucModel->kiemTraTenTonTai($name, $categoryId)) {
            $_SESSION['error'] = 'Tên chuyên mục đã tồn tại';
            header("Location: /WebsiteTinTuc/public/quan-tri/chuyenmuc/sua/?id={$categoryId}");
            exit;
        }

        // Không cho phép chuyên mục là parent của chính nó
        if ($parentId === $categoryId) {
            $_SESSION['error'] = 'Chuyên mục không thể là parent của chính nó';
            header("Location: /WebsiteTinTuc/public/quan-tri/chuyenmuc/sua/?id={$categoryId}");
            exit;
        }

        if (!in_array($status, ['active', 'hidden'])) {
            $status = 'active';
        }

        if ($chuyenMucModel->capNhatChuyenMuc($categoryId, $name, $description, $parentId, $status)) {
            $_SESSION['success'] = 'Chuyên mục đã được cập nhật thành công';
            header('Location: /WebsiteTinTuc/public/quan-tri/chuyenmuc/');
            exit;
        } else {
            $_SESSION['error'] = 'Lỗi cập nhật chuyên mục. Vui lòng thử lại';
            header("Location: /WebsiteTinTuc/public/quan-tri/chuyenmuc/sua/?id={$categoryId}");
            exit;
        }
    }

    // Xóa chuyên mục
    public function xoa(): void
    {
        $this->requireAdmin();

        $categoryId = (int)($_POST['category_id'] ?? 0);

        if ($categoryId < 1) {
            $_SESSION['error'] = 'ID chuyên mục không hợp lệ';
            header('Location: /WebsiteTinTuc/public/quan-tri/chuyenmuc/');
            exit;
        }

        $chuyenMucModel = new ChuyenMucModel();

        // Kiểm tra xem chuyên mục có bài viết không
        if ($chuyenMucModel->demBaiVietTheoChuyenMuc($categoryId) > 0) {
            $_SESSION['error'] = 'Không thể xóa chuyên mục có bài viết';
            header('Location: /WebsiteTinTuc/public/quan-tri/chuyenmuc/');
            exit;
        }

        // Kiểm tra xem có chuyên mục con không
        if ($chuyenMucModel->demChuyenMucCon($categoryId) > 0) {
            $_SESSION['error'] = 'Không thể xóa chuyên mục có chuyên mục con';
            header('Location: /WebsiteTinTuc/public/quan-tri/chuyenmuc/');
            exit;
        }

        if ($chuyenMucModel->xoaChuyenMuc($categoryId)) {
            $_SESSION['success'] = 'Chuyên mục đã được xóa thành công';
        } else {
            $_SESSION['error'] = 'Lỗi xóa chuyên mục. Vui lòng thử lại';
        }

        header('Location: /WebsiteTinTuc/public/quan-tri/chuyenmuc/');
        exit;
    }

    // Cập nhật thứ tự sắp xếp
    public function capNhatThuTu(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ']);
            exit;
        }

        $orders = $_POST['orders'] ?? [];

        if (empty($orders)) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        $chuyenMucModel = new ChuyenMucModel();

        try {
            foreach ($orders as $index => $categoryId) {
                $categoryId = (int)$categoryId;
                $sortOrder = $index;
                $chuyenMucModel->capNhatThuTu($categoryId, $sortOrder);
            }

            echo json_encode(['success' => true, 'message' => 'Thứ tự đã được cập nhật']);
            exit;
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
            exit;
        }
    }
}

<?php
namespace App\Controllers\QuanTri;

use App\Core\Controller;
use App\Core\Session;
use App\Models\BinhLuanModel;

class BinhLuanController extends Controller
{
    public function danhSach(): void
    {
        // Kiểm tra quyền admin
        $this->requireAdmin();

        // Ngăn browser cache
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        $binhLuanModel = new BinhLuanModel();

        // Lấy parameter từ URL
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';

        // Xác thực status
        $validStatuses = ['approved', 'pending', 'hidden', 'spam'];
        if (!empty($status) && !in_array($status, $validStatuses)) {
            $status = '';
        }

        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Lấy danh sách bình luận
        $comments = $binhLuanModel->layDanhSachBinhLuanAdmin($search, $status, $limit, $offset);
        $total = $binhLuanModel->demDanhSachBinhLuan($search, $status);

        $totalPages = ceil($total / $limit);

        // Thống kê
        $stats = [
            'total' => $binhLuanModel->demTatCaBinhLuan(),
            'approved' => $this->demBinhLuanTheoStatus('approved', $binhLuanModel),
            'pending' => $this->demBinhLuanTheoStatus('pending', $binhLuanModel),
            'hidden' => $this->demBinhLuanTheoStatus('hidden', $binhLuanModel),
            'spam' => $this->demBinhLuanTheoStatus('spam', $binhLuanModel),
        ];

        $data = [
            'comments' => $comments,
            'page' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'status' => $status,
            'stats' => $stats,
            'basePath' => '/WebsiteTinTuc/public'
        ];

        $this->renderView('quantri/binhluan/danh_sach', $data);
    }

    public function capNhatTrangThai(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $status = isset($_POST['status']) ? trim($_POST['status']) : '';

        if (empty($id) || empty($status)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }

        $validStatuses = ['approved', 'pending', 'hidden', 'spam'];
        if (!in_array($status, $validStatuses)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid status']);
            return;
        }

        $binhLuanModel = new BinhLuanModel();
        
        if ($binhLuanModel->capNhatTrangThaiComment($id, $status)) {
            $_SESSION['success'] = "Cập nhật trạng thái bình luận thành công!";
            echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
        } else {
            $_SESSION['error'] = "Cập nhật trạng thái bình luận thất bại!";
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update status']);
        }
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
            echo json_encode(['success' => false, 'message' => 'Missing comment ID']);
            return;
        }

        $binhLuanModel = new BinhLuanModel();

        if ($binhLuanModel->xoaBinhLuan($id)) {
            $_SESSION['success'] = "Xóa bình luận thành công!";
            echo json_encode(['success' => true, 'message' => 'Comment deleted successfully']);
        } else {
            $_SESSION['error'] = "Xóa bình luận thất bại!";
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to delete comment']);
        }
    }

    public function quanLyTuKhoa(): void
    {
        $this->requireAdmin();

        // Ngăn browser cache
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        $binhLuanModel = new BinhLuanModel();
        $badWords = $binhLuanModel->layTatCaTuKhoaXau();

        $data = [
            'badWords' => $badWords,
            'basePath' => '/WebsiteTinTuc/public'
        ];

        $this->renderView('quantri/binhluan/quan_ly_tu_khoa', $data);
    }

    public function themTuKhoa(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $word = isset($_POST['word']) ? trim($_POST['word']) : '';

        if (empty($word) || strlen($word) < 2) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Từ khóa phải ít nhất 2 ký tự']);
            return;
        }

        $binhLuanModel = new BinhLuanModel();

        if ($binhLuanModel->themTuKhoaXau($word)) {
            $_SESSION['success'] = "Thêm từ khóa xấu thành công!";
            echo json_encode(['success' => true, 'message' => 'Bad word added successfully']);
        } else {
            $_SESSION['error'] = "Từ khóa này đã tồn tại!";
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Bad word already exists']);
        }
    }

    public function xoaTuKhoa(): void
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
            echo json_encode(['success' => false, 'message' => 'Missing bad word ID']);
            return;
        }

        $binhLuanModel = new BinhLuanModel();

        if ($binhLuanModel->xoaTuKhoaXau($id)) {
            $_SESSION['success'] = "Xóa từ khóa xấu thành công!";
            echo json_encode(['success' => true, 'message' => 'Bad word deleted successfully']);
        } else {
            $_SESSION['error'] = "Xóa từ khóa xấu thất bại!";
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to delete bad word']);
        }
    }

    /**
     * Helper: đếm bình luận theo status
     */
    private function demBinhLuanTheoStatus(string $status, BinhLuanModel $model): int
    {
        return $model->demDanhSachBinhLuan('', $status);
    }
}

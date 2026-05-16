<?php
namespace App\Controllers\QuanTri;

use App\Core\Controller;
use App\Models\TaiKhoanModel;

class TaiKhoanController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new TaiKhoanModel();
    }

    public function danhSach(): void
    {
        $this->requireAdmin();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $roleId = isset($_GET['role_id']) ? (int)$_GET['role_id'] : null;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        if ($page < 1) {
            $page = 1;
        }

        $users = $this->model->layDanhSachUsers($page, 20, $roleId, $search);
        $totalUsers = $this->model->demUsersVoiFilter($roleId, $search);
        $roles = $this->model->layDanhSachRoles();

        $totalPages = ceil($totalUsers / 20);
        if ($totalPages < 1) {
            $totalPages = 1;
        }

        $data = [
            'users' => $users,
            'roles' => $roles,
            'page' => $page,
            'totalPages' => $totalPages,
            'roleId' => $roleId,
            'search' => $search,
            'totalUsers' => $totalUsers,
        ];

        $this->renderView('quantri/taikhoan/danh_sach', $data);
    }

    public function capNhatRole(): void
    {
        $this->requireAdmin();

        $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
        $newRoleId = isset($_POST['role_id']) ? (int)$_POST['role_id'] : 0;

        if ($userId <= 0 || !in_array($newRoleId, [1, 2, 3])) {
            $_SESSION['error'] = 'Dữ liệu không hợp lệ';
            header('Location: /WebsiteTinTuc/public/quan-tri/tai-khoan/');
            exit;
        }

        $user = $this->model->layThongTinUser($userId);
        if (!$user) {
            $_SESSION['error'] = 'Người dùng không tồn tại';
            header('Location: /WebsiteTinTuc/public/quan-tri/tai-khoan/');
            exit;
        }

        if ($this->model->capNhatRole($userId, $newRoleId)) {
            $_SESSION['success'] = 'Cập nhật quyền thành công';
        } else {
            $_SESSION['error'] = 'Cập nhật quyền thất bại';
        }

        header('Location: /WebsiteTinTuc/public/quan-tri/tai-khoan/');
        exit;
    }

    public function capNhatStatus(): void
    {
        $this->requireAdmin();

        $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
        $newStatus = isset($_POST['status']) ? trim($_POST['status']) : '';

        if ($userId <= 0 || !in_array($newStatus, ['active', 'locked'])) {
            $_SESSION['error'] = 'Dữ liệu không hợp lệ';
            header('Location: /WebsiteTinTuc/public/quan-tri/tai-khoan/');
            exit;
        }

        $user = $this->model->layThongTinUser($userId);
        if (!$user) {
            $_SESSION['error'] = 'Người dùng không tồn tại';
            header('Location: /WebsiteTinTuc/public/quan-tri/tai-khoan/');
            exit;
        }

        if ($this->model->capNhatStatus($userId, $newStatus)) {
            $statusText = ($newStatus === 'active') ? 'kích hoạt' : 'khóa';
            $_SESSION['success'] = "Tài khoản đã được $statusText thành công";
        } else {
            $_SESSION['error'] = 'Cập nhật trạng thái thất bại';
        }

        header('Location: /WebsiteTinTuc/public/quan-tri/tai-khoan/');
        exit;
    }

    public function xoa(): void
    {
        $this->requireAdmin();

        $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

        if ($userId <= 0) {
            $_SESSION['error'] = 'Dữ liệu không hợp lệ';
            header('Location: /WebsiteTinTuc/public/quan-tri/tai-khoan/');
            exit;
        }

        $user = $this->model->layThongTinUser($userId);
        if (!$user) {
            $_SESSION['error'] = 'Người dùng không tồn tại';
            header('Location: /WebsiteTinTuc/public/quan-tri/tai-khoan/');
            exit;
        }

        $userName = htmlspecialchars($user['full_name']);

        if ($this->model->xoaTaiKhoan($userId)) {
            $_SESSION['success'] = "Tài khoản '$userName' và tất cả dữ liệu liên quan đã được xóa";
        } else {
            $_SESSION['error'] = 'Xóa tài khoản thất bại';
        }

        header('Location: /WebsiteTinTuc/public/quan-tri/tai-khoan/');
        exit;
    }
}
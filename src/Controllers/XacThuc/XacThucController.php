<?php
namespace App\Controllers\XacThuc;

use App\Core\Controller;
use App\Models\TaiKhoanModel;
use App\Core\GuiMail;

class XacThucController extends Controller
{
    private TaiKhoanModel $model;
    private GuiMail $mailer;

    public function __construct()
    {
        $this->model = new TaiKhoanModel();
        $this->mailer = new GuiMail();
    }

    public function dang_ky(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->renderView('xacthuc/dang_ky', []);
            return;
        }

        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (strlen($fullName) < 2) {
            $_SESSION['error'] = 'Tên đầy đủ phải ít nhất 2 ký tự';
            header('Location: /WebsiteTinTuc/public/dang-ky/');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email không hợp lệ';
            header('Location: /WebsiteTinTuc/public/dang-ky/');
            exit;
        }

        if ($this->model->kiemTraEmailTonTai($email)) {
            $_SESSION['error'] = 'Email này đã được đăng ký';
            header('Location: /WebsiteTinTuc/public/dang-ky/');
            exit;
        }

        if (strlen($password) < 8) {
            $_SESSION['error'] = 'Mật khẩu phải ít nhất 8 ký tự';
            header('Location: /WebsiteTinTuc/public/dang-ky/');
            exit;
        }

        if (!$this->kiemTraMatKhauPhuc($password)) {
            $_SESSION['error'] = 'Mật khẩu phải chứa chữ hoa, chữ thường, số và ký tự đặc biệt';
            header('Location: /WebsiteTinTuc/public/dang-ky/');
            exit;
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['error'] = 'Mật khẩu xác nhận không khớp';
            header('Location: /WebsiteTinTuc/public/dang-ky/');
            exit;
        }

        $userId = $this->model->taoTaiKhoan($fullName, $email, $password);
        if (!$userId) {
            $_SESSION['error'] = 'Lỗi tạo tài khoản. Vui lòng thử lại';
            header('Location: /WebsiteTinTuc/public/dang-ky/');
            exit;
        }

        $this->mailer->sendWelcomeEmail($email, $fullName);

        $_SESSION['success'] = 'Đăng ký thành công! Vui lòng đăng nhập';
        header('Location: /WebsiteTinTuc/public/dang-nhap/');
        exit;
    }

    public function dang_nhap(): void
    {
        if (!empty($_SESSION['user_id'])) {
            header('Location: /WebsiteTinTuc/public/');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->renderView('xacthuc/dang_nhap', []);
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Vui lòng nhập email và mật khẩu';
            header('Location: /WebsiteTinTuc/public/dang-nhap/');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email không hợp lệ';
            header('Location: /WebsiteTinTuc/public/dang-nhap/');
            exit;
        }

        $user = $this->model->kiemTraThongTin($email, $password);
        if (!$user) {
            $_SESSION['error'] = 'Email hoặc mật khẩu không chính xác';
            header('Location: /WebsiteTinTuc/public/dang-nhap/');
            exit;
        }

        if ($user['status'] !== 'active') {
            $_SESSION['error'] = 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên';
            header('Location: /WebsiteTinTuc/public/dang-nhap/');
            exit;
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['avatar'] = !empty($user['avatar']) ? $user['avatar'] : '/WebsiteTinTuc/public/assets/default-avatar.png';

        $_SESSION['success'] = 'Đăng nhập thành công!';
        header('Location: /WebsiteTinTuc/public/');
        exit;
    }

    public function quen_mat_khau(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->renderView('xacthuc/quen_mat_khau', []);
            return;
        }

        $email = trim($_POST['email'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email không hợp lệ';
            header('Location: /WebsiteTinTuc/public/quen-mat-khau/');
            exit;
        }

        $user = $this->model->layUserByEmail($email);
        if (!$user) {
            $_SESSION['error'] = 'Email này chưa được đăng ký';
            header('Location: /WebsiteTinTuc/public/quen-mat-khau/');
            exit;
        }

        $resetToken = $this->model->taoDuongDanReset((int)$user['id']);
        if (!$resetToken) {
            $_SESSION['error'] = 'Lỗi xử lý yêu cầu. Vui lòng thử lại';
            header('Location: /WebsiteTinTuc/public/quen-mat-khau/');
            exit;
        }

        $siteUrl = $_ENV['SITE_URL'] ?? 'http://localhost/WebsiteTinTuc/public';
        $resetLink = $siteUrl . '/reset-mat-khau/?token=' . $resetToken;
        $mailSent = $this->mailer->sendPasswordResetEmail($email, $user['full_name'], $resetLink);

        if (!$mailSent) {
            $_SESSION['error'] = 'Không thể gửi email. Vui lòng thử lại';
            header('Location: /WebsiteTinTuc/public/quen-mat-khau/');
            exit;
        }

        $_SESSION['success'] = 'Email xác nhận đã được gửi. Vui lòng kiểm tra email của bạn';
        header('Location: /WebsiteTinTuc/public/dang-nhap/');
        exit;
    }

    public function reset_mat_khau(): void
    {
        $token = trim($_GET['token'] ?? $_POST['token'] ?? '');

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $user = $this->model->layUserByResetToken($token);
            if (!$user) {
                $_SESSION['error'] = 'Liên kết không hợp lệ hoặc đã hết hạn';
                header('Location: /WebsiteTinTuc/public/quen-mat-khau/');
                exit;
            }

            $this->renderView('xacthuc/reset_mat_khau', ['token' => $token]);
            return;
        }

        $newPassword = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        $user = $this->model->layUserByResetToken($token);
        if (!$user) {
            $_SESSION['error'] = 'Liên kết không hợp lệ hoặc đã hết hạn';
            header('Location: /WebsiteTinTuc/public/quen-mat-khau/');
            exit;
        }

        if (strlen($newPassword) < 8) {
            $_SESSION['error'] = 'Mật khẩu phải ít nhất 8 ký tự';
            header('Location: /WebsiteTinTuc/public/reset-mat-khau/?token=' . urlencode($token) . '/');
            exit;
        }

        if (!$this->kiemTraMatKhauPhuc($newPassword)) {
            $_SESSION['error'] = 'Mật khẩu phải chứa chữ hoa, chữ thường, số và ký tự đặc biệt';
            header('Location: /WebsiteTinTuc/public/reset-mat-khau/?token=' . urlencode($token) . '/');
            exit;
        }

        if ($newPassword !== $passwordConfirm) {
            $_SESSION['error'] = 'Mật khẩu xác nhận không khớp';
            header('Location: /WebsiteTinTuc/public/reset-mat-khau/?token=' . urlencode($token) . '/');
            exit;
        }

        $updated = $this->model->capNhatMatKhauByToken($token, $newPassword);
        if (!$updated) {
            $_SESSION['error'] = 'Lỗi cập nhật mật khẩu. Vui lòng thử lại';
            header('Location: /WebsiteTinTuc/public/quen-mat-khau/');
            exit;
        }

        $_SESSION['success'] = 'Mật khẩu đã được cập nhật thành công. Vui lòng đăng nhập';
        header('Location: /WebsiteTinTuc/public/dang-nhap/');
        exit;
    }

    public function dang_xuat(): void
    {
        session_destroy();
        header('Location: /WebsiteTinTuc/public/');
        exit;
    }

    public function cap_nhat_mat_khau(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /WebsiteTinTuc/public/dang-nhap/');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /WebsiteTinTuc/public/ca-nhan/');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (empty($oldPassword) || empty($newPassword)) {
            $_SESSION['error'] = 'Vui lòng nhập mật khẩu cũ và mật khẩu mới';
            header('Location: /WebsiteTinTuc/public/ca-nhan/?tab=thong-tin');
            exit;
        }

        if (strlen($newPassword) < 8) {
            $_SESSION['error'] = 'Mật khẩu mới phải ít nhất 8 ký tự';
            header('Location: /WebsiteTinTuc/public/ca-nhan/?tab=thong-tin');
            exit;
        }

        if (!$this->kiemTraMatKhauPhuc($newPassword)) {
            $_SESSION['error'] = 'Mật khẩu phải chứa chữ hoa, chữ thường, số và ký tự đặc biệt';
            header('Location: /WebsiteTinTuc/public/ca-nhan/?tab=thong-tin');
            exit;
        }

        if ($newPassword !== $passwordConfirm) {
            $_SESSION['error'] = 'Mật khẩu xác nhận không khớp';
            header('Location: /WebsiteTinTuc/public/ca-nhan/?tab=thong-tin');
            exit;
        }

        $updated = $this->model->capNhatMatKhauByUser($userId, $oldPassword, $newPassword);
        if (!$updated) {
            $_SESSION['error'] = 'Mật khẩu cũ không chính xác';
            header('Location: /WebsiteTinTuc/public/ca-nhan/?tab=thong-tin');
            exit;
        }

        $_SESSION['success'] = 'Mật khẩu đã được cập nhật thành công';
        header('Location: /WebsiteTinTuc/public/ca-nhan/?tab=thong-tin');
        exit;
    }

    private function kiemTraMatKhauPhuc(string $password): bool
    {
        $hasUppercase = preg_match('/[A-Z]/', $password);
        $hasLowercase = preg_match('/[a-z]/', $password);
        $hasNumber = preg_match('/[0-9]/', $password);
        $hasSpecial = preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password);

        return $hasUppercase && $hasLowercase && $hasNumber && $hasSpecial;
    }
}

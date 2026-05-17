<?php
namespace App\Controllers\NguoiDung;

use App\Core\Controller;
use App\Models\TrangCaNhanModel;

class TrangCaNhanController extends Controller
{
    public function index(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /WebsiteTinTuc/public/dang-nhap');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $tab = trim($_GET['tab'] ?? 'thong-tin');
        $page = (int)($_GET['page'] ?? 1);
        $limit = 10;

        if ($page < 1) {
            $page = 1;
        }

        $offset = ($page - 1) * $limit;
        $model = new TrangCaNhanModel();
        $user = $model->layThongTinUser($userId);

        if (!$user) {
            header('Location: /WebsiteTinTuc/public/');
            exit;
        }

        $data = [
            'user' => $user,
            'tab' => $tab,
            'page' => $page,
            'totalArticles' => 0,
            'totalComments' => 0,
            'totalLikes' => 0,
            'articles' => [],
            'comments' => [],
            'likes' => [],
            'totalPages' => 0,
        ];

        $data['totalLikes'] = $model->demBaiVietDaThich($userId);
        $data['totalArticles'] = $model->demBaiVietDaLuu($userId);
        $data['totalComments'] = $model->demBinhLuanDaDang($userId);

        if ($tab === 'thich') {
            $total = $model->demBaiVietDaThich($userId);
            $articles = $model->layBaiVietDaThich($userId, $limit, $offset);
            $totalPages = $total > 0 ? (int)ceil($total / $limit) : 0;

            if ($page > $totalPages && $totalPages > 0) {
                $page = $totalPages;
                $offset = ($page - 1) * $limit;
                $articles = $model->layBaiVietDaThich($userId, $limit, $offset);
            }

            $data['tab'] = 'thich';
            $data['articles'] = $articles;
            $data['totalLikes'] = $total;
            $data['totalPages'] = $totalPages;
            $data['page'] = $page;
        } elseif ($tab === 'luu') {
            $total = $model->demBaiVietDaLuu($userId);
            $articles = $model->layBaiVietDaLuu($userId, $limit, $offset);
            $totalPages = $total > 0 ? (int)ceil($total / $limit) : 0;

            if ($page > $totalPages && $totalPages > 0) {
                $page = $totalPages;
                $offset = ($page - 1) * $limit;
                $articles = $model->layBaiVietDaLuu($userId, $limit, $offset);
            }

            $data['tab'] = 'luu';
            $data['articles'] = $articles;
            $data['totalArticles'] = $total;
            $data['totalPages'] = $totalPages;
            $data['page'] = $page;
        } elseif ($tab === 'binh-luan') {
            $total = $model->demBinhLuanDaDang($userId);
            $comments = $model->layBinhLuanDaDang($userId, $limit, $offset);
            $totalPages = $total > 0 ? (int)ceil($total / $limit) : 0;

            if ($page > $totalPages && $totalPages > 0) {
                $page = $totalPages;
                $offset = ($page - 1) * $limit;
                $comments = $model->layBinhLuanDaDang($userId, $limit, $offset);
            }

            $data['tab'] = 'binh-luan';
            $data['comments'] = $comments;
            $data['totalComments'] = $total;
            $data['totalPages'] = $totalPages;
            $data['page'] = $page;
        } else {
            $data['tab'] = 'thong-tin';
        }

        $this->renderView('nguoidung/ca_nhan', $data);
    }

    public function capNhatThongTin(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /WebsiteTinTuc/public/dang-nhap');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /WebsiteTinTuc/public/ca-nhan');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (strlen($fullName) < 2) {
            $_SESSION['error'] = 'Tên đầy đủ phải ít nhất 2 ký tự';
            header('Location: /WebsiteTinTuc/public/ca-nhan?tab=thong-tin');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email không hợp lệ';
            header('Location: /WebsiteTinTuc/public/ca-nhan?tab=thong-tin');
            exit;
        }

        $model = new TrangCaNhanModel();
        
        if ($model->kiemTraEmailTonTaiKhac($email, $userId)) {
            $_SESSION['error'] = 'Email này đã được sử dụng bởi tài khoản khác';
            header('Location: /WebsiteTinTuc/public/ca-nhan?tab=thong-tin');
            exit;
        }

        $data = [
            'full_name' => $fullName,
            'email' => $email,
        ];

        if ($model->capNhatThongTinUser($userId, $data)) {
            $_SESSION['success'] = 'Cập nhật thông tin thành công';
            $_SESSION['full_name'] = $fullName;
            $_SESSION['email'] = $email;
        } else {
            $_SESSION['error'] = 'Cập nhật thông tin thất bại';
        }

        header('Location: /WebsiteTinTuc/public/ca-nhan?tab=thong-tin');
        exit;
    }

    public function uploadAvatar(): void
    {
        if (empty($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Chưa đăng nhập']);
            exit;
        }

        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Phương thức không hợp lệ']);
            exit;
        }

        if (!isset($_FILES['avatar'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Không có file được tải lên']);
            exit;
        }

        $file = $_FILES['avatar'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB

        // Validate file
        if ($file['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['error' => 'Lỗi tải file: ' . $this->getUploadError($file['error'])]);
            exit;
        }

        if (!in_array($file['type'], $allowed_types)) {
            http_response_code(400);
            echo json_encode(['error' => 'Chỉ chấp nhận file ảnh (JPEG, PNG, GIF, WebP)']);
            exit;
        }

        if ($file['size'] > $max_size) {
            http_response_code(400);
            echo json_encode(['error' => 'File quá lớn (tối đa 5MB)']);
            exit;
        }

        // Generate unique filename
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/WebsiteTinTuc/public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
        $filepath = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            http_response_code(500);
            echo json_encode(['error' => 'Lỗi lưu file']);
            exit;
        }

        // Update database
        $userId = $_SESSION['user_id'];
        $avatarUrl = '/WebsiteTinTuc/public/uploads/' . $filename;
        $oldAvatarUrl = $_SESSION['avatar'] ?? '';
        
        $model = new TrangCaNhanModel();
        if ($model->capNhatAvatar($userId, $avatarUrl)) {
            $_SESSION['avatar'] = $avatarUrl;

            if (!empty($oldAvatarUrl) && $oldAvatarUrl !== $avatarUrl) {
                $this->xoaFileLocalTuUrl($oldAvatarUrl);
            }

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Avatar cập nhật thành công',
                'avatar_url' => $avatarUrl
            ]);
        } else {
            // Delete uploaded file if DB update fails
            @unlink($filepath);
            http_response_code(500);
            echo json_encode(['error' => 'Lỗi cập nhật cơ sở dữ liệu']);
        }
        exit;
    }

    private function getUploadError(int $errorCode): string
    {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File vượt quá kích thước cho phép bởi server',
            UPLOAD_ERR_FORM_SIZE => 'File vượt quá kích thước cho phép bởi form',
            UPLOAD_ERR_PARTIAL => 'File được tải lên một phần',
            UPLOAD_ERR_NO_FILE => 'Không có file được tải lên',
            UPLOAD_ERR_NO_TMP_DIR => 'Không có thư mục tạm thời',
            UPLOAD_ERR_CANT_WRITE => 'Không thể ghi file vào đĩa',
            UPLOAD_ERR_EXTENSION => 'Một extension PHP đã dừng việc tải file',
        ];
        return $errors[$errorCode] ?? 'Lỗi không xác định';
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


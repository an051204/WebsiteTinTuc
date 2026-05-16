<?php
namespace App\Controllers\QuanTri;

use App\Core\Controller;

class UploadController extends Controller
{
    private const MAX_FILE_SIZE = 5 * 1024 * 1024;
    private const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private const UPLOAD_DIR = '/WebsiteTinTuc/public/uploads/';

    public function upload(): void
    {
        // Check if user is logged in (both admins and regular users can upload)
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(401);
            echo json_encode(['error' => 'Vui lòng đăng nhập để tải ảnh']);
            return;
        }

        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Phương thức không hợp lệ']);
            return;
        }

        if (!isset($_FILES['file'])) {
            $contentLength = (int)($_SERVER['CONTENT_LENGTH'] ?? 0);
            $postMax = (string)(ini_get('post_max_size') ?: '');
            $postMaxBytes = $this->iniSizeToBytes($postMax);

            if ($contentLength > 0 && $postMaxBytes > 0 && $contentLength > $postMaxBytes) {
                http_response_code(413);
                echo json_encode(['error' => 'File quá lớn (vượt quá giới hạn server: post_max_size=' . $postMax . ')']);
                return;
            }

            http_response_code(400);
            echo json_encode(['error' => 'Không có file được tải lên']);
            return;
        }

        $file = $_FILES['file'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            $errorMsg = $this->getUploadError($file['error']);
            if ($file['error'] === UPLOAD_ERR_INI_SIZE) {
                $uploadMax = (string)(ini_get('upload_max_filesize') ?: '');
                $postMax = (string)(ini_get('post_max_size') ?: '');
                $errorMsg .= ' (upload_max_filesize=' . $uploadMax . ', post_max_size=' . $postMax . ')';
            }
            echo json_encode(['error' => 'Lỗi tải file: ' . $errorMsg]);
            return;
        }

        if ($file['size'] > self::MAX_FILE_SIZE) {
            http_response_code(400);
            $sizeMb = round($file['size'] / 1024 / 1024, 2);
            echo json_encode(['error' => 'File quá lớn (' . $sizeMb . 'MB). Giới hạn tối đa 5MB']);
            return;
        }

        $mimeType = '';
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
                $mimeType = finfo_file($finfo, $file['tmp_name']) ?: '';
                finfo_close($finfo);
            }
        }
        if (empty($mimeType)) {
            $mimeType = $file['type'] ?? '';
        }

        if (!in_array($mimeType, self::ALLOWED_TYPES)) {
            http_response_code(400);
            echo json_encode(['error' => 'Loại file không được hỗ trợ. Chỉ chấp nhận ảnh (JPG, PNG, GIF, WebP)']);
            return;
        }

        $fileName = $this->generateFileName($file['name'], $mimeType);
        $uploadPath = dirname(dirname(dirname(__DIR__))) . '/public/uploads/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadPath . $fileName)) {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'url' => self::UPLOAD_DIR . $fileName,
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Không thể lưu file. Vui lòng kiểm tra quyền ghi thư mục public/uploads']);
        }
    }

    private function generateFileName(string $originalName, string $mimeType): string
    {
        $extension = strtolower((string)pathinfo($originalName, PATHINFO_EXTENSION));
        if (empty($extension)) {
            $extension = match ($mimeType) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
                default => 'jpg',
            };
        }

        $timestamp = time();
        $random = bin2hex(random_bytes(4));
        $fileName = 'article_' . $timestamp . '_' . $random . '.' . $extension;

        return $fileName;
    }

    private function getUploadError(int $errorCode): string
    {
        return match($errorCode) {
            UPLOAD_ERR_INI_SIZE => 'File vượt quá kích thước tối đa cho phép của server',
            UPLOAD_ERR_FORM_SIZE => 'File vượt quá kích thước tối đa cho phép của form',
            UPLOAD_ERR_PARTIAL => 'File chỉ được tải lên một phần',
            UPLOAD_ERR_NO_FILE => 'Không có file nào được chọn',
            UPLOAD_ERR_NO_TMP_DIR => 'Thư mục tạm không tồn tại',
            UPLOAD_ERR_CANT_WRITE => 'Không thể ghi file vào đĩa',
            UPLOAD_ERR_EXTENSION => 'Một extension của PHP đã dừng upload file',
            default => 'Lỗi không xác định'
        };
    }

    private function iniSizeToBytes(string $value): int
    {
        $value = trim($value);
        if ($value === '') {
            return 0;
        }

        $lastChar = strtolower($value[strlen($value) - 1]);
        $num = (int)$value;

        return match ($lastChar) {
            'g' => $num * 1024 * 1024 * 1024,
            'm' => $num * 1024 * 1024,
            'k' => $num * 1024,
            default => (int)$value,
        };
    }
}
?>

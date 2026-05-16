<?php
namespace App\Core;

use App\Models\CaiDatModel;

class Controller
{
    protected static array $routeParams = [];

    protected function renderView(string $viewPath, array $data = []): void
    {
        if (!array_key_exists('settings', $data)) {
            static $cachedSettings = null;

            if ($cachedSettings === null) {
                $cachedSettings = (new CaiDatModel())->layTatCaCaiDat();
            }

            $data['settings'] = $cachedSettings;
        }

        extract($data);

        $viewFile = dirname(__DIR__, 2) . '/views/' . $viewPath . '.php';

        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: {$viewFile}");
        }

        require $viewFile;
    }

    protected function getRouteParam(string $key, mixed $default = null): mixed
    {
        return self::$routeParams[$key] ?? $default;
    }

    public static function setRouteParams(array $params): void
    {
        self::$routeParams = $params;
    }

    /**
     * Kiểm tra người dùng đã đăng nhập và có quyền admin
     * Nếu không, redirect đến trang đăng nhập hoặc trang chủ
     */
    protected function requireAdmin(): void
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập';
            header('Location: /WebsiteTinTuc/public/dang-nhap/');
            exit;
        }

        // Kiểm tra quyền admin
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] !== 1) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /WebsiteTinTuc/public/');
            exit;
        }
    }
}

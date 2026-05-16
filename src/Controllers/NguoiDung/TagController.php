<?php
namespace App\Controllers\NguoiDung;

use App\Core\Controller;
use App\Models\BaiVietModel;
use App\Models\TheTagModel;

class TagController extends Controller
{
    public function index(): void
    {
        // Ngăn browser cache
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        $slug = urldecode($this->getRouteParam('slug') ?? '');
        $page = (int)($_GET['page'] ?? 1);
        $limit = 10;

        if (empty($slug)) {
            http_response_code(404);
            echo "<h1>Lỗi 404 - Thẻ tag không tồn tại</h1>";
            return;
        }

        $theTagModel = new TheTagModel();
        $baiVietModel = new BaiVietModel();

        $tag = $theTagModel->layTagTheoSlug($slug);

        if (!$tag) {
            http_response_code(404);
            echo "<h1>Lỗi 404 - Thẻ tag không tồn tại</h1>";
            return;
        }

        if ($page < 1) {
            $page = 1;
        }

        $offset = ($page - 1) * $limit;
        $totalArticles = $baiVietModel->demBaiVietTheoTag($tag['id']);
        $totalPages = (int)ceil($totalArticles / $limit);

        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
        }

        $articles = $baiVietModel->layBaiVietTheoTag($tag['id'], $limit, $offset);

        // Fetch tags for each article
        foreach ($articles as &$article) {
            $article['tags'] = $theTagModel->layTagsTheoBaiViet($article['id']);
        }
        unset($article);

        $data = [
            'tag' => $tag,
            'articles' => $articles,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalArticles' => $totalArticles,
        ];

        $this->renderView('nguoidung/tag', $data);
    }

    public function listAll(): void
    {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        $theTagModel = new TheTagModel();
        $baiVietModel = new BaiVietModel();

        $tags = $theTagModel->layTatCaTags();

        // Get article count for each tag
        foreach ($tags as &$tag) {
            $tag['article_count'] = $baiVietModel->demBaiVietTheoTag($tag['id']);
        }
        unset($tag);

        $data = [
            'tags' => $tags,
        ];

        $this->renderView('nguoidung/danh_sach_tag', $data);
    }
}

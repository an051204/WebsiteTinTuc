<?php
namespace App\Models;

use App\Core\Model;

class BaiVietModel extends Model
{
    public function layTinSlide(): array
    {
        $sql = "SELECT id, title, thumbnail, slug, created_at 
                FROM articles 
                WHERE status = 'published' AND is_featured = 1 
                AND (published_at IS NULL OR published_at <= NOW())
                ORDER BY created_at DESC 
                LIMIT 5";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function layTinMoiNhat(int $limit = 10): array
    {
        $sql = "SELECT id, title, thumbnail, slug, created_at, author_id 
                FROM articles 
                WHERE status = 'published' 
                AND (published_at IS NULL OR published_at <= NOW())
                ORDER BY created_at DESC 
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function layTinXemNhieu(int $limit = 10): array
    {
        $sql = "SELECT id, title, thumbnail, slug, views_count, created_at 
                FROM articles 
                WHERE status = 'published' 
                AND (published_at IS NULL OR published_at <= NOW())
                ORDER BY views_count DESC 
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function layTinTheoChuyenMuc(int $categoryId, int $limit = 6): array
    {
        $sql = "SELECT id, title, thumbnail, slug, created_at 
                FROM articles 
                WHERE status = 'published' AND category_id = :category_id 
                AND (published_at IS NULL OR published_at <= NOW())
                ORDER BY created_at DESC 
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':category_id', $categoryId, \PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function layTinTheoChuyenMucPhanTrang(int $categoryId, string $filter = 'newest', int $limit = 10, int $offset = 0): array
    {
        $orderBy = match($filter) {
            'most_viewed' => 'views_count DESC',
            'most_commented' => 'comments_count DESC',
            default => 'created_at DESC',
        };

        $sql = "SELECT id, title, thumbnail, slug, created_at, views_count, comments_count, content 
                FROM articles 
                WHERE status = 'published' AND category_id = :category_id 
                AND (published_at IS NULL OR published_at <= NOW())
                ORDER BY {$orderBy}
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':category_id', $categoryId, \PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function demTongTinTheoChuyenMuc(int $categoryId): int
    {
        $sql = "SELECT COUNT(*) as total 
                FROM articles 
                WHERE status = 'published' AND category_id = :category_id
                AND (published_at IS NULL OR published_at <= NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':category_id', $categoryId, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function layChiTietBaiVietTheoSlug(string $slug): array|false
    {
        $sql = "SELECT a.id, a.title, a.content, a.thumbnail, a.slug, a.created_at, 
                       a.views_count, a.comments_count, a.author_id, a.category_id,
                       u.full_name AS author_name, c.name AS category_name, c.slug AS category_slug
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN categories c ON a.category_id = c.id
                WHERE a.slug = :slug AND a.status = 'published' 
                AND (a.published_at IS NULL OR a.published_at <= NOW())
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function tangLuotXem(int $articleId): bool
    {
        $sql = "UPDATE articles 
                SET views_count = views_count + 1 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $articleId, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function layBaiLienQuan(int $categoryId, int $currentArticleId, int $limit = 5): array
    {
        $sql = "SELECT id, title, thumbnail, slug, created_at, views_count
                FROM articles 
                WHERE status = 'published' AND category_id = :category_id AND id != :current_id
                AND (published_at IS NULL OR published_at <= NOW())
                ORDER BY created_at DESC 
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':category_id', $categoryId, \PDO::PARAM_INT);
        $stmt->bindParam(':current_id', $currentArticleId, \PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function timKiemGopY(string $keyword, int $limit = 10): array
    {
        $keyword = trim($keyword);
        if (mb_strlen($keyword) < 1) {
            return [];
        }

        $likeKeyword = '%' . $keyword . '%';

        $sql = "SELECT DISTINCT a.id, a.title, a.slug 
                FROM articles a
                LEFT JOIN article_tag atg ON a.id = atg.article_id
                LEFT JOIN tags t ON atg.tag_id = t.id
                WHERE a.status = 'published' 
                AND (a.published_at IS NULL OR a.published_at <= NOW())
                AND (a.title LIKE :keyword_like OR t.name LIKE :tag_like)
                ORDER BY a.created_at DESC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':keyword_like', $likeKeyword);
        $stmt->bindParam(':tag_like', $likeKeyword);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function timKiem(string $keyword, int $limit = 10, int $offset = 0): array
    {
        $keyword = trim($keyword);
        if (strlen($keyword) < 2) {
            return [];
        }

        $sql = "SELECT a.id, a.title, a.content, a.thumbnail, a.slug, a.created_at, 
                       a.views_count, a.comments_count, a.author_id, a.category_id,
                       u.full_name AS author_name, c.name AS category_name, c.slug AS category_slug
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN categories c ON a.category_id = c.id
                WHERE a.status = 'published' 
                AND MATCH(a.title, a.content) AGAINST(:keyword IN BOOLEAN MODE)
                AND (a.published_at IS NULL OR a.published_at <= NOW())
                ORDER BY MATCH(a.title, a.content) AGAINST(:keyword IN BOOLEAN MODE) DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':keyword', $keyword);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function demTimKiem(string $keyword): int
    {
        $keyword = trim($keyword);
        if (strlen($keyword) < 2) {
            return 0;
        }

        $sql = "SELECT COUNT(*) as total 
                FROM articles 
                WHERE status = 'published' 
                AND MATCH(title, content) AGAINST(:keyword IN BOOLEAN MODE)
                AND (published_at IS NULL OR published_at <= NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function timKiemTheoTacGia(string $keyword, int $limit = 10, int $offset = 0): array
    {
        $keyword = trim($keyword);
        if (mb_strlen($keyword) < 1) {
            return [];
        }

        $keyword_like = '%' . $keyword . '%';

        $sql = "SELECT DISTINCT a.id, a.title, a.content, a.thumbnail, a.slug, a.created_at, 
                       a.views_count, a.comments_count, a.author_id, a.category_id,
                       u.full_name AS author_name, c.name AS category_name, c.slug AS category_slug
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN categories c ON a.category_id = c.id
                LEFT JOIN article_tag atg ON a.id = atg.article_id
                LEFT JOIN tags t ON atg.tag_id = t.id
                WHERE a.status = 'published' 
                AND (a.title LIKE :title_like
                     OR u.full_name LIKE :author_like
                     OR t.name LIKE :tag_like)
                AND (a.published_at IS NULL OR a.published_at <= NOW())
                ORDER BY a.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title_like', $keyword_like);
        $stmt->bindParam(':author_like', $keyword_like);
        $stmt->bindParam(':tag_like', $keyword_like);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function demTimKiemTheoTacGia(string $keyword): int
    {
        $keyword = trim($keyword);
        if (mb_strlen($keyword) < 1) {
            return 0;
        }

        $keyword_like = '%' . $keyword . '%';

        $sql = "SELECT COUNT(DISTINCT a.id) as total 
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN article_tag atg ON a.id = atg.article_id
                LEFT JOIN tags t ON atg.tag_id = t.id
                WHERE a.status = 'published' 
                AND (a.title LIKE :title_like
                     OR u.full_name LIKE :author_like
                     OR t.name LIKE :tag_like)
                AND (a.published_at IS NULL OR a.published_at <= NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title_like', $keyword_like);
        $stmt->bindParam(':author_like', $keyword_like);
        $stmt->bindParam(':tag_like', $keyword_like);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function layDanhSachBaiVietAdmin(string $search = '', string $status = '', int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT a.id, a.title, a.slug, a.thumbnail, a.status, a.is_featured, a.views_count, a.comments_count,
                       a.created_at, a.author_id, u.full_name AS author_name, c.name AS category_name
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN categories c ON a.category_id = c.id
                WHERE 1=1";

        $params = [];

        if (!empty($search)) {
            $search = '%' . trim($search) . '%';
            $sql .= " AND (a.title LIKE ? OR u.full_name LIKE ?)";
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($status)) {
            $sql .= " AND a.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY a.created_at DESC LIMIT " . (int)$offset . ", " . (int)$limit;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function demDanhSachBaiVietAdmin(string $search = '', string $status = ''): int
    {
        $sql = "SELECT COUNT(*) as total FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                WHERE 1=1";

        $params = [];

        if (!empty($search)) {
            $search = '%' . trim($search) . '%';
            $sql .= " AND (a.title LIKE ? OR u.full_name LIKE ?)";
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($status)) {
            $sql .= " AND a.status = ?";
            $params[] = $status;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function layChiTietBaiVietAdmin(int $articleId): array|false
    {
        $sql = "SELECT a.id, a.title, a.content, a.slug, a.thumbnail, a.summary,
                       a.status, a.is_featured, a.views_count, a.comments_count,
                       a.author_id, a.category_id, a.published_at, a.created_at, a.updated_at,
                       u.full_name AS author_name, c.name AS category_name
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN categories c ON a.category_id = c.id
                WHERE a.id = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $articleId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function themBaiViet(int $authorId, int $categoryId, string $title, string $content, 
                               string $summary, string $thumbnail, int $isFeatured, string $status, 
                               ?string $publishedAt = null): ?int
    {
        $slug = $this->taoSlug($title);

        if ($publishedAt !== null && !$this->kiemTraThoiGian($publishedAt)) {
            return null;
        }

        $sql = "INSERT INTO articles (author_id, category_id, title, slug, content, summary, 
                                    thumbnail, status, is_featured, published_at, created_at, updated_at)
                VALUES (:author_id, :category_id, :title, :slug, :content, :summary,
                        :thumbnail, :status, :is_featured, :published_at, NOW(), NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':author_id', $authorId, \PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $categoryId, \PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':summary', $summary);
        $stmt->bindParam(':thumbnail', $thumbnail);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':is_featured', $isFeatured, \PDO::PARAM_INT);
        $stmt->bindParam(':published_at', $publishedAt);

        if ($stmt->execute()) {
            return $this->db->lastInsertId() ?: null;
        }

        return null;
    }

    public function capNhatBaiViet(int $articleId, int $categoryId, string $title, string $content,
                                  string $summary, string $thumbnail, int $isFeatured, string $status,
                                  ?string $publishedAt = null): bool
    {
        if ($publishedAt !== null && !$this->kiemTraThoiGian($publishedAt)) {
            return false;
        }

        $sql = "UPDATE articles SET 
                category_id = :category_id,
                title = :title,
                content = :content,
                summary = :summary,
                thumbnail = :thumbnail,
                status = :status,
                is_featured = :is_featured,
                published_at = :published_at,
                updated_at = NOW()
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':category_id', $categoryId, \PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':summary', $summary);
        $stmt->bindParam(':thumbnail', $thumbnail);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':is_featured', $isFeatured, \PDO::PARAM_INT);
        $stmt->bindParam(':published_at', $publishedAt);
        $stmt->bindParam(':id', $articleId, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function xoaBaiViet(int $articleId): bool
    {
        $sql = "DELETE FROM articles WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $articleId, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function capNhatTrangThai(int $articleId, string $status): bool
    {
        $sql = "UPDATE articles SET status = :status, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $articleId, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    private function taoSlug(string $title): string
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');

        $baseSlug = $slug;
        $counter = 1;

        while ($this->kiemTraSlugTonTai($slug)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function kiemTraSlugTonTai(string $slug): bool
    {
        $sql = "SELECT COUNT(*) as count FROM articles WHERE slug = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug]);
        $result = $stmt->fetch();
        return ($result['count'] ?? 0) > 0;
    }

    private function kiemTraThoiGian(string $datetime): bool
    {
        $timestamp = strtotime($datetime);
        return $timestamp !== false;
    }

    public function layBaiVietNoiBat(int $limit = 5): array
    {
        $sql = "SELECT id, title, thumbnail, views_count FROM articles 
                WHERE is_featured = 1 AND status = 'published' 
                AND (published_at IS NULL OR published_at <= NOW())
                ORDER BY views_count DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function layBaiVietXemNhieuTuan(int $limit = 5): array
    {
        $sql = "SELECT id, title, views_count FROM articles 
                WHERE status = 'published' AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) 
                AND (published_at IS NULL OR published_at <= NOW())
                ORDER BY views_count DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function layBaiVietDangLich(): array
    {
        $sql = "SELECT id, title, published_at FROM articles 
                WHERE status = 'scheduled' AND published_at IS NOT NULL 
                ORDER BY published_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function xuatBanBaiVietTheoLich(): bool
    {
        $sql = "UPDATE articles 
                SET status = 'published', updated_at = NOW()
                WHERE status = 'scheduled' AND published_at IS NOT NULL AND published_at <= NOW()";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }

    public function layBaiVietTheoTag(int $tagId, int $limit = 10, int $offset = 0): array
    {
        try {
            $sql = "SELECT DISTINCT a.id, a.title, a.slug, a.content, a.thumbnail, 
                           a.views_count, a.comments_count, a.created_at, a.status,
                           u.full_name as author_name, c.name as category_name
                    FROM articles a
                    INNER JOIN article_tag at ON a.id = at.article_id
                    LEFT JOIN users u ON a.author_id = u.id
                    LEFT JOIN categories c ON a.category_id = c.id
                    WHERE at.tag_id = :tag_id 
                    AND a.status = 'published'
                    AND (a.published_at IS NULL OR a.published_at <= NOW())
                    ORDER BY a.created_at DESC
                    LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':tag_id', $tagId, \PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            error_log("Lỗi lấy bài viết theo tag: " . $e->getMessage());
            return [];
        }
    }

    public function demBaiVietTheoTag(int $tagId): int
    {
        try {
            $sql = "SELECT COUNT(DISTINCT a.id) as total
                    FROM articles a
                    INNER JOIN article_tag at ON a.id = at.article_id
                    WHERE at.tag_id = :tag_id 
                    AND a.status = 'published'
                    AND (a.published_at IS NULL OR a.published_at <= NOW())";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':tag_id', $tagId, \PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (\Exception $e) {
            error_log("Lỗi đếm bài viết theo tag: " . $e->getMessage());
            return 0;
        }
    }
}
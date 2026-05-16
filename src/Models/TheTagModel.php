<?php
namespace App\Models;

use App\Core\Model;

class TheTagModel extends Model
{
    public function layTatCaTags(): array
    {
        $sql = "SELECT id, name, slug FROM tags ORDER BY name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function themTag(string $name): ?int
    {
        $slug = $this->taoSlug($name);

        if ($this->kiemTraTagTonTai($slug)) {
            $sql = "SELECT id FROM tags WHERE slug = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$slug]);
            $result = $stmt->fetch();
            return $result['id'] ?? null;
        }

        $sql = "INSERT INTO tags (name, slug) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute([$name, $slug])) {
            return $this->db->lastInsertId() ?: null;
        }

        return null;
    }

    public function layTagsTheoBaiViet(int $articleId): array
    {
        $sql = "SELECT t.id, t.name, t.slug
                FROM tags t
                INNER JOIN article_tag at ON t.id = at.tag_id
                WHERE at.article_id = :article_id
                ORDER BY t.name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':article_id', $articleId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function lienKetTagVoiBaiViet(int $articleId, array $tagIds): bool
    {
        $this->xoaTatCaTagCuaBaiViet($articleId);

        if (empty($tagIds)) {
            return true;
        }

        $sql = "INSERT INTO article_tag (article_id, tag_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);

        foreach ($tagIds as $tagId) {
            $tagId = (int)$tagId;
            if ($tagId > 0) {
                $stmt->execute([$articleId, $tagId]);
            }
        }

        return true;
    }

    public function xoaTatCaTagCuaBaiViet(int $articleId): bool
    {
        $sql = "DELETE FROM article_tag WHERE article_id = :article_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':article_id', $articleId, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function layTagTheoSlug(string $slug): array|false
    {
        $sql = "SELECT id, name, slug FROM tags WHERE slug = :slug LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function layBaiVietTheoTag(string $slug, int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT a.id, a.title, a.slug, a.thumbnail, a.created_at, a.views_count
                FROM articles a
                INNER JOIN article_tag at ON a.id = at.article_id
                INNER JOIN tags t ON at.tag_id = t.id
                WHERE t.slug = :slug AND a.status = 'published'
                ORDER BY a.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function demBaiVietTheoTag(string $slug): int
    {
        $sql = "SELECT COUNT(*) as total
                FROM articles a
                INNER JOIN article_tag at ON a.id = at.article_id
                INNER JOIN tags t ON at.tag_id = t.id
                WHERE t.slug = :slug AND a.status = 'published'";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    private function kiemTraTagTonTai(string $slug): bool
    {
        $sql = "SELECT COUNT(*) as count FROM tags WHERE slug = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug]);
        $result = $stmt->fetch();
        return ($result['count'] ?? 0) > 0;
    }

    public function demTatCaTags(): int
    {
        $sql = "SELECT COUNT(*) as total FROM tags";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    private function taoSlug(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        return trim($slug, '-');
    }

    /**
     * Danh sách tag cho admin với pagination và search
     */
    public function layDanhSachTagAdmin(string $search = '', int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT id, name, slug FROM tags WHERE 1=1";

        if (!empty($search)) {
            $sql .= " AND (name LIKE :search OR slug LIKE :search)";
        }

        $sql .= " ORDER BY name ASC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        if (!empty($search)) {
            $searchParam = "%$search%";
            $stmt->bindParam(':search', $searchParam);
        }

        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Đếm danh sách tag với search
     */
    public function demDanhSachTagAdmin(string $search = ''): int
    {
        $sql = "SELECT COUNT(*) as total FROM tags WHERE 1=1";

        if (!empty($search)) {
            $sql .= " AND (name LIKE :search OR slug LIKE :search)";
        }

        $stmt = $this->db->prepare($sql);

        if (!empty($search)) {
            $searchParam = "%$search%";
            $stmt->bindParam(':search', $searchParam);
        }

        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    /**
     * Lấy chi tiết tag
     */
    public function layChiTietTag(int $id): array
    {
        $sql = "SELECT id, name, slug FROM tags WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Cập nhật tag
     */
    public function capNhatTag(int $id, string $name): bool
    {
        try {
            $slug = $this->taoSlug($name);

            // Kiểm tra slug tồn tại (ngoại trừ chính nó)
            $sql = "SELECT COUNT(*) as count FROM tags WHERE slug = :slug AND id != :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':slug', $slug);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return false; // Slug đã tồn tại
            }

            // Cập nhật tag
            $sql = "UPDATE tags SET name = :name, slug = :slug WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':slug', $slug);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\Exception $e) {
            error_log('Error updating tag: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa tag
     */
    public function xoaTag(int $id): bool
    {
        try {
            // Xóa liên kết tag từ bài viết trước
            $sql = "DELETE FROM article_tag WHERE tag_id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();

            // Xóa tag
            $sql = "DELETE FROM tags WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\Exception $e) {
            error_log('Error deleting tag: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra slug tồn tại
     */
    public function kiemTraSlugTonTai(string $slug, int $excludeId = 0): bool
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM tags WHERE slug = :slug";
            if ($excludeId > 0) {
                $sql .= " AND id != :excludeId";
            }

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':slug', $slug);
            if ($excludeId > 0) {
                $stmt->bindParam(':excludeId', $excludeId, \PDO::PARAM_INT);
            }
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (\Exception $e) {
            error_log('Error checking slug: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra tên tag tồn tại
     */
    public function kiemTraTenTagTonTai(string $name, int $excludeId = 0): bool
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM tags WHERE LOWER(name) = LOWER(:name)";
            if ($excludeId > 0) {
                $sql .= " AND id != :excludeId";
            }

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $name);
            if ($excludeId > 0) {
                $stmt->bindParam(':excludeId', $excludeId, \PDO::PARAM_INT);
            }
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (\Exception $e) {
            error_log('Error checking tag name: ' . $e->getMessage());
            return false;
        }
    }
}

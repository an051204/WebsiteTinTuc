<?php
namespace App\Models;

use App\Core\Model;

class ChuyenMucModel extends Model
{
    public function layChuyenMucActive(): array
    {
        $sql = "SELECT id, name, slug, sort_order
                FROM categories 
                WHERE status = 'active' AND parent_id IS NULL 
                ORDER BY sort_order ASC, name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function layChiTietTheoSlug(string $slug): array|false
    {
        $sql = "SELECT id, name, slug, status 
                FROM categories 
                WHERE slug = :slug AND status = 'active' 
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function layChiTietTheoId(int $id): array|false
    {
        $sql = "SELECT id, name, slug, status 
                FROM categories 
                WHERE id = :id AND status = 'active' 
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function demChuyenMuc(): int
    {
        $sql = "SELECT COUNT(*) as total FROM categories WHERE status = 'active'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    // Lấy tất cả chuyên mục (kể cả hidden) cho admin
    public function layTatCaChuyenMuc(): array
    {
        $sql = "SELECT id, parent_id, name, slug, description, sort_order, status, created_at
                FROM categories
                ORDER BY sort_order ASC, name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy danh sách chuyên mục với tìm kiếm
    public function layDanhSachChuyenMuc(string $search = '', int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT id, parent_id, name, slug, description, sort_order, status, created_at
                FROM categories
                WHERE 1=1";

        $params = [];

        if (!empty($search)) {
            $search = '%' . trim($search) . '%';
            $sql .= " AND name LIKE ?";
            $params[] = $search;
        }

        $sql .= " ORDER BY sort_order ASC, name ASC LIMIT " . (int)$offset . ", " . (int)$limit;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Đếm danh sách chuyên mục
    public function demDanhSachChuyenMuc(string $search = ''): int
    {
        $sql = "SELECT COUNT(*) as total FROM categories WHERE 1=1";

        $params = [];

        if (!empty($search)) {
            $search = '%' . trim($search) . '%';
            $sql .= " AND name LIKE ?";
            $params[] = $search;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    // Lấy chi tiết chuyên mục cho admin (kể cả hidden)
    public function layChiTietChuyenMucAdmin(int $id): array|false
    {
        $sql = "SELECT id, parent_id, name, slug, description, sort_order, status, created_at
                FROM categories
                WHERE id = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Thêm chuyên mục
    public function themChuyenMuc(string $name, string $description = '', ?int $parentId = null, string $status = 'active'): bool
    {
        $slug = $this->taoSlug($name);

        $sql = "INSERT INTO categories (parent_id, name, slug, description, status, sort_order)
                VALUES (:parent_id, :name, :slug, :description, :status, 0)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':parent_id', $parentId, $parentId === null ? \PDO::PARAM_NULL : \PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':status', $status);

        return $stmt->execute();
    }

    // Cập nhật chuyên mục
    public function capNhatChuyenMuc(int $id, string $name, string $description = '', ?int $parentId = null, string $status = 'active'): bool
    {
        $slug = $this->taoSlug($name, $id);

        $sql = "UPDATE categories 
                SET parent_id = :parent_id, name = :name, slug = :slug, 
                    description = :description, status = :status
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':parent_id', $parentId, $parentId === null ? \PDO::PARAM_NULL : \PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Xóa chuyên mục
    public function xoaChuyenMuc(int $id): bool
    {
        $sql = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Cập nhật thứ tự sắp xếp
    public function capNhatThuTu(int $id, int $sortOrder): bool
    {
        $sql = "UPDATE categories SET sort_order = :sort_order WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':sort_order', $sortOrder, \PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Kiểm tra tên chuyên mục đã tồn tại
    public function kiemTraTenTonTai(string $name, int $excludeId = 0): bool
    {
        $sql = "SELECT COUNT(*) as count FROM categories WHERE name = ?";
        
        if ($excludeId > 0) {
            $sql .= " AND id != ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$name, $excludeId]);
        } else {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$name]);
        }

        $result = $stmt->fetch();
        return ($result['count'] ?? 0) > 0;
    }

    // Đếm bài viết theo chuyên mục
    public function demBaiVietTheoChuyenMuc(int $categoryId): int
    {
        $sql = "SELECT COUNT(*) as total FROM articles WHERE category_id = :category_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':category_id', $categoryId, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    // Đếm chuyên mục con
    public function demChuyenMucCon(int $parentId): int
    {
        $sql = "SELECT COUNT(*) as total FROM categories WHERE parent_id = :parent_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':parent_id', $parentId, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    // Tạo slug
    private function taoSlug(string $name, int $excludeId = 0): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');

        $baseSlug = $slug;
        $counter = 1;

        while ($this->kiemTraSlugTonTai($slug, $excludeId)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    // Kiểm tra slug đã tồn tại
    private function kiemTraSlugTonTai(string $slug, int $excludeId = 0): bool
    {
        $sql = "SELECT COUNT(*) as count FROM categories WHERE slug = ?";
        
        if ($excludeId > 0) {
            $sql .= " AND id != ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$slug, $excludeId]);
        } else {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$slug]);
        }

        $result = $stmt->fetch();
        return ($result['count'] ?? 0) > 0;
    }
}

<?php
namespace App\Models;

use App\Core\Model;

class QuangCaoModel extends Model
{
    /**
     * Lấy quảng cáo theo vị trí (cho frontend)
     */
    public function layQuangCaoTheoViTri(string $position): array
    {
        $now = date('Y-m-d');
        $sql = "SELECT id, title, image_url, link_url, position 
                FROM advertisements 
                WHERE position = :position 
                AND status = 'active' 
                AND (start_date IS NULL OR start_date <= :now) 
                AND (end_date IS NULL OR end_date >= :now) 
                ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':position', $position);
        $stmt->bindParam(':now', $now);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Lấy danh sách quảng cáo cho admin (có phân trang, tìm kiếm)
     */
    public function layDanhSachAdmin(string $search = '', int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT * FROM advertisements";
        $params = [];

        if (!empty($search)) {
            $sql .= " WHERE title LIKE :search";
            $params[':search'] = "%{$search}%";
        }

        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Đếm tổng quảng cáo (cho phân trang)
     */
    public function demQuangCao(string $search = ''): int
    {
        $sql = "SELECT COUNT(*) as total FROM advertisements";
        $params = [];

        if (!empty($search)) {
            $sql .= " WHERE title LIKE :search";
            $params[':search'] = "%{$search}%";
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    /**
     * Lấy chi tiết 1 quảng cáo theo ID
     */
    public function layChiTiet(int $id): ?array
    {
        $sql = "SELECT * FROM advertisements WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Thêm quảng cáo mới
     */
    public function themQuangCao(array $data): int
    {
        $sql = "INSERT INTO advertisements (title, image_url, link_url, position, status, start_date, end_date)
                VALUES (:title, :image_url, :link_url, :position, :status, :start_date, :end_date)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':image_url', $data['image_url']);
        $stmt->bindParam(':link_url', $data['link_url']);
        $stmt->bindParam(':position', $data['position']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }

    /**
     * Cập nhật quảng cáo
     */
    public function capNhatQuangCao(int $id, array $data): bool
    {
        $sql = "UPDATE advertisements 
                SET title = :title, image_url = :image_url, link_url = :link_url, 
                    position = :position, status = :status, 
                    start_date = :start_date, end_date = :end_date
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':image_url', $data['image_url']);
        $stmt->bindParam(':link_url', $data['link_url']);
        $stmt->bindParam(':position', $data['position']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);

        return $stmt->execute();
    }

    /**
     * Xóa quảng cáo
     */
    public function xoaQuangCao(int $id): bool
    {
        $sql = "DELETE FROM advertisements WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Cập nhật trạng thái quảng cáo
     */
    public function capNhatTrangThai(int $id, string $status): bool
    {
        $sql = "UPDATE advertisements SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    /**
     * Tăng lượt click
     */
    public function tangLuotClick(int $id): bool
    {
        $sql = "UPDATE advertisements SET clicks_count = clicks_count + 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}

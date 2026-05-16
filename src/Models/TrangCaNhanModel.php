<?php
namespace App\Models;

use App\Core\Model;

class TrangCaNhanModel extends Model
{
    public function layThongTinUser(int $userId): array|false
    {
        $sql = "SELECT id, full_name, email, avatar, status, created_at 
                FROM users 
                WHERE id = :user_id 
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function kiemTraEmailTonTaiKhac(string $email, int $userId): bool
    {
        $sql = "SELECT COUNT(*) as total FROM users 
                WHERE email = :email AND id != :user_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return ($result['total'] ?? 0) > 0;
    }

    public function capNhatThongTinUser(int $userId, array $data): bool
    {
        if (!empty($data['email'])) {
            if ($this->kiemTraEmailTonTaiKhac($data['email'], $userId)) {
                return false;
            }
        }

        $fields = [];
        $params = [':user_id' => $userId];

        if (!empty($data['full_name'])) {
            $fields[] = 'full_name = :full_name';
            $params[':full_name'] = $data['full_name'];
        }

        if (!empty($data['email'])) {
            $fields[] = 'email = :email';
            $params[':email'] = $data['email'];
        }

        if (!empty($data['avatar'])) {
            $fields[] = 'avatar = :avatar';
            $params[':avatar'] = $data['avatar'];
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :user_id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function layBaiVietDaThich(int $userId, int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT a.id, a.title, a.slug, a.thumbnail, a.created_at, a.views_count, a.comments_count,
                       c.name AS category_name, c.slug AS category_slug
                FROM article_likes al
                JOIN articles a ON al.article_id = a.id
                LEFT JOIN categories c ON a.category_id = c.id
                WHERE al.user_id = :user_id AND a.status = 'published'
                ORDER BY al.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function demBaiVietDaThich(int $userId): int
    {
        $sql = "SELECT COUNT(*) as total 
                FROM article_likes al
                JOIN articles a ON al.article_id = a.id
                WHERE al.user_id = :user_id AND a.status = 'published'";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function layBaiVietDaLuu(int $userId, int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT a.id, a.title, a.slug, a.thumbnail, a.created_at, a.views_count, a.comments_count,
                       c.name AS category_name, c.slug AS category_slug
                FROM article_saves asv
                JOIN articles a ON asv.article_id = a.id
                LEFT JOIN categories c ON a.category_id = c.id
                WHERE asv.user_id = :user_id AND a.status = 'published'
                ORDER BY asv.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function demBaiVietDaLuu(int $userId): int
    {
        $sql = "SELECT COUNT(*) as total 
                FROM article_saves asv
                JOIN articles a ON asv.article_id = a.id
                WHERE asv.user_id = :user_id AND a.status = 'published'";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function layBinhLuanDaDang(int $userId, int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT c.id, c.content, c.status, c.created_at, a.id AS article_id, a.title, a.slug
                FROM comments c
                JOIN articles a ON c.article_id = a.id
                WHERE c.user_id = :user_id
                ORDER BY c.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function demBinhLuanDaDang(int $userId): int
    {
        $sql = "SELECT COUNT(*) as total 
                FROM comments 
                WHERE user_id = :user_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function capNhatAvatar(int $userId, string $avatarUrl): bool
    {
        $sql = "UPDATE users SET avatar = :avatar WHERE id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':avatar', $avatarUrl);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}

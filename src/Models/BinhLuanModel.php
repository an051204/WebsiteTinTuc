<?php
namespace App\Models;

use App\Core\Model;

class BinhLuanModel extends Model
{
    public function layBinhLuanTheoBaiViet(int $articleId): array
    {
        $sql = "SELECT c.id, c.content, c.created_at, c.user_id,
                       u.full_name, u.avatar
                FROM comments c
                LEFT JOIN users u ON c.user_id = u.id
                WHERE c.article_id = :article_id AND c.status = 'approved' AND c.parent_id IS NULL
                ORDER BY c.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':article_id', $articleId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function themBinhLuan(int $articleId, int $userId, string $content): bool
    {
        try {
            $sql = "INSERT INTO comments (article_id, user_id, content, status, parent_id) 
                    VALUES (:article_id, :user_id, :content, 'approved', NULL)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':article_id', $articleId, \PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
            $stmt->bindParam(':content', $content);
            return $stmt->execute();
        } catch (\Exception $e) {
            error_log('Error adding comment: ' . $e->getMessage());
            return false;
        }
    }

    public function kiemTraTuKhoa(string $content): bool
    {
        $badWords = $this->getBadWords();
        
        if (empty($badWords)) {
            return true;
        }
        
        $contentLower = strtolower($content);
        foreach ($badWords as $row) {
            $word = strtolower($row['word']);
            if (strpos($contentLower, $word) !== false) {
                return false;
            }
        }
        
        return true;
    }

    private function getBadWords(): array
    {
        try {
            $stmt = $this->db->prepare('SELECT word FROM bad_words');
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            error_log('Error fetching bad words: ' . $e->getMessage());
            return [];
        }
    }

    public function kiemTraCommentSpam(int $userId, int $articleId): bool
    {
        try {
            // Lấy comment gần nhất của user trên article này
            $sql = "SELECT UNIX_TIMESTAMP(created_at) as timestamp FROM comments 
                    WHERE user_id = :user_id AND article_id = :article_id 
                    ORDER BY created_at DESC LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
            $stmt->bindParam(':article_id', $articleId, \PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$result || empty($result['timestamp'])) {
                return true; // Chưa có comment nào, được phép
            }
            
            $lastCommentTime = (int)$result['timestamp'];
            $now = (int)time();
            $timeDiff = $now - $lastCommentTime;
            
            if ($timeDiff < 10) {
                return false; // SPAM - chưa đủ 10 giây
            }
            
            return true; // OK - được phép comment
        } catch (\Exception $e) {
            error_log('Error checking comment spam: ' . $e->getMessage());
            return true; // Nếu lỗi, cho phép comment
        }
    }

    public function demTatCaBinhLuan(): int
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM comments";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return (int)($result['total'] ?? 0);
        } catch (\Exception $e) {
            error_log('Error counting comments: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Lấy danh sách bình luận cho admin với pagination và search
     */
    public function layDanhSachBinhLuanAdmin(string $search = '', string $status = '', int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT c.id, c.content, c.status, c.created_at, 
                       u.full_name, u.email, 
                       a.title as article_title, a.slug
                FROM comments c
                LEFT JOIN users u ON c.user_id = u.id
                LEFT JOIN articles a ON c.article_id = a.id
                WHERE 1=1";

        if (!empty($search)) {
            $sql .= " AND (u.full_name LIKE :search OR c.content LIKE :search OR a.title LIKE :search)";
        }

        if (!empty($status)) {
            $sql .= " AND c.status = :status";
        }

        $sql .= " ORDER BY c.created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        
        if (!empty($search)) {
            $searchParam = "%$search%";
            $stmt->bindParam(':search', $searchParam);
        }

        if (!empty($status)) {
            $stmt->bindParam(':status', $status);
        }

        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Đếm danh sách bình luận với search và filter
     */
    public function demDanhSachBinhLuan(string $search = '', string $status = ''): int
    {
        $sql = "SELECT COUNT(*) as total FROM comments c
                LEFT JOIN users u ON c.user_id = u.id
                LEFT JOIN articles a ON c.article_id = a.id
                WHERE 1=1";

        if (!empty($search)) {
            $sql .= " AND (u.full_name LIKE :search OR c.content LIKE :search OR a.title LIKE :search)";
        }

        if (!empty($status)) {
            $sql .= " AND c.status = :status";
        }

        $stmt = $this->db->prepare($sql);

        if (!empty($search)) {
            $searchParam = "%$search%";
            $stmt->bindParam(':search', $searchParam);
        }

        if (!empty($status)) {
            $stmt->bindParam(':status', $status);
        }

        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Lấy chi tiết bình luận
     */
    public function layChiTietBinhLuan(int $id): array
    {
        $sql = "SELECT c.*, 
                       u.full_name, u.email, u.avatar,
                       a.title as article_title, a.slug
                FROM comments c
                LEFT JOIN users u ON c.user_id = u.id
                LEFT JOIN articles a ON c.article_id = a.id
                WHERE c.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Cập nhật trạng thái bình luận
     */
    public function capNhatTrangThaiComment(int $id, string $status): bool
    {
        $validStatuses = ['approved', 'pending', 'hidden', 'spam'];
        if (!in_array($status, $validStatuses)) {
            return false;
        }

        // Lấy comment cũ để cập nhật comments_count
        $oldComment = $this->layChiTietBinhLuan($id);
        if (empty($oldComment)) {
            return false;
        }

        $sql = "UPDATE comments SET status = :status, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            // Cập nhật comments_count của article
            $oldStatus = $oldComment['status'];
            $articleId = $oldComment['article_id'];

            if ($oldStatus === 'approved' && $status !== 'approved') {
                // Comment từ approved sang không approved
                $this->db->prepare("UPDATE articles SET comments_count = comments_count - 1 WHERE id = :id")
                    ->execute([':id' => $articleId]);
            } elseif ($oldStatus !== 'approved' && $status === 'approved') {
                // Comment từ không approved sang approved
                $this->db->prepare("UPDATE articles SET comments_count = comments_count + 1 WHERE id = :id")
                    ->execute([':id' => $articleId]);
            }

            return true;
        }

        return false;
    }

    /**
     * Xóa bình luận
     */
    public function xoaBinhLuan(int $id): bool
    {
        $comment = $this->layChiTietBinhLuan($id);
        if (empty($comment)) {
            return false;
        }

        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Lấy danh sách từ khóa xấu
     */
    public function layTatCaTuKhoaXau(): array
    {
        $sql = "SELECT id, word FROM bad_words ORDER BY word ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Kiểm tra từ khóa xấu đã tồn tại
     */
    public function kiemTraTuKhoaTonTai(string $word): bool
    {
        $sql = "SELECT COUNT(*) as total FROM bad_words WHERE LOWER(word) = LOWER(:word)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':word', $word);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['total'] > 0;
    }

    /**
     * Thêm từ khóa xấu mới
     */
    public function themTuKhoaXau(string $word): bool
    {
        // Kiểm tra đã tồn tại
        if ($this->kiemTraTuKhoaTonTai($word)) {
            return false;
        }

        $sql = "INSERT INTO bad_words (word) VALUES (:word)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':word', $word);
        return $stmt->execute();
    }

    /**
     * Xóa từ khóa xấu
     */
    public function xoaTuKhoaXau(int $id): bool
    {
        $sql = "DELETE FROM bad_words WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}
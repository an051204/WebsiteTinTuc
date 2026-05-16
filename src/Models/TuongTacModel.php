<?php
namespace App\Models;

use App\Core\Model;

class TuongTacModel extends Model
{
    public function kiemTraDaThich(int $articleId, int $userId): bool
    {
        $sql = "SELECT COUNT(*) as count FROM article_likes 
                WHERE article_id = :article_id AND user_id = :user_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':article_id', $articleId, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return ($result['count'] ?? 0) > 0;
    }

    public function thichBaiViet(int $articleId, int $userId): bool
    {
        if ($this->kiemTraDaThich($articleId, $userId)) {
            return $this->xoaThich($articleId, $userId);
        }

        $sql = "INSERT INTO article_likes (article_id, user_id) 
                VALUES (:article_id, :user_id)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':article_id', $articleId, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function xoaThich(int $articleId, int $userId): bool
    {
        $sql = "DELETE FROM article_likes 
                WHERE article_id = :article_id AND user_id = :user_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':article_id', $articleId, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function kiemTraDaLuu(int $articleId, int $userId): bool
    {
        $sql = "SELECT COUNT(*) as count FROM article_saves 
                WHERE article_id = :article_id AND user_id = :user_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':article_id', $articleId, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return ($result['count'] ?? 0) > 0;
    }

    public function luuBaiViet(int $articleId, int $userId): bool
    {
        if ($this->kiemTraDaLuu($articleId, $userId)) {
            return $this->xoaLuu($articleId, $userId);
        }

        $sql = "INSERT INTO article_saves (article_id, user_id) 
                VALUES (:article_id, :user_id)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':article_id', $articleId, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function xoaLuu(int $articleId, int $userId): bool
    {
        $sql = "DELETE FROM article_saves 
                WHERE article_id = :article_id AND user_id = :user_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':article_id', $articleId, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}

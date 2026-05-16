<?php
namespace App\Models;

use App\Core\Model;

class TaiKhoanModel extends Model
{
    public function layUserByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('
            SELECT id, role_id, full_name, email, password_hash, avatar, status, created_at
            FROM users
            WHERE email = ? AND status = "active"
        ');
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function kiemTraEmailTonTai(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->rowCount() > 0;
    }

    public function taoTaiKhoan(string $fullName, string $email, string $password): ?int
    {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
        $defaultAvatar = '/WebsiteTinTuc/public/assets/default-avatar.png';
        $stmt = $this->db->prepare('
            INSERT INTO users (role_id, full_name, email, password_hash, avatar, status, created_at)
            VALUES (?, ?, ?, ?, ?, "active", NOW())
        ');
        $stmt->execute([3, $fullName, $email, $passwordHash, $defaultAvatar]);
        return $this->db->lastInsertId() ?: null;
    }

    public function kiemTraThongTin(string $email, string $password): ?array
    {
        $user = $this->layUserByEmail($email);
        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return null;
        }

        return $user;
    }

    public function taoDuongDanReset(int $userId): ?string
    {
        $token = bin2hex(random_bytes(32));

        $stmt = $this->db->prepare('
            UPDATE users
            SET reset_password_token = ?, reset_token_expire = DATE_ADD(NOW(), INTERVAL 1 HOUR)
            WHERE id = ?
        ');
        $stmt->execute([$token, $userId]);

        return $stmt->rowCount() > 0 ? $token : null;
    }

    public function layUserByResetToken(string $token): ?array
    {
        $stmt = $this->db->prepare('
            SELECT id, full_name, email, reset_token_expire
            FROM users
            WHERE reset_password_token = ? AND reset_token_expire > NOW()
        ');
        $stmt->execute([$token]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function capNhatMatKhauByToken(string $token, string $newPassword): bool
    {
        $user = $this->layUserByResetToken($token);
        if (!$user) {
            return false;
        }

        $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 10]);
        $stmt = $this->db->prepare('
            UPDATE users
            SET password_hash = ?, reset_password_token = NULL, reset_token_expire = NULL
            WHERE id = ?
        ');
        $stmt->execute([$passwordHash, $user['id']]);

        return $stmt->rowCount() > 0;
    }

    public function capNhatMatKhauByUser(int $userId, string $oldPassword, string $newPassword): bool
    {
        $stmt = $this->db->prepare('SELECT password_hash FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $result = $stmt->fetch();

        if (!$result || !password_verify($oldPassword, $result['password_hash'])) {
            return false;
        }

        $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 10]);
        $stmt = $this->db->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
        $stmt->execute([$passwordHash, $userId]);

        return $stmt->rowCount() > 0;
    }

    public function capNhatProfile(int $userId, array $data): bool
    {
        $updates = [];
        $values = [];

        if (isset($data['full_name']) && !empty(trim($data['full_name']))) {
            $updates[] = 'full_name = ?';
            $values[] = trim($data['full_name']);
        }

        if (isset($data['email']) && !empty(trim($data['email']))) {
            $email = trim($data['email']);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $updates[] = 'email = ?';
                $values[] = $email;
            }
        }

        if (isset($data['avatar']) && !empty(trim($data['avatar']))) {
            $updates[] = 'avatar = ?';
            $values[] = trim($data['avatar']);
        }

        if (empty($updates)) {
            return false;
        }

        $values[] = $userId;
        $sql = 'UPDATE users SET ' . implode(', ', $updates) . ' WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($values);

        return $stmt->rowCount() > 0;
    }

    public function layThongTinUser(int $userId): ?array
    {
        $stmt = $this->db->prepare('
            SELECT id, role_id, full_name, email, avatar, status, created_at
            FROM users
            WHERE id = ?
        ');
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function layDanhSachUsers(int $page = 1, int $perPage = 20, ?int $roleId = null, string $search = ''): array
    {
        $offset = ($page - 1) * $perPage;
        $search = '%' . trim($search) . '%';
        
        $sql = 'SELECT id, role_id, full_name, email, status, created_at FROM users WHERE 1=1';
        $params = [];
        
        if ($roleId !== null) {
            $sql .= ' AND role_id = ?';
            $params[] = $roleId;
        }
        
        if (!empty(trim($search, '%'))) {
            $sql .= ' AND (full_name LIKE ? OR email LIKE ?)';
            $params[] = $search;
            $params[] = $search;
        }
        
        $sql .= ' ORDER BY created_at DESC LIMIT ' . (int)$offset . ', ' . (int)$perPage;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function demUsersVoiFilter(?int $roleId = null, string $search = ''): int
    {
        $search = '%' . trim($search) . '%';
        
        $sql = 'SELECT COUNT(*) as total FROM users WHERE 1=1';
        $params = [];
        
        if ($roleId !== null) {
            $sql .= ' AND role_id = ?';
            $params[] = $roleId;
        }
        
        if (!empty(trim($search, '%'))) {
            $sql .= ' AND (full_name LIKE ? OR email LIKE ?)';
            $params[] = $search;
            $params[] = $search;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return (int)($result['total'] ?? 0);
    }

    public function capNhatRole(int $userId, int $newRoleId): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET role_id = ? WHERE id = ?');
        $stmt->execute([$newRoleId, $userId]);
        return $stmt->rowCount() > 0;
    }

    public function capNhatStatus(int $userId, string $newStatus): bool
    {
        if (!in_array($newStatus, ['active', 'locked'])) {
            return false;
        }
        
        $stmt = $this->db->prepare('UPDATE users SET status = ? WHERE id = ?');
        $stmt->execute([$newStatus, $userId]);
        return $stmt->rowCount() > 0;
    }

    public function layDanhSachRoles(): array
    {
        $stmt = $this->db->prepare('SELECT id, name FROM roles ORDER BY id ASC');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function kiemTraEmailTonTaiKhac(string $email, int $userId): bool
    {
        $stmt = $this->db->prepare('
            SELECT COUNT(*) as total FROM users 
            WHERE email = ? AND id != ?
        ');
        $stmt->execute([$email, $userId]);
        $result = $stmt->fetch();
        return ($result['total'] ?? 0) > 0;
    }

    public function layUserByEmailWithStatus(string $email): ?array
    {
        $stmt = $this->db->prepare('
            SELECT id, role_id, full_name, email, password_hash, avatar, status, created_at
            FROM users
            WHERE email = ?
        ');
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function demTatCaTaiKhoan(): int
    {
        $sql = "SELECT COUNT(*) as total FROM users";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function xoaTaiKhoan(int $userId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM articles WHERE author_id = ?');
        $stmt->execute([$userId]);

        $stmt = $this->db->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        return $stmt->rowCount() > 0;
    }
}

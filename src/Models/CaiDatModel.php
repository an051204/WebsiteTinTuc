<?php
namespace App\Models;

use App\Core\Model;

class CaiDatModel extends Model
{
    /**
     * Lấy toàn bộ cài đặt dạng key => value
     */
    public function layTatCaCaiDat(): array
    {
        $sql = "SELECT setting_key, setting_value FROM settings";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }

    /**
     * Lấy giá trị một setting theo key
     */
    public function layGiaTri(string $key, string $default = ''): string
    {
        $sql = "SELECT setting_value FROM settings WHERE setting_key = :key LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':key', $key);
        $stmt->execute();
        $row = $stmt->fetch();

        return $row ? ($row['setting_value'] ?? $default) : $default;
    }

    /**
     * Cập nhật hoặc thêm mới một setting (UPSERT)
     */
    public function capNhatCaiDat(string $key, string $value, string $description = ''): bool
    {
        $sql = "SELECT id FROM settings WHERE setting_key = :key LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':key', $key);
        $stmt->execute();
        $exists = $stmt->fetch();

        if ($exists) {
            $sql = "UPDATE settings SET setting_value = :value WHERE setting_key = :key";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':value', $value);
            $stmt->bindParam(':key', $key);
        } else {
            $sql = "INSERT INTO settings (setting_key, setting_value, description) VALUES (:key, :value, :desc)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':key', $key);
            $stmt->bindParam(':value', $value);
            $stmt->bindParam(':desc', $description);
        }

        return $stmt->execute();
    }

    /**
     * Cập nhật nhiều settings cùng lúc
     */
    public function capNhatNhieuCaiDat(array $data): bool
    {
        $this->db->beginTransaction();
        try {
            foreach ($data as $key => $value) {
                $this->capNhatCaiDat($key, $value);
            }
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}

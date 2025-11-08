<?php
declare(strict_types=1);

namespace App\Models;

final class ItemFile extends Model
{
    public function getByVersion(int $versionId): array
    {
        $stmt = $this->db->prepare('
            SELECT * FROM item_files
            WHERE version_id = ? AND is_active = 1
            ORDER BY created_at DESC
        ');
        $stmt->execute([$versionId]);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM item_files WHERE id = ? AND is_active = 1 LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function incrementDownloads(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE item_files SET download_count = download_count + 1 WHERE id = ?');
        $stmt->execute([$id]);
    }
}

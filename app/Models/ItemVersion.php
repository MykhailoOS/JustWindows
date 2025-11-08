<?php
declare(strict_types=1);

namespace App\Models;

final class ItemVersion extends Model
{
    public function getByItem(int $itemId): array
    {
        $stmt = $this->db->prepare('
            SELECT * FROM item_versions
            WHERE item_id = ?
            ORDER BY release_date DESC, created_at DESC
        ');
        $stmt->execute([$itemId]);
        $versions = $stmt->fetchAll();

        $fileModel = new ItemFile();
        foreach ($versions as &$version) {
            $version['files'] = $fileModel->getByVersion((int) $version['id']);
        }

        return $versions;
    }
}

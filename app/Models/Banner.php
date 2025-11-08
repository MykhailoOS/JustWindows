<?php
declare(strict_types=1);

namespace App\Models;

final class Banner extends Model
{
    public function active(string $lang): array
    {
        $stmt = $this->db->prepare('
            SELECT b.*, bt.title, bt.subtitle, bt.cta_label
            FROM banners b
            LEFT JOIN banner_translations bt ON bt.banner_id = b.id AND bt.lang = ?
            WHERE b.is_active = 1
                AND (b.start_date IS NULL OR b.start_date <= NOW())
                AND (b.end_date IS NULL OR b.end_date >= NOW())
            ORDER BY b.sort_order ASC
            LIMIT 10
        ');
        $stmt->execute([$lang]);
        return $stmt->fetchAll();
    }
}

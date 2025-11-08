<?php
declare(strict_types=1);

namespace App\Models;

final class Category extends Model
{
    public function all(string $lang): array
    {
        $stmt = $this->db->prepare('
            SELECT c.*, ct.title
            FROM categories c
            LEFT JOIN category_translations ct ON ct.category_id = c.id AND ct.lang = ?
            ORDER BY c.sort ASC, ct.title ASC
        ');
        $stmt->execute([$lang]);
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug, string $lang): ?array
    {
        $stmt = $this->db->prepare('
            SELECT c.*, ct.title
            FROM categories c
            LEFT JOIN category_translations ct ON ct.category_id = c.id AND ct.lang = ?
            WHERE c.slug = ?
            LIMIT 1
        ');
        $stmt->execute([$lang, $slug]);
        return $stmt->fetch() ?: null;
    }
}

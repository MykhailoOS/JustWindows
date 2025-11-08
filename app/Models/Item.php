<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

final class Item extends Model
{
    public function findBySlug(string $slug, string $lang): ?array
    {
        $stmt = $this->db->prepare('
            SELECT i.*, 
                it.title, it.short_desc, it.description, it.changelog, it.system_requirements, it.meta_title, it.meta_description,
                c.slug as category_slug,
                ct.title as category_title
            FROM items i
            LEFT JOIN item_translations it ON it.item_id = i.id AND it.lang = ?
            LEFT JOIN categories c ON c.id = i.category_id
            LEFT JOIN category_translations ct ON ct.category_id = c.id AND ct.lang = ?
            WHERE i.slug = ? AND i.is_published = 1 AND i.published_at <= NOW()
            LIMIT 1
        ');
        $stmt->execute([$lang, $lang, $slug]);
        return $stmt->fetch() ?: null;
    }

    public function getAll(array $filters, string $lang, int $limit = 24, int $offset = 0): array
    {
        $where = ['i.is_published = 1', 'i.published_at <= NOW()'];
        $params = [$lang, $lang];

        if (!empty($filters['os'])) {
            $where[] = 'i.os = ?';
            $params[] = $filters['os'];
        }

        if (!empty($filters['type'])) {
            $where[] = 'i.type = ?';
            $params[] = $filters['type'];
        }

        if (!empty($filters['architecture'])) {
            $where[] = 'i.architecture = ?';
            $params[] = $filters['architecture'];
        }

        if (!empty($filters['license'])) {
            $where[] = 'i.license = ?';
            $params[] = $filters['license'];
        }

        if (!empty($filters['category'])) {
            $where[] = 'c.slug = ?';
            $params[] = $filters['category'];
        }

        $orderBy = match ($filters['sort'] ?? 'popular') {
            'new' => 'i.published_at DESC',
            'rating' => 'i.rating DESC',
            'downloads' => 'i.downloads DESC',
            default => 'i.is_featured DESC, i.downloads DESC',
        };

        $sql = '
            SELECT i.*, 
                it.title, it.short_desc,
                c.slug as category_slug,
                ct.title as category_title
            FROM items i
            LEFT JOIN item_translations it ON it.item_id = i.id AND it.lang = ?
            LEFT JOIN categories c ON c.id = i.category_id
            LEFT JOIN category_translations ct ON ct.category_id = c.id AND ct.lang = ?
            WHERE '.implode(' AND ', $where).'
            ORDER BY '.$orderBy.'
            LIMIT ? OFFSET ?
        ';

        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function count(array $filters): int
    {
        $where = ['i.is_published = 1', 'i.published_at <= NOW()'];
        $params = [];

        if (!empty($filters['os'])) {
            $where[] = 'i.os = ?';
            $params[] = $filters['os'];
        }

        if (!empty($filters['type'])) {
            $where[] = 'i.type = ?';
            $params[] = $filters['type'];
        }

        if (!empty($filters['architecture'])) {
            $where[] = 'i.architecture = ?';
            $params[] = $filters['architecture'];
        }

        if (!empty($filters['license'])) {
            $where[] = 'i.license = ?';
            $params[] = $filters['license'];
        }

        if (!empty($filters['category'])) {
            $where[] = 'c.slug = ?';
            $params[] = $filters['category'];
        }

        $stmt = $this->db->prepare('
            SELECT COUNT(*) 
            FROM items i
            LEFT JOIN categories c ON c.id = i.category_id
            WHERE '.implode(' AND ', $where)
        );
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    public function incrementViews(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE items SET views = views + 1 WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function incrementDownloads(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE items SET downloads = downloads + 1 WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function getSimilar(int $itemId, int $categoryId, string $lang, int $limit = 6): array
    {
        $stmt = $this->db->prepare('
            SELECT i.*, 
                it.title, it.short_desc
            FROM items i
            LEFT JOIN item_translations it ON it.item_id = i.id AND it.lang = ?
            WHERE i.is_published = 1 
                AND i.published_at <= NOW()
                AND i.category_id = ? 
                AND i.id != ?
            ORDER BY i.rating DESC, i.downloads DESC
            LIMIT ?
        ');
        $stmt->execute([$lang, $categoryId, $itemId, $limit]);

        return $stmt->fetchAll();
    }

    public function getFeatured(string $lang, int $limit = 6): array
    {
        $stmt = $this->db->prepare('
            SELECT i.*, 
                it.title, it.short_desc
            FROM items i
            LEFT JOIN item_translations it ON it.item_id = i.id AND it.lang = ?
            WHERE i.is_published = 1 
                AND i.published_at <= NOW()
                AND i.is_featured = 1
            ORDER BY i.rating DESC
            LIMIT ?
        ');
        $stmt->execute([$lang, $limit]);

        return $stmt->fetchAll();
    }
}
